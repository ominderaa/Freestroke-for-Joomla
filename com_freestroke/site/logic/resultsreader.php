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
 * Lenex processing class that reads the meet results.
 */
class FreestrokeResultsReader {
	protected $app;
	protected $club;
	
	/**
	 *
	 * @param unknown $lenex        	
	 * @param unknown $meetid        	
	 * @param unknown $clubcode        	
	 */
	public function process($lenex, $meetid, $clubcode, $clubname, $deletefirst) {
		require_once JPATH_COMPONENT . '/helpers/conversion.php';
		require_once JPATH_COMPONENT . '/helpers/swimstyle.php';
		require_once JPATH_COMPONENT . '/helpers/events.php';
		require_once JPATH_COMPONENT . '/helpers/members.php';
		require_once JPATH_COMPONENT . '/helpers/entries.php';
		require_once JPATH_COMPONENT . '/helpers/meets.php';
		
		$this->app = &JFactory::getApplication();
		$meet = $lenex->meets [0];
		$meet->id = $meetid;
		$meetObject = FreestrokeMeetsHelper::load($meetid);
		
		if ($this->check($lenex, $meetObject)) {
			foreach ( $meet->clubs as $club ) {
				if ($clubcode === $club->code || $clubname === $club->name) {
					
					$this->club = $club;
					
					if ($deletefirst) {
						$this->deleteResults($meet->id);
						$this->deleteRelayResults($meet->id);
					}
					
					$this->processClub($meet, $club);
					$msg = sprintf(JText::_('COM_FREESTROKE_MEETS_IMPORTRESULTS_MESSAGE'), $club->name);
					$this->app->enqueueMessage($msg);
					return true;
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
					if ($sessiondate < $meetObject->mindate || $sessiondate > $meetObject->maxdate ) {
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
		foreach ( $club->athletes as $athlete ) {
			$this->processAthlete($meet, $athlete);
		}
		foreach ( $club->relays as $relay ) {
			$this->processRelayResult($meet, $relay);
		}
	}
	
	/**
	 * Process the xml at the session level
	 */
	private function processAthlete($meet, $athlete) {
		$athleteEntity = FreestrokeMembersHelper::findByRegistrationid ( $athlete->license );
		if ($athleteEntity == null) {
			$athleteEntity = FreestrokeMembersHelper::findByNameGenderBirthdate ( $athlete->lastname, $athlete->firstname, $athlete->gender, $athlete->birthdate );
			if ($athleteEntity == null) {
				$this->app->enqueueMessage ( "member not found: " . $athlete->lastname . ", " . $athlete->firstname );
				return;
			}
		}
		$athlete->id = $athleteEntity->id;
		
		foreach ( $athlete->results as $result ) {
			$this->processResult ( $meet, $athlete, $result );
		}
	}
	
	/**
	 *
	 * @param unknown $meetid        	
	 * @param unknown $athleteid        	
	 * @param unknown $result        	
	 */
	private function processResult($meet, $athlete, $result) {
		$sessionevent = $this->findSessionAndEventByEventId($meet, $result->eventid);
		if ($sessionevent != null) {
			$session = $sessionevent ['session'];
			$event = $sessionevent ['event'];
			
			$ranking = $this->findRankingInEventByResultid($event, $result->resultid);
			
			$swimstyle = FreestrokeSwimstyleHelper::find($event->swimstyle->distance, $event->swimstyle->relaycount, $event->swimstyle->stroke);
			
			$resultsTable = & JTable::getInstance('result', 'FreestrokeTable');
			$resultEntity = array ();
			$resultEntity ["id"] = null;
			$resultEntity ["members_id"] = $athlete->id;
			$resultEntity ["meets_id"] = $meet->id;
			$resultEntity ["swimstyles_id"] = $swimstyle->id;
			$resultEntity ["eventdate"] = $session->date->toSql();
			
			$resultEntity ["eventnumber"] = $event->number;
			//$resultEntity ["points"] = $result->points;
			$resultEntity ["entrytime"] = $result->entrytime;
			$resultEntity ["totaltime"] = $result->swimtime;
			if ($ranking != null) {
				$resultEntity ["rank"] = $ranking->place;
			}
			$resultEntity ["course"] = $meet->course;
			if (! empty($result->status)) {
				$resultEntity ["resulttype"] = $result->status;
			} else {
				$resultEntity ["resulttype"] = "FIN";
			}
			$resultEntity ["comment"] = $result->comments;
			
			$resultsTable->bind($resultEntity);
			
			// Make sure the data is valid
			if (! $resultsTable->check()) {
				$this->setError($resultsTable->getError());
				echo JText::_('Error check: ') . $resultsTable->getError() . "\n";
				return;
			}
			if (! $resultsTable->store()) {
				echo JText::_('Error store: ') . $this->_db->getErrorMsg() . "\n";
				return;
			}
		} else {
			$this->app->enqueueMessage("Skipped event with id " . $entry->eventid);
		}
	}
	
	/**
	 *
	 * @param unknown $meet        	
	 * @param unknown $relay        	
	 * @param unknown $result        	
	 */
	private function processRelayResult($meet, $relay) {
		foreach ( $relay->results as $result ) {
			$sessionevent = $this->findSessionAndEventByEventId ( $meet, $result->eventid );
			if ($sessionevent != null) {
				$session = $sessionevent['session'];
				$event = $sessionevent['event'];

				$ranking = $this->findRankingInEventByResultid($event, $result->resultid);
				if ($ranking != null) {
					$swimstyle = FreestrokeSwimstyleHelper::find($event->swimstyle->distance, $event->swimstyle->relaycount, $event->swimstyle->stroke);

					$relayresultsTable = & JTable::getInstance ( 'relayresult', 'FreestrokeTable' );
					$relayEntity = array();
					$relayEntity ["id"] = null;
					$relayEntity ["meets_id"] = $meet->id;
					$relayEntity ["swimstyles_id"] = $swimstyle->id;
					$resultEntity ["eventdate"] = $session->date->toSql();

					$relayEntity ["eventnumber"] = $event->number;
					$relayEntity ["entrytime"] = $result->entrytime;
					$relayEntity ["totaltime"] = $result->swimtime;
					$relayEntity ["rank"] = $ranking->place;
					$relayEntity ["course"] = $meet->course;
					if (! empty($result->status)) {
						$relayEntity ["resulttype"] = $result->status;
					} else {
						$relayEntity ["resulttype"] = "FIN";
					}
					$relayEntity ["comment"] = $result->comments;
					
					$relayresultsTable->bind($relayEntity);
					
					// Make sure the data is valid
					if (! $relayresultsTable->check()) {
						$this->setError($resultsTable->getError());
						echo JText::_('Error check: ') . $relayresultsTable->getError() . "\n";
						return;
					}
					if (! $relayresultsTable->store()) {
						echo JText::_('Error store: ') . $this->_db->getErrorMsg() . "\n";
						return;
					}
					
					$this->processRelayPositions($meet, $result, $relayresultsTable->id );
					
				} else {
					$this->app->enqueueMessage("Skipped result with id " . $result->resultid);
				}
			} else {
				$this->app->enqueueMessage("Skipped event with id " . $entry->eventid);
			}
		}
	}
	
	/**
	 *
	 * @param unknown $meet        	
	 * @param unknown $relayEntity        	
	 * @param unknown $relayid        	
	 */
	private function processRelayPositions($meet, $result, $relayresultid) {
		foreach ( $result->relaypositions as $relayposition ) {
			if (! empty($relayposition->athleteid)) {
				$relayposTable = & JTable::getInstance('relayposresult', 'FreestrokeTable');
				$relayposEntity = array ();
				$relayposEntity ["id"] = null;
				$relayposEntity ["relayresults_id"] = $relayresultid;
				
				$athlete = $this->findAthleteById($relayposition->athleteid);
				$relayposEntity ["members_id"] = $athlete->id;
				$relayposEntity ["ordernumber"] = $relayposition->number;
				$relayposEntity ["reactiontime"] = $relayposition->reactiontime;
				if (! empty($result->status)) {
					$relayEntity ["resulttype"] = $result->status;
				} else {
					$relayEntity ["resulttype"] = "FIN";
				}
				$relayposTable->bind($relayposEntity);
				
				// Make sure the data is valid
				if (! $relayposTable->check()) {
					$this->setError($relayposTable->getError());
					echo JText::_('Error check: ') . $relayposTable->getError() . "\n";
					return;
				}
				if (! $relayposTable->store()) {
					echo JText::_('Error store: ') . $this->_db->getErrorMsg() . "\n";
					return;
				}
			}
		}
	}

	/**
	 *
	 * @param unknown $meet        	
	 * @param unknown $eventid        	
	 * @return string NULL
	 */
	private function findEventByEventId($meet, $eventid) {
		foreach ( $meet->sessions as $session ) {
			if (array_key_exists ( $eventid, $session->events )) {
				$event = $session->events [$eventid];
				return $event;
			}
		}
		return null;
	}

	/**
	 *
	 * @param unknown $meet
	 * @param unknown $eventid
	 * @return string NULL
	 */
	private function findSessionAndEventByEventId($meet, $eventid) {
		$result = array();
		foreach ( $meet->sessions as $session ) {
			if (array_key_exists ( $eventid, $session->events )) {
				$result['session'] = $session;
				$result['event'] = $session->events [$eventid];
				return $result;
			}
		}
		return null;
	}
	
	
	/**
	 *
	 * @param unknown $event        	
	 * @param unknown $resultid        	
	 * @return unknown NULL
	 */
	private function findRankingInEventByResultid($event, $resultid) {
		foreach ( $event->agegroups as $agegroup ) {
			if (array_key_exists ( $resultid, $agegroup->rankings )) {
				$ranking = $agegroup->rankings [$resultid];
				return $ranking;
			}
		}
		return null;
	}

	/**
	 * 
	 * @param unknown $athleteid
	 */
	private function  findAthleteById($athleteid) {
		return ($this->club->athletes[$athleteid]);
	}

	/**
	 * 
	 * @param unknown $meetid
	 */
	private function deleteResults($meetid) {
		$db = JFactory::getDbo ();
		$query = $db->getQuery ( true );
		
		$conditions = array (
			$db->quoteName ( 'meets_id' ) . '=' . $db->quote ( $meetid ) 
		);
		
		$query->delete ( $db->quoteName ( '#__freestroke_results' ) );
		$query->where ( $conditions );
		$db->setQuery ( $query );
		$result = $db->query ();
	}

	/**
	 * 
	 * @param unknown $meetid
	 */
	private function deleteRelayResults($meetid) {
		$db = JFactory::getDbo ();
		$query = $db->getQuery ( true );
	
		$conditions = array (
				$db->quoteName ( 'meets_id' ) . '=' . $db->quote ( $meetid )
		);
	
		$query->delete ( $db->quoteName ( '#__freestroke_relayresults' ) );
		$query->where ( $conditions );
		$db->setQuery ( $query );
		$result = $db->query ();
	}
	
}
