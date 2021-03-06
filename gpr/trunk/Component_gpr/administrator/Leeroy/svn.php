<?php

// Crée un sous domaine, une redirection dns, un repos svn, les logins/mdp des membres existants et envoit un email individuel avec les infos
function svn_create($service,$membres)
{
	require('config.php');

	$id_domaine = -1;
	$id_dns = -1;

	// On crée le domaine
	$id_domaine = svn_create_domain($service);
	// La redirection DNS
	$id_dns = svn_create_dns($service);
	
	// Si aucune erreur on passe a l'étape 2
	// Sinon on nettoie
	if ($id_dns != -1 && $id_domaine != -1)
	{
		try
		{
			add_row('#__gpr_updates',array('id','action','id_service','arg'),array('null',"'svn_create2'",$service->{'id'},time()));
		}
		catch(Exception $e)
		{
			echo 'svn_create add_row: '.$e->getMessage();
			// On nettoie
			svn_clean_up($id_domaine, $id_dns);
			return false;
		}
	}
	else
	{
		svn_clean_up($id_domaine, $id_dns);
		return false;
	}
	return true;
}

function svn_create2($service,$membres)
{	
	require('config.php');

	// Création du dossier SVN
	$out = array();
	$status = -1;
	exec('sh '.JPATH_BASE.'/components/com_gpr/Leeroy/svn_create.sh svn' .$service->{'id'}. '.' .$domain. ' '.$service->{'taille'}.' 2>&1 >> /var/log/projet.log', $out, $status);	
	if ( $status != 0 )
	{
		//Erreur dans le shell
		echo 'erreur shell : svn_create';
		return false;
	}
	// Ajout des membres
	foreach($membres as $membre)
	{
		if(isset($membre))
		{
			// On récupère l'user joomla
			$user = JFactory::getUser($membre->{'id_joomla'});
			// L'username joomla
			$username_joomla = $user->username;
			// On génère un mot de passe aléatoire
			$pass_svn = random_password();
			// On ajoute l'utilisateur
			if (IsNullOrEmptyString($username_joomla) or IsNullOrEmptyString($pass_svn))
			{
				echo 'User ('.$username_joomla.' seems unvalid';
				break;
			}
			else if(!svn_add_member($service,$membre,$username_joomla,$pass_svn))
			{
				echo 'Erreur svn_add_member in svn.php';
				return false;
			}
		}
	}
	if($service->{'droits'} == '1')
	{
			svn_add_anonymous($service);
	}
	// On modifie les droits dossiers, et owner
	exec( 'sh '.JPATH_BASE.'/components/com_gpr/Leeroy/svn_own_mod.sh svn'.$service->{'id'}.'.'.$domain.' '.$service->{'id_domaine'}.' 2>&1 >> /var/log/projet.log', $out, $status );
	if ( $status != 0 )
	{
		//Erreur dans le shell
		echo 'erreur shell : svn_own_mod';
		return false;
	}
	return true;
}

function svn_create_domain($service)
{
	require('config.php');

	$id_domaine = -1;
	
	// On se connecte à ISpConfig
	$client = new SoapClient(null, array('location' => $soap_location,
									 'uri'      => $soap_uri,
									 'trace' => 1,
									 'exceptions' => 1));								
	try 
	{
	
		if($session_id = $client->login($username,$password)) {
			//echo 'Logged successfull. Session ID:'.$session_id.'';
		}

		//* Set the function parameters.
		$client_id = 0;
		$params = array(
			'server_id' => 1,
			'ip_address' => '*',
			'domain' => 'svn'.$service->{'id'}.'.'.$domain,
			'type' => 'vhost',
			'parent_domain_id' => 0,
			'vhost_type' => 'name',
			'hd_quota' => $service->{'taille'},
			'traffic_quota' => -1,
			'cgi' => 'y',
			'ssi' => 'y',
			'suexec' => 'y',
			'errordocs' => 1,
			'is_subdomainwww' => 1,
			'subdomain' => '',
			'php' => 'y',
			'ruby' => 'n',
			'redirect_type' => '',
			'redirect_path' => '',
			'ssl' => 'n',
			'ssl_state' => '',
			'ssl_locality' => '',
			'ssl_organisation' => '',
			'ssl_organisation_unit' => '',
			'ssl_country' => '',
			'ssl_domain' => '',
			'ssl_request' => '',
			'ssl_cert' => '',
			'ssl_bundle' => '',
			'ssl_action' => '',
			'stats_password' => '',
			'stats_type' => 'webalizer',
			'allow_override' => 'All',
			'apache_directives' => get_directives_apache('svn'.$service->{'id'}.'.'.$domain),
			'php_open_basedir' => '/',
			'custom_php_ini' => '',
			'backup_interval' => '',
			'backup_copies' => 1,
			'active' => 'y',
			'traffic_quota_lock' => 'n'
			);
		$params['pm_max_requests'] = 0;
		$params['pm_process_idle_timeout'] = 10;

		// On ajoute le domaine à ISpConfig
		$id_domaine = $client->sites_web_domain_add($session_id, $client_id, $params, $readonly = false);		
		//echo "Web Domain ID: ".$id_domaine.'';
		set_row('#__gpr_svn','id_domaine',$id_domaine,'id',$service->{'id'});
		
		//On se déconnecte 
		if($client->logout($session_id)) 
		{
			//echo 'Logged out.';
		}
	}
	catch(SoapFault $e) 
	{
		$last = $client->__getLastResponse();
		echo 'error create_svn domain in svn.php: '.$last;
	}
	return $id_domaine;
}

function svn_create_dns($service)
{
	require('config.php');

	$id_dns = -1;
	
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

		//* Set the function parameters.
		$params = array(
			'server_id' => 1,
			'zone' => 1,
			'name' => 'svn' . $service->{'id'},
			'type' => 'a',
			'data' => '91.121.222.30',
			'aux' => '0',
			'ttl' => '86400',
			'active' => 'y',
			'stamp' => 'CURRENT_TIMESTAMP',
			'serial' => '1',
			);
		// On ajoute le domaine à ISpConfig
		$id_dns = $client->dns_a_add($session_id, $client_id, $params);
		// echo 'ID: '.$id_dns;
		set_row('#__gpr_svn','id_dns',$id_dns,'id',$service->{'id'});

		//On se déconnecte 
		if($client->logout($session_id)) 
		{
			//echo 'Logged out.';
		}
	}
	catch(SoapFault $e) 
	{
		echo 'erreur svn_create dns :'.$client->__getLastResponse();
	}
	return $id_dns;
}

function svn_delete($service)
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

		//* Parameters
		$id_dns = $service->{'id_dns'};
		$id_domaine = $service->{'id_domaine'};

		$client->sites_web_domain_delete($session_id, $id_domaine);
		$client->dns_a_delete($session_id, $id_dns);
		
		if($client->logout($session_id)) {
			// echo 'Logged out.<br />';
		}		
	} 
	catch (SoapFault $e) 
	{
		echo $client->__getLastResponse();
		die('SOAP Error: '.$e->getMessage());
		return false;
	}
	return true;
}

/* Fonction permettant de renvoyer la configuration type
pour apache */
function get_directives_apache($url)
{	
	$directives_apache = '<Location />
# Cette ligne active le repos
DAV svn
# Chemin du repo
SVNpath /var/www/'.$url.'/web/svn/projet
# système authentification
AuthType Basic
AuthName "Repository '.$url.'"
AuthUserFile /var/www/'.$url.'/web/svn/projet/conf/passwd
AuthzSVNAccessFile /var/www/'.$url.'/web/svn/projet/conf/authz
# The following line will allow to fall back to authenticated
# access when anonymous fails.
Satisfy Any
Require valid-user
</Location>';
	return $directives_apache;
}

function svn_add_member($service,$membre,$username_joomla,$pass_joomla)
{
	require('config.php');
	
	$out = array();
	$status = -1;
	exec( 'sh '.JPATH_BASE.'/components/com_gpr/Leeroy/svn_add_member.sh svn'.$service->{'id'}.'.'.$domain.' '.$username_joomla.' '.$pass_joomla.' 2>&1 >> /var/log/projet.log', $out, $status );
	if ( $status != 0 )
	{
		//Erreur dans le shell
		echo 'erreur shell : svn_add_member '.$status.'
';
		return false;
	}
	// Tout s'est bien passé, on envoit le mail
	send_mail($membre->{'id_joomla'},'Installation svn'.$service->{'id'}.'.'.$domain,
		'<html>
		  <head>
		   <title>Installation svn'.$service->{'id'}.'.'.$domain.'</title>
		  </head>
		  <body>
		   <p>Votre SVN a bien été installé. Il est dès à présent disponible ici: http://svn'.$service->{'id'}.'.'.$domain.'/ </br></p>
		   <p>Votre login : '.$username_joomla.'</br></p>
		   <p>Mot de passe : '.$pass_joomla.'</br></p>
		   <p>Veuillez ne pas répondre à cet e-mail.</p>
		  </body>
		 </html>'
		 );
	return true;
}
function svn_add_anonymous($service)
{
	require('config.php');
	
	$out = array();
	$status = -1;
	exec( 'sh '.JPATH_BASE.'/components/com_gpr/Leeroy/svn_add_member_anonymous.sh svn'.$service->{'id'}.'.'.$domain.' 2>&1 >> /var/log/projet.log', $out, $status );
	if ( $status != 0 )
	{
		//Erreur dans le shell
		echo 'erreur shell : svn_add_member';
		return false;
	}
	return true;
}

function svn_remove_member($service,$membre)
{

}

function svn_clean_up($id_domaine, $id_dns)
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

		if ($id_domaine != -1 ) $client->sites_web_domain_delete($session_id, $id_domaine);
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
function svn_update_quota($id_service)
{
	require('config.php');

	$out = array();
	$status = -1;	

	try 
	{
		//On récupère le quota
		exec( 'sh '.JPATH_BASE.'/components/com_gpr/Leeroy/svn_get_quota.sh svn'.$id_service.'.'.$domain.' 2>&1 >> /var/log/projet.log', $out, $status );
		if ( $status == -1 )
		{
			//Erreur dans le shell
			echo 'erreur shell : svn_get_quota';
			return false;
		}
		set_row('#__gpr_svn','quota_used',$status,'id',$id_service);	
	}
	catch(SoapFault $e) 
	{
		echo 'error : '.$client->__getLastResponse().'
';
	}
	echo 'Status : '.$status.'
';
	echo 'out : '.var_dump($out).'
';
}
?>