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
class FreestrokeControllerMeetentries extends JControllerLegacy {
	/**
	 * display task
	 *
	 * @return void
	 */
	public function display($cachable = false, $urlparams = false) {
		$view = $this->getView('Meetentries', 'html');
		$view->setModel ( $this->getModel ( 'Meet' ), true );
		$view->setModel ( $this->getModel ( 'Meetsessions' ));
		$view->setModel ( $this->getModel ( 'Events' ) );
		$view->setModel ( $this->getModel ( 'Entries' ) );
		$view->setModel ( $this->getModel ( 'Relayentries' ) );
		$view->display ();
	}
}