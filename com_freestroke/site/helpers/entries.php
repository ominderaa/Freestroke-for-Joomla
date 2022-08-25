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

/**
 * Events DAO helper class.
 * 
 */
class FreestrokeEntriesHelper {

	/**
	 * 
	 * @param unknown $meetsid
	 * @return unknown
	 */
	public static function findByMeetid($meetsid) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query
		    ->select($db->quoteName(array('id')))
		    ->from($db->quoteName('#__freestroke_entries'))
		    ->where($db->quoteName('meets_id') . ' = '. $db->quote($meetsid));
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
	}
}