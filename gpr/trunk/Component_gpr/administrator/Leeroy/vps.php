<?php

function vps_create($service,$admin)
{	
	require('config.php');
	$id_dns = -1;
	$id_domaine=-1;
			
	// On se connecte à ISpConfig
	$client = new SoapClient(null, array('location' => $soap_location,
									 'uri'      => $soap_uri,
									 'trace' => 1,
									 'exceptions' => 1));								
	try 
	{		
		if($session_id = $client->login($username,$password)) {
			// connection réussie
		}
		
		// Generate password
		$pass = random_password(14);	
		
		// Etant donné qu'ISPconfig ne semble pas supporter la création 
		// directement par la fonction d'ajout de vm. On va ajouter par 
		// un template, puis update avec nos paramètres
		
		$ip = vps_get_free_ip();		
		
		$params = array(
			'server_id' => $server_id,
			'vm_password' => $pass,
			'ip_address' => $ip["ip_address"],
			'hostname' => 'vps'.$service->{'id'}.'.'.$domain,
		);
		$id_domaine = $client->openvz_vm_add_from_template($session_id, $client_id, $ostemplate_id, $template_id, $params);
		
		// On enregistre le VEID du vps dans la bdd
		$vm_record = $client->openvz_vm_get($session_id, $id_domaine);
		set_row('#__gpr_vps','veid',$vm_record['veid'],'id',$service->{'id'});
		
		
		// On enregistre l'id domaine du vps dans la bdd
		set_row('#__gpr_vps','id_domaine',$id_domaine,'id',$service->{'id'});
		
		// On enregistre l'IP du vps dans la bdd
		set_row('#__gpr_vps','adresse_ip',$ip["ip_address"],'id',$service->{'id'});
		
		
		//Maintenant la redirection DNS
		$server_id = 1;
		$params = array(
			'server_id' => 1,
			'zone' => 1,
			'name' => 'vps' . $service->{'id'},
			'type' => 'a',
			'data' => $ip["ip_address"],
			'aux' => '0',
			'ttl' => '86400',
			'active' => 'y',
			'stamp' => 'CURRENT_TIMESTAMP',
			'serial' => '1',
			);
		
		// ON Ajoute un A record à ispconfig
		$id_dns = $client->dns_a_add($session_id, $client_id, $params);
		
		// On enregistre l'id dns du vps dans la bdd
		set_row('#__gpr_vps','id_dns',$id_dns,'id',$service->{'id'});
		set_row('#__gpr_vps','hostname','vps'.$service->{'id'}.'.'.$domain,'id',$service->{'id'});
		
		if($client->logout($session_id)) 
		{
			// echo 'Logged out.<br />';
		}
		// echo 'admin projet : '.$admin->{'id_joomla'}.'  '; 
		// Maintenant on envoit un mail
		send_mail($admin->{'id_joomla'},
			'Installation '.$vm_record['hostname'],
			'<html>
			  <head>
			   <title>Installation '.$vm_record['hostname'].'</title>
			  </head>
			  <body>
			   <p>Votre VPS est en cours d'."'".'installation, il sera disponible dans peu de temps.</p>
			   <p>Informations :</p>
			   <p>Votre login : root</p>
			   <p>Mot de passe : '.$vm_record['vm_password'].'</p>
			   <p>Hostname : vps'.$service->{'id'}.'.'.$domain.'</p>
			   <p>Adresse IP : '.$vm_record['ip_address'].'</p>
			   <p>Espace disque  : '.$vm_record['diskspace'].'Go<p>
			   <p>Ram : '.$vm_record['ram'].'Mo</p>
			   <p>Ram burst : '.$vm_record['ram_burst'].'Mo</p>
			   <p>Nombre de CPU : '.$vm_record['cpu_num'].'</p>
			   <p>Limite utilisation CPU : '.$vm_record['cpu_limit'].'%</p>
			   <p>Veuillez ne pas répondre à cet e-mail. Si vous avez des questions, contactez un admin.</p>
			  </body>
			 </html>'
			 );
	}
	catch (SoapFault $e) 
	{
		echo 'error : '.$client->__getLastResponse().'
';
		
		// Time to clean up
		if ($id_dns != -1 or $id_domaine != -1)
		{
			vps_clean_up($id_domaine, $id_dns);
		}
		return false;
	}
	return true;
}

function vps_delete($service)
{
	require('config.php');
	// On se connecte à ISpConfig
	$client = new SoapClient(null, array('location' => $soap_location,
									 'uri'      => $soap_uri,
									 'trace' => 1,
									 'exceptions' => 1));
	try {
		if($session_id = $client->login($username,$password)) {
			//echo 'Logged successfull. Session ID:'.$session_id.'<br />';
		}

		//* Parameters
		$id_dns = $service->{'id_dns'};
		$id_domaine = $service->{'id_domaine'};

		$client->openvz_vm_delete($session_id, $id_domaine);
		$client->dns_a_delete($session_id, $id_dns);
		
		if($client->logout($session_id)) {
			// echo 'Logged out.<br />';
		}		
	} 
	catch (SoapFault $e) 
	{
		echo $client->__getLastResponse().'
';
		die('SOAP Error: '.$e->getMessage());
		return false;
	}
	return true;
}

function vps_clean_up($id_domaine, $id_dns)
{
	require('config.php');
	// On se connecte à ISpConfig
	$client = new SoapClient(null, array('location' => $soap_location,
									 'uri'      => $soap_uri,
									 'trace' => 1,
									 'exceptions' => 1));
	try {
		if($session_id = $client->login($username,$password)) {
			// echo 'Logged successfull. Session ID:'.$session_id.'<br />';
		}

		if ($id_domaine != -1 ) $client->openvz_vm_delete($session_id, $id_domaine);
		if ($id_dns != -1) $client->dns_a_delete($session_id, $id_dns);
		
		if($client->logout($session_id)) {
			// echo 'Logged out.<br />';
		}		
	} 
	catch (SoapFault $e) 
	{
		echo $client->__getLastResponse().'
';
		die('SOAP Error: '.$e->getMessage());
		return false;
	}
	return true;
}





	
// Renvoit la liste des IP libres
function vps_get_free_ip()
{	
	require('config.php');
	// On se connecte à ISpConfig
	$client = new SoapClient(null, array('location' => $soap_location,
									 'uri'      => $soap_uri,
									 'trace' => 1,
									 'exceptions' => 1));								
	try 
	{		
		if($session_id = $client->login($username,$password)) {
			// echo 'Logged successfull. Session ID:'.$session_id.'';
		}
		$free_ip = $client->openvz_get_free_ip($session_id, $server_id = 0);

		if($client->logout($session_id)) {
		// echo 'Logged out.<br />';
	}
	}
	catch (SoapFault $e) 
	{
		echo 'error : '.$client->__getLastResponse().'
';
		return false;
	}
	return $free_ip;
}
function vps_update_quota($veid_service)
{
	require('config.php');

	$out = array();
	$status = -1;	

	try 
	{
		//On récupère le quota
		exec('sh '.JPATH_BASE.'/components/com_gpr/Leeroy/vps_get_quota.sh '.$veid_service, $out, $status );
		if ( $status != 0 )
		{
			//Erreur dans le shell
			echo 'erreur shell : vps_update_quota';
			return false;
		}
		set_row('#__gpr_vps','quota_used',$out[0]/1024,'veid',$veid_service);	
	}
	catch(SoapFault $e) 
	{
		echo 'error : '.$client->__getLastResponse().'
';
	}
}

function vps_refresh_all_quota_used()
{
	try
	{
		// Get a db connection.
		$db = JFactory::getDbo();
		// Create a new query object.
		$query_vps_quota = $db->getQuery(true);
		$query_vps_quota
			->select('*')
			->from('#__gpr_vps AS service')
			->where("service.etat ='ok'");						
		// Reset the query using our newly populated query object.
		$db->setQuery($query_vps_quota);
		// Load the results as a list of stdClass objects.
		$liste = $db->loadObjectList();
		foreach($liste as $service)
		{
			vps_update_quota($service->{'veid'});
		}
	}
	catch(Exception $e)
	{
		 $e->getMessage();
		// On arrête la création de en cas d'erreur
	}
}
?>