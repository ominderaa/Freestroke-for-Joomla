<?php
/**
 * @version     1.0.0
 * @package     com_freestroke
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      O.Minderaa <ominderaa@gmail.com> - http://
 */

// No direct access.
defined ( '_JEXEC' ) or die ();

jimport ( 'joomla.application.component.controlleradmin' );

/**
 * Members list controller class.
 */
class FreestrokeControllerMembers extends JControllerAdmin {
	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    Optional. Model name
	 * @param   string  $prefix  Optional. Class prefix
	 * @param   array   $config  Optional. Configuration array for model
	 *
	 * @since 1.6
	 */
	public function getModel($name = 'member', $prefix = 'FreestrokeModel', $config = Array()) {
		$model = parent::getModel ( $name, $prefix, array ('ignore_request' => true));
		return $model;
	}
	
	/**
	 * Set member to active
	 */
	public function active() {
		// Get the input
		$input = JFactory::getApplication ()->input;
		$pks = $input->post->get ( 'cid', array (), 'array' );
		
		// Must be a valid primary key value.
		foreach ( $pks as $key => $value ) {
			$object->id = $value;
			$object->isactive = 1;
			
			// Update their details in the users table using id as the primary key.
			$result = JFactory::getDbo ()->updateObject ( '#__freestroke_members', $object, 'id' );
		}
		$this->setRedirect ( 'index.php?option=com_freestroke&view=members', 'COM_FREESTROKE_MEMBER_ACTIVATED' );
	}
	
	/**
	 * Set member to inactive
	 */
	public function inactive() {
		// Get the input
		$input = JFactory::getApplication ()->input;
		$pks = $input->post->get ( 'cid', array (), 'array' );
		
		// Must be a valid primary key value.
		foreach ( $pks as $key => $value ) {
			$object->id = $value;
			$object->isactive = 0;
			
			// Update their details in the users table using id as the primary key.
			$result = JFactory::getDbo ()->updateObject ( '#__freestroke_members', $object, 'id' );
		}
		$this->setRedirect ( 'index.php?option=com_freestroke&view=members', 'Lid is gedeactiveerd' );
	}
	
	/**
	 * import members from csv file
	 */
	function csvimport() {
		JTable::addIncludePath ( JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_freestroke' . DIRECTORY_SEPARATOR . 'tables' );
		$object = & JTable::getInstance ( 'member', 'FreestrokeTable' );
		$object_fields = get_object_vars ( $object );
		
		$msg = '';
		if ($file = JRequest::getVar ( 'importfile', null, 'files', 'array' )) {
			$handle = fopen ( $file ['tmp_name'], 'r' );
			if (! $handle) {
				$msg = JText::_ ( 'Cannot open uploaded file.' );
				$this->setRedirect ( 'index.php?option=com_freestroke&view=members', $msg, 'error' );
			}
			
			// get fields, on first row of the file
			$fields = array ();
			if (($data = fgetcsv ( $handle, 1000, ';', '"' )) !== FALSE) {
				$numfields = count ( $data );
				for($c = 0; $c < $numfields; $c ++) {
					// here, we make sure that the field match one of the fields of freestroke_members table or special fields,
					// otherwise, we don't add it
					if (array_key_exists ( strtolower ( $data [$c] ), $object_fields )) {
						$fields [$c] = strtolower ( $data [$c] );
					}
				}
			}
			
			// If there is no validated fields, there is a problem...
			if (! count ( $fields )) {
				$msg .= "<p>Error parsing column names. Are you sure this is a proper csv export ?<br />try to export first to get an example of formatting</p>\n";
				$this->setRedirect ( 'index.php?option=com_freestroke&view=members', $msg, 'error' );
				return;
			} else {
				$msg .= "<p>" . $numfields . " fields found in first row</p>\n";
				$msg .= "<p>" . count ( $fields ) . " fields were kept</p>\n";
			}
			
			// Now get the records, meaning the rest of the rows.
			$records = array ();
			$row = 1;
			while ( ($data = fgetcsv ( $handle, 10000, ';', '"' )) !== FALSE ) {
				$num = count ( $data );
				if ($numfields != $num) {
					$msg .= "<p>Wrong number of fields ($num) record $row<br /></p>\n";
				} else {
					$r = array ();
					// only extract columns with validated header, from previous step.
					foreach ( $fields as $k => $v ) {
						$r [] = $this->_formatcsvfield($v, $data [$k]);
					}
					$records [] = $r;
				}
				$row ++;
			}
			fclose ( $handle );
			$msg .= "<p>total records found: " . count ( $records ) . "<br /></p>\n";
			
			// database update
			if (count ( $records )) {
				$model = $this->getModel ( 'members' );
				$result = $model->import ( $fields, $records, $replace );
				$msg .= "<p>total added records: " . $result ['added'] . "<br /></p>\n";
				$msg .= "<p>total updated records: " . $result ['updated'] . "<br /></p>\n";
			}
			$this->setRedirect ( 'index.php?option=com_freestroke&view=members', $msg );
		} else {
			parent::display ();
		}
	}
	
	/**
	 * handle special field conversion eg. for dates 
	 *
	 * @param string column name
	 * @param string $value
	 * @return string
	 */
	function _formatcsvfield($type, $value)
	{
		switch($type)
		{
			case 'birthdate':
				if ($value != '') {
					$date = strtotime($value);
					$field = strftime('%Y-%m-%d',$date);
				}
				else {
					$field = null;
				}
				break;
			default:
				$field = $value;
				break;
		}
		return $field;
	}
}