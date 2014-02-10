<?php
/**
 * @version     1.0.0
 * @package     com_gpr
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Choulet Quentin, Fourgeaud Mickaël, Hauguel Antoine, Malory Tristan <> - 
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Gpr records.
 */
class GprModellistesvn extends JModelList
{

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                                'id', 'a.id',
                'ordering', 'a.ordering',

            );
        }

        parent::__construct($config);
    }


	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $app->getUserStateFromRequest($this->context.'.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $published);
        
        
        
		// Load the parameters.
		$params = JComponentHelper::getParams('com_gpr');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.id', 'asc');
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param	string		$id	A prefix for the store id.
	 * @return	string		A store id.
	 * @since	1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id.= ':' . $this->getState('filter.search');
		$id.= ':' . $this->getState('filter.state');

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	JDatabaseQuery
	 * @since	1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.*'
			)
		);
		$query->from('`#__gpr_svn` AS a');

		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
                
			}
		}
        
        
        
        
		// Add the list ordering clause.
        $orderCol	= $this->state->get('list.ordering');
        $orderDirn	= $this->state->get('list.direction');
        if ($orderCol && $orderDirn) {
            $query->order($db->escape($orderCol.' '.$orderDirn));
        }

		return $query;
	}
	
	public function accept($pks)
	{
		// Get a db connection.
		$db = JFactory::getDbo();
		
		// Pour chacun des services donnés en paramètre on modifie l'état et on ajoute à la table d'update
		foreach($pks as $id)
		{
			// On update le champ dans la table svn
			// Create a new query object.
			$query = $db->getQuery(true);
			 
			//Build the query
			$query->update('#__gpr_svn');
			$query->set('etat = '.$db->quote('ok'));
			$query->where('id = '. $db->quote($id));
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
				return false;
			}
			
			// On ajoute à la table update
			// Prepare the insert query.
			$query_add = $db->getQuery(true);
			
			$query_add
				->insert($db->quoteName('#__gpr_updates'))
				->columns($db->quoteName(array('id','action','id_service','arg')))
				->values(implode(',',array('null',"'svn_create'",$id,time())));
		
			// Reset the query using our newly populated query object.
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
				return false;
			}
		}
		// perform whatever you want on each item checked in the list
		return true;
	}
	public function delete($pks)
	{
		// Get a db connection.
		$db = JFactory::getDbo();
		
		// Pour chacun des services donnés en paramètre on modifie l'état et on ajoute à la table d'update
		foreach($pks as $id)
		{
			// On update le champ dans la table svn
			// Create a new query object.
			$query = $db->getQuery(true);
			 
			//Build the query
			$query->update('#__gpr_svn');
			$query->set('etat = '.$db->quote('deleting'));
			$query->where('id = '. $db->quote($id));
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
				return false;
			}
			
			// On ajoute à la table update
			// Prepare the insert query.
			$query_add = $db->getQuery(true);
			
			$query_add
				->insert($db->quoteName('#__gpr_updates'))
				->columns($db->quoteName(array('id','action','id_service','arg')))
				->values(implode(',',array('null',"'svn_delete'",$id,time())));
		
			// Reset the query using our newly populated query object.
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
				return false;
			}
		}
		// perform whatever you want on each item checked in the list
		return true;
	}

}
