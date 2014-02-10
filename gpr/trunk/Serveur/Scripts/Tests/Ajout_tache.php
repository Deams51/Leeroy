<?php
if (file_exists(__DIR__ . '/defines.php'))
{
	include_once __DIR__ . '/defines.php';
}
if (!defined('_JDEFINES'))
{
	define('JPATH_BASE', __DIR__);
	require_once JPATH_BASE . '/includes/defines.php';
}
require_once JPATH_BASE . '/includes/framework.php';
$app = JFactory::getApplication('site');
$app->initialise();

// Get a db connection.
$db = JFactory::getDbo();
 
// Create a new query object.
$query = $db->getQuery(true);
 
// Select all articles for users who have a username which starts with 'a'.
// Order it by the created date.
$query
    ->select(array('a.*', 'b.username', 'b.name'))
    ->from('#__content AS a')
    ->join('INNER', '#__users AS b ON (a.created_by = b.id)')
    ->where('b.username LIKE \'a%\'')
    ->order('a.created DESC');
 
// Reset the query using our newly populated query object.
$db->setQuery($query);
 
// Load the results as a list of stdClass objects.
$results = $db->loadObjectList();
?>