<?php
/**
 * @version     1.0.0
 * @package     com_freestroke
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      O.Minderaa <ominderaa@gmail.com> - http://
 */
defined('_JEXEC') or die();
abstract class FreestrokeSeasonHelper {
	
	/**
	 *
	 * @param unknown $dateofmeet        	
	 * @return multitype:unknown
	 */
	public static function getSeasonForDate($dateofmeet) {
		$startDate = JDate::getInstance($dateofmeet);
		$startDate->setTime(0, 0, 0);
		$startDate->setDate($startDate->year, 8, 1);
		if ($dateofmeet < $startDate) {
			$startDate->setDate($startDate->year - 1, 8, 1);
		}
		$endDate = JDate::getInstance($startDate);
		$endDate->setDate($startDate->year + 1, 7, 31);
		$season = new StdClass();
		$season->startdate = $startDate;
		$season->enddate = $endDate;
		return $season;
	}

	/**
	 *
	 * @param unknown $startyear
	 */
	public static function getSeasonStartEnd($startyear) {
		$startDate = JDate::getInstance($startyear . '-08-01');
		$startDate->setTime(0, 0, 0);
		$endDate = JDate::getInstance($startDate);
		$endDate->setDate($startDate->year + 1, 7, 31);
	
		$season = new StdClass();
		$season->startdate = $startDate;
		$season->enddate = $endDate;
		return $season;
	}
}

