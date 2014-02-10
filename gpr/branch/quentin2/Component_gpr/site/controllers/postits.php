<?php
	/**
	 * @version     1.0.0
	 * @package     com_gpr
	 * @copyright   Copyright (C) 2013. All rights reserved.
	 * @license     GNU General Public License version 2 or later; see LICENSE.txt
	 * @author      Choulet Quentin, Fourgeaud Mickaël, Hauguel Antoine, Malory Tristan <> - 
	 */


	//empeche l'accès direct au fichier
	defined('_JEXEC') or die ('Restricted access');

	//import joomla controller library
	jimport('joomla.application.component.controller');

	require_once(JPATH_COMPONENT_SITE.'/controller.php');

	/**
	* Controleur du composant Gestion de Projet
	* Gère les tâches liées aux postits
	* 	-> afficher une note (AJAX controller)
	*	-> modifier une note (AJAX controller)
	*/
	class gprControllerMembres extends gprController
	{
		public function afficher()
		{

			$this->_display('afficher_note');
		}

		public function modifier()
		{
			$this->_display('modifier_note');
		}
	}