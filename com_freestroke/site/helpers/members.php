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
class FreestrokeMembersHelper {
	
	/**
	 * Find by Registrationid
	 */
	public static function findByRegistrationid($registrationid) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query
		    ->select($db->quoteName(array('id')))
		    ->from($db->quoteName('#__freestroke_members'))
		    ->where($db->quoteName('registrationid') . ' = '. $db->quote($registrationid));
		$db->setQuery($query);
		$result = $db->loadObject();
		return $result;
	}

	/**
	 * Find by Registrationid
	 */
	public static function findByNameGenderBirthdate( $lastname, $firstname, $gender, $birthdate ) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query
		    ->select($db->quoteName(array('id')))
		    ->from($db->quoteName('#__freestroke_members'))
		    ->where($db->quoteName('lastname') . ' = '. $db->quote($lastname))
		    ->where($db->quoteName('firstname') . ' = '. $db->quote($firstname), 'AND')
		    ->where($db->quoteName('gender') . ' = '. $db->quote($gender), 'AND')
		    ->where($db->quoteName('birthdate') . ' = '. $db->quote($birthdate), 'AND')
		    ;
		$db->setQuery($query);
		$result = $db->loadObject();
		return $result;
	}
	
	public static function updateLisenceForMember($id, $license) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$where = "id = " . $id;
		$query->update($db->quoteName('#__freestroke_members'))
			->set(array($db->quoteName('registrationid') . ' = ' . $db->quote($license)))
			->where(array($db->quoteName('id') . ' = ' . $db->quote($id)));
		$db->setQuery($query);
		return $db->query();
	}
}
