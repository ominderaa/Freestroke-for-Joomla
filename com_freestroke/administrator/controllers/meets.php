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
 * Meets list controller class.
 */
class FreestrokeControllerMeets extends JControllerAdmin {
	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    Optional. Model name
	 * @param   string  $prefix  Optional. Class prefix
	 * @param   array   $config  Optional. Configuration array for model
	 *
	 * @return  object	The Model
	 *
	 * @since 1.6
	 */
	public function getModel($name = 'meet', $prefix = 'FreestrokeModel', $config = Array()) {
		$model = parent::getModel ( $name, $prefix, array ('ignore_request' => true));
		return $model;
	}

	
	/**
	 * import meets from csv file
	 */
	function csvimport() {
		JTable::addIncludePath ( JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_freestroke' . DIRECTORY_SEPARATOR . 'tables' );
		$meet = & JTable::getInstance ( 'meet', 'FreestrokeTable', null );
		$meet_fields = get_object_vars ( $meet );
		$user = JFactory::getUser();
		
		$msg = '';
		if ($file = JRequest::getVar ( 'importfile', null, 'files', 'array' )) {
			$handle = fopen ( $file ['tmp_name'], 'r' );
			if (! $handle) {
				$msg = JText::_ ( 'Cannot open uploaded file.' );
				$this->setRedirect ( 'index.php?option=com_freestroke&view=meets', $msg, 'error' );
			}
			
			// get fields, on first row of the file
			$meetfieldnames = array ();
			if (($data = fgetcsv ( $handle, 1000, ';', '"' )) !== FALSE) {
				$numfields = count ( $data );
				for($c = 0; $c < $numfields; $c ++) {
					// here, we make sure that the field match one of the fields of freestroke_meets and freestroke_meetsessions tables or special fields,
					// otherwise, we don't add it
					if (array_key_exists ( strtolower ( $data [$c] ), $meet_fields )) {
						$meetfieldnames [$c] = strtolower ( $data [$c] );
					}
				}
				$meetfieldnames[] = "created_by";
				$meetfieldnames[] = "meetstate";
			}
			
			// If there is no validated fields, there is a problem...
			if (! count ( $meetfieldnames )) {
				$msg .= "<p>Error parsing column names. Are you sure this is a proper csv export ?<br />try to export first to get an example of formatting</p>\n";
				$this->setRedirect ( 'index.php?option=com_freestroke&view=meets', $msg, 'error' );
				return;
			} else {
				$msg .= "<p>" . $numfields . " fields found in first row</p>\n";
				$msg .= "<p>" . count ( $meetfieldnames ) . " fields were kept</p>\n";
			}
			
			// Now get the records, meaning the rest of the rows.
			$meetrecords = array ();
			$sessionrecords = array ();
			$row = 1;
			while ( ($data = fgetcsv ( $handle, 10000, ';', '"' )) !== FALSE ) {
				$num = count ( $data );
				if ($numfields != $num) {
					$msg .= "<p>Wrong number of fields ($num) record $row<br /></p>\n";
				} else {
					$r = array ();
					// only extract columns with validated header, from previous step.
					foreach ( $meetfieldnames as $k => $v ) {
						$r [] = $this->formatcsvfield ( $v, $data [$k] );
					}
					$r[] = $user->id; // set created_by
					$r[] = 'N';	// set meetstate default value
					$meetrecords [] = $r;
				}
				$row ++;
			}
			fclose ( $handle );
			$msg .= "<p>total records found: " . count ( $records ) . "<br /></p>\n";
			
			// database update
			if (count ( $meetrecords )) {
				$model = $this->getModel ( 'meets' );
				$resultmeets = $model->import ( $meetfieldnames, $meetrecords, $replace );
				$msg .= "<p>total meets added records: " . $resultmeets ['added'] . "<br /></p>\n";
				$msg .= "<p>total meets updated records: " . $resultmeets ['updated'] . "<br /></p>\n";
				
			}
			$this->setRedirect ( 'index.php?option=com_freestroke&view=meets', $msg );
		} else {
			parent::display ();
		}
	}
	
	/**
	 * handle date conversion to prepare for store in DB
	 *
	 * @param
	 *        	string column name
	 * @param string $value        	
	 * @return string
	 */
	function formatcsvfield($type, $value) {
		switch ($type) {
			case 'mindate' :
			case 'maxdate' :
			case 'startdate' :
			case 'deadline' :
				if ($value != '') {
					$date = strtotime ( $value );
					$field = strftime ( '%Y-%m-%d', $date );
				} else {
					$field = null;
				}
				break;
			case 'starttime' :
				if ($value != '') {
					$time = strtotime ( $value );
					$field = strftime ( '%H:%M', $time );
				} else {
					$field = null;
				}
				break;
			default :
				$field = $value;
				break;
		}
		return $field;
	}
}
