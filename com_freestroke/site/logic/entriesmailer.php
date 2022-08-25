<?php
/**
 * @version     1.0.0
* @package     com_freestroke
* @copyright   Copyright (C) 2013. All rights reserved.
* @license     GNU General Public License version 2 or later; see LICENSE.txt
* @author      O.Minderaa <ominderaa@gmail.com> - http://
*/

// No direct access
defined('_JEXEC') or die();

/**
 * Processing class creates and sends reminder emails for swimmers entered into meets.
 */
class FreestrokeEntriesMailer {
	protected $app;
	
	/**
	 *
	 * @param unknown $lenex        	
	 * @param unknown $meetid        	
	 * @param unknown $clubcode        	
	 */
	public function sendmails($meetid, $reminder = false, $correction = false) {
		require_once JPATH_COMPONENT . '/helpers/conversion.php';
		require_once JPATH_COMPONENT . '/helpers/swimstyle.php';
		require_once JPATH_COMPONENT . '/helpers/events.php';
		require_once JPATH_COMPONENT . '/helpers/members.php';
		require_once JPATH_COMPONENT . '/helpers/entries.php';
		require_once JPATH_COMPONENT . '/helpers/relays.php';
		
		$this->app = &JFactory::getApplication();
		return false;
	}
}
