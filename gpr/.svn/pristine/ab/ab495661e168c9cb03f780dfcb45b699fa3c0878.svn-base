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
	* Gère les tâches liées aux projets
	* 	-> affichage liste projets
	*	-> affichage projet
	*	-> créer un projet
	*	-> supprimer un projet
	*/
	class gprControllerProjets extends gprController
	{

		/**
		  * Tache pour la page d'accueil de l'espace gestion de projet
		  * Vérifie les droits de l'utilisateurs avant d'appeler la méthode de la vue correspondante
		  * @return void
		  * @TODO vérfier que l'utilisateur est un étudiant
		*/
		/*public function affichers()
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
				$this->_display('acces_interdit');
			}
		}*/

		/**
		  * Tache pour l'affichage d'un projet
		  * Vérifie les droits de l'utilisateur avant d'appeler la méthode de la vue correspondante
		  * @input : id_projet : l'id du projet à afficher
		  * @return void
		  * @TODO vérifier que l'utilisateur est un étudiant
		*/
		public function afficher()
		{
			$user = JFactory::getUser();
			$app = JFactory::getApplication();
			$input = JFactory::getApplication()->input;

			//on récupère le model et la vue
			$model = $this->getModel();
			$view = $this->_getView();
			$view->setModel($model);

			$id_projet = $input->getInt('id_projet');

			//on vérifie que l'id est renseigné
			if($id_projet != null)
			{
				//on vérifie que l'utilisateur est connecté
				if(!$user->guest)
				{
					$view = $this->_getView();
					$view->afficher_projet($id_projet);
				}
				else
				{
					$this->_display('acces_interdit');
				}
			}
			else
			{
				$view->afficher_projets();
			}
		}

		/**
		  * Tache pour la création d'un projet
		  * Si le formulaire de création a été envoyé, on créer un nouveau projet
		  * @input : nom
		  * @input : nom_court
		  * @input : description
		  * @input : submit
		  * @return void
		  */
		public function creer()
		{
			$user = JFactory::getUser();
			$app = JFactory::getApplication();
			$input = JFactory::getApplication()->input;

			// On récupère le model et la vue
			$model = $this->getModel();
			$view = $this->_getView();
			$view->setModel($model);

			//on vérifie que l'utilisateur est connecté
			if(!$user->guest)
			{
				$submit = $input->get('submit');
				/* Si le formulaire a été envoyé on le traite */
				if($submit == 'Valider')
				{
					/* on récupère les infos*/
					$nom = $input->get('nom', null, null);
					$description = $input->get('description', null, null);

					/* On vérifie qu'on a toutes les infos */
					if($nom && $description)
					{
						/* On crée le projet */
						if($model->creerProjet($nom, nl2br($description), $user->id))
						{
							$app->enqueueMessage('Le projet a bien été créé.');
							$this->setRedirect(JRoute::_('index.php?option=com_gpr&task=projets.afficher&view=projets'));
						}
						else
						{
							$app->enqueueMessage("Une erreur interne est survenue : ".$model->getErreur(), 'erreur');
							$this->setRedirect(JRoute::_('index.php?option=com_gpr&task=projets.creer&view=projets'));
						}
						
					}
				}
				/* Sinon on affiche le formulaire */
				else
					$this->_display('creer_projet');
			}
			else
			{
				$this->_display('acces_interdit');
			}
			
		}

		/**
		 * tache pour la suppresion d'un projet
		 * @input : id_projet- int : id du projet à supprimer
		 * @return void
		 */
		public function supprimer()
		{
			$user = JFactory::getUser();
			$app = JFactory::getApplication();
			$input = JFactory::getApplication()->input;

			//on récupère le model et la vue
			$model = $this->getModel();
			$view = $this->_getView();
			$view->setModel($model);

			//on vérifie que l'utilisateur est connecté
			if(!$user->guest)
			{
				/* on récupère l'info nécessaire*/
				
				$id_projet = $input->get('id_projet', null);
				
				/* On vérifie qu'on a toutes les infos */
				if($id_projet)
				{
					/* On récupère le model */
					
					if($model->estProprietaire($user->id, $id_projet))
					{
						/* On supprime le projet */
						/*
							/!\   /!\   /!\   /!\   /!\
							A FAIRE
							Supprimer les entrées correspondant au projet dans la table membre
							Supprimer les postits des membres
							/!\   /!\   /!\   /!\   /!\
						*/
						if($model->supprimerProjet($id_projet, $user->id))
						{
							$app->enqueueMessage('Le projet a bien été supprimé.');
							$this->setRedirect(JRoute::_('index.php?option=com_gpr&task=projets.afficher&view=projet'));

						}
					}
					else
					{
						$view->setErreur("Vous n'etes pas proprietaire du projet.");
						$this->_display('erreur');
					}
				}
				else
				{
					$view->setErreur("Le projet n'existe pas.");
					$this->_display('erreur');
				}
			}
			else
			{
				$view->setErreur("Vous n'êtes pas autorisé à accéder à cette page");
				$this->_display('acces_interdit');
			}
		}
	}