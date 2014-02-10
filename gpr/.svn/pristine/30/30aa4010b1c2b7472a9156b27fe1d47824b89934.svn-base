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
	  * Gère les vues raltives aux services
	*/

	//empeche l'accès direct au fichier
	defined('_JEXEC') or die ('Restricted access');

	require_once(JPATH_COMPONENT_SITE.'/views/view.php');

	/**
	* HTML View class pour le composant Gestion de Projet
	*/
	class gprViewServices extends gprView
	{
		/**
		 * Récupères les services liés au projet et les affiche
		 * @param $id_projet - int : id du projet
		 * @return void
		 */
		public function gestion_services($id_projet)
		{
			$model = $this->getModel();
			$this->msg = array();

			$this->msg = $model->getDemandesProj($id_projet);
			$this->msg['id_projet'] = $id_projet;

			if($this->msg != false)
			{
				$this->_display('gestion_services');
			}
			else
			{
				$this->setErreur("Une erreur interne est survenue : ".$model->getErreur());
				$this->_display('erreur');
			}
		}

		/**
		 * Récupère les demandes de services en attente et les affiche
		 * @return void
		 */
		public function gestion_services_admin()
		{
			$model = $this->getModel();
			$this->msg = array();

			$this->msg = $model->getDemandes();

			if($this->msg !== false)
			{
				$this->_display('gestion_services_admin');
			}
			else
			{
				$this->setErreur("Une erreur interne est survenue : ".$model->getErreur());
				$this->_display('erreur');
			}
		}
	}