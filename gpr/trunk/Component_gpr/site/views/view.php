<?php
	/**
	 * @version     1.0.0
	 * @package     com_gpr
	 * @copyright   Copyright (C) 2013. All rights reserved.
	 * @license     GNU General Public License version 2 or later; see LICENSE.txt
	 * @author      Choulet Quentin, Fourgeaud Mickaël, Hauguel Antoine, Malory Tristan <> - 
	 */
	 
	/**
	  * Classe vue de base dont héritent toutes les vues
	*/

	//empeche l'accès direct au fichier
	defined('_JEXEC') or die ('Restricted access');

	//import joomla view library
	jimport('joomla.application.component.view');

	/**
	* HTML View class pour le composant Gestion de Projet
	*/
	class gprView extends JViewLegacy
	{
		//contient le message d'erreur
		protected $erreur;

		//contient le essage ou les données à afficher
		protected $msg;

		/**
		  * set message d'erreur
		  */
		public function setErreur($err)
		{
			$this->erreur = $err;
		}

		/**
		  * set message
		  */
		public function setMessage($message)
		{
			$this->msg = $message;
		}

		//Overwriting JView display method
		public function _display($tpl = '')
		{
			//Check for errrors
			if(count($errors = $this->get('Errors')))
			{
				JError::raiseError(500, implode('<br />', $errors));
				return false;
			}

			//display the view using $tpl as template
			parent::display($tpl);
		}
	}