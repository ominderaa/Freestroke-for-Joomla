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

/**
 * Methods supporting a list of Freestroke records.
 */
class FreestrokeModelmembers extends JModelList {
	
	/**
	 * Constructor.
	 *
	 * @param
	 *        	array An optional associative array of configuration settings.
	 * @see JController
	 * @since 1.6
	 */
	public function __construct($config = array()) {
		if (empty ( $config ['filter_fields'] )) {
			$config ['filter_fields'] = array (
					'id',
					'a.id',
					'firstname',
					'a.firstname',
					'lastname',
					'a.lastname',
					'initials',
					'a.initials',
					'nameprefix',
					'a.nameprefix',
					'place',
					'a.place',
					'registrationid',
					'a.registrationid',
					'active',
					'a.isactive',
					'created_by',
					'a.created_by' 
			);
		}
		
		parent::__construct ( $config );
	}
	
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 */
	protected function populateState($ordering = null, $direction = null) {
		// Initialise variables.
		$app = JFactory::getApplication ( 'administrator' );
		
		// Load the filter state.
		$search = $app->getUserStateFromRequest ( $this->context . '.filter.search', 'filter_search' );
		$this->setState ( 'filter.search', $search );
		
		$published = $app->getUserStateFromRequest ( $this->context . '.filter.state', 'filter_published', '', 'string' );
		$this->setState ( 'filter.state', $published );
		
		// Filtering active
		$active = $app->getUserStateFromRequest ( $this->context . '.filter.active', 'filter_active', '', 'string' );
		$this->setState ( 'filter.active', $active );
		
		// Load the parameters.
		$params = JComponentHelper::getParams ( 'com_freestroke' );
		$this->setState ( 'params', $params );
		
		// List state information.
		parent::populateState ( 'a.lastname', 'asc' );
	}
	
	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param string $id
	 *        	for the store id.
	 * @return string store id.
	 * @since 1.6
	 */
	protected function getStoreId($id = '') {
		// Compile the store id.
		$id .= ':' . $this->getState ( 'filter.search' );
		$id .= ':' . $this->getState ( 'filter.state' );
		$id .= ':' . $this->getState ( 'filter.active' );
		
		return parent::getStoreId ( $id );
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
		$query->from ( '`#__freestroke_members` AS a' );
		
		// Join over the user field 'created_by'
		$query->select ( 'created_by.name AS created_by' );
		$query->join ( 'LEFT', '#__users AS created_by ON created_by.id = a.created_by' );
	
		// Filter by search in title
		$search = $this->getState ( 'filter.search' );
		if (! empty ( $search )) {
			if (stripos ( $search, 'id:' ) === 0) {
				$query->where ( 'a.id = ' . ( int ) substr ( $search, 3 ) );
			} else {
				$search = $db->Quote ( '%' . $db->escape ( $search, true ) . '%' );
				$query->where ( '( a.lastname LIKE ' . $search . ' )' );
			}
		}
		
		// Filtering active
		$filter_active = $this->state->get ( "filter.active" );
		if($filter_active === "1" || $filter_active === "0") {
			$query->where ( "a.isactive = " . $filter_active, 'AND' );
		}
			
		// Add the list ordering clause.
		$orderCol = $this->state->get ( 'list.ordering' );
		$orderDirn = $this->state->get ( 'list.direction' );
		if ($orderCol && $orderDirn) {
			$query->order ( $db->escape ( $orderCol . ' ' . $orderDirn ) );
		}
		
		return $query;
	}
	
	// Get the items, and change the TAG ID FOR TAG NAMES OVER EACH TAG
	public function getItems() {
		$items = parent::getItems ();
		
		return $items;
	}
	
	/**
	 * return __freestroke_members table fields names
	 *
	 * @return array
	 */
	function getFields() {
		$tables = array (
				'#__freestroke_members' 
		);
		$tablesfields = $this->_db->getTableFields ( $tables );
		
		return array_keys ( $tablesfields ['#__freestroke_members'] );
	}
	
	/**
	 * import data corresponding to fieldsname into events table
	 *
	 * @param array $fieldsname        	
	 * @param array $data
	 *        	the records
	 * @param boolean $replace
	 *        	replace if id already exists
	 * @return int number of records inserted
	 */
	function import($fieldsname, & $data, $replace = true) {
		$ignore = array ();
		if (! $replace) {
			$ignore [] = 'id';
		}
		$rec = array (
				'added' => 0,
				'updated' => 0 
		);
		// parse each row
		foreach ( $data as $row ) {
			$values = array ();
			// parse each specified field and retrieve corresponding value for the record
			foreach ( $fieldsname as $k => $field ) {
				$values [$field] = $row [$k];
			}
			
			$object = & JTable::getInstance ( 'member', 'FreestrokeTable' );
			
			// print_r($values);exit;
			$object->bind ( $values, $ignore );
			
			// Make sure the data is valid
			if (! $object->check ()) {
				$this->setError ( $object->getError () );
				echo JText::_ ( 'Error check: ' ) . $object->getError () . "\n";
				continue;
			}
			
			// Store it in the db
			if ($replace) {
				// We want to keep id from database so first we try to insert into database. if it fails,
				// it means the record already exists, we can use store().
				if (! $object->insertIgnore ()) {
					if (! $object->store ()) {
						echo JText::_ ( 'Error store: ' ) . $this->_db->getErrorMsg () . "\n";
						continue;
					} else {
						$rec ['updated'] ++;
					}
				} else {
					$rec ['added'] ++;
				}
			} else {
				if (! $object->store ()) {
					echo JText::_ ( 'Error store: ' ) . $this->_db->getErrorMsg () . "\n";
					continue;
				} else {
					$rec ['added'] ++;
				}
			}
		}
		
		return $rec;
	}
}
