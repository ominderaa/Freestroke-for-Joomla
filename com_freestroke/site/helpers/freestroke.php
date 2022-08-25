<?php
/**
 * @version     1.0.0
 * @package     com_freestroke
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      O.Minderaa <ominderaa@gmail.com> - http://
 */
defined('_JEXEC') or die();
abstract class FreestrokeHelper {
	
	/**
	 *
	 * @param unknown $gender        	
	 * @param unknown $minage        	
	 * @param unknown $maxage        	
	 * @return string
	 */
	public static function MapAgeCategory($gender, $minage, $maxage) {
		if ($gender == 'M') {
			if ($minage == $maxage) {
				return FreestrokeHelper::mapGenderMale($gender, $minage) . ' ' . FreestrokeHelper::mapMiniorenAgeMale($minage);
			} else {
				$mincat = FreestrokeHelper::mapMiniorenAgeMale($minage);
				$maxcat = FreestrokeHelper::mapMiniorenAgeMale($maxage);
				if (strncmp($mincat, $maxcat, 5) == 0) {
					$cat = $mincat . ' -' . substr($maxcat, strpos($maxcat, ' '));
				} else {
					$cat = $mincat . ' - ' . $maxcat;
				}
				
				return FreestrokeHelper::mapGenderMale($gender, $minage) . ' ' . $cat;
			}
		} else if ($gender == 'F') {
			if ($minage == $maxage) {
				return FreestrokeHelper::mapGenderFemale($gender, $minage) . ' ' . FreestrokeHelper::mapMiniorenAgeFemale($minage);
			} else {
				$mincat = FreestrokeHelper::mapMiniorenAgeFemale($minage);
				$maxcat = FreestrokeHelper::mapMiniorenAgeFemale($maxage);
				if (strncmp($mincat, $maxcat, 5) == 0) {
					$cat = $mincat . ' -' . substr($maxcat, strpos($maxcat, ' '));
				} else {
					$cat = $mincat . ' - ' . $maxcat;
				}
				return FreestrokeHelper::mapGenderFemale($gender, $minage) . ' ' . $cat;
			}
		} else {
			if ($minage == $maxage) {
				return FreestrokeHelper::mapGenderFemale($gender, $minage) . ' ' . FreestrokeHelper::mapMiniorenAgeFemale($minage);
			} else {
				$mincat = FreestrokeHelper::mapMiniorenAgeFemale($minage);
				$maxcat = FreestrokeHelper::mapMiniorenAgeMale($maxage);
				if (strncmp($mincat, $maxcat, 5) == 0) {
					$cat = $mincat . ' -' . substr($maxcat, strpos($maxcat, ' '));
				} else {
					$cat = $mincat . ' - ' . $maxcat;
				}
				return JText::_('COM_FREESTROKE_GENDER_X') . ' ' . $cat;
			}
			
		}
	}
	
	/**
	 * 
	 * @param unknown $age
	 */
	private static function mapgenderMale($age) {
		if ($age < 18)
			return JText::_('COM_FREESTROKE_GENDER_MALE_YOUNG');
		else
			return JText::_('COM_FREESTROKE_GENDER_MALE');
	}
	private static function mapgenderFemale($age) {
		if ($age < 16)
			return JText::_('COM_FREESTROKE_GENDER_FEMALE_YOUNG');
		else
			return JText::_('COM_FREESTROKE_GENDER_FEMALE');
	}
	
	/**
	 *
	 * @param unknown $age        	
	 * @return Ambigous <string>
	 */
	private static function mapMiniorenAgeMale($age) {
		if ($age >= 5) {
			$agemap = array (
					5 => 'minioren 1',
					6 => 'minioren 1',
					7 => 'minioren 2',
					8 => 'minioren 3',
					9 => 'minioren 4',
					10 => 'minioren 5',
					11 => 'minioren 6',
					12 => 'junioren 1',
					13 => 'junioren 2',
					14 => 'junioren 3',
					15 => 'junioren 4',
					16 => 'jeugd 1',
					17 => 'jeugd 2',
					18 => 'senioren 1',
					19 => 'senioren 2',
					20 => 'senioren open' 
			);
			if ($age > 20)
				return ($agemap [20]);
			else
				return ($agemap [$age]);
		}
		return "";
	}
	
	/**
	 *
	 * @param unknown $age        	
	 * @return Ambigous <string>
	 */
	private static function mapMiniorenAgeFemale($age) {
		if ($age >= 5) {
			$agemap = array (
					5 => 'minioren 1',
					6 => 'minioren 1',
					7 => 'minioren 2',
					8 => 'minioren 3',
					9 => 'minioren 4',
					10 => 'minioren 5',
					11 => 'junioren 1',
					12 => 'junioren 2',
					13 => 'junioren 3',
					14 => 'jeugd 1',
					15 => 'jeugd 2',
					16 => 'senioren 1',
					17 => 'senioren 2',
					18 => 'senioren open' 
			);
			if ($age > 18)
				return ($agemap [18]);
			else
				return ($agemap [$age]);
		}
		return "";
	}
	
	/**
	 * 
	 */
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

