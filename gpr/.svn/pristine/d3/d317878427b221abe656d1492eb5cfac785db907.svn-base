<?php
 /* Fonction permettant d'envoyer un email */
function send_mail($user_id,$subject,$body)
{
	$user = JFactory::getUser($user_id);
	$to = $user->email;
	if($to =='') return;
	echo 'mail : '. $to .'
';
	
	 // plusieurs destinataires
     //$to  = 'aidan@example.com' . ', '; // notez la virgule
     //$to .= 'wez@example.com';


     // pour envoyer un mail HTML, l'en-tête Content-type doit être défini
     $headers  = 'MIME-Version: 1.0' . "\r\n";
     $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

     // En-têtes additionnels
     $headers .= 'To: '.$user->username.'<'.$to.'>' . "\r\n";
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

function delete_row($table,$column_cond,$value)
{
	$db = JFactory::getDbo();
	$query_delete = $db->getQuery(true);
	$condition = $column_cond.'='.$value;
	$result = 0;
	// delete all custom keys for user 1001.
	/*
		$conditions = array(
		'user_id=1001', 
		'profile_key LIKE \'custom.%\'');
	 */
	$query_delete->delete($db->quoteName($table));
	$query_delete->where($condition);
	$db->setQuery($query_delete); 
	try 
	{
	   $result = $db->execute(); // $db->execute(); for Joomla 3.0.
	} 
	catch (Exception $e) 
	{
	   // catch the error.
	   echo $e->getMessage();
	}
	return $result;
}
function set_row($table,$column,$value,$id_name,$id)
{
	// Get a db connection.
	$db = JFactory::getDbo();
	 
	// Create a new query object.
	$query_add = $db->getQuery(true);
	 
	//Build the query
	$query_add->update($table);
	$query_add->set($column.' = '.$db->quote($value));
	$query_add->where($id_name.' = '. $db->quote($id));
	$db->setQuery($query_add);
	 
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
function add_row($table,$columns,$values)
/*	
	$table = '#__blablabla'
	$columns = array('user_id', 'profile_key', 'profile_value', 'ordering');
	$values = array(1001, $db->quote('custom.message'), $db->quote('Inserting a record using insert()'), 1);
*/
{
	// Get a db connection.
	$db = JFactory::getDbo();
	 
	// Create a new query object.
	$query = $db->getQuery(true);
	 

	// Prepare the insert query.
	$query
		->insert($db->quoteName($table))
		->columns($db->quoteName($columns))
		->values(implode(',', $values));
		
	// Reset the query using our newly populated query object.
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
function get_string_between($string, $start, $end){
	$string = " ".$string;
	$ini = strpos($string,$start);
	if ($ini == 0) return "";
	$ini += strlen($start);   
	$len = strpos($string,$end,$ini) - $ini;
	return substr($string,$ini,$len);
}

function IsNullOrEmptyString($question){
    return (!isset($question) || trim($question)==='');
}
?>