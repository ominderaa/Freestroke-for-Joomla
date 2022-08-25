<?php
/**
 * @version     1.0.0
 * @package     com_freestroke
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      O.Minderaa <ominderaa@gmail.com> - http://
 */

// No direct access
defined('_JEXEC') or die();

jimport('joomla.application.component.view');
class FreestrokeViewMeetentriesHelper {

	/**
	 */
	private function mergeEntries() {
		foreach ( $this->entries as $entry ) {
			$event = $this->findByEventnumber($entry->eventnumber);
			if ($event != null) {
				if (! isset($event->entries)) {
					$event->entries = array ();
				}
				$event->entries [$entry->id] = $entry;
			}
		}
	}
	
	/**
	 */
	private function mergeRelays() {
		foreach ( $this->relays as $entry ) {
			$event = $this->findByEventnumber($entry->eventnumber);
			if ($event != null) {
				if (! isset($event->teams)) {
					$event->teams = array ();
				}
				if (! array_key_exists($entry->teamnumber, $event->teams)) {
					$team = new stdClass();
					$team->teamnumber = $entry->teamnumber;
					$team->entries = array ();
					$event->teams [$entry->teamnumber] = $team;
				}
				$event->teams [$entry->teamnumber]->entries [$entry->ordernumber] = $entry;
			}
		}
	}
	
	/**
	 *
	 * @param unknown $eventnumber        	
	 * @return unknown NULL
	 */
	private function findByEventnumber($eventnumber) {
		foreach ( $this->events as $event ) {
			if ($event->programnumber == $eventnumber) {
				return $event;
			}
		}
		return null;
	}
}