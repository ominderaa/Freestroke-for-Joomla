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
 */
class FreestrokeSwimstyleHelper {
	
	/**
	 * Find by distance, relaycount, stroke
	 */
	public static function find($distance, $relaycount, $stroke) {
		$db = JFactory::getDbo ();
		$query = $db->getQuery ( true );
		$query->select ( $db->quoteName ( array (
				'id',
				'distance',
				'relaycount',
				'strokecode' )))
		->from ( $db->quoteName ( '#__freestroke_swimstyles' ) )
		->where ( $db->quoteName ( 'distance' ) . ' = ' . $db->quote ( $distance ) )
		->where ( $db->quoteName ( 'relaycount' ) . ' = ' . $db->quote ( $relaycount ), 'and' )
		->where ( $db->quoteName ( 'strokecode' ) . ' = ' . $db->quote ( $stroke ), 'and' );
		$db->setQuery ( $query );
		$result = $db->loadObject ();
		return $result;
	}
	
	/**
	 *
	 * @param unknown $code        	
	 * @return unknown
	 */
	public static function findByCode($code) {
		$db = JFactory::getDbo ();
		$query = $db->getQuery ( true );
		$query->select ( $db->quoteName ( array (
				'id',
				'distance',
				'relaycount',
				'strokecode' )))
		->from ( $db->quoteName ( '#__freestroke_swimstyles' ) )
		->where ( $db->quoteName ( 'code' ) . ' = ' . $db->quote ( $code ) );
		$db->setQuery ( $query );
		$result = $db->loadObject ();
		return $result;
	}
	
	/**
	 *
	 * @param unknown $swimstyle        	
	 */
	public static function create($swimstyle) {
		$swimstyleTable = & JTable::getInstance ( 'swimstyle', 'FreestrokeTable' );
		
		$swimstyleEntity = array ();
		$swimstyleEntity ['id'] = null;
		$swimstyleEntity ['distance'] = $swimstyle->distance;
		$swimstyleEntity ['relaycount'] = $swimstyle->relaycount;
		$swimstyleEntity ['strokecode'] = $swimstyle->stroke;
		$swimstyleEntity ['code'] = $swimstyle->swimstyleid;
		$swimstyleEntity ['name'] = $swimstyle->name;
		
		$swimstyleTable->bind ( $swimstyleEntity );
		
		// Make sure the data is valid
		if (! $swimstyleTable->check ()) {
			echo JText::_ ( 'Error check: ' ) . $swimstyleTable->getError () . "\n";
			return null;
		}
		if (! $swimstyleTable->store ()) {
			echo JText::_ ( 'Error store: ' ) . $swimstyleTable->_db->getErrorMsg () . "\n";
			return null;
		}
		$swimstyle->id = $swimstyleTable->id;
		return $swimstyle;
	}
}
