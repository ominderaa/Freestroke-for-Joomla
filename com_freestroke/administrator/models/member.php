<?php
/**
 * @version     1.0.0
 * @package     com_freestroke
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      O.Minderaa <ominderaa@gmail.com> - http://
 */

// No direct access.
defined('_JEXEC') or die();

jimport('joomla.application.component.modeladmin');

/**
 * Freestroke model.
 */
class FreestrokeModelmember extends JModelAdmin {
	/**
	 *
	 * @var string prefix to use with controller messages.
	 * @since 1.6
	 */
	protected $text_prefix = 'COM_FREESTROKE';
	/**
	 *
	 * @var string Alias to manage history control
	 * @since 3.2
	 */
	public $typeAlias = 'com_freestroke.member';
	
	/**
	 *
	 * @var null Item data
	 * @since 1.6
	 */
	protected $item = null;
	
	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param string $type
	 *        	The table type to instantiate
	 * @param string $prefix
	 *        	A prefix for the table class name. Optional.
	 * @param array $config
	 *        	Configuration array for model. Optional.
	 *        	
	 * @return JTable database object
	 *        
	 * @since 1.6
	 *       
	 */
	public function getTable($type = 'Member', $prefix = 'FreestrokeTable', $config = array()) {
		return JTable::getInstance($type, $prefix, $config);
	}
	
	/**
	 * Method to get the record form.
	 *
	 * @param array $data
	 *        	An optional array of data for the form to interogate.
	 * @param boolean $loadData
	 *        	True if the form is to load its own data (default case), false if not.
	 *        	
	 * @return JForm A JForm object on success, false on failure
	 *        
	 * @since 1.6
	 */
	public function getForm($data = array(), $loadData = true) {
		// Initialise variables.
		$app = JFactory::getApplication();
		
		// Get the form.
		$form = $this->loadForm('com_freestroke.member', 'member', array(
				'control' => 'jform',
				'load_data' => $loadData
		));
		if (empty($form)) {
			return false;
		}
		
		return $form;
	}
	
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return mixed data for the form.
	 * @since 1.6
	 */
	protected function loadFormData() {
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_freestroke.edit.member.data', array());
		
		if (empty($data)) {
			if ($this->item === null) {
				$this->item = $this->getItem();
			}
			
			$data = $this->item;
		}
		
		return $data;
	}
	
	/**
	 * Method to get a single record.
	 *
	 * @param
	 *        	integer The id of the primary key.
	 *        	
	 * @return mixed on success, false on failure.
	 * @since 1.6
	 */
	public function getItem($pk = null) {
		if ($item = parent::getItem($pk)) {
			
			// Do any procesing on fields here if needed
		}
		
		return $item;
	}
	
	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @since 1.6
	 */
	protected function prepareTable($table) {
		jimport('joomla.filter.output');
		
		if (empty($table->id)) {
			
			// Set ordering to the last item if not set
			if (@$table->ordering === '') {
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__freestroke_members');
				$max = $db->loadResult();
				$table->ordering = $max + 1;
			}
		}
	}
}