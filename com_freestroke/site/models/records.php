<?php

/**
 * @version     1.0.0
 * @package     com_freestroke
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      O.Minderaa <ominderaa@gmail.com> - http://
 */
defined ( '_JEXEC' ) or die ();

jimport ( 'joomla.application.component.modellist' );
jimport ( 'joomla.date.date' );

/**
 * Methods supporting a list of Freestroke records.
 */
class FreestrokeModelRecords extends JModelList {
	protected $meetsid;
	
	/**
	 * Constructor.
	 *
	 * @param
	 *        	array An optional associative array of configuration settings.
	 * @see JController
	 * @since 1.6
	 */
	public function __construct($config = array()) {
		parent::__construct ( $config );
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
		$app = JFactory::getApplication('com_freestroke');
		
		// Load state from the request userState on edit or from the passed variable on default
		if (JFactory::getApplication()->input->get('layout') == 'edit') {
			$id = JFactory::getApplication()->getUserState('com_freestroke.edit.meet.id');
		} else {
			$id = JFactory::getApplication()->input->get('meet_id');
			if (! isset($id)) {
				$id = JFactory::getApplication()->input->get('id');
			}
			JFactory::getApplication()->setUserState('com_freestroke.edit.meet.id', $id);
		}
		$this->setState('meet.id', $id);
		
		// Load the parameters.
		$params = $app->getParams();
		$params_array = $params->toArray();
		if (isset($params_array ['item_id'])) {
			$this->setState('meet.id', $params_array ['item_id']);
		}
		$this->setState('params', $params);
		
		// List state information.
		parent::populateState($ordering, $direction);
		
		// disable pagination
		$this->setState('list.limit', 0);
	}
	
	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return JDatabaseQuery
	 * @since 1.6
	 */
	protected function getListQuery() {
		// Create a new query object.
		$db = $this->getDbo ();
		$query = $db->getQuery ( true );
		$subquery = $db->getQuery ( true );
		$id = $this->getState('meet.id');
		
		$subquery->select(
			array('en.members_id', 'en.swimstyles_id')
		);
		$subquery->from ( '`#__freestroke_entries` AS en' );
		$subquery->where ( 'en.meets_id = ' . $db->Quote($id) );
		
		// Select the required fields from the table.
		$query->select ( 
				array(
						'rs.members_id, rs.swimstyles_id, rs.eventdate, rs.totaltime, rs.resulttype'
					) );
		
		$query->from ( '`#__freestroke_results` AS rs' );
		$query->where('(members_id, swimstyles_id )' . ' IN (' . $subquery->__toString() . ')');	
		$query->where ( 'rs.meets_id <> ' . $db->Quote($id), 'AND' );
		$query->order ( array (
				'members_id', 'swimstyles_id', 'eventdate desc'
		) );
		
		return $query;
	}
}
