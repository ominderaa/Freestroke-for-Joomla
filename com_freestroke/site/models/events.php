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
class FreestrokeModelEvents extends JModelList {
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
		$app = JFactory::getApplication ( 'com_freestroke' );
		
		// Load state from the request userState on edit or from the passed variable on default
		if (JFactory::getApplication ()->input->get ( 'layout' ) == 'edit') {
			$id = JFactory::getApplication ()->getUserState ( 'com_freestroke.edit.meet.id' );
		} else {
			$id = JFactory::getApplication ()->input->get ( 'meet_id' );
			if(!isset($id)) {
				$id = JFactory::getApplication()->input->get('id');
			}
			JFactory::getApplication ()->setUserState ( 'com_freestroke.edit.meet.id', $id );
		}
		$this->setState ( 'meet.id', $id );
		
		// Load the parameters.
		$params = $app->getParams ();
		$params_array = $params->toArray ();
		if (isset ( $params_array ['item_id'] )) {
			$this->setState ( 'meet.id', $params_array ['item_id'] );
		}
		$this->setState ( 'params', $params );
		
		// List state information.
		parent::populateState ( $ordering, $direction );
		
		// List state information - we do not want paging in this model
		// but I haven't figured out how just yet
		$this->setState ( 'list.limit', 200 );
		$this->setState ( 'list.start', 0 );
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
		$query->select ( array (
				'a.*',
				'ss.code',
				'ss.name',
				'ss.distance',
				'ss.relaycount',
				'ss.strokecode' 
		) );
		$id = $this->getState ( 'meet.id' );
		
		$query->from ( '`#__freestroke_events` AS a' );
		$query->join ( 'LEFT', '#__freestroke_swimstyles AS ss ON ss.id = a.swimstyles_id' );
		$query->where ( 'a.meets_id = ' . $db->Quote ( $id ) );
		$query->order ( array (
				'a.programnumber ASC' 
		) );
		
		return $query;
	}
}
