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

jimport('joomla.application.component.controllerform');

/**
 * Ajouterprojet controller class.
 */
class GprControllerAjouterprojet extends JControllerForm
{

    function __construct() {
        $this->view_list = 'projets';
        parent::__construct();
    }

}