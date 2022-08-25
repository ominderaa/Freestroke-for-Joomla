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
 * Lenex processing class that reads the meet program.
 */
class FreestrokeInvitationReader {
	protected $app;
	
	/**
	 */
	public function process($lenexRoot, $meetid) {
		require_once JPATH_COMPONENT . '/helpers/conversion.php';
		require_once JPATH_COMPONENT . '/helpers/swimstyle.php';
		require_once JPATH_COMPONENT . '/helpers/events.php';
		require_once JPATH_COMPONENT . '/helpers/meets.php';
		require_once JPATH_COMPONENT . '/helpers/meetsessions.php';
		
		$this->app = &JFactory::getApplication ();
		$meetObject = FreestrokeMeetsHelper::load($meetid);
		
		if($this->check($lenexRoot, $meetObject)) {
			foreach ( $lenexRoot->meets as $meet ) {
				$hasinvite = $this->processMeet ( $meetObject, $meet );
				$this->app->enqueueMessage ( sprintf(JText::_('COM_FREESTROKE_MEETS_IMPORTINVITE_MESSAGE'), $meet->name)  );
			}
		}
		return $hasinvite;
	}
	
	/**
	 *
	 * @param unknown $lenexRoot        	
	 * @param unknown $meetObject        	
	 * @return boolean
	 */
	private function check($lenexRoot, $meetObject) {
		foreach ( $lenexRoot->meets as $meet ) {
			if (! empty($meetObject->place) && $meetObject->place != $meet->city) {
				$this->app->enqueueMessage(JText::_('COM_FREESTROKE_MEETS_CITY_DIFFERENT'));
				return false;
			}
			foreach ( $meet->sessions as $session ) {
				$sessiondate = $session->date->format('Y-m-d');
				if (empty($meetObject->maxdate) || $meetObject->maxdate == "0000-00-00") {
					if ($meetObject->mindate != $sessiondate ) {
						$this->app->enqueueMessage(
								sprintf(JText::_('COM_FREESTROKE_MEETS_DATES_NOT_MINDATE'), $sessiondate, $meetObject->mindate ));
						return false;
					}
				} else {
					if ($meetObject->mindate > $sessiondate || $meetObject->maxdate < $sessiondate) {
						$this->app->enqueueMessage(
								sprintf(JText::_('COM_FREESTROKE_MEETS_DATES_NOT_MINMAXDATE'), $sessiondate, $meetObject->mindate, $meetObject->maxdate ));
						return false;
					}
				}
			}
		}
		
		return true;
	}
		
	/**
	 * Process the xml at the meet level
	 * @param unknown $meetObject
	 * @param unknown $meet
	 * @return boolean
	 */
	private function processMeet($meetObject, $meet) {
		$this->updateMeet($meetObject, $meet);
		$this->deleteProgram ( $meetObject->id );
		$hasinvite = false;
		foreach ( $meet->sessions as $session ) {
			$this->processSession ( $meetObject, $session );
			$hasinvite = true;
		}
		return $hasinvite;
	}
	
	/**
	 * Process the xml at the session level
	 */
	private function processSession($meetObject, $session) {
		foreach ( $session->events as $event ) {
			$this->processEvent ( $meetObject, $event );
		}
		
		$sessionTable = & JTable::getInstance ( 'meetsession', 'FreestrokeTable' );
		$sessionEntity = array ();
		$sessionEntity ["id"] = null;
		$sessionEntity ["meets_id"] = $meetObject->id;
		$sessionEntity ["sessionnumber"] = $session->number;
		$sessionEntity ["startdate"] = $session->date->toSql();
		$sessionEntity ["starttime"] = $session->daytime;
		if (! empty($session->name)) {
			$sessionEntity ["name"] = $session->name;
		} else {
			$sessionEntity ["name"] = $meetObject->name;
		}
		
		$sessionEntity ["officialmeeting"] = $session->officialmeeting;
		$sessionEntity ["teamleadermeeting"] = $session->teamleadermeeting;
		$sessionEntity ["warmupfrom"] = $session->warmupfrom;
		$sessionEntity ["warmupuntil"] = $session->warmupuntil;
		
		$sessionTable->bind ( $sessionEntity );
		
		// Make sure the data is valid
		if (! $sessionTable->check ()) {
			$this->setError ( $sessionTable->getError () );
			echo JText::_( 'Error check: ' ) . $sessionTable->getError () . "\n";
			return;
		}
		if (! $sessionTable->store ()) {
			echo JText::_( 'Error store: ' ) . $this->_db->getErrorMsg () . "\n";
			return;
		}
	}
	
	/**
	 *
	 * @param unknown $meetid        	
	 * @param unknown $event        	
	 */
	private function processEvent($meetObject, $event) {
		$eventTable = & JTable::getInstance('event', 'FreestrokeTable');
		
		$eventEntity = array ();
		$eventEntity ["id"] = null;
		$eventEntity ["meets_id"] = $meetObject->id;
		$eventEntity ["eventtype"] = 1; // no good value yet
		$eventEntity ["sessionnumber"] = $event->sessionnumber;
		$eventEntity ["programnumber"] = $event->number;
		if ($event->order != 0) {
			$eventEntity ["programorder"] = $event->order;
		} else {
			$eventEntity ["programorder"] = $event->number;
		}
		$eventEntity ["daytime"] = $event->daytime;
		if (isset($event->gender) && strlen($event->gender) > 0) {
			$eventEntity ["gender"] = $event->gender;
		} else {
			$eventEntity ["gender"] = 'X';
		}
		
		$swimstyle = FreestrokeSwimstyleHelper::find($event->swimstyle->distance, $event->swimstyle->relaycount, $event->swimstyle->stroke);
		if ($swimstyle == null) {
			$swimstyle = FreestrokeSwimstyleHelper::findByCode($event->swimstyle->swimstyleid);
			if ($swimstyle == null) {
				$swimstyle = FreestrokeSwimstyleHelper::create($event->swimstyle);
			}
		}
		$eventEntity ["swimstyles_id"] = $swimstyle->id;
		
		$eventDetails = $this->createEventFromAgegroups($event->agegroups);
		$eventEntity ["minage"] = $eventDetails ["agemin"];
		$eventEntity ["maxage"] = $eventDetails ["agemax"];
		$eventEntity ["limitmax1"] = 0;
		$eventEntity ["limitmax2"] = 0;
		$eventEntity ["limitmax3"] = 0;
		$eventEntity ["limitmin1"] = 0;
		$eventEntity ["limitmin2"] = 0;
		$eventEntity ["limitmin3"] = 0;
		if (array_key_exists("gender", $eventDetails)) {
			$eventEntity ["gender"] = $eventDetails ["gender"];
		}
		
		$eventTable->bind($eventEntity);
		
		// Make sure the data is valid
		if (! $eventTable->check()) {
			$this->setError($eventTable->getError());
			echo JText::_('Error check: ') . $eventTable->getError() . "\n";
			return;
		}
		if (! $eventTable->store()) {
			echo JText::_('Error store: ') . $this->_db->getErrorMsg() . "\n";
			return;
		}
	}
	
	/**
	 *
	 * @param unknown $agegroups        	
	 * @return multitype:NULL
	 */
	private function createEventFromAgegroups($agegroups) {
		$eventDetails = array ();
		foreach ( $agegroups as $agegroup ) {
			if (! array_key_exists("agemin", $eventDetails)) {
				$eventDetails ["agemin"] = $agegroup->agemin;
			} else {
				$eventDetails ["agemin"] = min($agegroup->agemin, $eventDetails ["agemin"]);
			}
			
			if (! array_key_exists("agemax", $eventDetails)) {
				$eventDetails ["agemax"] = $agegroup->agemax;
			} else {
				$eventDetails ["agemax"] = max($agegroup->agemax, $eventDetails ["agemax"]);
			}
			
			if (strlen($agegroup->gender) > 0) {
				if (! array_key_exists("gender", $eventDetails)) {
					$eventDetails ["gender"] = $agegroup->gender;
				} else {
					if ($eventDetails ["gender"] != $agegroup->gender) {
						$eventDetails ["gender"] = "X";
					}
				}
			}
		}
		return $eventDetails;
	}
	
	/**
	 * 
	 * @param unknown $metObject
	 * @param unknown $meet
	 */
	private function updateMeet($meetObject, $meet) {
		$meetsTable = & JTable::getInstance ( 'meet', 'FreestrokeTable' );
		$meetEntity = array ();
		$meetEntity ["id"] = $meetObject->id;
		$meetEntity ["name"] = $meet->name;
		$meetEntity ["place"] = $meet->city;
		$meetEntity ["deadline"] = $meet->deadline->toSql();
		$meetEntity ["course"] = $meet->course;
		if(!empty($meet->agedate)) {
			$meetEntity["agedate"] = $meet->agedate->value->toSql();
			$meetEntity["agecalctype"] = $meet->agedate->type;
		}
		if(!empty($meet->pool)) {
			$meetEntity["poolname"] = $meet->pool->name;
		}
		if(!empty($meet->qualify)) {
			$meetEntity["qualifyfrom"] = $meet->qualify->from->toSql();
			$meetEntity["qualifyuntil"] = $meet->qualify->until->toSql();
		}
		$meetsTable->bind ( $meetEntity );
		
		// Make sure the data is valid
		if (! $meetsTable->check ()) {
			$this->setError ( $meetsTable->getError () );
			echo JText::_( 'Error check: ' ) . $meetsTable->getError () . "\n";
			return;
		}
		if (! $meetsTable->store ()) {
			echo JText::_( 'Error store: ' ) . $this->_db->getErrorMsg () . "\n";
			return;
		}
		
	}
	/**
	 *
	 * @param unknown $meetid        	
	 */
	private function deleteProgram($meetid) {
		//$this->deleteMeetsessions($meetid);
		$this->deleteEvents($meetid);
	}
	
	/**
	 * 
	 * @param unknown $meetid
	 */
	private function deleteMeetsessions($meetid) {
		$ids = FreestrokeMeetsessionsHelper::load ( $meetid );
		$sessionTable = & JTable::getInstance ( 'meetsession', 'FreestrokeTable' );
		foreach ( $ids as $pkid ) {
			$sessionTable->delete ( $pkid->id );
		}
	}
	
	/**
	 * 
	 * @param unknown $meetid
	 */
	private function deleteEvents($meetid) {
		$ids = FreestrokeEventsHelper::find ( $meetid );
		$eventTable = & JTable::getInstance ( 'event', 'FreestrokeTable' );
		foreach ( $ids as $pkid ) {
			$eventTable->delete ( $pkid->id );
		}
	}
}
