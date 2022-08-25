<?php
/**
 * @version     1.0.0
 * @package     com_freestroke
 * @copyright   Copyright (C) 2013. Alle rechten voorbehouden.
 * @license     GNU General Public License versie 2 of hoger; Zie LICENSE.txt
 * @author      O.Minderaa <ominderaa@gmail.com> - http://
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Swimstyle controller class.
 */
class FreestrokeControllerSwimstyle extends JControllerForm
{

    function __construct() {
        $this->view_list = 'swimstyles';
        parent::__construct();
    }

}