<?php
defined('JPATH_BASE') or die();

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * Custom Field class for the Joomla Framework.
 *
 * @package Joomla.Administrator
 * @subpackage com_my
 * @since 1.6
 */
class JFormFieldSeason extends JFormFieldList {
	/**
	 * The form field type.
	 *
	 * @var string
	 * @since 1.6
	 */
	protected $type = 'season';

	/**
	 * Method to get the field options.
	 *
	 * @return array The field option objects.
	 * @since 1.6
	 */
	public function getOptions() {
		// Initialize variables.
		$options = array ();
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->select('min(mindate) as firstdate, max(maxdate) as lastdate');
		$query->from('#__freestroke_meets AS a');
		
		// Get the options.
		$db->setQuery($query);
		$meetdates = $db->loadObjectList();
		
		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}
		
		require_once JPATH_COMPONENT . '/helpers/freestroke.php';
		$firstseason = FreestrokeHelper::getSeasonForDate($meetdates[0]->firstdate);
		$lastseason = FreestrokeHelper::getSeasonForDate($meetdates[0]->lastdate);
		
		$season = $firstseason;
		$options = array();
		while ($season->startdate < $lastseason->enddate) {
			$option = new stdClass();
			$option->id = $season->startdate->year;
			$option->name = $season->startdate->year . '-' . $season->enddate->year;
			$options[] = $option;
			
			$season->startdate->setDate($season->startdate->year + 1, $season->startdate->month, $season->startdate->day);
			$season->enddate->setDate($season->enddate->year + 1, $season->startdate->month, $season->startdate->day);
		}
		
		usort($options, array('JFormFieldSeason','compareSeasons'));
		return $options;
	}
	
	function compareSeasons($a, $b) {
		return strcmp($b->name, $a->name);
	}
	
	/**
	 *
	 * @param unknown $dateofmeet        	
	 * @return multitype:unknown
	 */
	protected function getSeasonForDate($dateofmeet) {
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
}