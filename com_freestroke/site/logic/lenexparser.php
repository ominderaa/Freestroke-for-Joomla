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
 * Lenex parser class.
 * The XML file is read and transformed into a hierarchy of classes
 */
class FreestrokeLenexParser {
	
	/**
	 */
	public function parse($lenexNode) {
		require_once JPATH_COMPONENT . '/helpers/conversion.php';
		
		$lenex = new stdClass ();
		$lenex->version = ( string ) $lenexNode ["version"];
		$lenex->constructor = $this->readConstructor ( $lenexNode->CONSTRUCTOR );
		$lenex->meets = $this->readMeets ( $lenexNode->MEETS );
		$lenex->recordlists = $this->readRecordlists ( $lenexNode->RECORDLISTS );
		$lenex->timestandardlists = $this->readTimestandardLists ( $lenexNode->TIMESTANDARDLISTS );
		return $lenex;
	}
	
	/**
	 *
	 * @param unknown $constructorNode        	
	 */
	private function readConstructor($constructorNode) {
		$constructor = new stdClass ();
		$constructor->name = ( string ) $constructorNode ['name'];
		$constructor->registration = ( string ) $constructorNode ['registration'];
		$constructor->version = ( string ) $constructorNode ['version'];
		$constructor->contact = $this->readContact ( $constructorNode->CONTACT );
		RETURN $constructor;
	}
	
	/**
	 *
	 * @param unknown $readRecordlistsNode        	
	 * @return multitype:
	 */
	private function readRecordlists($readRecordlistsNode) {
		$readRecordlists = array ();
		// to do
		return $readRecordlists;
	}
	
	/**
	 * readTimestandardLists
	 *
	 * @param unknown $timestandardNode        	
	 */
	private function readTimestandardLists($timestandardlistsNode) {
		$timestandardlists = array ();
		if (! empty ( $timestandardlistsNode )) {
			foreach ( $timestandardlistsNode->TIMESTANDARDLIST as $timestandardlistNode ) {
				$timestandardlists [] = $this->readTimestandardlist ( $timestandardlistNode );
			}
		}
		return $timestandardlists;
	}
	
	/**
	 *
	 * @param unknown $timestandardListNode        	
	 * @return stdClass
	 */
	private function readTimestandardList($timestandardListNode) {
		$timestandardList = new stdClass ();
		$timestandardList->agegroup = $this->readAgegroup($timestandardListNode->AGEGROUP);
		$timestandardList->course = ( string ) $timestandardListNode ["course"];
		$timestandardList->gender = ( string ) $timestandardListNode ["gender"];
		$timestandardList->handicap = ( string ) $timestandardListNode ["handicap"];
		$timestandardList->name = ( string ) $timestandardListNode ["name"];
		$timestandardList->timestandards = $this->readTimestandards ( $timestandardListNode->TIMESTANDARDS );
		$timestandardListtype = ( string ) $timestandardListNode ["type"];
		return $timestandardList;
	}
	
	/**
	 *
	 * @param unknown $timestandardsNode        	
	 * @return multitype:NULL
	 */
	private function readTimestandards($timestandardsNode) {
		$timestandards = array ();
		if (! empty ( $timestandardsNode )) {
			foreach ( $timestandardsNode->TIMESTANDARD as $timestandardNode ) {
				$timestandards [] = $this->readTimestandard ( $timestandardNode );
			}
		}
		return $timestandards;
	}
	
	/**
	 *
	 * @param unknown $timestandardNode        	
	 * @return stdClass
	 */
	private function readTimestandard($timestandardNode) {
		$timestandard = new stdClass ();
		$timestandard->swimstyle = $this->readSwimstyle ( $timestandardNode->SWIMSTYLE );
		$timestandard->swimtime = FreestrokeConversionHelper::parseSwimtime ( ( string ) $timestandardNode ["swimtime"] );
		return $timestandard;
	}
	
	/**
	 *
	 * @param unknown $TimestandardrefsNode        	
	 * @return unknown
	 */
	private function readTimestandardrefs($timestandardrefsNode) {
		$timestandardrefs = array ();
		if (! empty ( $timestandardrefsNode )) {
			foreach ( $timestandardrefsNode->TIMESTANDARDSREF as $timestandardrefNode ) {
				$timestandardrefs [] = $this->readTimestandardref ( $timestandardrefNode );
			}
		}
		return $timestandardrefs;
	}
	
	/**
	 *
	 * @param unknown $timestandardrefNode        	
	 * @return stdClass
	 */
	private function readTimestandardref($timestandardrefNode) {
		$timestandardref = new stdClass ();
		$timestandardref->timestandardlistid = intval ( $timestandardrefNode ["timestandardlistid"] );
		$timestandardref->fee = $this->readFee ( $timestandardrefNode->FEE );
		$timestandardref->marker = ( string ) $timestandardrefNode ["marker"];
		return $timestandardref;
	}
	
	/**
	 *
	 * @param unknown $meetsNode        	
	 * @return multitype:NULL
	 */
	private function readMeets($meetsNode) {
		$meets = array ();
		if (! empty ( $meetsNode )) {
			foreach ( $meetsNode->MEET as $meetNode ) {
				$meets [] = $this->readMeet ( $meetNode );
			}
		}
		return $meets;
	}
	
	/**
	 * Process the xml at the meet level
	 */
	private function readMeet($meetNode) {
		$meet = new stdClass ();
		$meet->name = ucfirst(( string ) $meetNode ["name"]);
		$meet->city = ucfirst(( string ) $meetNode ["city"]);
		$meet->course = ( string ) $meetNode ["course"];
		$meet->deadline = JFactory::getDate((string) $meetNode ["deadline"] );
		$meet->agedate = $this->readAgedate ( $meetNode->AGEDATE );
		$meet->pool = $this->readPool ( $meetNode->POOL );
		$meet->pointtable = $this->readPointtable ( $meetNode->POINTTABLE );
		$meet->contact = $this->readContact ( $meetNode->CONTACT );
		$meet->qualify = $this->readQualify ( $meetNode->QUALIFY );
		
		$meet->sessions = $this->readSessions ( $meetNode->SESSIONS );
		$meet->clubs = $this->readClubs ( $meetNode->CLUBS );
		return $meet;
	}
	
	/**
	 *
	 * @param unknown $sessionsNode        	
	 * @return multitype:NULL
	 */
	private function readSessions($sessionsNode) {
		$sessions = array ();
		if (! empty ( $sessionsNode )) {
			foreach ( $sessionsNode->SESSION as $sessionNode ) {
				$sessions [] = $this->readSession ( $sessionNode );
			}
		}
		return $sessions;
	}
	
	/**
	 *
	 * @param unknown $sessionNode        	
	 * @return LxSession
	 */
	private function readSession($sessionNode) {
		$session = new stdClass ();
		$session->number = intval ( $sessionNode ["number"] );
		$session->date = JFactory::getDate((string) $sessionNode ["date"]);
		$session->daytime = ( string ) $sessionNode ["daytime"];
		$session->name = ucfirst(( string ) $sessionNode ["name"]);
		$session->officialmeeting = ( string ) $sessionNode ["officialmeeting"];
		$session->teamleadermeeting = ( string ) $sessionNode ["teamleadermeeting"];
		$session->warmupfrom = ( string ) $sessionNode ["warmupfrom"];
		$session->warmupuntil = ( string ) $sessionNode ["warmupuntil"];
		$session->pool = $this->readPool ( $sessionNode->POOL );
		
		$session->events = $this->readEvents ( $sessionNode->EVENTS, $session->number );
		return $session;
	}
	
	/**
	 *
	 * @param unknown $eventsNode        	
	 * @return multitype:unknown
	 */
	private function readEvents($eventsNode, $sessionnumber) {
		$events = array ();
		if (! empty ( $eventsNode )) {
			foreach ( $eventsNode->EVENT as $eventNode ) {
				$event = $this->readEvent ( $eventNode, $sessionnumber );
				$events [$event->eventid] = $event;
			}
		}
		return $events;
	}
	
	/**
	 *
	 * @param unknown $eventNode        	
	 * @return LxEvent
	 */
	private function readEvent($eventNode, $sessionnumber) {
		$event = new stdClass ();
		$event->agegroups = $this->readAgegroups ( $eventNode->AGEGROUPS );
		$event->daytime = ( string ) $eventNode ["daytime"];
		$event->eventid = intval ( $eventNode ["eventid"] );
		$event->fee = $this->readFee ( $eventNode->FEE );
		$event->gender = ( string ) $eventNode ["gender"];
		$event->heats = $this->readHeats ( $eventNode->HEATS );
		$event->maxentries = intval ( $eventNode ["maxentries"] );
		$event->number = intval ( $eventNode ["number"] );
		$event->sessionnumber = $sessionnumber;
		$event->order = intval ( $eventNode ["order"] );
		$event->preveventid = intval ( $eventNode ["preveventid"] );
		$event->round = ( string ) $eventNode ["round"];
		$event->run = intval ( $eventNode ["run"] );
		$event->swimstyle = $this->readSwimstyle ( $eventNode->SWIMSTYLE );
		$event->timestandardrefs = $this->readTimestandardrefs ( $eventNode->TIMESTANDARDREFS );
		$event->timing = ( string ) $eventNode ["timing"];
		return $event;
	}
	
	/**
	 *
	 * @param unknown $swimstyleNode        	
	 * @return LxSwimstyle
	 */
	public function readSwimstyle($swimstyleNode) {
		$swimstyle = new stdClass ();
		$swimstyle->distance = intval ( $swimstyleNode ["distance"] );
		$swimstyle->swimstyleid = intval ( $swimstyleNode ["swimstyleid"] );
		$swimstyle->stroke = ( string ) $swimstyleNode ["stroke"];
		$swimstyle->name = ( string ) $swimstyleNode ["name"];
		$swimstyle->relaycount = intval($swimstyleNode ["relaycount"]);
		$swimstyle->code = ( string ) $swimstyleNode ["code"];
		$swimstyle->technique = ( string ) $swimstyleNode ["technique"];
		return $swimstyle;
	}
	
	/**
	 *
	 * @param unknown $poolNode        	
	 * @return stdClass
	 *
	 */
	private function readPool($poolNode) {
		$pool = new stdClass ();
		$pool->name = ucfirst(( string ) $poolNode ["name"]);
		$pool->lanemax = intval ( $poolNode ["lanemax"] );
		$pool->lanemin = intval ( $poolNode ["lanemin"] );
		$pool->temperature = floatval ( $poolNode ["temperature"] );
		$pool->type = ( string ) $poolNode ["type"];
		return $pool;
	}
	
	/**
	 *
	 * @param unknown $pointtableNode        	
	 * @return stdClass
	 */
	private function readPointtable($pointtableNode) {
		$pointtable = new stdClass ();
		$pointtable->name = ( string ) $pointtableNode ["name"];
		$pointtable->version = ( string ) $pointtableNode ["version"];
		$pointtable->pointtableid = intval ( $pointtableNode ["pointtableid"] );
		return $pointtable;
	}
	
	/**
	 *
	 * @param unknown $qualifyNode        	
	 * @return stdClass
	 */
	private function readQualify($qualifyNode) {
		$qualify = new stdClass ();
		$qualify->conversion = ( string ) $qualifyNode ["conversion"];
		$qualify->from = JFactory::getDate( ( string ) $qualifyNode ["from"] );
		$qualify->percent = floatval ( $qualifyNode ["percent"] );
		$qualify->until = JFactory::getDate( ( string ) $qualifyNode ["until"] );
		return $qualify;
	}
	
	/**
	 *
	 * @param unknown $feeNode        	
	 * @return LxFee
	 */
	public function readFee($feeNode) {
		$fee = new stdClass ();
		$fee->currency = ( string ) $feeNode ["currency"];
		$fee->type = ( string ) $feeNode ["type"];
		$fee->value = intval ( $feeNode ["value"] );
		return $fee;
	}
	
	/**
	 *
	 * @param unknown $heatsNode        	
	 * @return multitype:unknown
	 */
	public function readHeats($heatsNode) {
		$heats = array ();
		if (! empty ( $heatsNode )) {
			foreach ( $heatsNode->HEAT as $heatNode ) {
				$heat = $this->readHeat ( $heatNode );
				$heats [$heat->heatid] = $heat;
			}
		}
		return $heats;
	}
	
	/**
	 *
	 * @param unknown $heatNode        	
	 * @return LxHeat
	 */
	private function readHeat($heatNode) {
		$heat = new stdClass ();
		$heat->heatid = intval ( $heatNode ["heatid"] );
		$heat->agegroupid = intval ( $heatNode ["agegroupid"] );
		$heat->daytime = ( string ) $heatNode ["daytime"];
		$heat->final = ( string ) $heatNode ["final"];
		$heat->number = intval ( $heatNode ["number"] );
		$heat->order = intval ( $heatNode ["order"] );
		$heat->status = ( string ) $heatNode ["status"];
		return $heat;
	}
	
	/**
	 *
	 * @param unknown $clubsNode        	
	 * @return multitype:unknown
	 */
	public function readClubs($clubsNode) {
		$clubs = array ();
		if (! empty($clubsNode)) {
			foreach ( $clubsNode->CLUB as $clubNode ) {
				$club = $this->readClub($clubNode);
				$clubs [] = $club;
			}
		}
		return $clubs;
	}
	
	/**
	 *
	 * @param unknown $clubNode        	
	 * @return LxClub
	 */
	private function readClub($clubNode) {
		$club = new stdClass ();
		
		$club->clubid = (string) $clubNode ["clubid"];
		$club->athletes = $this->readAthletes ( $clubNode->ATHLETES );
		$club->code = ( string ) $clubNode ["code"];
		if (! empty ( $clubNode->CONTACT )) {
			$club->contact = $this->readContact ( $clubNode->CONTACT );
		} else {
			$club->contact = null;
		}
		$club->name = ( string ) $clubNode ["name"];
		$club->shortname = ( string ) $clubNode ["shortname"];
		$club->name_en = ( string ) $clubNode ["name.en"];
		$club->shortname_en = ( string ) $clubNode ["shortname.en"];
		$club->nation = ( string ) $clubNode ["nation"];
		$club->number = intval ( $clubNode ["number"] );
		$club->officials = $this->readOfficials ( $clubNode->OFFICIALS );
		$club->region = ( string ) $clubNode ["region"];
		$club->relays = $this->readRelays ( $clubNode->RELAYS );
		$club->swrid = ( string ) $clubNode ["swrid"];
		$club->type = ( string ) $clubNode ["type"];
		
		return $club;
	}
	
	/**
	 *
	 * @param unknown $contactNode        	
	 * @return stdClass
	 */
	private function readContact($contactNode) {
		$contact = new stdClass ();
		$contact->city = ( string ) $contactNode ["city"];
		$contact->email = ( string ) $contactNode ["email"];
		$contact->fax = ( string ) $contactNode ["fax"];
		$contact->internet = ( string ) $contactNode ["internet"];
		$contact->name = ( string ) $contactNode ["name"];
		$contact->mobile = ( string ) $contactNode ["mobile"];
		$contact->phone = ( string ) $contactNode ["phone"];
		$contact->state = ( string ) $contactNode ["state"];
		$contact->street = ( string ) $contactNode ["street"];
		$contact->street2 = ( string ) $contactNode ["street2"];
		$contact->zip = ( string ) $contactNode ["zip"];
		return $contact;
	}
	
	/**
	 *
	 * @param unknown $officialsNode        	
	 * @return multitype:
	 */
	private function readOfficials($officialsNode) {
		$officials = array ();
		// TODO: implement reading officials
		return $officials;
	}
	
	/**
	 *
	 * @param unknown $relaysNode        	
	 * @return multitype:
	 */
	private function readRelays($relaysNode) {
		$relays = array ();
		if (! empty ( $relaysNode )) {
			foreach ( $relaysNode->RELAY as $relayNode ) {
				$relays [] = $this->readRelay ( $relayNode );
			}
		}
		return $relays;
	}
	
	/**
	 *
	 * @param unknown $relayNode        	
	 * @return LxRelay
	 */
	private function readRelay($relayNode) {
		$relay = new stdClass ();
		$relay->agemax = intval ( $relayNode ["agemax"] );
		$relay->agemin = intval ( $relayNode ["agemin"] );
		$relay->agetotalmax = intval ( $relayNode ["agetotalmax"] );
		$relay->agetotalmin = intval ( $relayNode ["agetotalmin"] );
		$relay->gender = ( string ) $relayNode ["gender"];
		$relay->handicap = intval ( $relayNode ["handicap"] );
		$relay->name = ( string ) $relayNode ["name"];
		$relay->number = intval ( $relayNode ["number"] );
		$relay->club = $this->readClub ( $relayNode->CLUB );
		$relay->entries = $this->readEntries ( $relayNode->ENTRIES );
		$relay->relaypositions = $this->readRelaypositions ( $relayNode->RELAYPOSITIONS );
		$relay->results = $this->readResults ( $relayNode->RESULTS );
		
		return $relay;
	}
	
	/**
	 *
	 * @param unknown $entriesNode        	
	 * @return multitype:
	 */
	private function readEntries($entriesNode) {
		$entries = array ();
		if (! empty ( $entriesNode )) {
			foreach ( $entriesNode->ENTRY as $entryNode ) {
				$entries [] = $this->readEntry ( $entryNode );
			}
		}
		return $entries;
	}
	
	/**
	 *
	 * @param unknown $entryNode        	
	 * @return LxEntry
	 */
	private function readEntry($entryNode) {
		$entry = new stdClass ();
		$entry->agegroupid = intval ( $entryNode ["agegroupid"] );
		$entry->entrycourse = ( string ) $entryNode ["entrycourse"];
		$entry->entrytime = FreestrokeConversionHelper::parseSwimtime ( ( string ) $entryNode ["entrytime"] );
		$entry->eventid = intval ( $entryNode ["eventid"] );
		$entry->heatid = intval ( $entryNode ["heatid"] );
		$entry->lane = intval ( $entryNode ["lane"] );

		$entry->meetinfo = null;
		$entry->meetinfo = $this->readMeetinfo ( $entryNode->MEETINFO );
		$entry->relaypositions = $this->readRelaypositions ( $entryNode->RELAYPOSITIONS );
		$entry->status = ( string ) $entryNode ["status"];
		return $entry;
	}
	
	/**
	 *
	 * @param unknown $resultsNode        	
	 * @return multitype:
	 */
	private function readResults($resultsNode) {
		$results = array ();
		if (! empty ( $resultsNode )) {
			foreach ( $resultsNode->RESULT as $resultNode ) {
				$result = $this->readResult ( $resultNode );
				$results [$result->resultid] = $result;
			}
		}
		return $results;
	}
	
	/**
	 *
	 * @param unknown $resultNode        	
	 * @return stdClass
	 */
	private function readResult($resultNode) {
		$result = new stdClass ();
		$result->comments = ( string ) $resultNode ["comments"];
		$result->eventid = intval ( $resultNode ["eventid"] );
		$result->heatid = intval ( $resultNode ["heatid"] );
		$result->lane = intval ( $resultNode ["lane"] );
		$result->points = intval ( $resultNode ["points"] );
		$result->reactiontime = intval ( $resultNode ["reactiontime"] );
		$result->relaypositions = $this->readRelaypositions ( $resultNode->RELAYPOSITIONS );
		$result->resultid = intval ( $resultNode ["resultid"] );
		$result->status = ( string ) $resultNode ["status"];
		$result->splits = $this->readSplits ( $resultNode->SPLITS );
		$result->entrytime = FreestrokeConversionHelper::parseSwimtime ( $resultNode ["entrytime"] );
		$result->swimtime = FreestrokeConversionHelper::parseSwimtime ( $resultNode ["swimtime"] );
		return $result;
	}
	
	/**
	 *
	 * @param unknown $relaypositionsNode        	
	 * @return multitype:NULL
	 */
	private function readRelaypositions($relaypositionsNode) {
		$relaypositions = array ();
		if (! empty ( $relaypositionsNode )) {
			foreach ( $relaypositionsNode->RELAYPOSITION as $relaypositionNode ) {
				$relaypositions [] = $this->readRelayposition ( $relaypositionNode );
			}
		}
		return $relaypositions;
	}
	
	/**
	 *
	 * @param unknown $relaypositionNode        	
	 * @return LxRelayposition
	 */
	private function readRelayposition($relaypositionNode) {
		$relayposition = new stdClass ();
		$relayposition->number = intval($relaypositionNode["number"]);
		$relayposition->athleteid = intval($relaypositionNode["athleteid"]);
		$relayposition->meetinfo = $this->readMeetInfo($relaypositionNode->MEETINFO);
		$relayposition->reactiontime = intval($relaypositionNode["reactiontime"]);
		$relayposition->status = (string)$relaypositionNode["status"];
		return $relayposition;
	}
	
	/**
	 *
	 * @param unknown $meetinfoNode        	
	 * @return stdClass
	 */
	private function readMeetInfo($meetinfoNode) {
		$meetinfo = new stdClass();
		if (! empty($meetinfoNode)) {
			$meetinfo->approved = ( string ) $meetinfoNode ["approved"];
			$meetinfo->city = ( string ) $meetinfoNode ["city"];
			$meetinfo->course = ( string ) $meetinfoNode ["course"];
			$meetinfo->date = JFactory::getDate(( string ) $meetinfoNode ["date"]);
			$meetinfo->daytime = ( string ) $meetinfoNode ["daytime"];
			$meetinfo->name = ( string ) $meetinfoNode ["name"];
			$meetinfo->nation = ( string ) $meetinfoNode ["nation"];
			$meetinfo->pool = $this->readPool($meetinfoNode->POOL);
			$meetinfo->qualificationtime = FreestrokeConversionHelper::parseSwimtime($meetinfoNode ["qualificationtime"]);
			$meetinfo->state = ( string ) $meetinfoNode ["state"];
		}
		return $meetinfo;
	}
	

	/**
	 *
	 * @param unknown $splitsNode        	
	 * @return multitype:NULL
	 */
	private function readSplits($splitsNode) {
		$splits = array ();
		if (! empty ( $splitsNode )) {
			foreach ( $splitsNode->SPLIT as $splitNode ) {
				$splits [] = $this->readSplit ( $splitNode );
			}
		}
		return $splits;
	}
	
	/**
	 *
	 * @param unknown $splitNode        	
	 * @return stdClass
	 */
	private function readSplit($splitNode) {
		$split = new stdClass ();
		$split->distance = intval ( $splitNode ["distance"] );
		$split->swimtime = FreestrokeConversionHelper::parseSwimtime ( ( string ) $splitNode ["swimtime"] );
		return $split;
	}
	
	/**
	 *
	 * @param unknown $athletesNode        	
	 * @return multitype:unknown
	 */
	private function readAthletes($athletesNode) {
		$athletes = array ();
		if (! empty ( $athletesNode )) {
			foreach ( $athletesNode->ATHLETE as $athleteNode ) {
				$athlete = $this->readAthlete ( $athleteNode );
				$athletes [$athlete->athleteid] = $athlete;
			}
		}
		return $athletes;
	}
	
	/**
	 *
	 * @param unknown $athleteNode        	
	 * @return stdClass
	 */
	private function readAthlete($athleteNode) {
		$athlete = new stdClass ();
		$athlete->athleteid = intval ( $athleteNode ["athleteid"] );
		$athlete->birthdate = JFactory::getDate( ( string ) $athleteNode ["birthdate"] );
		$athlete->club = $this->readClub ( $athleteNode->CLUB );
		$athlete->entries = $this->readEntries ( $athleteNode->ENTRIES );
		$athlete->firstname = ( string ) $athleteNode ["firstname"];
		$athlete->firstname_en = ( string ) $athleteNode ["firstname.en"];
		$athlete->gender = ( string ) $athleteNode ["gender"];
		if (! empty ( $athleteNode->HANDICAP )) {
			$athlete->handicap = $this->readHandicap ( $athleteNode->HANDICAP );
		} else {
			$athlete->handicap = null;
		}
		$athlete->lastname = ( string ) $athleteNode ["lastname"];
		$athlete->lastname_en = ( string ) $athleteNode ["lastname.en"];
		$athlete->license = ( string ) $athleteNode ["license"];
		$athlete->middlename = ( string ) $athleteNode ["middlename"];
		$athlete->nameprefix = ( string ) $athleteNode ["nameprefix"];
		$athlete->nation = ( string ) $athleteNode ["nation"];
		$athlete->passport = ( string ) $athleteNode ["passport"];
		$athlete->results = $this->readResults ( $athleteNode->RESULTS );
		$athlete->swrid = ( string ) $athleteNode ["swrid"];
		return $athlete;
	}
	
	/**
	 *
	 * @param unknown $agedateNode        	
	 * @return LxAgeDate
	 */
	private function readAgedate($agedateNode) {
		$agedate = new stdClass ();
		$agedate->type = ( string ) $agedateNode ["type"];
		$agedate->value = JFactory::getDate( ( string ) $agedateNode ["value"] );
		return $agedate;
	}
	
	/**
	 *
	 * @param unknown $agegroupsNode        	
	 * @return multitype:unknown
	 */
	private function readAgegroups($agegroupsNode) {
		$agegroups = array ();
		if (! empty ( $agegroupsNode )) {
			foreach ( $agegroupsNode->AGEGROUP as $agegroupNode ) {
				$agegroup = $this->readAgegroup ( $agegroupNode );
				$agegroups [$agegroup->agegroupid] = $agegroup;
			}
		}
		return $agegroups;
	}
	
	/**
	 *
	 * @param unknown $agegroupNode        	
	 * @return LxAgegroup
	 */
	private function readAgegroup($agegroupNode) {
		$agegroup = new stdClass ();
		$agegroup->agegroupid = intval ( $agegroupNode ["agegroupid"] );
		$agegroup->agemax = intval ( $agegroupNode ["agemax"] );
		$agegroup->agemin = intval ( $agegroupNode ["agemin"] );
		$agegroup->gender = ( string ) $agegroupNode ["gender"];
		$agegroup->calculate = ( string ) $agegroupNode ["calculate"];
		$agegroup->handicap = intval ( $agegroupNode ["handicap"] );
		$agegroup->levelmax = ( string ) $agegroupNode ["levelmax"];
		$agegroup->levelmin = ( string ) $agegroupNode ["levelmin"];
		$agegroup->name = ( string ) $agegroupNode ["name"];
		$agegroup->type = ( string ) $agegroupNode ["type"];
		
		$agegroup->rankings = $this->readRankings ( $agegroupNode->RANKINGS );
		return $agegroup;
	}
	
	/**
	 *
	 * @param unknown $rankingsNode        	
	 * @return multitype:NULL
	 */
	private function readRankings($rankingsNode) {
		$rankings = array ();
		if (! empty ( $rankingsNode )) {
			foreach ( $rankingsNode->RANKING as $rankingNode ) {
				$ranking = $this->readRanking ( $rankingNode );
				$rankings [$ranking->resultid] = $ranking;
			}
		}
		return $rankings;
	}
	
	/**
	 *
	 * @param unknown $rankingNode        	
	 */
	private function readRanking($rankingNode) {
		$ranking = new stdClass ();
		$ranking->order = intval ( $rankingNode ["order"] );
		$ranking->place = intval ( $rankingNode ["place"] );
		$ranking->resultid = intval ( $rankingNode ["resultid"] );
		return $ranking;
	}
}
