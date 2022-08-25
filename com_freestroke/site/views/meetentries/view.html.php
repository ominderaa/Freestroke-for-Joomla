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

jimport ( 'joomla.application.component.view' );

/**
 * View to edit
 */
class FreestrokeViewMeetentries extends JViewLegacy {
	protected $state;
	protected $item;
	protected $form;
	protected $params;
	
	/**
	 * Display the view
	 */
	public function display($tpl = null) {
		$app = JFactory::getApplication ();
		$user = JFactory::getUser ();
		$this->document = JFactory::getDocument();
		
		$this->state = $this->get ( 'State' );
		$this->item = $this->get ( 'Data' );
		$this->meetsessions = $this->get( 'Items', 'meetsessions');
		$this->events =  $this->get( 'Items', 'events');
		$this->entries = $this->get( 'Items', 'entries');
		$this->mergeEntries();
		$this->relays = $this->get('Items', 'relayentries');
		$this->mergeRelays();
		$this->mergeEvents();
	
		$this->params = $app->getParams ( 'com_freestroke' );
		$this->form = $this->get ( 'Form' );
		
		// Check for errors.
		if (count ( $errors = $this->get ( 'Errors' ) )) {
			throw new Exception ( implode ( "\n", $errors ) );
		}
		
		if ($this->_layout == 'edit') {
			$authorised = $user->authorise ( 'core.create', 'com_freestroke' );
			if ($authorised !== true) {
				throw new Exception ( JText::_ ( 'JERROR_ALERTNOAUTHOR' ) );
			}
		}
		
		$this->_prepareDocument ();
		parent::display ( $tpl );
	}
	
	/**
	 * 
	 */
	private function mergeEvents() {
		foreach ( $this->events as $event ) {
			$session = $this->findBySessionnumber($event->sessionnumber);
			if($session != null) {
				if (! isset($session->events)) {
					$session->events = array ();
				}
				$session->events[$event->eventid] = $event;
			}
		}
	}
	
	/**
	 */
	private function mergeEntries() {
		foreach ( $this->entries as $entry ) {
			$event = $this->findByEventid($entry->eventid);
			if ($event != null) {
				if (! isset($event->entries)) {
					$event->entries = array ();
				}
				$event->entries[$entry->id] = $entry;
			}
		}
	}
	
	/**
	 * 
	 */
	private function mergeRelays() {
		foreach ( $this->relays as $entry ) {
		    $event = $this->findByEventid($entry->eventid);
			if ($event != null) {
				if (! isset($event->teams)) {
					$event->teams = array ();
				}
				if (! array_key_exists($entry->teamnumber, $event->teams)) {
					$team = new stdClass();
					$team->teamnumber = $entry->teamnumber;
					$team->entries = array ();
					$event->teams[$entry->teamnumber] = $team;
				}
			$event->teams[$entry->teamnumber]->entries[$entry->ordernumber] = $entry;
			}
		}
	}
	
	/**
	 * 
	 * @param unknown $eventnumber
	 * @return unknown|NULL
	 */
	private function findByEventnumber($eventnumber) {
		foreach ( $this->events as $event ) {
			if ($event->programnumber == $eventnumber) {
				return $event;
			}
		}
		return null;
	}
	
	/**
	 *
	 * @param unknown $eventid
	 * @return unknown|NULL
	 */
	private function findByEventid($eventid) {
	    foreach ( $this->events as $event ) {
	        if ($event->eventid == $eventid) {
	            return $event;
	        }
	    }
	    return null;
	}
	
	/**
	 *
	 * @param unknown $eventnumber
	 * @return unknown|NULL
	 */
	private function findBySessionnumber($sessionnumber) {
		foreach ( $this->meetsessions as $session ) {
			if ($session->sessionnumber == $sessionnumber) {
				return $session;
			}
		}
		return null;
	}
	
	/**
	 * The list model of entries is really a hierarchy of events en entries.
	 * Here the hierarchy is restored to make view generation easier.
	 * After this, $events will contain:
	 * 	 event-1
	 *       entry-1
	 *       entry-2
	 *   event-2
	 *       entry-3
	 *       entry-4
	 *   etc.... 
	 * @return multitype:stdClass
	 * 
	 */
	private function reorganiseEntries() {
		$events = array();
		foreach ($this->entries as $entry) {
			if(!array_key_exists($entry->eventnumber, $events)) {
				$event = new stdClass();
				$event->eventnumber = $entry->eventnumber;
				$event->relaycount = $entry->relaycount;
				$event->distance = $entry->distance;
				$event->name = $entry->name;
				$event->sessionnumber= $entry->sessionnumber;
				$event->entries = array();				
				$events[$entry->eventnumber] = $event;
			}
			$events[$entry->eventnumber]->entries[$entry->id] = $entry;
		}
		return $events;
	}
	
	private function mergeRelayEntries() {
		$events = &$this->events;
		foreach ($this->relays as $entry) {
			if(!array_key_exists($entry->eventnumber, $events)) {
				$event = new stdClass();
				$event->eventnumber = $entry->eventnumber;
				$event->relaycount = $entry->relaycount;
				$event->distance = $entry->distance;
				$event->name = $entry->name;
				$event->sessionnumber= $entry->sessionnumber;
				$event->teams = array();
				$events[$entry->eventnumber] = $event;
			}
			if(!array_key_exists($entry->teamnumber, $events[$entry->eventnumber]->teams)) {
				$team = new stdClass();
				$team->teamnumber = $entry->teamnumber;
				$team->entries = array();
				$events[$entry->eventnumber]->teams[$entry->teamnumber] = $team;
			}
			$events[$entry->eventnumber]->teams[$entry->teamnumber]->entries[$entry->ordernumber] = $entry;
		}
		return $events;
	}
	
	/**
	 * Prepares the document
	 */
	protected function _prepareDocument() {
		$app = JFactory::getApplication ();
		$menus = $app->getMenu ();
		$title = null;
		
		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive ();
		if ($menu) {
			$this->params->def ( 'page_heading', $this->params->get ( 'page_title', $menu->title ) );
		} else {
			$this->params->def ( 'page_heading', JText::_ ( 'com_freestroke_DEFAULT_PAGE_TITLE' ) );
		}
		$title = $this->params->get ( 'page_title', '' );
		if (empty ( $title )) {
			$title = $app->getCfg ( 'sitename' );
		} elseif ($app->getCfg ( 'sitename_pagetitles', 0 ) == 1) {
			$title = JText::sprintf ( 'JPAGETITLE', $app->getCfg ( 'sitename' ), $title );
		} elseif ($app->getCfg ( 'sitename_pagetitles', 0 ) == 2) {
			$title = JText::sprintf ( 'JPAGETITLE', $title, $app->getCfg ( 'sitename' ) );
		}
		$this->document->setTitle ( $title );
		
		if ($this->params->get ( 'menu-meta_description' )) {
			$this->document->setDescription ( $this->params->get ( 'menu-meta_description' ) );
		}
		
		if ($this->params->get ( 'menu-meta_keywords' )) {
			$this->document->setMetadata ( 'keywords', $this->params->get ( 'menu-meta_keywords' ) );
		}
		
		if ($this->params->get ( 'robots' )) {
			$this->document->setMetadata ( 'robots', $this->params->get ( 'robots' ) );
		}
	}
}
