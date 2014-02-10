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
	* Gère les tâches liées aux services
	* 	-> affichage des services du projet
	*	-> demande de services
	*	sera déplacé dans la partie administrateur
	*	-> gestion des services admin
	*	-> accepter une demande de service
	*	-> refuser une demande de service
	*/
	class gprControllerServices extends gprController
	{

		/**
		 * Tache pour la partie admin de gestion des services
		 * @return void
		 */
		/*public function gestion_admin()
		{
			$user = JFactory::getUser();

			//on récupère le model et la vue
			$model = $this->getModel();
			$view = $this->_getView();
			$view->setModel($model);

			if(!$user->guest)
			{
				//-------------------
				// /!\   /!\   /!\
				//   tester la permission de l'utilisateur
				// /!\   /!\   /!\
				//-------------------
				if(true)
				{
					$view->gestion_services_admin();
				}
				else
				{
					$app->enqueueMessage("Vous n'êtes pas autorisé à voir cette page.");
					$this->_display('acces_interdit');
				}
			}
			else
			{
				$app->enqueueMessage("Vous devez n'êtes pas autorisé à voir cette page.");
				$this->_display('acces_interdit');
			}
		}*/

		/**
		  * Tache pour la partie gestion des services d'un projet
		  * @input : id_projet - id du projet pour lequel on souhaite gérer les services
		  * @input : submit - est défini si le formulaire a été envoyé
		  * @input : tout un tas d'infos concernant les options des services si le formulaire a été envoyé
		  * @return void
		  */
		public function gestion()
		{
			$user = JFactory::getUser();
			$app = JFactory::getApplication();
			$input = $app->input;

			//on récupère le model et la vue
			$model = $this->getModel();
			$view = $this->_getView();
			$view->setModel($model);

			//on récupère les infos dans la requête
			$id_projet = $input->getInt('id_projet', false);
			$submit = $input->get('submit', false, null);

			//on vérifie si l'utilisateur est connecté
			if(!$user->guest)
			{
				//on vérifie qu'on a toutes les infos
				if($id_projet)
				{
					//on vérifie que l'utilisateur est bien propriétaire du projet
					if($model->estMembre($user->id, $id_projet))
					{
						//si le formulaire a été envoyé on le traite
						if($submit)
						{
							if($model->estProprietaire($user->id, $id_projet))
							{
								//on récupère un partie des infos
								$svn = $input->get('svn', false);
								$vps = $input->get('vps', false);

								//traitement de la demande svn si coché
								if($svn)
								{
									//on récupère les infos relatives à la demande de svn
									$description_svn = $input->get('description_svn', false, null);
									$taille_svn = $input->getInt('taille_svn', false);
									$droits_svn = $input->get('droits_svn', false);

									//on vérifie que tout a été renseigné
									if($description_svn && $taille_svn && $droits_svn !== false)
									{
										if($model->ajouterDemandeSvn($description_svn, $taille_svn, $droits_svn, $id_projet))
										{
											if(!$vps)
											{
												$app->enqueueMessage('Votre demande a été prise en compte. <br /> Vous receverez un email lorsqu\'elle aura été étudiée par un administrateur.');
												$this->setRedirect(JRoute::_('index.php?option=com_gpr&task=projets.afficher&view=projets&id_projet='.$id_projet));
											}
										}
										else
										{
											$app->enqueueMessage("Erreur interne : ".$model->getErreur(), 'error');
											$this->setRedirect(JRoute::_('index.php?option=com_gpr&task=services.gestion&view=services&id_projet='.$id_projet));
										}
									}
									else
									{
										$app->enqueueMessage("Veuillez renseigner tous les champs", 'error');
										$this->setRedirect(JRoute::_('index.php?option=com_gpr&task=services.gestion&view=services&id_projet='.$id_projet));
									}
								}

								//traitement de la demande vps si coché
								if($vps)
								{
									$description_vps = $input->get('description_vps', false, null);
									$taille_vps = $input->getInt('taille_vps', false);
									$ram_mini = $input->getInt('ram_mini_vps', false);
									$ram_burst = $input->getInt('ram_burst_vps', false);
									$cpu_vps = $input->getInt('cpu_vps', false);

									//on vérifie que tout a été renseigné
									if($description_vps && $taille_vps && $ram_mini && $ram_burst && $cpu_vps)
									{
										if($model->ajouterDemandeVps($description_vps, $taille_vps, $ram_mini, $ram_burst, $cpu_vps, $id_projet))
										{
											$app->enqueueMessage('Votre demande a été prise en compte. <br /> Vous receverez un email lorsqu\'elle aura été étudiée par un administrateur.');
											$this->setRedirect(JRoute::_('index.php?option=com_gpr&task=projets.afficher&view=projets&id_projet='.$id_projet));
										}
										else
										{
											$app->enqueueMessage("Erreur interne : ".$model->getErreur(), 'error');
											$this->setRedirect(JRoute::_('index.php?option=com_gpr&task=services.gestion&view=services&id_projet='.$id_projet));
										}
									}
									else
									{
										$app->enqueueMessage("Veuillez renseigner tous les champs", 'error');
										$this->setRedirect(JRoute::_('index.php?option=com_gpr&task=services.gestion&view=services&id_projet='.$id_projet));
									}
								}
							}
							else
							{
								$app->enqueueMessage("Accès interdit : vous n'êtes pas propriétaire du projet.", 'error');
								$this->setRedirect(JRoute::_('index.php?option=com_gpr&task=services.gestion&view=services&id_projet='.$id_projet));
							}
						}
						//si le formualire n'a pas été envoyé on affiche directement la page de gestion des services
						else
						{
							$view->gestion_services($id_projet);
						}
					}
					else
					{
						$app->enqueueMessage("Accès interdit : vous n'êtes pas membre du projet.", 'error');
						$this->setRedirect(JRoute::_('index.php?option=com_gpr&task=projets.afficher&view=projets'));
					}
				}
				else
				{
					$app->enqueueMessage("Aucun id de projet renseigné", 'error');
					$this->setRedirect(JRoute::_('index.php?option=com_gpr&task=projets.afficher&view=projets'));
				}
			}
			else
			{
				$app->enqueueMessage("Vous n'avez pas le droit d'accéder à cette page.", 'error');
			}
		}

		/**
		  * Tache pour l'acceptation d'une demande de service
		  * @input : id_demande
		  * @input : service_demande
		  * @return void
		  */
		/*public function accepter_demande()
		{
			$user = JFactory::getUser();
			$input = JFactory::getApplication()->input;

			//on récupère le model et la vue
			$model = $this->getModel();
			$view = $this->_getView();
			$view->setModel($model);

			if(!$user->guest)
			{
				//-------------------
				// /!\   /!\   /!\
				//   tester la permission de l'utilisateur
				// /!\   /!\   /!\
				//-------------------
				if(true)
				{
					//on récupère les infos
					$id_demande = $input->getInt('id_demande', false);
					$service_demande = $input->get('service_demande',  false);

					if($id_demande && $service_demande && ($service_demande == "svn" || $service_demande == "vps"))
					{
						if($model->traiterDemande($id_demande, $service_demande, true))
						{
							$view->gestion_services_admin();
						}
						else
						{
							$app->enqueueMessage("Une erreur interne est survenue : ".$model->getErreur());
							$this->_display('erreur');
						}
					}
					else
					{
						$app->enqueueMessage("Demande introuvable");
						$this->_display('erreur');
					}
				}
				else
				{
					$app->enqueueMessage("Vous devez n'êtes pas autorisé à voir cette page.");
					$this->_display('acces_interdit');
				}
			}
			else
			{
				$app->enqueueMessage("Vous devez n'êtes pas autorisé à voir cette page.");
				$this->_display('acces_interdit');
			}

		}*/

		/**
		  * Tache pour le refus d'une demande de service
		  * @input : id_demande
		  * @input : service_demande
		  * @return void
		  */
		/*public function refuser_demande()
		{
			$user = JFactory::getUser();
			$input = JFactory::getApplication()->input;

			//on récupère le model et la vue
			$model = $this->getModel();
			$view = $this->_getView();
			$view->setModel($model);

			if(!$user->guest)
			{
				//-------------------
				// /!\   /!\   /!\
				//   tester la permission de l'utilisateur
				// /!\   /!\   /!\
				//-------------------
				if(true)
				{
					//on récupère les infos
					$id_demande = $input->getInt('id_demande', false);
					$service_demande = $input->get('service_demande',  false);

					if($id_demande && $service_demande && ($service_demande == "svn" || $service_demande == "vps"))
					{
						if($model->traiterDemande($id_demande, $service_demande, false))
						{
							$view->gestion_services_admin();
						}
						else
						{
							$app->enqueueMessage("Une erreur interne est survenue : ".$model->getErreur());
							$this->_display('erreur');
						}
					}
					else
					{
						$app->enqueueMessage("Demande introuvable");
						$this->_display('erreur');
					}
				}
				else
				{
					$app->enqueueMessage("Vous devez n'êtes pas autorisé à voir cette page.");
					$this->_display('acces_interdit');
				}
			}
			else
			{
				$app->enqueueMessage("Vous devez n'êtes pas autorisé à voir cette page.");
				$this->_display('acces_interdit');
			}
		}*/
	}