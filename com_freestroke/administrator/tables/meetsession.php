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

/**
 * club Table class
 */
class FreestrokeTablemeetsession extends JTable {
	
	/**
	 * Constructor
	 *
	 * @param
	 *        	JDatabase A database connector object
	 */
	public function __construct(&$db) {
		JObserverMapper::addObserverClassToClass('JTableObserverContenthistory', 'FreestrokeTablemember', array('typeAlias' => 'com_freestroke.member'));
		parent::__construct ( '#__freestroke_meetsessions', 'id', $db );
	}
	
	/**
	 * Overloaded bind function to pre-process the params.
	 *
	 * @param
	 *        	array		Named array
	 * @return null string operation was satisfactory, otherwise returns an error
	 * @see JTable:bind
	 * @since 1.5
	 */
	public function bind($array, $ignore = '') {
		$input = JFactory::getApplication()->input;
		$task = $input->getString('task', '');

		if ($array['created_by'] == 0)
		{
			$array['created_by'] = JFactory::getUser()->id;
		}

		if (isset($array['params']) && is_array($array['params']))
		{
			$registry = new JRegistry;
			$registry->loadArray($array['params']);
			$array['params'] = (string) $registry;
		}

		if (isset($array['metadata']) && is_array($array['metadata']))
		{
			$registry = new JRegistry;
			$registry->loadArray($array['metadata']);
			$array['metadata'] = (string) $registry;
		}

		if (!JFactory::getUser()->authorise('core.admin', 'com_freestroke.meetsession.' . $array['id']))
		{
			$actions         = JAccess::getActionsFromFile(
				JPATH_ADMINISTRATOR . '/components/com_freestroke/access.xml',
				"/access/section[@name='member']/"
			);
			$default_actions = JAccess::getAssetRules('com_freestroke.meetsession.' . $array['id'])->getData();
			$array_jaccess   = array();

			foreach ($actions as $action)
			{
				$array_jaccess[$action->name] = $default_actions[$action->name];
			}

			$array['rules'] = $this->JAccessRulestoArray($array_jaccess);
		}

		// Bind the rules for ACL where supported.
		if (isset($array['rules']) && is_array($array['rules']))
		{
			$this->setRules($array['rules']);
		}

		return parent::bind($array, $ignore);
	}

	public function bindObject($obj, $ignore = '') {
		return parent::bind ( $obj, $ignore );
	}

	/**
	 * This function convert an array of JAccessRule objects into an rules array.
	 * 
	 * @param type $jaccessrules
	 *        	an arrao of JAccessRule objects.
	 */
	private function JAccessRulestoArray($jaccessrules) {
		$rules = array ();
		foreach ( $jaccessrules as $action => $jaccess ) {
			$actions = array ();
			foreach ( $jaccess->getData () as $group => $allow ) {
				$actions [$group] = (( bool ) $allow);
			}
			$rules [$action] = $actions;
		}
		return $rules;
	}
	
	/**
	 * Overloaded check function
	 */
	public function check() {
		
		// If there is an ordering column and this is a new row then get the next ordering value
		if (property_exists ( $this, 'ordering' ) && $this->id == 0) {
			$this->ordering = self::getNextOrder ();
		}
		
		return parent::check ();
	}
	
	
	/**
	 * Define a namespaced asset name for inclusion in the #__assets table
	 * 
	 * @return string The asset name
	 *        
	 * @see JTable::_getAssetName
	 */
	protected function _getAssetName() {
		$k = $this->_tbl_key;
		return 'com_freestroke.meetsession.' . ( int ) $this->$k;
	}
	
	/**
	 * Returns the parrent asset's id.
	 * If you have a tree structure, retrieve the parent's id using the external key field
	 *
	 * @see JTable::_getAssetParentId
	 */
	protected function _getAssetParentId(JTable $table = null, $id = null) {
		// We will retrieve the parent-asset from the Asset-table
		$assetParent = JTable::getInstance ( 'Asset' );
		// Default: if no asset-parent can be found we take the global asset
		$assetParentId = $assetParent->getRootId ();
		// The item has the component as asset-parent
		$assetParent->loadByName ( 'com_freestroke' );
		// Return the found asset-parent-id
		if ($assetParent->id) {
			$assetParentId = $assetParent->id;
		}
		return $assetParentId;
	}
}
