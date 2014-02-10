<?php

/**
 * @version     1.0.0
 * @package     com_gpr
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Choulet Quentin, Fourgeaud Mickaël, Hauguel Antoine, Malory Tristan <> - 
 */


// No direct access
defined('_JEXEC') or die;

/**
  * Format des URLS
  *		- JOOMLA_URL/id_projet/vue/tache
  *		- Dans le cas où la vue est "projets" on n'ajoute pas la vue à l'url
  * 	- projets
  *			-> JOOMLA_URL/creer => création d'un projet
  *			-> JOOMLA_URL/voir => liste des projets
  *			-> JOOMLA_URL/id_projet/voir => voir un projet
  * Le nom d'une tache est en deux parties
  * 	=> nom_controller.nom_méthode
*/


/**
 * Construit une URL lisible à partir de la requête (voir formats des urls au dessus)
 * @param	array	A named array
 * @return	array
 */
function gprBuildRoute(&$query)
{
	$segments = array();
	$task = array();
	$view = null;
	$task = null;
	$id_projet = null;

	// la vue
	if(isset($query['view'])){
		$view = $query['view'];
		unset($query['view']);
	}

	// l'id du projet
	if (isset($query['id_projet'])){
		$id_projet = $query['id_projet'];
		unset($query['id_projet']);
	}

	// la tache
	// la deuxième partie de la tache est celle qui nous intéresse
	if (isset($query['task'])) {
		$task = explode('.',$query['task']);
		unset($query['task']);
	}

	//on construit l'url
	if($view == "projets")
	{
		if($id_projet)
		{
			$segments[] = $id_projet;
		}
		$segments[] = $task[1];
	}
	else
	{
		$segments[] = $id_projet;
		$segments[] = $view;
		$segments[] = $task[1];
	}

	return $segments;
}


/**
 * Parse une url lisible
 * Construit les variables de la requete à partir de cette url
 * (voir format des urls en début de fichier)
 * @param	array	A named array
 * @param	array
 */

function gprParseRoute($segments)
{
	$vars = array();    

	$count = count($segments);

    if ($count)
	{
		//on récupère le premier élément de l'url
		$segment = array_shift($segments);
		$count--;

		//si le premier élément est numérique, c'est l'id du projet
		if(is_numeric($segment))
		{
			$vars['id_projet'] = $segment;

			//si il reste au moins deux éléments, le premier élément est la vue
			//le deuxième élément est la deuxième partie de la tache
			if($count > 1)
			{
				$vars['view'] = $segments[0];
				$vars['task'] = implode('.', $segments);
			}
			//sinon c'est qu'on a à faire à la vue "projets", qu'on affiche pas dans l'url
			//l'élément restant correspond à la deuxième partie de la tache
			else if ($count)
			{
				$vars['view'] = "projets";
				$vars['task'] = "projets.".$segments[0];
			}
		}
		//sinon c'est une tache et la vue est "projets"
		else
		{
			$vars['task'] = "projets".$segment;
			$vars['view'] = "projets";
		}
	}

	return $vars;
}

