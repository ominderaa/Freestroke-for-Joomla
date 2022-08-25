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
class FreestrokeViewMeetattendance extends JViewLegacy {
	protected $state;
	protected $item;
	protected $form;
	protected $params;
	protected $members;
	
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
		$this->mergeByMember();
		$this->relays = $this->get('Items', 'relayentries');
		$this->mergeRelays();
		usort($this->members, array('FreestrokeViewMeetattendance','compareMembers'));
		
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
				$session->events[$event->id] = $event;
			}
		}
	}
	
	
	/**
	 */
	private function mergeByMember() {
		$this->members = array ();
		foreach ( $this->entries as $entry ) {
			if (array_key_exists($entry->memberid, $this->members)) {
				$member = $this->members [$entry->memberid];
			} else {
				$member = new stdClass();
				$member->firstname = $entry->firstname;
				$member->lastname = $entry->lastname;
				$member->nameprefix = $entry->nameprefix;
				$member->birthdate = $entry->birthdate;
				$member->registrationid = $entry->registrationid;
				$member->events = array ();
				$this->members [$entry->memberid] = $member;
			}
			$member->events [] = $entry;
			$entry->personalbest = null;
		}
	}
	
	/**
	 */
	private function mergeRelays() {
		foreach ( $this->relays as $entry ) {
			if (array_key_exists($entry->memberid, $this->members)) {
				$member = $this->members [$entry->memberid];
			} else {
				$member = new stdClass();
				$member->firstname = $entry->firstname;
				$member->lastname = $entry->lastname;
				$member->nameprefix = $entry->nameprefix;
				$member->birthdate = $entry->birthdate;
				$member->registrationid = $entry->registrationid;
				$member->events = array ();
				$this->members [$entry->memberid] = $member;
			}
			$member->events [] = $entry;
		}
	}
	
	/**
	 * 
	 * @param unknown $member
	 * @param unknown $swimstyle
	 */
	private function findEntryBySwimstyle($member, $swimstyle) {
		foreach($member->events as $event ) {
			if($event->swimstyles_id == $swimstyle ) {
				return $event;
			}
		}
		return null;
	}
	
	/**
	 * 
	 * @param unknown $a
	 * @param unknown $b
	 * @return number
	 */
	private function compareMembers($a, $b) {
		$result = strcmp($a->lastname, $b->lastname);
		if($result == 0 ) {
			$result = strcmp($a->firstname, $b->firstname);
		}
		return $result;
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
