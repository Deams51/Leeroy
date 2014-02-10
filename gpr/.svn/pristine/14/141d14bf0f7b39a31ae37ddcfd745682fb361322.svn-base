<?php

if (file_exists(__DIR__ . '/defines.php'))
{
	include_once __DIR__ . '/defines.php';
}
define('_JEXEC', 1);

if (!defined('_JDEFINES'))
{
	define('JPATH_BASE', __DIR__);
	require_once JPATH_BASE . '/includes/defines.php';
}
require_once JPATH_BASE . '/includes/framework.php';




/* Pour les erreurs */

ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);


// Get a db connection.
$db = JFactory::getDbo();
// Create a new query object.
$query = $db->getQuery(true);

// On init la query
$query
    ->select('*')
    ->from('test');
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
foreach ($results as $object) {
	switch($object->{'action'}) {
		case 'Ajouter':
			// On ajoute le sous domaine. Pour ça on se connecte a l'api ISPCONFIG
			require('soap_config.php');
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
						'domain' => $object->{'data'},
						'type' => 'vhost',
						'parent_domain_id' => 0,
						'vhost_type' => 'name',
						'hd_quota' => 100000,
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
						'backup_interval' => '<Location />
	  DAV svn
	  SVNPath /var/www/'.$object->{'data'}.'/web/svn/Projet
	  AuthType Basic
	  AuthName "Subversion Repository"
	  AuthUserFile  /var/www/'.$object->{'data'}.'/web/svn/Projet/conf/passwd
	  <LimitExcept GET PROPFIND OPTIONS REPORT>
		Require valid-user
	  </LimitExcept>
	</Location>',
						'backup_copies' => 1,
						'active' => 'y',
						'traffic_quota_lock' => 'n'
						);
				
				$affected_rows = $client->sites_web_domain_add($session_id, $client_id, $params, $readonly = false);
				
				echo "Web Domain ID: ".$affected_rows."<br>";

				
				if($client->logout($session_id)) {
					echo 'Logged out.<br />';
				}
				
				
			} catch (SoapFault $e) {
				echo $client->__getLastResponse();
				die('SOAP Error: '.$e->getMessage());
			}
			// FIN ISPCONFIG

			
			// Script de création des fichiers et SVN
			echo  '['.$object->{'id'}.'] Ajouter : '.$object->{'data'};
			$output = shell_exec('sh /home/gpr/create.sh svn1.stic-test.tk');
			echo  "<pre>$output</pre>";
			break;
		case 'Supprimer':
			echo '['.$object->{'id'}.'] Supprimer : '.$object->{'data'};
			break;
	}
	echo '<br/>';
}
?>