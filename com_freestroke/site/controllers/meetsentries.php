<?php
/**
 * @version     1.0.0
 * @package     com_freestroke
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      O.Minderaa <ominderaa@gmail.com> - http://
 */

// No direct access.
defined('_JEXEC') or die;

jimport ( 'joomla.application.component.controller' );

/**
 * Meets list controller class.
 */
class FreestrokeControllerMeetsentries extends JControllerLegacy
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function &getModel($name = 'Meetsentries', $prefix = 'FreestrokeModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
}