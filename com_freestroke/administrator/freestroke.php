<?php
/**
 * @version     1.0.0
 * @package     com_freestroke
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      O.Minderaa <ominderaa@gmail.com> - http://
 */

// no direct access
defined ( '_JEXEC' ) or die ();

// Access check.
if (! JFactory::getUser ()->authorise ( 'core.manage', 'com_freestroke' )) {
	throw new Exception ( JText::_ ( 'JERROR_ALERTNOAUTHOR' ) );
}

// Include dependancies
jimport ( 'joomla.application.component.controller' );
JLoader::registerPrefix ( 'Freestroke', JPATH_COMPONENT_ADMINISTRATOR );

$controller = JControllerLegacy::getInstance ( 'Freestroke' );
$controller->execute ( JFactory::getApplication ()->input->get ( 'task' ) );
$controller->redirect ();
