<?php
/**
 * @version     1.0.0
 * @package     com_freestroke
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      O.Minderaa <ominderaa@gmail.com> - http://
 */
defined ( '_JEXEC' ) or die ();

// Include dependancies
jimport ( 'joomla.application.component.controller' );

$view = JFactory::getApplication ()->input->get ( 'view' );
if (isset ( $view )) {
	$file = JPATH_COMPONENT.'/controllers/'.$view.'.php';
	require_once ($file);
	$controllerClass = 'FreestrokeController' . ucfirst ( $view );
	
	// Execute the task.
	// $controller = JController::getInstance($controllerClass);
	$controller = new $controllerClass ();
	$controller->execute ( JFactory::getApplication ()->input->get ( 'task', 'display' ) );
} else {
	// revert to default behaviour
	$controller = JControllerLegacy::getInstance ( 'Freestroke' );
	$controller->execute ( JFactory::getApplication ()->input->get ( 'task' ) );
}
$controller->redirect ();
