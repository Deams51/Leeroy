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

	/* pour les erreurs */

	ini_set('display_errors', 'On');
	error_reporting(E_ALL | E_STRICT);

	// Get a db connection.
	$db = JFactory::getDbo();
	// Create a new query object.
	$query = $db->getQuery(true);

	// On init la query
	$query
		->select('*')
		->from('#__gpr_updates');
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
	echo 'Objets dans table update : ';
	var_dump($results);	
	echo '';
	foreach ($results as $object) 
	{
		switch($object->{'action'}) 
		{
			case 'svn_create':
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
				try
				{
					// Create a new query object.
					$query_svn = $db->getQuery(true);

					$query_svn
						->select('*')
						->from('#__gpr_svn AS service')
						->where('service.id = '.$id_service);
					// Reset the query using our newly populated query object.
					$db->setQuery($query_svn);

					// Load the results as a list of stdClass objects.
					$liste = $db->loadObjectList();
					// On ne s'intéresse qu'au premier résultat
					$service = $liste[0];
					// Create a new query object.
					$query_svn_membres = $db->getQuery(true);
					
					// On va récupérer les membres du projet
					$query_svn_membres
						->select('*')
						->from('#__gpr_membres AS m')
						->where('m.id_projet = '.$service->{'id_projet'});
					// Reset the query using our newly populated query object.
					$db->setQuery($query_svn_membres);

					// Load the results as a list of stdClass objects.
					$membres = $db->loadObjectList();
					
				}
				catch(Exception $e)
				{
					echo $e->getMessage();
					// On arrête la création de en cas d'erreur
					return false;
				}	
				/* On appel la création */
				$res = svn_create($service,$membres);
				if($res != true)
				{
					echo 'svn_create : failed';
				}
				else
				{
					echo 'svn_create : success';
				}
				break;
			case 'Supprimer':
				echo '['.$object->{'id'}.'] Supprimer : '.$object->{'data'};
				break;
		}
	}
	
/* Fonction permettant d'envoyer un email */
function send_mail($user_id,$subject,$body)
{
	$user = JFactory::getUser($user_id);
	$to = $user->email;
	
	 // plusieurs destinataires
     //$to  = 'aidan@example.com' . ', '; // notez la virgule
     //$to .= 'wez@example.com';


     // pour envoyer un mail HTML, l'en-tête Content-type doit être défini
     $headers  = 'MIME-Version: 1.0' . "\r\n";
     $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

     // En-têtes additionnels
     //$headers .= 'To: Mary <mary@example.com>, Kelly <kelly@example.com>' . "\r\n";
     $headers .= 'From: postmaster <postmaster@stic-test.tk>' . "\r\n";
     //$headers .= 'Cc: anniversaire_archive@example.com' . "\r\n";
     //$headers .= 'Bcc: anniversaire_verif@example.com' . "\r\n";

     // Envoi
     mail($to, $subject, $body, $headers);
	 
	 
/*
	$mailer =& JFactory::getMailer();
	$config =& JFactory::getConfig();
	$sender = array( 
		$config->get( 'config.mailfrom' ),
		$config->get( 'config.fromname' ) );
	 
	$mailer->setSender($sender);
	$user =& JFactory::getUser($user_id);
	$recipient = $user->email;
	 
	$mailer->addRecipient($recipient);
	$mailer->setSubject('Installation');
	$mailer->setBody($body);
	$send =& $mailer->Send();
	if ( $send !== true )
	{
		echo 'Error sending email: ' . $send->message;
	} 
	else 
	{
		echo 'Mail sent';
	}
	*/
}

function random_password( $length = 8, $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789' ) 
{
    return substr( str_shuffle( $chars ), 0, $length );
}

?>