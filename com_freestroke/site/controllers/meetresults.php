<?php
/**
 * @version     1.0.0
 * @package     com_freestroke
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      O.Minderaa <ominderaa@gmail.com> - http://
 */

// No direct access
defined ( '_JEXEC' ) or die ();

jimport ( 'joomla.application.component.controller' );

/**
 * Meet controller class.
 */
class FreestrokeControllerMeetresults extends JControllerLegacy {
	/**
	 * display task
	 *
	 * @return void
	 */
	public function display($cachable = false, $urlparams = false) {
		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$viewName = JRequest::getCmd('view', $this->default_view);
		$viewLayout = JRequest::getCmd('layout', 'default');
		$view = $this->getView($viewName, $viewType, '', array('base_path' => $this->basePath, 'layout' => $viewLayout));

		$view->setModel ( $this->getModel ( 'Meet' ), true );
		$view->setModel ( $this->getModel ( 'Meetsessions' ) );
		$view->setModel ( $this->getModel ( 'Results' ) );
		$view->setModel ( $this->getModel ( 'RelayResults' ) );
		$view->display ();
	}
}