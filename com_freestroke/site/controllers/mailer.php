<?php
/**
 * @version     1.0.0
 * @package     com_freestroke
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      O.Minderaa <ominderaa@gmail.com> - http://
 */

// No direct access
defined('_JEXEC') or die();

// require_once JPATH_COMPONENT . '/controller.php';
jimport('joomla.application.component.controller');
jimport('joomla.log.log');

/**
 * Meet controller class.
 */
class FreestrokeControllerMailer extends JControllerLegacy {
	/**
	 * display task
	 *
	 * @return void
	 */
	public function display($cachable = false, $urlparams = false) {
		$view = $this->getView('Mailer', 'html');
		$view->setModel($this->getModel('Mailer'), true);
		$view->display();
	}
	

	/**
	 */
	function cancel() {
		$app = JFactory::getApplication();
		$id = ( int ) $app->getUserState('com_freestroke.edit.meet.id');
		$this->setRedirect(JRoute::_('index.php?option=com_freestroke&view=meet&layout=edit&id=' . $id, false));
	}
	
	public function domailing()
	{
		$app = JFactory::getApplication();
		
		JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_freestroke/tables');
		
		$msg = '';
		$itemid = JRequest::getVar('itemid');
		$reminder = JRequest::getVar('reminder');
		$correction = JRequest::getVar('correction');
		
		$mailer = JFactory::getMailer();
		$config = JFactory::getConfig();
		$sender = array(
				$config->getValue( 'config.mailfrom' ),
				$config->getValue( 'config.fromname' ) );
		
		$mailer->setSender($sender);
		$user = JFactory::getUser();
		$recipient = 'onnominderaa@hotmail.com';
		$mailer->addRecipient($recipient);

		$body   = '<h2>Je bent opgesteld</h2>'
		    . '<div>Je bent opgesteld voor een wedstrijd</div>';
		$mailer->isHTML(true);
		$mailer->Encoding = 'base64';
		$mailer->setBody($body);
		$send = $mailer->Send();
		if ( $send !== true ) {
			$msg = 'Error sending email: ' . $send->__toString();
		} else {
			$msg = 'Mail sent';
		}		
		$this->setRedirect(JRoute::_('index.php?option=com_freestroke&view=meet&id=' . $data ["id"], $msg));
	}
	

}
