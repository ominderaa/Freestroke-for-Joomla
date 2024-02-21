<?php
/**
 * @version     1.0.0
 * @package     com_freestroke
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      O.Minderaa <ominderaa@gmail.com> - http://
 */
defined('_JEXEC') or die();
abstract class FreestrokeAgecategoryHelper {
	
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
				return FreestrokeAgecategoryHelper::mapGenderMale($gender, $minage) . ' ' . FreestrokeAgecategoryHelper::mapMiniorenAgeMale($minage);
			} else {
				$mincat = FreestrokeAgecategoryHelper::mapMiniorenAgeMale($minage);
				$maxcat = FreestrokeAgecategoryHelper::mapMiniorenAgeMale($maxage);
				if (strncmp($mincat, $maxcat, 5) == 0) {
					$cat = $mincat . ' -' . substr($maxcat, strpos($maxcat, ' '));
				} else {
					$cat = $mincat . ' - ' . $maxcat;
				}
				
				return FreestrokeAgecategoryHelper::mapGenderMale($gender, $minage) . ' ' . $cat;
			}
		} else if ($gender == 'F') {
			if ($minage == $maxage) {
				return FreestrokeAgecategoryHelper::mapGenderFemale($gender, $minage) . ' ' . FreestrokeAgecategoryHelper::mapMiniorenAgeFemale($minage);
			} else {
				$mincat = FreestrokeAgecategoryHelper::mapMiniorenAgeFemale($minage);
				$maxcat = FreestrokeAgecategoryHelper::mapMiniorenAgeFemale($maxage);
				if (strncmp($mincat, $maxcat, 5) == 0) {
					$cat = $mincat . ' -' . substr($maxcat, strpos($maxcat, ' '));
				} else {
					$cat = $mincat . ' - ' . $maxcat;
				}
				return FreestrokeAgecategoryHelper::mapGenderFemale($gender, $minage) . ' ' . $cat;
			}
		} else {
			if ($minage == $maxage) {
				return JText::_('COM_FREESTROKE_GENDER_X');
			} else {
				$mincat = FreestrokeAgecategoryHelper::mapMiniorenAgeFemale($minage);
				$maxcat = FreestrokeAgecategoryHelper::mapMiniorenAgeMale($maxage);
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
					14 => 'junioren 4',
					15 => 'jeugd 1',
					16 => 'jeugd 2',
					17 => 'senioren open' 
			);
			if ($age > 17)
				return ($agemap [17]);
			else
				return ($agemap [$age]);
		}
		return "";
	}
	
}

