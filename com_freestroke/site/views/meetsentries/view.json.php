<?php
/**
 * @version     1.0.0
 * @package     com_freestroke
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      O.Minderaa <ominderaa@gmail.com> - http://
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Freestroke.
 */
class FreestrokeViewMeetsentries extends JView
{
	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
        $document =& JFactory::getDocument();
        
        // Set the MIME type for JSON output.
        $document->setMimeEncoding('application/json');
        
        // Change the suggested filename.
        JResponse::setHeader('Content-Disposition','attachment;filename="meetscalendar.json"');
        
        // Output the JSON data.
        echo json_encode($this->get('Items'));   
        jexit();     
	}

}
