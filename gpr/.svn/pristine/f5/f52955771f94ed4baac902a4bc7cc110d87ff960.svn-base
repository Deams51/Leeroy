<?php
	/**
	 * @version     1.0.0
	 * @package     com_gpr
	 * @copyright   Copyright (C) 2013. All rights reserved.
	 * @license     GNU General Public License version 2 or later; see LICENSE.txt
	 * @author      Choulet Quentin, Fourgeaud Mickaël, Hauguel Antoine, Malory Tristan <> - 
	 */

	//Empeche l'acces direct au fichier
	defined('_JEXEC') or die ('Restricted access');

	// import Joomla modelitem library
	jimport('joomla.application.component.modelitem');

	class gprModelgpr extends JModelItem
	{
		//string msg, le message que récupère la vue
		protected $msg;

		//string err, un message d'erreur si une des fonctions fonctionne mal
		protected $erreur;

		/**
		  * Renvoie le message
		  * @return string - données ou message à afficher
		  */
		public function getMsg()
		{
			return $this->msg;
		}

		/**
		  * Renvoie l'erreur
		  * @return : string - un message d'erreur
		  */
		public function getErreur()
		{
			return $this->erreur;
		}

		/*===============================================
				FONCTIONS GENERALES DE TEST
		================================================*/

		/**
		  * Identifie si un utilisateur est propriétaire du projet
		  * @param : $id_projet - int : id du projet
		  * @param : $id_utilisateur - int : id user
		  * @return : bool : true si il est propriétaire, false sinon (ou si un erreur survient)
		  */
		public function estProprietaire($id_membre, $id_projet)
		{
			if(!isset($this->erreur))
			{
				$this->erreur = "";
			}

			//on récupère la bdd
			$db = $this->getDBO();

			$query = 'SELECT * FROM `#__gpr_membres` WHERE
						`id_joomla`='.$db->quote($id_membre).' 
						AND `id_projet`='.$db->quote($id_projet).' 
						AND `statut` = 1';
			$db->setQuery($query);

			//on essaye d'exécuter la requête
			try
			{
				$db->execute();

				//on regarde s'il  a un résultat
				if($db->getNumRows() > 0)
				{
					return true;
				}
				else
				{
					return false;
				}

			}
			catch(Exception $e)
			{
				if(defined('_DEBUG'))
				{
					$this->erreur = "Erreur : ".$e->getMessage();
					echo $this->erreur;
				}
				return false;
			}
		}

		/**
		  * Vérifie si un utilisateur est membre du projet
		  * @param : $id_projet - int : id du projet
		  * @param : $id_utilisateur - int : id user
		  * @return : bool : true si il est membre, false sinon (ou si un erreur survient)
		  */
		public function estMembre($id_membre, $id_projet)
		{
			if(!isset($this->erreur))
			{
				$this->erreur = "";
			}

			//on récupère la bdd
			$db = $this->getDBO();

			$query = 'SELECT * FROM `#__gpr_membres` WHERE
						`id_joomla`='.$db->quote($id_membre).' 
						AND `id_projet`='.$db->quote($id_projet);
			$db->setQuery($query);

			//on essaye d'exécuter la requête
			try
			{
				$db->execute();

				//on regarde s'il  a un résultat
				if($db->getNumRows() > 0)
				{
					return true;
				}
				else
				{
					return false;
				}

			}
			catch(Exception $e)
			{
				if(defined('_DEBUG'))
				{
					$this->erreur = "Erreur : ".$e->getMessage();
					echo $this->erreur;
				}
				return false;
			}
		}

		/*===============================================
				FONCTIONS RELATIVES AUX PROJETS
		================================================*/
		/**
		  * Récupère tous les tps et projets de l'utilisateur et les sépare en deux tableaux disctincts
		  * @param $id_user - int : l'id joomla de l'utilisateur
		  * @return mixed : -> En cas de succès => array - tableau associatif conteant les projets et les tps
		  *					-> En cas d'erreur => false - Le message d'erreur est stocké dans $erreur et est accssible via getErreur()
		  */
		public function getProjTp($id_user)
		{
			if(!isset($this->erreur))
			{
				$this->erreur = "";
			}

			//get a a database object
			$db = $this->getDBO();

			$query = "	SELECT projet.*, membre.statut, postit.token
						FROM `#__gpr_projets` AS projet
						INNER JOIN `#__gpr_membres` AS membre ON membre.id_projet = projet.id
						INNER JOIN `#__gpr_postit` AS postit ON postit.id = membre.id_postit
						WHERE membre.id_joomla =".$db->quote($id_user);
			$db->setQuery($query);
			
			// On essaye d'exécuter la requête
			try
			{
				$db->execute();

				//on récupère les résultats dans un tableau associatif
				$result = $db->loadAssocList();

				$projets = array();
				//on parcourt les résulats et on les ajoute
				foreach ($result as $key => $row) {
					$projets[] = $row;
				}
				return $projets;
			}
			catch(Exception $e)
			{
				if(defined('_DEBUG'))
				{
					$this->erreur = $db->getErrorMsg();;
				}
				else
				{
					$this->erreur = "Impossible d'afficher les projets";
				}
				return false;
			}
		}

		/**
		  * Récupère toutes les infos d'un projet
		  * @param $id_projet - int : l'id du projet
		  * @return mixed :
		  *				-> En cas de succès => array : tableau associatif contenant les infos du projet ['field_name' => 'row_value']
		  *				-> S'il n'y a pas de résulat => 0
		  *				-> En cas d'erreur => false - Le message d'erreur est stocké dans $erreur et est accssible via getErreur()
		  * @TODO finir la fonction ^^"
		  */
		public function getProjet($id_projet)
		{
			if(!isset($this->erreur))
			{
				$this->erreur = "";
			}

			// on récupère la bdd
			$db = $this->getDBO();
			// stockera les infos du projet
			$projet = array();

			$query = "SELECT * FROM `#__gpr_projets` WHERE id = ".$db->quote($id_projet);

			$db->setQuery($query);

			//on exécute la requête
			try
			{
				$db->execute();

				$result = array();

				//on récupère le résultat dans un tableau associatif
				$result = $db->loadAssoc();

				//on renvoie le résultat
				return $result;
			}
			catch(Exception $e)
			{
				if(defined('_DEBUG'))
				{
					$this->erreur = $e->getMessage();
				}
				else
				{
					$this->erreur = "Impossible d'afficher le projet";
				}
				return false;
			}			
		}

		/**
		  * Créer un projet
		  * @param $nom - string : nom du projet à créer
		  * @param $description - string : description du projet
		  * @return bool : true en cas de succès, false si un problème survient
		  *				-> En cas d'erreur le message est stocké dans $erreur et peut être récupéré grâce à la méthode getErreur();
		  * @TODO :	- récupérer la date courante et l'ajouter dans la requête
		  */
		public function creerProjet($nom, $description, $id_createur)
		{
			if(!isset($this->erreur))
			{
				$this->erreur = "";
			}

			$db = $this->getDBO();

			// Requête pour vérifier qu'il n' a pas déjà un projet du même nom
			$query="SELECT * FROM `#__gpr_projets` WHERE nom=".$db->quote($nom);
			
			$db->setQuery($query);

			// On essaye d'exécuter la requête 
			try
			{
				$db->execute();
			}
			catch(Exception $e)
			{
				if(defined('_DEBUG'))
				{
					$this->erreur = $e->getMessage();
				}
				else
				{
					$this->erreur = "Impossible de creer le projet";
				}
				return false;
			}

			// Si un projet du même nom existe déjà on renvoie une erreur 
			if($db->getNumRows() > 0)
			{
				$this->erreur = "Un projet de ce nom existe déjà";
				return false;
			}
			else
			{
				// requête pour l'ajout du projet 
				$query = "INSERT INTO `#__gpr_projets`(nom, description)
								VALUES (".$db->quote($nom).",".$db->quote($description).")";
				$db->setQuery($query);
				
				// On exécute la requête 
				try
				{
					$db->execute();

					// on récupère l'id du projet nouvellement créé 
					$id_projet = $db->insertid();

					// On ajoute le créateur dans la table des membres 
					if($this->ajouterMembre($id_createur, $id_projet, 1))
					{
						return true;
					}
					//En cas d'erreur lors de l'ajout du createur on supprime le projet
					else
					{
						$query = "DELETE FROM `#__gpr_projets` 
								WHERE id=".$db->quote($id_projet);
						$db->setQuery($query);

						$db->execute();

						$this->erreur = "Erreur lors de l'ajout du createur au projet. Le projet n'a pas été";
						return false;
					}
				}
				catch(Exception $e)
				{
					if(defined('_DEBUG'))
					{
						$this->erreur = $e->getMessage();
					}
					else
					{
						$this->erreur = "Impossible de creer le projet";
					}
				}
			}
		}
		
		
		/**
		  * Supprime un projet
		  * @param : $id_projet - int : id du projet à supprimer
		  * @return bool : true en cas de succès, false si un problème survient
		  *				-> En cas d'erreur le message est stocké dans $erreur et peut être récupéré grâce à la méthode getErreur();
		  * @TODO :	- récupérer la date courante et l'ajouter dans la requête
		  *			- tests
		  */
		
		public function supprimerProjet($id_projet, $id_user)
		{
			if(!isset($this->erreur))
			{
				$this->erreur = "";
			}

			$db = $this->getDBO();


			// On modifie l'état des vps et svn
			$query = "UPDATE `jos_gpr_svn` SET  `etat` = ".$db->quote('deleting')." WHERE `id_projet` =".$db->quote($id_projet);
			$db->setQuery($query);
			// On exécute la requête 
			try
			{
				$db->execute();
			}
			catch(Exception $e)
			{
				if(defined('_DEBUG'))
				{
					$this->erreur = $e->getMessage();
				}
				else
				{
					$this->erreur = "Impossible de supprimer le projet : erreur lors de l'arrêt des services";
				}
				return false;
			}
			$query = "UPDATE `jos_gpr_vps` SET  `etat` = ".$db->quote('deleting')." WHERE `id_projet` =".$db->quote($id_projet);
			$db->setQuery($query);
			// On exécute la requête 
			try
			{
				$db->execute();
			}
			catch(Exception $e)
			{
				if(defined('_DEBUG'))
				{
					$this->erreur = $e->getMessage();
				}
				else
				{
					$this->erreur = "Impossible de supprimer le projet : erreur lors de l'arrêt des services";
				}
				return false;
			}


			// On demande la suppression de tous les svn liés à ce projet 
			$query = "INSERT IGNORE INTO `#__gpr_updates` (`id`, `action`, `id_service`, `arg`) SELECT 'null','svn_delete',`id`,null from `#__gpr_svn` where id_projet =".$db->quote($id_projet);
			$db->setQuery($query);
			// On exécute la requête 
			try
			{
				$db->execute();
			}			
			catch(Exception $e)
			{
				if(defined('_DEBUG'))
				{
					$this->erreur = $e->getMessage();
				}
				else
				{
					$this->erreur = "Impossible de supprimer les SVN";
				}
				return false;
			}


			// On demande la suppression de tous les vps liés à ce projet 
			$query = "INSERT IGNORE INTO `#__gpr_updates` (`id`, `action`, `id_service`, `arg`) SELECT 'null',`id`,'vps_delete',null from `#__gpr_vps` where id_projet =".$db->quote($id_projet);
			$db->setQuery($query);
			// On exécute la requête 
			try
			{
				$db->execute();
			}			
			catch(Exception $e)
			{
				if(defined('_DEBUG'))
				{
					$this->erreur = $e->getMessage();
				}
				else
				{
					$this->erreur = "Impossible de supprimer les VPS";
				}
				return false;
			}


			//On supprime les postits
			$query = "DELETE FROM `#__gpr_postit` WHERE id
					IN (SELECT `id_postit`
						FROM `#__gpr_membres`
						WHERE `id_projet` = ".$db->quote($id_projet).")";
			$db->setQuery($query);
			try
			{
				$db->execute();
			}
			catch(Exception $e)
			{
				if(defined('_DEBUG'))
				{
					$this->erreur = $e->getMessage();
				}
				else
				{
					$this->erreur = "Impossible de supprimer le projet : erreur lors de la suppression des postits";
				}
				return false;
			}

			//On supprime les membres
			$query = "DELETE FROM `#__gpr_membres` WHERE `id_projet` = ".$db->quote($id_projet);
			$db->setQuery($query);
			try
			{
				$db->execute();
			}
			catch(Exception $e)
			{
				if(defined('_DEBUG'))
				{
					$this->erreur = $e->getMessage();
				}
				else
				{
					$this->erreur = "Impossible de supprimer le projet : erreur lors de la suppression des membres";
				}
				return false;
			}


			// On supprime le projet
			$query = "DELETE FROM `#__gpr_projets` WHERE id=".$db->quote($id_projet);
			$db->setQuery($query);

			
			// On exécute la requête 
			try
			{
				$db->execute();

				// Si le projet existe toujours on renvoie une erreur
				if($db->getAffectedRows() == 0)
				{
					$this->erreur = "Le projet n'existe pas";
					return false;
				}
			}
			catch(Exception $e)
			{
				if(defined('_DEBUG'))
				{
					$this->erreur = $e->getMessage();
				}
				else
				{
					$this->erreur = "Impossible de supprimer le projet";
				}
				return false;
			}
			return true;
		}

		/*===============================================
				FONCTIONS RELATIVES AUX MEMBRES
		================================================*/

		/**
		  *
		  */
		public function findUser($name){
			if(!isset($this->erreur))
			{
				$this->erreur = "";
			}

			//on récupère la bdd
			$db = $this->getDBO();

			$query = "SELECT * FROM `#__users` WHERE name LIKE ".$db->quote('%'.$name.'%')." OR username LIKE ".$db->quote('%'.$name.'%')." LIMIT 10";

			$db->setQuery($query);

			try
			{
				$db->execute();

				if($db->getNumRows() > 0){
					//on récupère les résultats dans un tableau associatif
					$result = $db->loadAssocList();
					return $result;
				}
				else{
					$this->erreur = "Aucun membre ne correspond";
					return false;
				}

			}
			catch(Exception $e)
			{
				if(defined('_DEBUG'))
				{
					$this->erreur = $db->getErrorMsg();;
				}
				else
				{
					$this->erreur = "Erreur";
				}
				return false;
			}
		}

		/**
		  * Ajoute un membre à un projet
		  * @param : $id_membre - int : id du membre à ajouter
		  * @param : $id_projet - int : projet dans lequel ajouter le membre
		  * @param : $statut - int : 1 si le membre est propriétaire, 0 sinon
		  * @return : bool - true en cas de succès, false en cas d'erreur
		  *				-> En cas d'erreur le message est stocké dans $erreur et peut être récupéré grâce à la méthode getErreur();
		  */
		public function ajouterMembre($id_membre, $id_projet, $statut)
		{
			if(!isset($this->erreur))
			{
				$this->erreur = "";
			}

			//on récupère la bdd
			$db = $this->getDBO();

			//on crée un postit pour le membre
			$id_postit = $this->creerPostit();

			//on vérifie que le postit a bien été créé
			if($id_postit)
			{

				// requete pour ajouter le membre
				$query = 	"INSERT INTO `#__gpr_membres` (`id_joomla`, `id_projet`, `id_postit`, `statut`)
							 VALUES (".$db->quote($id_membre).", ".$db->quote($id_projet).", "
							 		.$db->quote($id_postit).", ".$db->quote($statut).")";
				$db->setQuery($query);

				//on essaye d'exécuter la requête
				try
				{
					$db->execute();

					return true;
				}
				catch(Exception $e)
				{
					
					if(defined('_DEBUG'))
					{
						$this->erreur = $e->getMessage();
					}
					else
					{
						$this->erreur = "Impossible d'ajouter le membre au projet";
					}
				}
			}
			else
			{
				return false;
			}
		}

		/**
		  * Récupère les infos (nom) des membres d'un projet
		  * @param $id_projet - int : l'id du projet dont on cherche à récupérer les membres
		  * @return mixed 	-> En cas de succès => array : 
		  *					-> En cas d'erreur => false - Le message d'erreur est stocké dans $erreur et est accessible via getErreur()
		  * @TODO 	-> Voir avec le groupe qui gère les utilisateurs comment récupérer la photo de profil et le nom
		  */
		public function getInfosMembres($id_projet)
		{
			if(!isset($this->erreur))
			{
				$this->erreur = "";
			}

			//on récupère la bdd
			$db = $this->getDBO();

			//requête pour récupérer les membres du projet
			$query = "SELECT `id_joomla` FROM `#__gpr_membres` WHERE `id_projet` = ".$db->quote($id_projet);

			$db->setQuery($query);

			//on essaye d'exécuter la requête
			try
			{
				$db->execute();

				//stockera les infos des membres
				$membres = array();

				$result = $db->loadAssocList();

				foreach ($result as $key => $membre) 
				{
					$membres[$key]['nom'] = JFactory::getUser($membre['id_joomla'])->get('name');
					$membres[$key]['id'] = $membre['id_joomla'];
				}

				return $membres;
			}
			catch (Exception $e)
			{
				
				if(defined('_DEBUG'))
				{
					$this->erreur = $e->getMessage();
				}
				else
				{
					$this->erreur = "Impossible de récupérer les infos des membres";
				}
				return false;
			}
		}

		/**
		  * Supprime un membre d'un projet
		  * @param : $id_membre - int : id du membre à supprimer
		  * @param : $id_projet - int : id du projet duquel supprimer le membre
		  * @return : bool - true en cas de succès, false sinon
		  */
		public function supprimerMembre($id_membre, $id_projet)
		{
			if(!isset($this->erreur))
			{
				$this->erreur = "";
			}

			//on récupère la bdd
			$db = $this->getDBO();

			$query = "DELETE FROM `#__gpr_membres`
						WHERE `id_joomla` = ".$db->quote($id_membre)."
						AND `id_projet` = ".$db->quote($id_projet);

			$db->setQuery($query);

			try
			{
				$db->execute();

				//on regarde si une ligne a été supprimée
				if($db->getAffectedRows() > 0)
				{
					return true;
				}
				else
				{
					$this->erreur = "L'utilisateur demandé ne fait pas partie du projet ou le projet n'existe pas.";
					return false;
				}
			}
			catch (Exception $e)
			{
				if(defined('_DEBUG'))
				{
					$this->erreur = $e->getMessage();
				}
				else
				{
					$this->erreur = "Impossible de supprimer le membre";
				}
				return false;
			}
		}

		/**
		  * Tranfert les droits du projet à un membre
		  * @param : $id_membre - int : id du membre à promouvoir
		  * @param : $id_proprietaire - int : id du proprietaire actuel du projet
		  *				-> permet d'éviter un requête pour le récupérer
		  * @param : $id_projet - int : id du projet concerné
		  * @return : bool : true si le transfert s'est effectué sans problème, false en cas d'erreur
		  */
		public function transfererDroits($id_membre, $id_proprietaire, $id_projet)
		{
			if(!isset($this->erreur))
			{
				$this->erreur = "";
			}

			//on récupère la bdd
			$db = $this->getDBO();

			//requête pour promouvoir le membre
			$query = "UPDATE `#__gpr_membres`
						SET statut = 1
						WHERE id_projet = ".$db->quote($id_projet)."
						AND id_joomla =".$db->quote($id_membre);

			$db->setQuery($query);

			//on essaye de promouvoir le membre
			try
			{
				$db->execute();

				if($db->getAffectedRows() > 0)
				{
					//requête pour dépromouvoir le propriétaire
					$query = "UPDATE `#__gpr_membres`
								SET statut = 0
								WHERE id_projet = ".$db->quote($id_projet)."
								AND id_joomla =".$db->quote($id_proprietaire);

					$db->setQuery($query);

					//on essaye de dépromouvoir le membre
					try
					{
						$db->execute();

						//on vérifie qu'une ligne a été modifiée
						if($db->getAffectedRows() > 0)
						{
							return true;
						}
						//si on a pas pu dépromouvoir le propriétaire on annule tout
						else
						{
							//on annule la promotion du membre
							$query = "UPDATE `#__gpr_membres`
										SET statut = 0
										WHERE id_projet = ".$db_quote($id_projet)."
										AND id_joomla =".$db->quote($id_membre);

							$db->setQuery($query);

							$db->execute();

							$this->erreur = "Impossible de trouver le propriétaire du projet";
							return false;
						}

					}
					catch (Exception $e)
					{
						if(defined('_DEBUG'))
						{
							$this->erreur = $e->getMessage();
						}
						else
						{
							$this->erreur = "Impossible de transférer les droits";
						}
						return false;
					}
				}
				else
				{
					$this->erreur = "Le membre n'est pas membre du projet";
					return false;
				}
			}
			catch (Exception $e)
			{
				if(defined('_DEBUG'))
				{
					$this->erreur = $e->getMessage();
				}
				else
				{
					$this->erreur = "Impossible de transférer les droits";
				}
				return false;
			}
		}

		/*===============================================
				FONCTIONS RELATIVES AUX POSTITS
		================================================*/

		/**
		  * Crée un postit pour un membre et un projet
		  * @param : $id_membre - int : id du membre pour qui créer le postit
		  * @param : $id_projet - int : id du projet
		  * @return : mixed :
		  *				-> Si tout se passe bien => l'id du postit créé
		  *				-> Si une erreur survient => false
		  */
		public function creerPostit()
		{
			if(!isset($this->erreur))
			{
				$this->erreur = "";
			}

			//on récupère la bdd
			$db = $this->getDBO();

			//requête pour créer le postit
			$query = "INSERT INTO `#__gpr_postit` (`texte`, `token`) 
						VALUES ('',".$db->quote(0).")";

			$db->setQuery($query);

			//on essaye d'exécuter la requête
			try
			{
				$db->execute();

				$id_postit = $db->insertid();

				return $id_postit;
			}
			catch (Exception $e)
			{
				if(defined('_DEBUG'))
				{
					$this->erreur = $e->getMessage();
				}
				else
				{
					$this->erreur = "Impossible de créer le postit";
				}
				return false;
			}
		}

		/*===============================================
				FONCTIONS RELATIVES AUX SERVICES
		================================================*/

		/**
		  * Récupère les demandes de services d'un projet
		  * @param : $id_projet - int : in du projet dont on veut récupérer les demandes
		  * @return : mixed
		  */
		public function getDemandesProj($id_projet)
		{
			if(!isset($this->erreur))
			{
				$this->erreur = "";
			}

			//bdd
			$db = $this->getDBO();

			$query = "SELECT * FROM `#__gpr_svn` WHERE id_projet=".$db->quote($id_projet);

			$db->setQuery($query);

			//try : récupérer les demandes de svn
			try
			{
				$db->execute();

				//on traite les demandes de svn
				if($db->getNumRows() > 0)
				{
					$this->msg['svn'] = array();

					$result = $db->loadAssocList();

					foreach ($result as $key => $demande) {
						$this->msg['svn'][] = $demande;
					}
				}

				$query = "SELECT * FROM `#__gpr_vps` WHERE id_projet=".$db->quote($id_projet);

				$db->setQuery($query);

				//try : récupérer les demandes de vps
				try
				{
					$db->execute();

					//on traite les demandes de vps
					if($db->getNumRows() > 0)
					{
						$this->msg['vps'] = array();

						$result = $db->loadAssocList();

						foreach ($result as $key => $demande) {
							$this->msg['vps'][] = $demande;
						}
					}

					return $this->msg;
				}
				catch(Exception $e)
				{
					if(defined('_DEBUG'))
					{
						$this->erreur = $e->getMessage();
					}
					else
					{
						$this->erreur = "Impossible de récupérer les demandes";
					}
					return false;
				}

			}
			catch (Exception $e)
			{
				if(defined('_DEBUG'))
				{
					$this->erreur = $e->getMessage();
				}
				else
				{
					$this->erreur = "Impossible de récupérer les demandes";
				}
				return false;
			}
		}

		/**
		  * Récupère les demandes de services en attente
		  * @return : mixed
		  *				=> En cas d'erreur : bool false
		  *				=> Si tout va bien : array()
		  *						-> ['svn'] contient les demandes de svn
		  *						-> ['vps'] contient les demandes de vps
		  */
		public function getDemandes()
		{
			if(!isset($this->erreur))
			{
				$this->erreur = "";
			}

			//bdd
			$db = $this->getDBO();

			$query = "SELECT * FROM `#__gpr_svn` WHERE `etat`='attente'";

			$db->setQuery($query);

			//try : récupérer les demandes de svn
			try
			{
				$db->execute();

				//on traite les demandes de svn
				if($db->getNumRows() > 0)
				{
					$this->msg['svn'] = array();

					$result = $db->loadAssocList();

					foreach ($result as $key => $demande) {
						$this->msg['svn'][] = $demande;
					}
				}

				$query = "SELECT * FROM `#__gpr_vps` WHERE `etat`='attente'";

				$db->setQuery($query);

				//try : récupérer les demandes de vps
				try
				{
					$db->execute();

					//on traite les demandes de vps
					if($db->getNumRows() > 0)
					{
						$this->msg['vps'] = array();

						$result = $db->loadAssocList();

						foreach ($result as $key => $demande) {
							$this->msg['vps'][] = $demande;
						}
					}

					return $this->msg;
				}
				catch(Exception $e)
				{
					if(defined('_DEBUG'))
					{
						$this->erreur = $e->getMessage();
					}
					else
					{
						$this->erreur = "Impossible de récupérer les demandes";
					}
					return false;
				}

			}
			catch (Exception $e)
			{
				if(defined('_DEBUG'))
				{
					$this->erreur = $e->getMessage();
				}
				else
				{
					$this->erreur = "Impossible de récupérer les demandes";
				}
				return false;
			}
		}

		/**
		  * Ajoute une demande de svn dans la bdd
		  * @param : $descritption - string
		  * @param : $taille - int
		  * @param : $droits - string
		  * @param : $id_projet - int
		  * @return : bool : true si l'ajout s'est effectué sans problème, false en cas d'erreur
		  */
		public function ajouterDemandeSvn($description, $taille, $droits, $id_projet)
		{
			if(!isset($this->erreur))
			{
				$this->erreur = "";
			}

			//bdd
			$db = $this->getDBO();

			$query = "INSERT INTO `#__gpr_svn` (`raison_demande`,`taille`,`droits`, `id_projet`, `etat`)
						VALUES (".$db->quote($description).",".$db->quote($taille).",".$db->quote($droits).",
							".$db->quote($id_projet).",".$db->quote("attente").")";

			$db->setQuery($query);

			try
			{
				$db->execute();

				return true;
			}
			catch(Exception $e)
			{
				if(defined('_DEBUG'))
				{
					$this->erreur = $e->getMessage();
				}
				else
				{
					$this->erreur = "Impossible de créer la demande";
				}
				return false;
			}
		}

		/**
		  * Ajoute une demande de vps dans la bdd
		  * @param : $descritption - string
		  * @param : $taille - int
		  * @param : $ram_mini - int
		  * @param : $ram_burst - int
		  * @param : $nb_cpu - int
		  * @param : $id_projet - int
		  * @return : bool : true si l'ajout s'est effectué sans problème, false en cas d'erreur
		  */
		public function ajouterDemandeVps($description, $taille, $ram_mini, $ram_burst, $nb_cpu, $id_projet)
		{
			if(!isset($this->erreur))
			{
				$this->erreur = "";
			}

			//bdd
			$db = $this->getDBO();

			$query = "INSERT INTO `#__gpr_vps` (`raison_demande`,`espace_disque`,`ram_min`, `ram_burst`, `nb_cpu`, `id_projet`, `etat`)
						VALUES (".$db->quote($description).",".$db->quote($taille).",
							".$db->quote($ram_mini).", ".$db->quote($ram_burst).",
							".$db->quote($nb_cpu).",".$db->quote($id_projet).",".$db->quote("attente").")";

			$db->setQuery($query);

			try
			{
				$db->execute();

				return true;
			}
			catch(Exception $e)
			{
				if(defined('_DEBUG'))
				{
					$this->erreur = $e->getMessage();
				}
				else
				{
					$this->erreur = "Impossible de créer la demande";
				}
				return false;
			}
		}

		/**
		  * Change l'état de la demande dans la bdd
		  * Ajoute la tache dans la table pour le deamon si la demande est acceptée
		  * @param : $id_demande - int : id de la demande à traiter
		  * @param : $service_demande - string : "svn" ou "vps"
		  * @param : $accepte - bool : true pour accepter la demande false pour la refuser
		  * @return : bool - true si tout se déroule bien, false sinon
		  * En cas d'erreur le message est stocké dans $this->erreur accessible via la méthode getErreur()
		  */
		public function traiterDemande ($id_demande, $service_demande, $accepte)
		{
			if(!isset($this->erreur))
			{
				$this->erreur = "";
			}

			//on récupère la bdd
			$db = $this->getDBO();

			if($accepte)
			{
				$statut = "accepte";
			}
			else
			{
				$statut = "refus";
			}

			$query = "UPDATE".$db->quoteName("#__gpr_".$service_demande)."
						SET `etat` =".$db->quote($statut)."
						WHERE `id`=".$db->quote($id_demande);

			$db->setQuery($query);

			// on essaye d'exécuter la requête
			try
			{
				//exécute la requête
				$db->execute();

				/*=============================
				Pense à vérifier si ta requete
				s'est bien effectué et renvoyer
				true ou false en conséquence
				==============================*/
				if($service_demande == "svn" && $accepte)
				{
					/*==================================================

					--------> Insérer le code pour le svn ici <---------

					==================================================*/
				}
				if($service_demande == "vps" && $accepte)
				{
					/*==================================================

					--------> Insérer le code pour le vps ici <---------
					
					==================================================*/
				}
				return true;
			}
			catch (Exception $e)
			{
				if(defined('_DEBUG'))
				{
					$this->erreur = $e->getMessage();
				}
				else
				{
					$this->erreur = "Impossible d'accepter la demande";
				}
				return false;
			}
		}
	}