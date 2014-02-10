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
		JHtmlSidebar::addEntry(
			JText::_('COM_GPR_TITLE_LISTEIP'),
			'index.php?option=com_gpr&view=listeip',
			$vName == 'listeip'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_GPR_TITLE_MONITORING'),
			'index.php?option=com_gpr&view=monitoring',
			$vName == 'monitoring'
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
	
	
	/**
	 * Renvoit le nombre de Mb utilisé par le vps
	 *
	 * @return	float
	 */
	public static function getVpsDiskUsed($vps)
	{
		ini_set("precision", "3");
		$out = shell_exec('du -sm /vz/ | sed -r "s/^([0-9\.]+).+/\1/" && exit');
		return $out/1024;
	}
}