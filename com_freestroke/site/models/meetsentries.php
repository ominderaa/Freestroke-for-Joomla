<?php

/**
 * @version     1.0.0
 * @package     com_freestroke
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      O.Minderaa <ominderaa@gmail.com> - http://
 */
defined('_JEXEC') or die();

jimport('joomla.application.component.modellist');
jimport('joomla.date.date');

/**
 * Methods supporting a list of Freestroke Meets that have entries.
 */
class FreestrokeModelMeetsentries extends JModelList
{
    /**
     * Constructor.
     *
     * @param
     *            array An optional associative array of configuration settings.
     * @see JController
     * @since 1.6
     */
    public function __construct($config = array())
    {
        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since 1.6
     */
    protected function populateState($ordering = null, $direction = null)
    {
        
        // Initialise variables.
        $app = JFactory::getApplication();
        
        // List state information
        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));
        $this->setState('list.limit', $limit);
        
        $limitstart = JFactory::getApplication()->input->getInt('limitstart', 0);
        $this->setState('list.start', $limitstart);
        
        // List state information.
        parent::populateState($ordering, $direction);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return JDatabaseQuery
     * @since 1.6
     */
    protected function getListQuery()
    {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        
        // Select the required fields from the table.
        $query->select($this->getState('list.select', 'm.*'));
        
        $query->from('`#__freestroke_meets` AS m');
        
        $now = JDate::getInstance('now');
        $now->setTime(0, 0, 0);
        
        $query->where('m.mindate >= ' . $db->Quote($season->startdate))
            ->where('exists (select distinct 1 from `#__freestroke_entries` he where he.meets_id = m.id)', 'and');
        $query->order(array(
            'mindate ASC',
            'id ASC'
        ));
        
        return $query;
    }
}
