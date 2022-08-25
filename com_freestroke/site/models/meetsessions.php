<?php

/**
 * @version     1.0.0
 * @package     com_freestroke
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      O.Minderaa <ominderaa@gmail.com> - http://
 * 
 * Model to retrieve all meetsessions for a single meet
 * Uses meet.id from state as key
 * 
 */
defined ( '_JEXEC' ) or die ();

jimport ( 'joomla.application.component.modellist' );
jimport ( 'joomla.date.date' );

/**
 * Methods supporting a list of Freestroke records.
 */
class FreestrokeModelMeetsessions extends JModelList {
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
			$id = JFactory::getApplication()->input->get('id');
			JFactory::getApplication()->setUserState('com_freestroke.edit.meet.id', $id);
		}
		$this->setState('meet.id', $id);
		
		// Load the parameters.
		$params = $app->getParams();
		$params_array = $params->toArray();
		if(isset($params_array['item_id'])){
			$this->setState('meet.id', $params_array['item_id']);
		}
		$this->setState('params', $params);

		$this->setState ( 'list.limit', 0);
		$this->setState ( 'list.start', 0 );
		
		// List state information.
		parent::populateState ( $ordering, $direction );
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
		
		// Select the required fields from the table.
		$query->select ( $this->getState ( 'list.select', 'a.*' ) );
		$id = $this->getState('meet.id');
		
		$query->from ( '`#__freestroke_meetsessions` AS a' );
		
		$query->where ( 'a.meets_id = ' . $db->Quote($id) );
		$query->order ( array (
				'a.sessionnumber ASC',
				'id ASC' 
		) );
		
		return $query;
	}
}
