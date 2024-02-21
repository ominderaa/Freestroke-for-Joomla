<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;

/**
 * Script file ofFreestroke component.
 *
 * The name of this class is dependent on the component being installed.
 * The class name should have the component's name, directly followed by
 * the text InstallerScript (ex:. com_helloWorldInstallerScript).
 *
 * This class will be called by Joomla!'s installer, if specified in your component's
 * manifest file, and is used for custom automation actions in its installation process.
 *
 * In order to use this automation script, you should reference it in your component's
 * manifest file as follows:
 * <scriptfile>script.php</scriptfile>
 *
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
class com_FreestrokeInstallerScript
{
    /**
     * This method is called after a component is installed.
     *
     * @param  \stdClass $parent - Parent object calling this method.
     *
     * @return void
     */
    public function install($parent) 
    {
    }

    /**
     * This method is called after a component is uninstalled.
     *
     * @param  \stdClass $parent - Parent object calling this method.
     *
     * @return void
     */
    public function uninstall($parent) 
    {
    }

    /**
     * This method is called after a component is updated.
     *
     * @param  \stdClass $parent - Parent object calling object.
     *
     * @return void
     */
    public function update($parent) 
    {
        JFolder::delete(JPATH_ROOT.'/components/com_freestroke/assets/scripts');
        JFile::delete(JPATH_ROOT.'/components/com_freestroke/assets/css/mobile.css');

        JFile::delete(JPATH_ROOT.'/components/com_freestroke/controllers/mailer.php');
        JFile::delete(JPATH_ROOT.'/components/com_freestroke/controllers/meetcoaching.php');
        JFile::delete(JPATH_ROOT.'/components/com_freestroke/controllers/member.php');
        JFile::delete(JPATH_ROOT.'/components/com_freestroke/controllers/memberform.php');
        JFile::delete(JPATH_ROOT.'/components/com_freestroke/controllers/members.php');
        JFile::delete(JPATH_ROOT.'/components/com_freestroke/controllers/mobile.php');

        JFile::delete(JPATH_ROOT.'/components/com_freestroke/logic/entriesmailer.php');
        
        JFile::delete(JPATH_ROOT.'/components/com_freestroke/models/mailer.php');
        JFile::delete(JPATH_ROOT.'/components/com_freestroke/models/member.php');
        JFile::delete(JPATH_ROOT.'/components/com_freestroke/models/memberform.php');
        JFile::delete(JPATH_ROOT.'/components/com_freestroke/models/members.php');
        JFile::delete(JPATH_ROOT.'/components/com_freestroke/models/personalrecords.php');
        JFile::delete(JPATH_ROOT.'/components/com_freestroke/models/records.php');

        JFolder::delete(JPATH_ROOT.'/components/com_freestroke/views/meetcoaching');
        JFolder::delete(JPATH_ROOT.'/components/com_freestroke/views/member');
        JFolder::delete(JPATH_ROOT.'/components/com_freestroke/views/memberform');
        JFolder::delete(JPATH_ROOT.'/components/com_freestroke/views/members');
        JFolder::delete(JPATH_ROOT.'/components/com_freestroke/views/mobile');
        JFile::delete(JPATH_ROOT.'/components/com_freestroke/views/meetresults/view.json.php');
        JFile::delete(JPATH_ROOT.'/components/com_freestroke/views/meetscalendar/view.json.php');
        JFile::delete(JPATH_ROOT.'/components/com_freestroke/views/meetentries/view.json.php');
        JFile::delete(JPATH_ROOT.'/components/com_freestroke/views/meetsresults/view.json.php');

        JFile::delete(JPATH_ADMINISTRATOR.'/components/com_freestroke/controllers/venue.php');
        JFile::delete(JPATH_ADMINISTRATOR.'/components/com_freestroke/controllers/venues.php');
        JFile::delete(JPATH_ADMINISTRATOR.'/components/com_freestroke/models/venue.php');
        JFile::delete(JPATH_ADMINISTRATOR.'/components/com_freestroke/models/venues.php');
        JFile::delete(JPATH_ADMINISTRATOR.'/components/com_freestroke/models/forms/venue.xml');

        JFile::delete(JPATH_ADMINISTRATOR.'/components/com_freestroke/tables/venue.php');

        JFolder::delete(JPATH_ADMINISTRATOR.'/components/com_freestroke/views/venue');
        JFolder::delete(JPATH_ADMINISTRATOR.'/components/com_freestroke/views/venues');
    }

    /**
     * Runs just before any installation action is performed on the component.
     * Verifications and pre-requisites should run in this function.
     *
     * @param  string    $type   - Type of PreFlight action. Possible values are:
     *                           - * install
     *                           - * update
     *                           - * discover_install
     * @param  \stdClass $parent - Parent object calling object.
     *
     * @return void
     */
    public function preflight($type, $parent) 
    {
    }

    /**
     * Runs right after any installation action is performed on the component.
     *
     * @param  string    $type   - Type of PostFlight action. Possible values are:
     *                           - * install
     *                           - * update
     *                           - * discover_install
     * @param  \stdClass $parent - Parent object calling object.
     *
     * @return void
     */
    function postflight($type, $parent) 
    {
    }
}