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
 * Processing class that reads the entries for a meet.
 */
class FreestrokeEntriesReader {
	protected $app;
	protected $meetsessions;

	/**
	 *
	 * @param unknown $lenex        	
	 * @param unknown $meetid        	
	 * @param unknown $clubcode        	
	 */
	public function process($lenex, $meetid, $clubcode) {
		require_once JPATH_COMPONENT . '/helpers/conversion.php';
		require_once JPATH_COMPONENT . '/helpers/swimstyle.php';
		require_once JPATH_COMPONENT . '/helpers/meets.php';
		require_once JPATH_COMPONENT . '/helpers/meetsessions.php';
		require_once JPATH_COMPONENT . '/helpers/events.php';
		require_once JPATH_COMPONENT . '/helpers/members.php';
		require_once JPATH_COMPONENT . '/helpers/entries.php';
		require_once JPATH_COMPONENT . '/helpers/relays.php';
		
		$this->app = &JFactory::getApplication();
		$meet = $lenex->meets [0];
		$meet->id = $meetid;
		$meetObject = FreestrokeMeetsHelper::load($meetid);
		
		if ($this->check($lenex, $meetObject)) {
			foreach ( $meet->clubs as $club ) {
			if ($clubcode === $club->code) {
				$this->meetsessions = FreestrokeMeetsessionsHelper::load($meetid);
					$return = $this->processClub($meet, $club);
					$this->app->enqueueMessage(sprintf(JText::_('COM_FREESTROKE_MEETS_IMPORTENTRIES_MESSAGE'), $club->name));
					return $return;
				}
			}
		}
		return false;
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
					if ($sessiondate < $meetObject->mindate ) {
						$this->app->enqueueMessage(
								sprintf(JText::_('COM_FREESTROKE_MEETS_DATES_NOT_MINDATE'), $sessiondate, $meetObject->mindate ));
						return false;
					}
				} else {
					if ($sessiondate < $meetObject->mindate || $sessiondate > $meetObject->maxdate) {
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
	 * Process the xml at the club level
	 */
	private function processClub($meet, $club) {
		$this->deleteEntries($meet->id);
		$this->deleteRelays($meet->id);
		
		foreach ( $club->athletes as $athlete ) {
			$this->processAthlete($meet, $club, $athlete);
		}
		
		foreach ( $club->relays as $relay ) {
			$this->processRelay($meet, $club, $relay);
		}
		return true;
	}
	
	/**
	 * Process the xml at the session level
	 */
	private function processAthlete($meet, $club, $athlete) {
		$athleteEntity = $this->findAthleteEntity($athlete);
		if ($athleteEntity == null) {
			return;
		}
		$athlete->id = $athleteEntity->id;
		
		foreach ( $athlete->entries as $entry ) {
			$this->processEntry($meet, $athlete, $entry);
		}
	}

	/**
	 *
	 * @param unknown $meetid        	
	 * @param unknown $athleteid        	
	 * @param unknown $event        	
	 */
	private function processEntry($meet, $athlete, $entry) {
		$sessionevent = $this->findEventByEventId($meet, $entry->eventid);
		if ($sessionevent != null) {
			$event = $sessionevent ['event'];
			$session = $sessionevent ['session'];
			$swimstyle = FreestrokeSwimstyleHelper::find($event->swimstyle->distance, $event->swimstyle->relaycount, $event->swimstyle->stroke);
			
			if ($event->swimstyle->relaycount == 1) {
				$entryTable = & JTable::getInstance('entrie', 'FreestrokeTable');
				$entryEntity = array ();
				$entryEntity ["id"] = null;
				$entryEntity ["members_id"] = $athlete->id;
				$entryEntity ["meets_id"] = $meet->id;
				$entryEntity ["swimstyles_id"] = $swimstyle->id;
				
				$entryEntity ["sessionnumber"] = $session->number;
				$entryEntity ["eventnumber"] = $event->number;
				$entryEntity ["entrytime"] = $entry->entrytime;
				if($entry->meetinfo != null && $entry->meetinfo->date != null) {
					$entryEntity ["entrytimedate"] = $entry->meetinfo->date->toSql();				
				}
				$entryEntity["meetsession_id"] = $this->findSessionByNumber($session->number);
				$entryTable->bind($entryEntity);
				
				// Make sure the data is valid
				if (! $entryTable->check()) {
					$this->setError($entryTable->getError());
					echo JText::_('Error check: ') . $entryTable->getError() . "\n";
					return;
				}
				if (! $entryTable->store()) {
					echo JText::_('Error store: ') . $this->_db->getErrorMsg() . "\n";
					return;
				}
			}
		} else {
			$this->app->enqueueMessage("Skipped event with id " . $entry->eventid);
		}
	}
	
	/**
	 *
	 * @param unknown $meet        	
	 * @param unknown $club        	
	 * @param unknown $relay        	
	 */
	private function processRelay($meet, $club, $relay) {
		foreach($relay->entries as $entry ) {
			$this->processRelayEntry($meet, $club, $relay, $entry);
		}
	}

	/**
	 * 
	 * @param unknown $meet
	 * @param unknown $club
	 * @param unknown $relay
	 * @param unknown $entry
	 */
	private function processRelayEntry($meet, $club, $relay, $entry) {
		$sessionevent = $this->findEventByEventId($meet, $entry->eventid);
		if ($sessionevent != null) {
			$event = $sessionevent ['event'];
			$session = $sessionevent ['session'];
			$swimstyle = FreestrokeSwimstyleHelper::find($event->swimstyle->distance, $event->swimstyle->relaycount, $event->swimstyle->stroke);
			
			if ($event->swimstyle->relaycount > 1) {
				$relayentryTable = & JTable::getInstance('relayentrie', 'FreestrokeTable');
				$relayentryEntity = array ();
				$relayentryEntity ["id"] = null;
				$relayentryEntity ["meets_id"] = $meet->id;
				$relayentryEntity ["swimstyles_id"] = $swimstyle->id;
				$relayentryEntity ["teamnumber"] = $relay->number;
				$relayentryEntity ["sessionnumber"] = $session->number;
				$relayentryEntity ["eventnumber"] = $event->number;
				$relayentryEntity ["entrytime"] = $entry->entrytime;
				$entryEntity["meetsession_id"] = $this->findSessionByNumber($session->number);
				$relayentryTable->bind($relayentryEntity);
				
				// Make sure the data is valid
				if (! $relayentryTable->check()) {
					$this->setError($relayentryTable->getError());
					echo JText::_('Error check: ') . $relayentryTable->getError() . "\n";
					return;
				}
				if (! $relayentryTable->store()) {
					echo JText::_('Error store: ') . $this->_db->getErrorMsg() . "\n";
					return;
				}
				
				foreach($entry->relaypositions as $relayposition) {
					$this->processRelayPosition($club, $relayentryTable->id, $relayposition);
				}
			}
		}
	}
	
	/**
	 *
	 * @param unknown $relayentryId        	
	 * @param unknown $relayposition        	
	 */
	private function processRelayPosition($club, $relayentryId, $relayposition) {
		$athlete = $this->findAthleteByAthleteId($club, $relayposition->athleteid);
		if ($athlete != null) {
			$relayposentrieTable = & JTable::getInstance('relayposentrie', 'FreestrokeTable');
			$relayposentryEntity = array ();
			$relayposentryEntity ["id"] = null;
			$relayposentryEntity ["relayentries_id"] = $relayentryId;
			$relayposentryEntity ["members_id"] = $athlete->id;
			$relayposentryEntity ["ordernumber"] = $relayposition->number;
			if (! empty($relayposition->meetinfo->qualificationtime)) {
				if ($relayposition->meetinfo->qualificationtime == "NT") {
					$relayposentryEntity ["entrytime"] = 0;
				} else {
					$relayposentryEntity ["entrytime"] = $relayposition->meetinfo->qualificationtime;
				}
			}
			$relayposentrieTable->bind($relayposentryEntity);
			
			// Make sure the data is valid
			if (! $relayposentrieTable->check()) {
				$this->setError($relayposentrieTable->getError());
				echo JText::_('Error check: ') . $relayposentrieTable->getError() . "\n";
				return;
			}
			if (! $relayposentrieTable->store()) {
				echo JText::_('Error store: ') . $this->_db->getErrorMsg() . "\n";
				return;
			}
		}
	}

	/**
	 * 
	 * @param unknown $athlete
	 * @return NULL|unknown
	 */
	private function findAthleteEntity($athlete) {
		$athleteEntity = FreestrokeMembersHelper::findByRegistrationid($athlete->license);
		if ($athleteEntity == null) {
			$athleteEntity = FreestrokeMembersHelper::findByNameGenderBirthdate($athlete->lastname, $athlete->firstname, $athlete->gender, $athlete->birthdate);
			if ($athleteEntity == null) {
				$memberTable = & JTable::getInstance('member', 'FreestrokeTable');
				$memberEntity = array();
				$memberEntity["id"] = null;
				$memberEntity["firstname"] = $athlete->firstname;
				$memberEntity["lastname"] = $athlete->lastname;
				$memberEntity["nameprefix"] = $athlete->nameprefix;
				$memberEntity["birthdate"] = $athlete->birthdate->toSql();
				$memberEntity["registrationid"] = $athlete->license;
				$memberEntity["isactive"] = true;
				$memberTable->bind($memberEntity);
					
				// Make sure the data is valid
				if (! $memberTable->check()) {
					$this->setError($relayposentrieTable->getError());
					echo JText::_('Error check: ') . $memberTable->getError() . "\n";
					return null;
				}
				if (! $memberTable->store()) {
					echo JText::_('Error store: ') . $this->_db->getErrorMsg() . "\n";
					return null;
				}
				
				$this->app->enqueueMessage("Lid " . 
						$athlete->firstname .  ' ' . $athlete->nameprefix . ' ' . $athlete->lastname . 
						' is toegevoegd' );
				
				return $memberTable;
			} else {
				FreestrokeMembersHelper::updateLisenceForMember($athleteEntity->id, $athlete->license);
				$this->app->enqueueMessage("Startnummer van lid " . 
						$athlete->firstname .  ' ' . $athlete->nameprefix . ' ' . $athlete->lastname . 
						' is aangepast naar ' .  $athlete->license);
			}
		}
		return $athleteEntity;
	}
	

	/**
	 * 
	 * @param unknown $club
	 * @param unknown $athleteId
	 * @return NULL
	 */
	private function findAthleteByAthleteId($club, $athleteId) {
		if(array_key_exists($athleteId, $club->athletes)) {
			return $club->athletes[$athleteId];
		}
		return null;
	}
	
	/**
	 * 
	 */
	private function findSessionByNumber($sessionnumber) {
		if(isset($this->meetsessions)) {
			foreach($this->meetsessions as $session ) {
				if($session->sessionnumber == $sessionnumber) {
					return $session->id;
				}
			}
		}
		return null;
	}

	/**
	 * 
	 * @param unknown $meet
	 * @param unknown $eventid
	 * @return string|NULL
	 */
	private function findEventByEventId($meet, $eventid) {
		foreach ( $meet->sessions as $session) {
			if (array_key_exists ( $eventid, $session->events )) {
				$event = $session->events[$eventid];
				
				$result = array();
				$result['session'] = $session;
				$result['event'] = $event;
				return $result;
			}
		}
		return null;
	}
	
	/**
	 *
	 * @param unknown $meetid        	
	 */
	private function deleteEntries($meetid) {
		$ids = FreestrokeEntriesHelper::findByMeetid ( $meetid );
		$entryTable = & JTable::getInstance ( 'entrie', 'FreestrokeTable' );
		foreach ( $ids as $pkid ) {
			$entryTable->delete ( $pkid->id );
		}
	}

	/**
	 *
	 * @param unknown $meetid        	
	 */
	private function deleteRelays($meetid) {
		$ids = FreestrokeRelaysHelper::findByMeetid ( $meetid );
		$relaysTable = & JTable::getInstance ( 'relayentrie', 'FreestrokeTable' );
		foreach ( $ids as $pkid ) {
			$relaysTable->delete ( $pkid->id );
		}
	}
}
