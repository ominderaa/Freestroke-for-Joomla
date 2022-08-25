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
class FreestrokeMeetsHelper {
	
	/**
	 * Load by id
	 */
	public static function load($meetsid) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query
		    ->select($db->quoteName(array('id', 'name', 'mindate', 'maxdate', 'place')))
		    ->from($db->quoteName('#__freestroke_meets'))
		    ->where($db->quoteName('id') . ' = '. $db->quote($meetsid));
		$db->setQuery($query);
		$result = $db->loadObject();
		return $result;
	}

	/**
	 * Load by name, city, min_date
	 */
	public static function findByNameCityAndDate($name, $city, $mindate) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query
		    ->select($db->quoteName(array('id', 'name', 'mindate', 'maxdate', 'place')))
		    ->from($db->quoteName('#__freestroke_meets'))
		    ->where($db->quoteName('name') . ' = '. $db->quote($name))
		    ->where($db->quoteName('place') . ' = '. $db->quote($city), 'and')
		    ->where($db->quoteName('mindate') . ' = '. $db->quote($mindate), 'and');
		$db->setQuery($query);
		$result = $db->loadObject();
		return $result;
	}

	/**
	 * Load by name, city, min_date
	 */
	public static function countByNameCityAndDate($name, $city, $mindate) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query
		    ->select('COUNT(*)')
		    ->from($db->quoteName('#__freestroke_meets'))
		    ->where($db->quoteName('name') . ' = '. $db->quote($name))
		    ->where($db->quoteName('place') . ' = '. $db->quote($city), 'and')
		    ->where($db->quoteName('mindate') . ' = '. $db->quote($mindate), 'and');
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}

	/**
	 * Load by city, min_date
	 */
	public static function findByCityAndDate($city, $mindate) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query
		    ->select($db->quoteName(array('id', 'name', 'mindate', 'maxdate', 'place')))
		    ->from($db->quoteName('#__freestroke_meets'))
		    ->where($db->quoteName('place') . ' = '. $db->quote($city))
		    ->where($db->quoteName('mindate') . ' = '. $db->quote($mindate), 'and');
		$db->setQuery($query);
		$result = $db->loadObject();
		return $result;
	}

	/**
	 * Load by city, min_date
	 */
	public static function countByCityAndDate($city, $mindate) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query
			->select('COUNT(*)')
			->from($db->quoteName('#__freestroke_meets'))
		    ->where($db->quoteName('place') . ' = '. $db->quote($city))
		    ->where($db->quoteName('mindate') . ' = '. $db->quote($mindate), 'and');
		$db->setQuery($query);
		$result = $db->loadObject();
		return $result;
	}
}