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
 * Lenex processing helper class.
 */
class FreestrokeLenexHelper  {
	protected $timestandardlist;
	
	/**
	 * 
	 */
	public function parseFile($lenex, $meetid) {
		
		/* process TIMESTANDARDLISTS first becasue the are referred to by ENTRY */
		if(!is_null($lenex->TIMESTANDARDLISTS)) {
			$this->timestandardlist = array();
			foreach ( $lenex->TIMESTANDARDLISTS as $timestandardNode ) {
				$this->processTimestandard ( timestandardNode );
			}
		} 
		
		$meetsNode = $lenex->MEETS;
		foreach ( $meetsNode->MEET as $meetNode ) {
			$this->processMeet ( $meetid, $meetNode );
		}
	}

	/**
	 * processTimestandard
	 * @param unknown $timestandardNode
	 */
	private function processTimestandard($timestandardNode) {
		$timestandard = new stdClass();
		$timestandard->id = (int) $timestandardNode["timestandardlistid"];
	}

	/**
	 * Process the xml at the meet level
	 */
	private function processMeet($meetid, $meet) {
		foreach ( $meet->SESSIONS->SESSION as $sessionNode ) {
			// 			echo "Name: " . $meet->name;
			// 			echo "City: " . $meet->city;
			// 			echo "Course: " . $meet->course;
			// 			echo "Deadline: " . $meet->deadline;
				
			$this->processSession ( $meetid, $sessionNode );
		}
	}
	
	/**
	 * Process the xml at the session level
	 */
	private function processSession($meetid, $sessionNode) {
		// echo "Session date: " . $sessionNode->date;
		// echo "Session time: " . $sessionNode->daytime;
		// echo "Session number: " . $sessionNode->number;
		// echo "Session officialmeeting: " . $sessionNode->officialmeeting;
		// echo "Session teamleadermeeting: " . $sessionNode->teamleadermeeting;
		// echo "Session warmup: " . $sessionNode->warmupfrom;
		$sessionTable = & JTable::getInstance ( 'meetsession', 'FreestrokeTable' );
	
		$session = array ();
		$session ["meets_id"] = $meetid;
		$session ["number"] = ( int ) $sessionNode ["number"];
		$session ["startdate"] = $this->formatdatetime ( ( string ) $sessionNode ["date"], ( string ) $sessionNode ["daytime"] );
		$session ["starttime"] = $this->formatdatetime ( ( string ) $sessionNode ["date"], ( string ) $sessionNode ["daytime"] );
		$session ["name"] = "dag " . ( string ) $sessionNode ["number"];
		// print_r($session);exit;
	
		$sessionTable->bind ( $session );
	
		// Make sure the data is valid
		if (! $sessionTable->check ()) {
			$this->setError ( $sessionTable->getError () );
			echo JText::_ ( 'Error check: ' ) . $sessionTable->getError () . "\n";
			return;
		}
		if (! $sessionTable->store ()) {
			echo JText::_ ( 'Error store: ' ) . $this->_db->getErrorMsg () . "\n";
			return;
		}
	
		foreach ( $sessionNode->EVENTS->EVENT as $event ) {
			$this->processEvent ( $meetid, $event );
		}
	}
	
	/**
	 * processEvent
	 */
	private function processEvent($meetid, $eventNode) {
		$eventTable = & JTable::getInstance ( 'event', 'FreestrokeTable' );
	
		// print_r($session);exit;
		$event = array ();
		$event ["meets_id"] = $meetid;
		$event ["eventtype"] = 1; // no good value yet
		$event ["sessionnumber"] = ( int ) $eventNode ["number"];
		$event ["ordering"] = ( int ) $eventNode ["order"];
		$event ["daytime"] = (string) $eventNode["daytime"];
		$event ["gender"] = (string) $eventNode["gender"];
	
		$distance = (int) $eventNode->SWIMSTYLE["distance"];
		$stroke = (string) $eventNode->SWIMSTYLE["stroke"];
		$relaycount = (int) $eventNode->SWIMSTYLE["srelaycount"];
	
		$swimstyleTable = & JTable::getInstance ( 'swimstyle', 'FreestrokeTable' );
		$swimstyle = $swimstyleTable->find($distance, $relaycount, $stroke);
		$event["swimstyles_id"] = $swimstyle->id;
	
		foreach ( $eventNode->AGEGROUPS->AGEGROUP as $agegroup ) {
			$event ["minage"] = ( int ) $agegroup ["agemin"];
			$event ["maxage"] = ( int ) $agegroup ["agemax"];
			$event ["limitmax1"] = 0;
			$event ["limitmax2"] = 0;
			$event ["limitmax3"] = 0;
			$event ["limitmin1"] = 0;
			$event ["limitmin2"] = 0;
			$event ["limitmin3"] = 0;
				
			$eventTable->bind ( $event );
				
			// Make sure the data is valid
			if (! $eventTable->check ()) {
				$this->setError ( $eventTable->getError () );
				echo JText::_ ( 'Error check: ' ) . $eventTable->getError () . "\n";
				return;
			}
			if (! $eventTable->store ()) {
				echo JText::_ ( 'Error store: ' ) . $this->_db->getErrorMsg () . "\n";
				return;
			}
		}
	}
	
	/**
	 *
	 * @param unknown $thedate
	 * @param unknown $thetime
	 * @return NULL
	 */
	private function formatdatetime($thedate, $thetime) {
		if ($thedate != '') {
			$value = $thedate . " " . $thetime;
			$date = strtotime ( $value );
			$field = strftime ( '%Y-%m-%d %H:%M:00', $date );
		} else {
			$field = null;
		}
		return $field;
	}
	
	/**
	 *
	 * @param unknown $value
	 * @return NULL
	 */
	private function formattime($value) {
		if ($value != '') {
			$time = strtotime ( $value );
			$field = strftime ( '%H:%M', $time );
		} else {
			$field = null;
		}
		return $field;
	}
	
}
