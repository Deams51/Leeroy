<?php
/**
 * @version     1.0.0
 * @package     com_gpr
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Choulet Quentin, Fourgeaud Mickaël, Hauguel Antoine, Malory Tristan <> - 
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * Listesvn list controller class.
 */
class GprControllerListesvn extends JControllerAdmin
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'Listesvn', $prefix = 'GprModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
    
    
	/**
	 * Method to save the submitted ordering values for records via AJAX.
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function saveOrderAjax()
	{
		// Get the input
		$input = JFactory::getApplication()->input;
		$pks = $input->post->get('cid', array(), 'array');
		$order = $input->post->get('order', array(), 'array');

		// Sanitize the input
		JArrayHelper::toInteger($pks);
		JArrayHelper::toInteger($order);

		// Get the model
		$model = $this->getModel();

		// Save the ordering
		$return = $model->saveorder($pks, $order);

		if ($return)
		{
			echo "1";
		}

		// Close the application
		JFactory::getApplication()->close();
	}
    
	public function accept()
	{
		// Get the input
		$input = JFactory::getApplication()->input;
		$pks = $input->post->get('cid', array(), 'array');

		// Sanitize the input
		JArrayHelper::toInteger($pks);

		// Get the model
		$model = $this->getModel();
		
		$return = $model->accept($pks);

		// Redirect to the list screen.
		if($return) $this->setRedirect(JRoute::_('index.php?option=com_gpr&view=listesvn', false));
	}
    
	public function delete()
	{
		// Get the input
		$input = JFactory::getApplication()->input;
		$pks = $input->post->get('cid', array(), 'array');

		// Sanitize the input
		JArrayHelper::toInteger($pks);

		// Get the model
		$model = $this->getModel();
		
		$return = $model->delete($pks);

		// Redirect to the list screen.
		if($return) $this->setRedirect(JRoute::_('index.php?option=com_gpr&view=listesvn', false));
	}
    
}