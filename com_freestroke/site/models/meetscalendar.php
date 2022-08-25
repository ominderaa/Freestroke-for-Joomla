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
class FreestrokeModelMeetscalendar extends JModelList {
	protected $startDate;
	protected $endDate;
	
	/**
	 * Constructor.
	 *
	 * @param
	 *        	array An optional associative array of configuration settings.
	 * @see JController
	 * @since 1.6
	 */
	public function __construct($config = array()) {
		$config['filter_fields'] = array(
				'a.mindate'
		);
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
		$app = JFactory::getApplication ();
		
		if(empty($ordering)) {
			$ordering = 'a.mindate';
			$direction = 'ASC';
		}

		// Check if the ordering field is in the white list, otherwise use the incoming value.
		$value = $app->getUserStateFromRequest($this->context . '.ordercol', 'filter_order', $ordering);
		$this->setState('list.ordering', $value);
		
		// Check if the ordering direction is valid, otherwise use the incoming value.
		$value = $app->getUserStateFromRequest($this->context . '.orderdirn', 'filter_order_Dir', $direction);
		$this->setState('list.direction', $value);
		
		$value = $app->getUserStateFromRequest($this->context . '.filter.season', 'filter_season');
		$this->setState('filter.season', $value);
		
		// List state information.
		parent::populateState ( $ordering, $direction );

		// disable pagination
		$this->setState ( 'list.limit', 0 );
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
		// Select the required fields from the table.
		$query->select($this->getState('list.select', 
				array('a.*', 
				  '(select distinct 1 from `#__freestroke_meetsessions` hp where hp.meets_id = a.id) as hasinvite',
				  '(select distinct 1 from `#__freestroke_entries` he where he.meets_id = a.id) as hasentries',
				  '(select distinct 1 from `#__freestroke_results` hr where hr.meets_id = a.id) as hasresults'	)));
		$query->from('`#__freestroke_meets` AS a');
				
		$seasontext = $this->getState('filter.season');
		if ($seasontext == '') {
			$seasondate = JDate::getInstance('now');
			if ($seasondate->month <= 7) {
				$seasondate->setDate($seasondate->year - 1, 8, 1);
			}
			$seasontext = $seasondate->format('Y');
		}
		$season = $this->getSeasonStartEnd($seasontext);
		
		$query->where('a.mindate >= ' . $db->Quote($season->startdate->toSql()));
		$query->where('a.mindate <= ' . $db->Quote($season->enddate->toSql()), 'AND');
		$query->order(array (
				$db->escape($this->getState('list.ordering', 'a.mindate')) . ' ' . $db->escape($this->getState('list.direction', 'ASC')),
				'id ASC' 
		));
		
		// keep dates in model
		$this->startDate = $season->startdate;
		$this->endDate = $season->enddate;
		
		return $query;
	}

	/**
	 * 
	 * @param unknown $startyear
	 */
	protected function getSeasonStartEnd($startyear) {
		$startDate = JDate::getInstance($startyear . '-08-01');
		$startDate->setTime(0, 0, 0);
		$endDate = JDate::getInstance($startDate);
		$endDate->setDate($startDate->year + 1, 7, 31);
		
		$season = new StdClass();
		$season->startdate = $startDate;
		$season->enddate = $endDate;
		return $season;
	}

	/**
	 * 
	 */
	public function getStartDate() {
		return $this->startDate;
	}
	
	/**
	 * 
	 */
	public function getEndDate() {
		return $this->endDate;
	}
}
