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

/**
 * View to edit
 */
class FreestrokeViewMeet extends JViewLegacy
{

    protected $state;
    protected $item;
    protected $form;
    protected $params;

    /**
     * Display the view
     */
    public function display($tpl = null)
    {
        $app = JFactory::getApplication();
        $user = JFactory::getUser();
        $this->document = JFactory::getDocument();
        
        $this->state = $this->get('State');
        $this->item = $this->get('Data');
        $this->meetsessions = $this->get('Items', 'meetsessions');
        $this->events = $this->get('Items', 'events');
        $this->relays = $this->get('Items', 'relayentries');
        
        $this->params = $app->getParams('com_freestroke');
        $this->form = $this->get('Form');
        
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }
        
        if ($this->_layout == 'edit') {
            $authorised = $user->authorise('core.create', 'com_freestroke');
            if ($authorised !== true) {
                throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
            }
        }
        
        $this->_prepareDocument();
        parent::display($tpl);
    }

    /**
     * The list model of entries is really a hierarchy of events en entries.
     * Here the hierarchy is restored to make view generation easier.
     * After this, $events will contain:
     * event-1
     * entry-1
     * entry-2
     * event-2
     * entry-3
     * entry-4
     * etc....
     *
     * @return multitype:stdClass
     *
     */
    private function reorganiseEntries()
    {
        $events = null;
        if (! empty($this->entries)) {
            $events = array();
            foreach ($this->entries as $entry) {
                if (! array_key_exists($entry->eventnumber, $events)) {
                    $event = new stdClass();
                    $event->eventnumber = $entry->eventnumber;
                    $event->relaycount = $entry->relaycount;
                    $event->distance = $entry->distance;
                    $event->name = $entry->name;
                    $event->sessionnumber = $entry->sessionnumber;
                    $event->teams = array();
                    $event->entries = array();
                    $events[$entry->eventnumber] = $event;
                }
                $events[$entry->eventnumber]->entries[$entry->id] = $entry;
            }
        }
        return $events;
    }

    /**
     * The list model of entries is really a hierarchy of events en entries.
     * Here the hierarchy is restored to make view generation easier.
     * After this, $events ill contain:
     * event-1
     * entry-1
     * entry-2
     * event-2
     * entry-3
     * entry-4
     * etc....
     * 
     * @return multitype:stdClass
     *
     */
    private function groupResultsByMember($resultsModel)
    {
        $members = array();
        foreach ($resultsModel as $result) {
            if (! array_key_exists($result->memberid, $members)) {
                $member = new stdClass();
                $member->memberid = $result->memberid;
                $member->lastname = $result->lastname;
                $member->firstname = $result->firstname;
                $member->nameprefix = $result->nameprefix;
                $member->birthdate = $result->birthdate;
                $member->results = array();
                $members[$member->memberid] = $member;
            }
            if ($result->resulttype == "FIN") {
                $members[$member->memberid]->results[$result->id] = $result;
            }
        }
        foreach ($members as $member) {
            if (count($member->results) == 0) {
                unset($members[$member->memberid]);
            }
        }
        return $members;
    }

    /**
     *
     * @return stdClass
     */
    private function mergeRelayEntries()
    {
        $events = &$this->evententries;
        foreach ($this->relays as $entry) {
            if (! array_key_exists($entry->eventnumber, $events)) {
                $event = new stdClass();
                $event->eventnumber = $entry->eventnumber;
                $event->relaycount = $entry->relaycount;
                $event->distance = $entry->distance;
                $event->name = $entry->name;
                $event->sessionnumber = $entry->sessionnumber;
                $event->teams = array();
                $events[$entry->eventnumber] = $event;
            }
            if (! array_key_exists($entry->teamnumber, $events[$entry->eventnumber]->teams)) {
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
     *
     * @param unknown $resultsModel
     */
    private function calculateResultStatistics($resultsModel)
    {
        $bestimprovement = 0;
        $this->totalimprovement = 0;
        $this->amountofpersonalrecords = 0;
        $this->countofresults = 0;
        $countofimprovements = 0;
        
        foreach ($resultsModel as $result) {
            if ($result->resulttype == "FIN") {
                $this->countofresults ++;
                
                if ($result->entrytime != 0) {
                    $difference = 100 + ($result->entrytime - $result->totaltime) / $result->entrytime * 100;
                    $result->difference = intval($difference);
                    $this->totalimprovement += $result->difference;
                    $countofimprovements ++;
                    if ($difference > $bestimprovement) {
                        $bestimprovement = $difference;
                        $this->bestimprovedresult = $result;
                    }
                }
                if ($result->entrytime > $result->totaltime) {
                    $result->personalrecord = true;
                    $this->amountofpersonalrecords ++;
                } else {
                    $result->personalrecord = false;
                }
            }
        }
        if ($countofimprovements != 0) {
            $this->agerageimprovement = $this->totalimprovement / $countofimprovements;
        } else {
            $this->agerageimprovement = "onbekend";
        }
    }

    /**
     * Prepares the document
     */
    protected function _prepareDocument()
    {
        $app = JFactory::getApplication();
        $menus = $app->getMenu();
        $title = null;
        
        // Because the application sets a default page title,
        // we need to get it from the menu item itself
        $menu = $menus->getActive();
        if ($menu) {
            $this->params->def('page_heading', $this->params->get('page_title', $menu->title));
        } else {
            $this->params->def('page_heading', JText::_('com_freestroke_DEFAULT_PAGE_TITLE'));
        }
        $title = $this->params->get('page_title', '');
        if (empty($title)) {
            $title = $app->getCfg('sitename');
        } elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
            $title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
        } elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
            $title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
        }
        $this->document->setTitle($title);
        
        if ($this->params->get('menu-meta_description')) {
            $this->document->setDescription($this->params->get('menu-meta_description'));
        }
        
        if ($this->params->get('menu-meta_keywords')) {
            $this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
        }
        
        if ($this->params->get('robots')) {
            $this->document->setMetadata('robots', $this->params->get('robots'));
        }
    }
}
