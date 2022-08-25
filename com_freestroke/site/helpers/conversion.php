<?php
/**
 * @version     1.0.0
* @package     com_freestroke
* @copyright   Copyright (C) 2013. All rights reserved.
* @license     GNU General Public License version 2 or later; see LICENSE.txt
* @author      O.Minderaa <ominderaa@gmail.com> - http://
*/
defined ( '_JEXEC' ) or die ();
abstract class FreestrokeConversionHelper {
	/**
	 * Formats a result time in 1/100 secs to string
	 *
	 * @param int $timeinhsecs        	
	 * @return string
	 */
	public static function formatSwimtime($timeinhsecs) {
		$hours = intval($timeinhsecs / 360000);
		$leftover = $timeinhsecs - ($hours * 360000);
		$minutes = intval($leftover / 6000);
		$leftover = $leftover - ($minutes * 6000);
		$seconds = intval($leftover / 100);
		$decsecs = $leftover - ($seconds * 100);
		if ($timeinhsecs >= 360000) {
			$result = sprintf("%d:%02d:%02d.%02d", $hours, $minutes, $seconds, $decsecs);
		} else if ($timeinhsecs >= 6000) {
			$result = sprintf("%d:%02d.%02d", $minutes, $seconds, $decsecs);
		} else if ($timeinhsecs >= 100) {
			$result = sprintf("%d.%02d", $seconds, $decsecs);
		} else {
			$result = sprintf("%d.%02d", $seconds, $decsecs);
		}
		return $result;
	}
	
	/**
	 * parses an entry time in string format "HH:MM:SS:DD" into an int of 1/100 cecods
	 *
	 * @param string $entrytime        	
	 * @return number
	 */
	public static function parseSwimtime($entrytime) {
		$time = 0;
		if (strlen($entrytime) && $entrytime != 'NT') {
			$parts = preg_split("/[:\.]/", $entrytime);
			$time = intval($parts [0]) * 360000 + 			// 1 hour = 360000
			intval($parts [1]) * 6000 + 			// 1 minute = 6000
			intval($parts [2]) * 100 + 			// 1 second
			intval($parts [3]); // 1/100th of a second
		}
		return $time;
	}

	/**
	 *
	 * @param string $value        	
	 * @return NULL
	 */
	public static function parseDate($value) {
		if ($value != '') {
			$field = DateTime::createFromFormat ( 'Y-m-d', $value );
		} else {
			$field = null;
		}
		return $field;
	}
	/**
	 *
	 * @param string $value        	
	 * @return NULL
	 */
	public static function parseDateTime($timevalue) {
		if ($timevalue != '') {
			$field = DateTime::createFromFormat ( 'Y-m-d H:i', $timevalue );
			if (! $field) {
				$parseError = DateTime::getLastErrors ();
				echo $parseError;
			}
		} else {
			$field = null;
		}
		return $field;
	}

	public static function format($format, $value) {
		setlocale(LC_TIME, 'NL_nl');
		return strftime( $format, strtotime($value) );
	}
	
	public static function formatDate($value) {
		return strftime( '%d-%m-%Y', strtotime($value) );
	}
}

