<?php
/**
 * @version     1.0.0
 * @package     com_freestroke
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      O.Minderaa <ominderaa@gmail.com> - http://
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once (dirname(__FILE__).'/helper.php');
$items = modFreestrokecalHelper::getMeetsList($params);
$maxitems = $params->get('maxitems', '2');

$eventlistintegration = $params->get('eventlistintegration', false);
if ($eventlistintegration) {
	$jemModule = modFreestrokecalHelper::getEventlistModulePath() . '/mod_jem.php';
	if (file_exists($jemModule)) {

		require_once(JPATH_SITE.'/components/com_jem/helpers/route.php');
		require_once(JPATH_SITE.'/components/com_jem/helpers/helper.php');
		require_once(JPATH_SITE.'/components/com_jem/classes/output.class.php');
		require_once(JPATH_SITE.'/components/com_jem/factory.php');

		$jemitems = modFreestrokecalHelper::getJEMEventList($params);
		$items = modFreestrokecalHelper::mergeEvents($items, $jemitems, $maxitems);
	} else {
		$items = modFreestrokecalHelper::mergeEvents($items, $jemitems, $maxitems);
	}
}
require (JModuleHelper::getLayoutPath('mod_freestrokecal'));
?>