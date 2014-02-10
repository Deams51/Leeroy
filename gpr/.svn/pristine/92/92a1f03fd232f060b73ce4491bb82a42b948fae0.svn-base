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
	* Gère les tâches liées aux membres d'un projet
	* 	-> ajout d'un membre
	* 	-> suppression d'un membre
	*	-> transfert des droits
	*/
	class gprControllerMembres extends gprController
	{
		/**
		  * Tache pour l'ajout d'un membre à un projet
		  * @input : id_projet - int : id du projet auquel ajouter le membre
		  * @input : id_membre - int : id du membre à ajouter
		  * @return void
		  */
		public function ajouter()
		{
			$user = JFactory::getUser();
			$app = JFactory::getApplication();
			$input = JFactory::getApplication()->input;

			//on récupère le model et la vue
			$model = $this->getModel();
			$view = $this->_getView();
			$view->setModel($model);

			//on récupère les infos dans la requête
			$id_projet = $input->getInt('id_projet', false);
			$id_membre = $input->getInt('id_membre', false);
			$submit = $input->get('submit', false, null);

			//on vérifie que l'utilisateur est connecté
			if(!$user->guest)
			{
				//on vérifie qu'on a bien un id pour le projet
				if($id_projet == false)
				{
					$view->setErreur("Aucun projet spécifié");
					$view->display('erreur');
				}
				else
				{
					//on vérifie que l'utilisateur connecté est propriétaire du projet
					if($model->estProprietaire($user->id, $id_projet))
					{
						/* Si le formulaire a été envoyé on le traite */
						if($submit == 'Valider')
						{
							if($id_membre != false)
							{
								//on vérifie que le membre existe et qu'il n'est pas déjà membre du projet
								if(!$model->estMembre($id_membre, $id_projet) && JFactory::getUser($id_membre)->id != 0)
								{
									if($model->ajouterMembre($id_membre, $id_projet, 0))
									{
										$app->enqueueMessage('Le membre a bien été ajouté.');
										$this->setRedirect(JRoute::_('index.php?option=com_gpr&task=projets.afficher&view=projets&id_projet='.$id_projet));
									}
									else
									{
										$view->setErreur("Une erreur interne est survenue : ".$model->getErreur());
										$this->_display('erreur');
									}
									
								}
								else
								{
									$view->setErreur("Le membre demandé n'existe pas ou est déjà membre du projet.");
									$this->_display('erreur');
								}
							}
							else
							{
								$view->setErreur("Aucun id de membre spécifié.");
								$this->_display('erreur');
							}
						}
						/* Sinon on affiche le formulaire */
						else
						{
							$view->setMessage($id_projet);
							$this->_display('ajouter_membre');
						}
					}
					else
					{
						$view->setErreur("Vous n'êtes pas propriétaire du projet");
						$this->_display('acces_interdit');
					}
				}
			}
			else
			{
				$this->_display('acces_interdit');
			}
		}

		/**
		  * Tache pour la suppression d'un membre
		  * @input : id_membre - int : id du membre à supprimer
		  * @input : id_projet - int : id du projetoù on supprime le membre
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

			//on récupère les infos dans la requête
			$id_projet = $input->getInt('id_projet', false);
			$id_membre = $input->getInt('id_membre', false);

			//on vérifie si l'utilisateur est connecté
			if(!$user->guest)
			{
				//on vérifie qu'on a toutes les infos
				if($id_projet && $id_membre)
				{
					//on vérifie que l'utilisateur est bien propriétaire du projet
					if($model->estProprietaire($user->id, $id_projet))
					{
						//on vérifie que le propriétaire n'est pas en train d'essayer de se supprimer
						if($user->id != $id_membre)
						{
							//on supprime le membre
							if($model->supprimerMembre($id_membre, $id_projet))
							{
								$app->enqueueMessage('Le membre a bien été supprimé.');
								$this->setRedirect(JRoute::_('index.php?option=com_gpr&task=projets.afficher&view=projets&id_projet='.$id_projet));
							}
							else
							{
								$view->setErreur("Une erreur interne est survenue : ".$model->getErreur());
								$this->_display('erreur');
							}
						}
						else
						{
							$view->setErreur("Vous ne pouvez pas vous supprimer vous même");
							$this->_display('erreur');
						}
					}
					else
					{
						$view->setErreur("Vous n'êtes pas propriétaire du projet.");
						$this->_display('acces_interdit');
					}
				}
				else
				{
					$view->setErreur("Aucun id de projet ou de membre renseigné");
					$this->_display('erreur');
				}
			}
			else
			{
				$this->_display('acces_interdit');
			}
		}

		/**
		  * Tache pour le transfert de droits
		  * @input : id_membre - int : id du membre à promouvoir
		  * @input : id_projet - int : id du projet concerné
		  * @return void
		  */
		public function transferer_droits()
		{
			$user = JFactory::getUser();
			$app = JFactory::getApplication();
			$input = JFactory::getApplication()->input;

			//on récupère le model et la vue
			$model = $this->getModel();
			$view = $this->_getView();
			$view->setModel($model);

			//on récupère les infos dans la requête
			$id_projet = $input->getInt('id_projet', false);
			$id_membre = $input->getInt('id_membre', false);

			//on vérifie si l'utilisateur est connecté
			if(!$user->guest)
			{
				//on vérifie qu'on a toutes les infos
				if($id_projet && $id_membre)
				{
					//on vérifie que l'utilisateur est bien propriétaire du projet
					if($model->estProprietaire($user->id, $id_projet))
					{
						//on effectue le tranfert des droits
						if($model->transfererDroits($id_membre, $user->id, $id_projet))
						{
							$app->enqueueMessage('Le transfert a été effectué.');
							$this->setRedirect(JRoute::_('index.php?option=com_gpr&task=projets.afficher&view=projets&id_projet='.$id_projet));
						}
						else
						{
							$view->setErreur("Une erreur interne est survenue : ".$model->getErreur());
							$this->_display('erreur');
						}
					}
					else
					{
						$view->setErreur("Vous n'êtes pas propriétaire du projet.");
						$this->_display('acces_interdit');
					}
				}
				else
				{
					$view->setErreur("Aucun id de projet ou de membre renseigné");
					$this->_display('erreur');
				}
			}
			else
			{
				$this->_display('acces_interdit');
			}
		}
	}