<?php
	/**
	 * @version     1.0.0
	 * @package     com_gpr
	 * @copyright   Copyright (C) 2013. All rights reserved.
	 * @license     GNU General Public License version 2 or later; see LICENSE.txt
	 * @author      Choulet Quentin, Fourgeaud Mickaël, Hauguel Antoine, Malory Tristan <> - 
	 */

	/**
	  * Point d'entrée de la partie utilisateur du composant
	  * Initialise le composant et le controlleur pour 
	  * l'exécution du composant
	*/	
	

	//empeche l'accès direct au fichier
	defined('_JEXEC') or die ('Restricted access');

	/* commenter cette ligne pour la mise en prod */
	define('_DEBUG', 1);

	//import joomla controller library
	jimport('joomla.application.component.controller');

	// Crée une instance du controleur
	// instancie un objet de commande d'une classe nommée gprController
	// Joomla va chercher la déclaration de cette classe dans le fichier controller.php (comportement par défaut) 
	$controller = JControllerLegacy::getInstance('gpr');

	// on récupère la requête et on la stock dans $input
	$input = JFactory::getApplication()->input;
	//getCmd va chercher un champs task dans la requete et le "transformer" en méthod
	//execute va chercher à exécuter la méthode du controller passée en paramètre
	//(retournée par getCmd et dont le nom est contenu dans le champs task de la requête)
	//par exemple on aura une méthode "ajouter_membre" dans le controller
	//et l'url sera du genre : <URL>?task=ajouter_membre&id=10...
	$controller->registerDefaultTask('default_task');
	$controller->execute($input->getCmd('task', 'default_task'));
 
	// Redirect if set by the controller
	$controller->redirect();