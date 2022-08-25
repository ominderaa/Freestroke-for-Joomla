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

/**
 * Methods supporting a list of Freestroke records.
 */
class FreestrokeModelmeets extends JModelList {
	
	/**
	 * Constructor.
	 *
	 * @param
	 *        	array An optional associative array of configuration settings.
	 * @see JController
	 * @since 1.6
	 */
	public function __construct($config = array()) {
		if (empty($config ['filter_fields'])) {
			$config ['filter_fields'] = array(
					'id',
					'a.id',
					'name',
					'a.name',
					'poolname',
					'a.poolname',
					'place',
					'a.place',
					'mindate',
					'a.mindate',
					'maxdate',
					'a.maxdate',
					'a.teamlead'
			);
		}
		
		parent::__construct($config);
	}
	
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   Elements order
	 * @param   string  $direction  Order direction
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	protected function populateState($ordering = null, $direction = null) {
		// Initialise variables.
		$app = JFactory::getApplication('administrator');
		
		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		
		$published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $published);
		
		// Load the parameters.
		$params = JComponentHelper::getParams('com_freestroke');
		$this->setState('params', $params);
		
		// List state information.
		parent::populateState('a.name', 'asc');
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
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.state');
		
		return parent::getStoreId($id);
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
		$query->select($this->getState('list.select', 'a.*'));
		$query->from('`#__freestroke_meets` AS a');
		
		// Join over the user field 'created_by'
		$query->select('created_by.name AS created_by');
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');
		
		// Filter by search in title
		$search = $this->getState('filter.search');
		if (! empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = ' . (int) substr($search, 3));
			} else {
				$search = $db->Quote('%' . $db->escape($search, true) . '%');
				$query->where('( a.name LIKE ' . $search . '  OR  a.poolname LIKE ' . $search . '  OR  a.mindate LIKE ' . $search . ' )');
			}
		}
		
		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');
		if ($orderCol && $orderDirn) {
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}
		
		return $query;
	}
	
	/**
	 * Get an array of data items
	 *
	 * @return mixed Array of data items on success, false on failure.
	 */
	public function getItems() {
		$items = parent::getItems();
		
		return $items;
	}
	
	/**
	 * return __freestroke_meets table fields names
	 *
	 * @return array
	 */
	function getFields() {
		$tables = array('#__freestroke_meets');
		$tablesfields = $this->_db->getTableFields($tables);
		
		return array_keys($tablesfields ['#__freestroke_meets']);
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
		$ignore = array();
		if (! $replace) {
			$ignore [] = 'id';
		}
		$rec = array(
				'added' => 0,
				'updated' => 0
		);
		// parse each row
		foreach ( $data as $row ) {
			$values = array();
			// parse each specified field and retrieve corresponding value for the record
			foreach ( $fieldsname as $k => $field ) {
				$values [$field] = $row [$k];
			}
			
			$object = & JTable::getInstance('meet', 'FreestrokeTable');
			
			// print_r($values);exit;
			$object->bind($values, $ignore);
			
			// Make sure the data is valid
			if (! $object->check()) {
				$this->setError($object->getError());
				echo JText::_('Error check: ') . $object->getError() . "\n";
				continue;
			}
			
			// Store it in the db
			if ($replace) {
				// We want to keep id from database so first we try to insert into database. if it fails,
				// it means the record already exists, we can use store().
				if (! $object->insertIgnore()) {
					if (! $object->store()) {
						echo JText::_('Error store: ') . $this->_db->getErrorMsg() . "\n";
						continue;
					} else {
						$rec ['updated'] ++;
					}
				} else {
					$rec ['added'] ++;
				}
			} else {
				if (! $object->store()) {
					echo JText::_('Error store: ') . $this->_db->getErrorMsg() . "\n";
					continue;
				} else {
					$rec ['added'] ++;
				}
			}
		}
		
		return $rec;
	}
}
