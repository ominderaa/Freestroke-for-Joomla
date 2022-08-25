<?php

/**
 * @version     1.0.0
 * @package     com_freestroke
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      O.Minderaa <ominderaa@gmail.com> - http://
 */
defined('_JEXEC') or die();

jimport('joomla.application.component.modellist');
jimport('joomla.date.date');

class FreestrokeModelMeetmailer extends JModelList {
	
	/**
	 * Constructor.
	 *
	 * @param
	 *        	array An optional associative array of configuration settings.
	 * @see JController
	 * @since 1.6
	 */
	public function __construct($config = array()) {
		parent::__construct($config);
	}
	
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since 1.6
	 */
	protected function populateState($ordering = null, $direction = null) {
		
		// Initialise variables.
		$app = JFactory::getApplication();
		
		// disable pagination
		$this->setState('list.limit', 0);
		
		parent::populateState($ordering, $direction);
	}
	
	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return JDatabaseQuery
	 * @since 1.6
	 */
	protected function getListQuery() {
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		
		// Select the required fields from the table.
		$query->select(array (
				'ms.name, ms.startdate, mb.firstname, mb.nameprefix, mb.lastname, mb.birthdate, mb.email, e.entrytime,',
				'ss.name as stylename',
				'ss.distance',
				'ss.relaycount',
				'ss.strokecode' 
		));
		$id = $this->getState('meet.id');
		
		$query->from('#__freestroke_meets m')
			->join('INNER', '#__freestroke_meetsessions ms on ms.meets_id = m.id')
			->join('INNER', '#__freestroke_entries e on m.id = e.meets_id and ms.sessionnumber = e.sessionnumber')
			->join('INNER', '#__freestroke_members mb on mb.id = e.members_id')
			->order(array (
				'mb.lastname ASC', 
				'mb.birthdate DESC'
		));
		
		return $query;
	}
	
}
