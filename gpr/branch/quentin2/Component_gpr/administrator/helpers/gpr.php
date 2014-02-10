<?php
/**
 * @version     1.0.0
 * @package     com_gpr
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Choulet Quentin, Fourgeaud Mickaël, Hauguel Antoine, Malory Tristan <> - 
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Gpr helper.
 */
class GprHelper
{
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($vName = '')
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_GPR_TITLE_PROJETS'),
			'index.php?option=com_gpr&view=projets',
			$vName == 'projets'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_GPR_TITLE_LISTESVN'),
			'index.php?option=com_gpr&view=listesvn',
			$vName == 'listesvn'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_GPR_TITLE_LISTEVPS'),
			'index.php?option=com_gpr&view=listevps',
			$vName == 'listevps'
		);

	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return	JObject
	 * @since	1.6
	 */
	public static function getActions()
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		$assetName = 'com_gpr';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
}