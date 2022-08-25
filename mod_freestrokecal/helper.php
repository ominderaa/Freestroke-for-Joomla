<?php
/**
 * @version     1.0.0
 * @package     com_freestroke
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      O.Minderaa <ominderaa@gmail.com> - http://
 */
class modFreestrokecalHelper {
	const FREESTROKE_TIMESEPARATOR = ' | ';
	const FREESTROKE_TIME0000 = '00:00';
	
	/**
	 *
	 * @return unknown
	 */
	public function getMeetsList(&$params) {
		// Create a new query object.
		$db = JFactory::getDBO ();
		$query = $db->getQuery ( true );
		
		// Select the required fields from the table.
		$now = JDate::getInstance ( 'now' );
		$now->setTime ( 0, 0, 0 );
		$query->select ( array (
				'm.id',
				'm.name',
				'm.mindate',
				'ms.id as msid',
				'ms.sessionnumber',
				'ms.name as sessionname',
				'ms.startdate',
				'ms.starttime') )
				->from ( '#__freestroke_meets AS m' )
				->join ( 'LEFT', '#__freestroke_meetsessions ms ON ms.meets_id = m.id' )
				->where ( 'm.mindate >= ' . $db->Quote ( $now ), 'OR' )
				->where ( 'ms.startdate >= ' . $db->Quote ( $now ))
				->order ( array (
						'm.mindate ASC',
						'ms.startdate ASC',
						'm.id ASC'
				) );
				
				$maxitems = intval ( $params->get ( 'maxitems', '4' ) );
				$db->setQuery ( $query );
				$meets = $db->loadObjectList ();
				$result = array ();
				if ($meets) {
					
					$i = 0;
					$meetsincluded = array ();
					foreach ( $meets as $meet ) {
						if ($i >= $maxitems)
							break;
							
							if (! $meetsincluded [$meet->id]) {
								$meetsincluded [$meet->id] = true;
								
								$length = strlen ( htmlspecialchars ( $meet->name ) );
								if ($length > $params->get ( 'maxnamelength', '40' )) {
									$meetname = substr ( $meet->name, 0, $params->get ( 'maxnamelength', '40' ) );
									$meetname = htmlspecialchars ( $meetname . '...', ENT_COMPAT, 'UTF-8' );
								} else {
									$meetname = $meet->name;
								}
								$meetname = str_replace ( "/", "/&#8203;", $meetname );
								$meetname = str_replace ( "-", "-&#8203;", $meetname );
								
								$dateformat = $params->get ( 'dateformat', '%d.%m.%Y' );
								if (! empty ( $meet->startdate )) {
									$meetdate = JFactory::getDate ( $meet->startdate );
									$meettime = $meet->starttime;
								} else {
									$meetdate = JFactory::getDate ( $meet->mindate );
									$meettime = null;
								}
								
								$result [$i] = new stdClass ();
								$result [$i]->startdate = $meetdate;
								$result [$i]->starttime = $meettime;
								$result [$i]->link = JRoute::_ ( 'index.php?option=com_freestroke&view=meetattendance&id=' . ( int ) $meet->id );
								$result [$i]->text = $meetname;
								$result [$i]->dateinfo = $meetdate;
								$i ++;
							}
					}
				}
				return $result;
	}
	
	/**
	 *
	 * @param unknown $params
	 */
	static function getJEMEventList(&$params) {
		JModelLegacy::addIncludePath ( JPATH_SITE . '/components/com_jem/models', 'JemModel' );
		$model = JModelLegacy::getInstance ( 'Eventslist', 'JemModel', array (
				'ignore_request' => true
		) );
		$model->setState ( 'filter.published', 1 );
		$model->setState ( 'filter.calendar_from', JDate::getInstance ( 'now' ) );
		$count = $params->get ( 'maxitems', '4' );
		$model->setState ( 'list.limit', $count );
		$events = $model->getItems ();
		
		// Loop through the result rows and prepare data
		$i = 0;
		$lists = array ();
		
		foreach ( $events as $row ) {
			// cut titel
			$length = mb_strlen ( $row->title );
			
			if ($length > $params->get ( 'maxnamelength', '18' )) {
				$row->title = mb_substr ( $row->title, 0, $params->get ( 'maxnamelength', '18' ) );
				$row->title = $row->title . '...';
			}
			$row->title = str_replace ( "/", "/&#8203;", $row->title );
			$row->title = str_replace ( "-", "-&#8203;", $row->title );
			
			$lists [$i] = new stdClass ();
			$lists [$i]->link = JRoute::_ ( JemHelperRoute::getEventRoute ( $row->slug ) );
			$lists [$i]->startdate = JFactory::getDate ( $row->dates );
			$lists [$i]->starttime = $row->times;
			
			$lists [$i]->text = $row->title;
			$lists [$i]->city = htmlspecialchars ( $row->city, ENT_COMPAT, 'UTF-8' );
			$i ++;
		}
		
		return $lists;
	}
	
	/**
	 * Compare date and time of two events.
	 * Event date and time are formatted as: DD-MM-YYYY[ | HH:MM]
	 *
	 * @param string $a
	 * @param string $b
	 * @return see strcmp
	 */
	static function compareEvents($a, $b) {
		if ($a->startdate > $b->startdate)
			return 1;
			if ($b->startdate > $a->startdate)
				return - 1;
				return 0;
	}
	public function mergeEvents($meetslist, $eventlist, $maxitems) {
		if (is_array ( $meetslist )) {
			$result = $meetslist;
		}
		if (is_array ( $eventlist )) {
			if (is_array ( $meetslist )) {
				$result = array_merge ( $meetslist, $eventlist );
			} else {
				$result = $eventlist;
			}
		}
		
		usort ( $result, 'modFreestrokecalHelper::compareEvents' );
		return array_slice ( $result, 0, $maxitems );
	}
	public function getEventlistModulePath() {
		return JPATH_BASE . '/modules/mod_jem';
	}
}
