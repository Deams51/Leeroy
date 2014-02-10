<?php
	/**
	 * @version     1.0.0
	 * @package     com_gpr
	 * @copyright   Copyright (C) 2013. All rights reserved.
	 * @license     GNU General Public License version 2 or later; see LICENSE.txt
	 * @author      Choulet Quentin, Fourgeaud Mickaël, Hauguel Antoine, Malory Tristan <> - 
	 */

	/**
	  * Classe de base pour le controller du composant
	  * Tous les controlleurs en héritent
	  */

	//empeche l'accès direct au fichier
	defined('_JEXEC') or die ('Restricted access');

	//import joomla controller library
	jimport('joomla.application.component.controller');


	class gprController extends JControllerLegacy
	{
		/**
		  * Fonction bidon de test
		  * Affiche toto et demande l'affichage de la vue en utilisant le template default_toto.php
		  */
		public function toto ()
		{
			echo 'toto';
			$this->_display('toto');
		}

		/**
		  * Récupère et renvoie la vue par défaut du controller
		  * @return $object - la vue
		  */
		public function _getView()
		{
			//on récupère la vue code copier coller de la méthode display de la classe JController
			//définie à la ligne 580 de libraries/joomla/application/component/controller.php
			$document = JFactory::getDocument();
			$viewType = $document->getType();
			$viewName = JRequest::getCmd('view', $this->default_view);
			$viewLayout = JRequest::getCmd('layout', 'default');

			$view = parent::getView($viewName, $viewType, '', array('base_path' => $this->basePath, 'layout' => $viewLayout));

			// Get/Create the model
			if ($model = $this->getModel())
			{
				// Push the model into the view (as default)
				$view->setModel($model, true);
			}

			return $view;
		}

		/**
		  * Récupère la vue et appelle la méthode display de cette dernière
		  * @param $tpl string - le template que la vue doit utiliser
		  * @return void
		  */
		public function _display($tpl = null, $view = null)
		{
			if($view == null)
			{
				$view = $this->_getView();
			}

			//on appelle la méthode display de la vue qui se charge de récupérer les données à afficher
			$view->display($tpl);
		}

		/**
		  * Tache par défaut si aucune tache n'est passée en paramètre
		  * Ou si la tache demandée n'existe pas
		  * Affiche la liste des projets de l'utilisateur connecté
		  */
		public function default_task()
		{
			$user = JFactory::getUser();

			//on récupère le model et la vue
			$model = $this->getModel();
			$view = $this->_getView();
			$view->setModel($model);

			//on vérifie que l'utilisateur est connecté
			if(!$user->guest)
			{
				$view = $this->_getView();
				$view->afficher_projets($user);
			}
			else
			{
				$this->display('acces_interdit');
			}
		}

		/*==========================================

				TACHES LIEES AUX MEMBRES
				
		==========================================*/

		

		/*==========================================

				TACHES LIEES AUX PROJETS
				
		==========================================*/

		

		/*==========================================

				TACHES LIEES AUX POSTIT
				
		==========================================*/


		/*==========================================

				TACHES LIEES AUX SERVICES
				
		==========================================*/

		
	}