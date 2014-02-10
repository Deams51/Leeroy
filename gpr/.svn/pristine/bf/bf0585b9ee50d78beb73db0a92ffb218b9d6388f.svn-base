<?php
	
// Crée un sous domaine, une redirection dns, un repos svn, les logins/mdp des membres existants et envoit un email individuel avec les infos
function svn_create($service,$membres)
{	
	var_dump($service);
	var_dump($membres);
	require('config.php');
	// On se connecte à ISpConfig
	$client = new SoapClient(null, array('location' => $soap_location,
									 'uri'      => $soap_uri,
									 'trace' => 1,
									 'exceptions' => 1));								
	try 
	{
		if($session_id = $client->login($username,$password)) {
			echo 'Logged successfull. Session ID:'.$session_id.'';
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
		echo "Web Domain ID: ".$id_domaine.'';
		set_row('#__gpr_svn','id_domaine',$id_domaine,'id',$service->{'id'});
		
		//Maintenant la redirection DNS
		$server_id = 1;
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
		
		// ON Ajoute un A record à ispconfig
		$id_dns = $client->dns_a_add($session_id, $client_id, $params);
		echo 'ID: '.$id_dns;
		set_row('#__gpr_svn','id_dns',$id,'id',$service->{'id'});
		
		//On se déconnecte 
		if($client->logout($session_id)) {
			echo 'Logged out.';
		}
		
		// On attends 1minute que ISpconfig fasse les modifs, crée les dossiers etc...
		sleep(60); // Oui l'attente active c'est moche... :'(
		
		// Création du dossier SVN et du compte Admin
		
		// On génère un mot de passe pour le chef du projet
		$admin_pass = random_password();
		$admin_username = null;
		foreach($membres as $membre)
		{
			if ($membre->{'statut'} == '1')
			{
				$user = JFactory::getUser($membre->{'id_joomla'});
				$admin_username = $user->username;
			}
		}
		if ($admin_username == null)
		{
			echo 'Erreur : username chef de projet non trouvé. Chef de projet existant ?';
		}
		echo ('id web : '.$id_domaine.'');
		echo ('call : sh '.JPATH_BASE.'/components/com_gpr/Leeroy/svn_create.sh svn'.
			$service->{'id'}.'.'.
			$domain.' '.
			$id_domaine.' '.
			$admin_username.' '.
			$service->{'droits'});
		echo (shell_exec('sh '.JPATH_BASE.'/components/com_gpr/Leeroy/svn_create.sh svn'.
			$service->{'id'}.'.'.
			$domain.' '.
			$id_domaine.' '.
			$admin_username.' '.
			$admin_pass.' '.
			$service->{'droits'}));
	}
	catch (SoapFault $e) {
		echo 'error : '.$client->__getLastResponse();
		return false;
	}

	echo 'svn_create success';
	echo 'Sending mail';
	foreach ($membres as $membre) 
	{
		// Le chef de projet a déja un login/mot de passe
		if($membre->{'statut'} == '1')
		{
			echo'admin/';
			$pass = $admin_pass;
			$username = $admin_username;
			echo $pass.'/'.$username;
		}
		// Les autres non, on génere
		else 
		{
			$pass = random_password();
			$user_to = JFactory::getUser($membre->{'id_joomla'});
			$username = $user_to->username;
			// On ajoute au fichier
			echo (shell_exec('sh '.JPATH_BASE.'/components/com_gpr/Leeroy/svn_add_user.sh svn'.
				$service->{'id'}.'.'.
				$domain.' '.
				$id_domaine.' '.
				$username.' '.
				$pass));
				
			echo'autre/';
			echo $pass.'/'.$username;
		}
		send_mail($membre->{'id_joomla'},
			'Installation svn'.$service->{'id'}.'.'.$domain,
			'<html>
			  <head>
			   <title>Installation svn'.$service->{'id'}.'.'.$domain.'</title>
			  </head>
			  <body>
			   <p>Votre SVN a bien été installé. Il est dès à présent disponible ici: http://svn'.$service->{'id'}.'.'.$domain.'/ </br></p>
			   <p>Votre login : '.$username.'</br></p>
			   <p>Mot de passe : '.$pass.'</br></p>
			   <p>Veuillez ne pas répondre à cet e-mail.</p>
			  </body>
			 </html>'
			 );
	}
	// On force Apache a recharger les config
	shell_exec('/etc/init.d/apache2 graceful  2>/var/log/projet.log');
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

function set_row($table,$column,$value,$id_name,$id)
{
	// Get a db connection.
	$db = JFactory::getDbo();
	 
	// Create a new query object.
	$query = $db->getQuery(true);
	 
	//Build the query
	$query->update($table);
	$query->set($column.' = '.$db->quote($value));
	$query->where($id_name.' = '. $db->quote($id));
	$db->setQuery($query);
	 
	//execute db object
	try 
	{
		// Execute the query in Joomla 3.0.
		$result = $db->execute();
	} 
	catch (Exception $e) 
	{
		//print the errors
		print_r($e);
	}
}
?>