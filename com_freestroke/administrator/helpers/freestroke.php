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
 * Freestroke helper.
 */
class FreestrokeHelpersFreestroke {
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($vName = '') {
		JHtmlSidebar::addEntry(JText::_('COM_FREESTROKE_TITLE_MEMBERS'), 'index.php?option=com_freestroke&view=members', $vName == 'members');
		JHtmlSidebar::addEntry(JText::_('COM_FREESTROKE_TITLE_MEETS'), 'index.php?option=com_freestroke&view=meets', $vName == 'meets');
		JHtmlSidebar::addEntry(JText::_('COM_FREESTROKE_TITLE_VENUES'), 'index.php?option=com_freestroke&view=venues', $vName == 'venues');
		JHtmlSidebar::addEntry(JText::_('COM_FREESTROKE_TITLE_SWIMSTYLES'), 'index.php?option=com_freestroke&view=swimstyles', $vName == 'swimstyles');
	}
	
	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return JObject
	 * @since 1.6
	 */
	public static function getActions() {
		$user = JFactory::getUser();
		$result = new JObject();
		
		$assetName = 'com_freestroke';
		
		$actions = array(
				'core.admin',
				'core.manage',
				'core.create',
				'core.edit',
				'core.edit.own',
				'core.edit.state',
				'core.delete'
		);
		
		foreach ( $actions as $action ) {
			$result->set($action, $user->authorise($action, $assetName));
		}
		
		return $result;
	}
}


class FreestrokeHelper extends FreestrokeHelpersFreestroke
{

}