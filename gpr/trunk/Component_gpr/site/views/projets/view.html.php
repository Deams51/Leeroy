<?php
	/**
	 * @version     1.0.0
	 * @package     com_gpr
	 * @copyright   Copyright (C) 2013. All rights reserved.
	 * @license     GNU General Public License version 2 or later; see LICENSE.txt
	 * @author      Choulet Quentin, Fourgeaud Mickaël, Hauguel Antoine, Malory Tristan <> - 
	 */

	/**
	  * Vue pour le côté utilisateur du composant gestion de projet
	  * Gère les vues liées aux projets
	*/

	//empeche l'accès direct au fichier
	defined('_JEXEC') or die ('Restricted access');

	jimport('joomla.application.component.view');

	require_once(JPATH_COMPONENT_SITE.'/views/view.php');

	/**
	* HTML View class pour le composant Gestion de Projet
	*/
	class gprViewProjets extends JViewLegacy
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

		/**
		  * Récupère la liste des projets et tps de l'utilisateur et les affiche
		  * @param $user - int : L'utilisateur dont on veut récupérer les projets/tps
		  */
		public function afficher_projets($user)
		{
			$model = $this->getModel();
			$this->msg = $model->getProjTp($user->id);

			if($this->msg !== false)
			{
				$this->_display('afficher_projets');
			}
			else
			{
				$this->erreur = $model->getErreur();
				$this->_display('erreur');
			}
		}

		/**
		  * Récupère le projet en question et l'affiche
		  * @param : $id_projet - int : le projet à afficher
		  * @return void
		  */
		public function afficher_projet($id_projet)
		{
			$model = $this->getModel();
			$this->msg = array();

			//on récupère les infos du projet
			$this->msg['infos_projet'] = $model->getProjet($id_projet);

			//on vérifie qu'on a un résultat
			if($this->msg['infos_projet'] !== false)
			{
				//on récupère les infos des membres du projet
				$this->msg['infos_membres'] = $model->getInfosMembres($id_projet);

				//on vérifie qu'on a un résultat
				if($this->msg['infos_membres'] !== false)
				{
					$this->msg['demandes'] = $model->getDemandesProj($id_projet);

					if($this->msg['demandes'] !== false)
					{
						$this->_display('afficher_projet');
					}
					else
					{
						$this->erreur = $model->getErreur();
						$this->_display('erreur');
					}
					
				}
				else
				{
					$this->erreur = $model->getErreur();
					$this->_display('erreur');
				}
			}
			else
			{
				$this->erreur = $model->getErreur();
				$this->_display('erreur');
			}
		}
	}