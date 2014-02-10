<?php
	if (file_exists(__DIR__ . '/config.php'))
	{
		include_once __DIR__ . '/config.php';
	}
	if (file_exists(__DIR__ . '/svn.php'))
	{
		include_once __DIR__ . '/svn.php';
	}
	
	//init Joomla Framework
	define( '_JEXEC', 1 );
	define( 'DS', DIRECTORY_SEPARATOR );
	define( 'JPATH_BASE', realpath(dirname(__FILE__).DS.'..'.DS.'..'.DS.'..' ));
	
	require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
	require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

	$mainframe = JFactory::getApplication('site');

	/* Pour les erreurs */

	ini_set('display_errors', 'On');
	error_reporting(E_ALL | E_STRICT);

    echo JPATH_BASE .'
	';
	// Get a db connection.
	$db = JFactory::getDbo();
	// Create a new query object.
	$query = $db->getQuery(true);

	// On init la query
	$query
		->select('*')
		->from('gpr_updates');
	 /*
	// Select all articles for users who have a username which starts with 'a'.
	// Order it by the created date.
	$query
		->select(array('', 'b.username', 'b.name'))
		->from('#__content AS a')
		->join('INNER', '#__users AS b ON (a.created_by = b.id)')
		->where('b.username LIKE \'a%\'')
		->order('a.created DESC');
	 */
	 
	// Reset the query using our newly populated query object.
	$db->setQuery($query);

	// Load the results as a list of stdClass objects.
	$results = $db->loadObjectList();
	var_dump($results);
	echo 'test2
	';
	foreach ($results as $object) 
	{
		echo 'foreach
		';
		switch($object->{'action'}) 
		{
			case 'svn_create':
			echo 'svn_create
			';
				/* On récupère les arguments nécessaire dans la bdd */
				/* On a besoin du domaine (dans le fichier de config)
				de l'id du service (table gpr_update)
				de la taille du svn (table #__gpr_svn)
				des droits à paramétrer // // // //
				des username des membres à ajouter (tables membres)
				des mots de passe pour chaque membres (généré, puis envoyer à la fin par email si tout c'est bien passé)
				donc leur emails aussi
				*/
				$id_service = $object->{'id_service'};
	echo 'test3
	';
				try
				{
				// Create a new query object.
				$query_svn = $db->getQuery(true);

				$query_svn
					->select('*')
					->from('#__gpr_svn AS service')
					->where('service.id = '.$id_service);
	echo 'test4
	';
				// Reset the query using our newly populated query object.
				$db->setQuery($query_svn);
	echo 'test5
	';

				// Load the results as a list of stdClass objects.
				$liste = $db->loadObjectList();
				echo 'test6
	';
				// On ne s'intéresse qu'au premier résultat
				$service = $liste[0];
	echo 'test7
	';
				// Create a new query object.
				$query_svn_membres = $db->getQuery(true);
				
				// On va récupérer les membres du projet
				$query_svn_membres
					->select('*')
					->from('#__gpr_membres AS m')
					->where('m.id_projet = '.$service->{'id_projet'});
	echo 'test8
	';
				// Reset the query using our newly populated query object.
				$db->setQuery($query_svn_membres);
	echo 'test9
	';

				// Load the results as a list of stdClass objects.
				$membres = $db->loadObjectList();

				
	echo 'test10
	';
				}
				catch(Exception $e)
			{
					echo $e->getMessage();
				
				return false;
			}	
				/* On appel la création */
				$res = svn_create($service,$membres);
				if($res != true)
				{
					echo 'error svn_create';
				}
				else
				{
					echo 'success create
					';
				}
				break;
			case 'Supprimer':
				echo '['.$object->{'id'}.'] Supprimer : '.$object->{'data'};
				break;
		}
	}
	
function svn_create($service,$membres)
{	
	var_dump($service);
	var_dump($membres);
	require('config.php');


$client = new SoapClient(null, array('location' => $soap_location,
                                     'uri'      => $soap_uri,
									 'trace' => 1,
									 'exceptions' => 1));


try {
	if($session_id = $client->login($username,$password)) {
		echo 'Logged successfull. Session ID:'.$session_id.'<br />';
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
			'apache_directives' => '',
			'php_open_basedir' => '/',
			'custom_php_ini' => '',
			'backup_interval' => '',
			'backup_copies' => 1,
			'active' => 'y',
			'traffic_quota_lock' => 'n'
			);
	$params['pm_max_requests'] = 0;
	$params['pm_process_idle_timeout'] = 10;
	
		$affected_rows = $client->sites_web_domain_add($session_id, $client_id, $params, $readonly = false);
		
		echo "Web Domain ID: ".$affected_rows."<br>";

		
	$server_id = 1;
	$record_record = $client->dns_zone_get_by_user($session_id, $client_id, $server_id);
	print_r($record_record);
	var_dump($record_record);
	echo "<br>";
		
	
		
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
	
		$id = $client->dns_a_add($session_id, $client_id, $params);

	echo "ID: ".$id."<br>";

	if($client->logout($session_id)) {
		echo 'Logged out.<br />';
	}
	
	
} catch (SoapFault $e) {
	echo $client->__getLastResponse();
	die('SOAP Error: '.$e->getMessage());
}


	echo 'ok';
    return true;
}
?>