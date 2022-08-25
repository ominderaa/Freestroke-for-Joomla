<?php
/**
 * @version     1.0.0
 * @package     com_freestroke
 * @copyright   Copyright (C) 2013. Alle rechten voorbehouden.
 * @license     GNU General Public License versie 2 of hoger; Zie LICENSE.txt
 * @author      O.Minderaa <ominderaa@gmail.com> - http://
 */

// No direct access.
defined ( '_JEXEC' ) or die ();

jimport ( 'joomla.application.component.controlleradmin' );
use Joomla\Utilities\ArrayHelper;

/**
 * Venues list controller class.
 */
class FreestrokeControllerVenues extends JControllerAdmin {
	/**
	 * Proxy for getModel.
	 * 
	 * @param   string  $name    Optional. Model name
	 * @param   string  $prefix  Optional. Class prefix
	 * @param   array   $config  Optional. Configuration array for model
	 *
	 * @since 1.6
	 */
	public function getModel($name = 'venue', $prefix = 'FreestrokeModel', $config = Array() ) {
		$model = parent::getModel ( $name, $prefix, array ('ignore_request' => true));
		return $model;
	}	
	
	/**
	 * import venues from csv file
	 */
	function csvimport() {
		JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_freestroke/tables');
		$object = & JTable::getInstance('venue', 'FreestrokeTable');
		$object_fields = get_object_vars($object);
		
		$msg = '';
		if ($file = JRequest::getVar ( 'importfile', null, 'files', 'array' )) {
			$handle = fopen ( $file ['tmp_name'], 'r' );
			if (! $handle) {
				$msg = JText::_ ( 'Cannot open uploaded file.' );
				$this->setRedirect ( 'index.php?option=com_freestroke&view=venues', $msg, 'error' );
			}
			
			// get fields, on first row of the file
			$fields = array ();
			if (($data = fgetcsv ( $handle, 1000, ';', '"' )) !== FALSE) {
				$numfields = count ( $data );
				for($c = 0; $c < $numfields; $c ++) {
					// here, we make sure that the field match one of the fields of freestroke_venues table or special fields,
					// otherwise, we don't add it
					if (array_key_exists ( strtolower($data [$c]), $object_fields )) {
						$fields [$c] = strtolower($data [$c]);
					}
				}
			}
			// If there is no validated fields, there is a problem...
			if (! count ( $fields )) {
				$msg .= "<p>Error parsing column names. Are you sure this is a proper csv export ?<br />try to export first to get an example of formatting</p>\n";
				$this->setRedirect ( 'index.php?option=com_freestroke&view=venues', $msg, 'error' );
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
						$r [] = $data[$k];
					}
					$records [] = $r;
				}
				$row ++;
			}
			fclose ( $handle );
			$msg .= "<p>total records found: " . count ( $records ) . "<br /></p>\n";
			
			// database update
			if (count ( $records )) {
				$model = $this->getModel ( 'venues' );
				$result = $model->import ( $fields, $records, $replace );
				$msg .= "<p>total added records: " . $result ['added'] . "<br /></p>\n";
				$msg .= "<p>total updated records: " . $result ['updated'] . "<br /></p>\n";
			}
			$this->setRedirect ( 'index.php?option=com_freestroke&view=venues', $msg );
		} else {
			parent::display ();
		}
	}
}