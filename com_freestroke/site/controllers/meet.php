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
class FreestrokeControllerMeet extends JControllerLegacy {
	/**
	 * display task
	 *
	 * @return void
	 */
	public function display($cachable = false, $urlparams = false) {
		$view = $this->getView('Meet', 'html');
		$view->setModel($this->getModel('Meet'), true);
		$view->setModel($this->getModel('Meetsessions'));
		$view->setModel($this->getModel('Events'));
		$view->setModel($this->getModel('Entries'));
		$view->setModel($this->getModel('Relayentries'));
		$view->setModel($this->getModel('Results'));
		$view->setModel($this->getModel('RelayResults'));
		$view->display();
	}
	
	/**
	 * Method to check out an item for editing and redirect to the edit form.
	 *
	 * @since 1.6
	 */
	public function edit() {
		$app = JFactory::getApplication();
		
		// Get the previous edit id (if any) and the current edit id.
		$previousId = ( int ) $app->getUserState('com_freestroke.edit.meet.id');
		$editId = JFactory::getApplication()->input->getInt('id', null, 'array');
		
		// Set the user id for the user to edit in the session.
		$app->setUserState('com_freestroke.edit.meet.id', $editId);
		
		// Get the model.
// 		$model = $this->getModel('Meet', 'FreestrokeModel');
		
		// Redirect to the edit screen.
		$this->setRedirect(JRoute::_('index.php?option=com_freestroke&view=meetform&layout=edit', false));
	}
	
	/**
	 * Method to save a meet.
	 *
	 * @return void
	 */
	public function save() {
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		// Initialise variables.
		$app = JFactory::getApplication();
		$model = $this->getModel('Meet', 'FreestrokeModel');
		$id = ( int ) $app->getUserState('com_freestroke.edit.meet.id');
		
		// Get the user data.
		$data = JFactory::getApplication()->input->get('jform', array (), 'array');
		
		// Validate the posted data.
		$form = $model->getForm();
		if (! $form) {
			JError::raiseError(500, $model->getError());
			return false;
		}
		
		// Validate the posted data.
		$data = $model->validate($form, $data);
		
		// Check for errors.
		if ($data === false) {
			// Get the validation messages.
			$errors = $model->getErrors();
			
			// Push up to three validation messages out to the user.
			for($i = 0, $n = count($errors); $i < $n && $i < 3; $i ++) {
				if ($errors [$i] instanceof Exception) {
					$app->enqueueMessage($errors [$i]->getMessage(), 'warning');
				} else {
					$app->enqueueMessage($errors [$i], 'warning');
				}
			}
			
			// Save the data in the session.
			$app->setUserState('com_freestroke.edit.meet.data', JRequest::getVar('jform'), array ());
			
			// Redirect back to the edit screen.
			$id = ( int ) $app->getUserState('com_freestroke.edit.meet.id');
			$this->setRedirect(JRoute::_('index.php?option=com_freestroke&view=meet&layout=edit&id=' . $id, false));
			return false;
		}
		
		// Attempt to save the data.
		$return = $model->save($data);
		
		// Check for errors.
		if ($return === false) {
			// Save the data in the session.
			$app->setUserState('com_freestroke.edit.meet.data', $data);
			
			// Redirect back to the edit screen.
			$id = ( int ) $app->getUserState('com_freestroke.edit.meet.id');
			$this->setMessage(JText::sprintf('Save failed', $model->getError()), 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_freestroke&view=meet&layout=edit&id=' . $id, false));
			return false;
		}
		
		// Clear the profile id from the session.
		$app->setUserState('com_freestroke.edit.meet.id', null);
		
		// Redirect to the list screen.
		$this->setMessage(JText::_('COM_FREESTROKE_ITEM_SAVED_SUCCESSFULLY'));
		$this->setRedirect(JRoute::_('index.php?option=com_freestroke&view=meet&layout=edit&id=' . $id, false));
		
		// Flush the data from the session.
		$app->setUserState('com_freestroke.edit.meet.data', null);
	}
	
	/**
	 */
	function cancel() {
		$app = JFactory::getApplication();
		$id = ( int ) $app->getUserState('com_freestroke.edit.meet.id');
		$this->setRedirect(JRoute::_('index.php?option=com_freestroke&view=meet&layout=edit&id=' . $id, false));
	}
	
	/**
	 *
	 * @return boolean
	 */
	public function remove() {
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		// Initialise variables.
		$app = JFactory::getApplication();
		$model = $this->getModel('Meet', 'FreestrokeModel');
		
		// Get the user data.
		$data = JFactory::getApplication()->input->get('jform', array (), 'array');
		
		// Validate the posted data.
		$form = $model->getForm();
		if (! $form) {
			JError::raiseError(500, $model->getError());
			return false;
		}
		
		// Attempt to save the data.
		$return = $model->delete($data);
		
		// Check for errors.
		if ($return === false) {
			// Save the data in the session.
			$app->setUserState('com_freestroke.edit.meet.data', $data);
			
			// Redirect back to the edit screen.
			$id = ( int ) $app->getUserState('com_freestroke.edit.meet.id');
			$this->setMessage(JText::sprintf('Delete failed', $model->getError()), 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_freestroke&view=meet&layout=edit&id=' . $id, false));
			return false;
		}
		
		// Clear the profile id from the session.
		$app->setUserState('com_freestroke.edit.meet.id', null);
		
		// Redirect to the list screen.
		$this->setMessage(JText::_('COM_FREESTROKE_ITEM_DELETED_SUCCESSFULLY'));
		$this->setRedirect(JRoute::_($_SERVER ['HTTP_REFERER'], false));
		
		// Flush the data from the session.
		$app->setUserState('com_freestroke.edit.meet.data', null);
	}
	
	/**
	 * import members and program from lxf file
	 *
	 * @return void boolean
	 */
	function lxfimportinvitation() {
		$app = JFactory::getApplication();
		
		JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_freestroke/tables');
		
		$msg = '';
		$itemid = $app->input->get('itemid');
		if ($file = $app->input->files->get('importfile')) {
			$handle = fopen($file ['tmp_name'], 'r');
			if (! $handle) {
				$app->enqueueMessage(JText::_('Cannot open uploaded file.'));
				return;
			}
			fclose($handle);
			
			// parse the lenex file
			$xmlFile = $this->extractXMLfromArchive($file ['tmp_name']);
			$lenexXml = simplexml_load_file($xmlFile);
			if ($lenexXml === FALSE) {
				$app->enqueueMessage("Dit bestand wordt niet herkend. Is het wel een LXF bestand?");
			} else {
				require_once JPATH_COMPONENT . '/logic/lenexparser.php';
				$parser = new FreestrokeLenexParser();
				$lenex = $parser->parse($lenexXml);
				
				require_once JPATH_COMPONENT . '/logic/invitationreader.php';
				$reader = new FreestrokeInvitationReader();
				$hasinvite = $reader->process($lenex, $itemid);
				
				// Flush the data from the session.
// 				$app->setUserState('com_freestroke.edit.meet.data', null);
			}
		}
		
		$this->setRedirect(JRoute::_('index.php?option=com_freestroke&view=meet&id=' . $itemid, $msg));
	}
	
	/**
	 * import meet entries from lxf file
	 *
	 * @return void boolean
	 */
	function lxfimportentries() {
		$app = JFactory::getApplication();
		
		JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_freestroke/tables');
		
		$msg = '';
		$itemid = $app->input->get('itemid');
	
		if ($file = $app->input->files->get('importfile')) {
			$handle = fopen($file ['tmp_name'], 'r');
			if (! $handle) {
				$app->enqueueMessage(JText::_('Cannot open uploaded file.'));
				return;
			}
			fclose($handle);
			
			// parse the lenex file
			$xmlFile = $this->extractXMLfromArchive($file ['tmp_name']);
			$lenexXml = simplexml_load_file($xmlFile);
			if ($lenexXml === FALSE) {
				$app->enqueueMessage("Dit bestand wordt niet herkend. Is het wel een LXF bestand?");
			} else {
				require_once JPATH_COMPONENT . '/logic/lenexparser.php';
				$parser = new FreestrokeLenexParser();
				$lenex = $parser->parse($lenexXml);
				
				require_once JPATH_COMPONENT . '/logic/invitationreader.php';
				$reader = new FreestrokeInvitationReader();
				$hasinvite = $reader->process($lenex, $itemid);
				
				require_once JPATH_COMPONENT . '/logic/entriesreader.php';
				$componentParams = &JComponentHelper::getParams('com_freestroke');
				$clubcode = $componentParams->get('associationcode', null);
				if ($clubcode != null && strlen($clubcode) > 0) {
					$reader = new FreestrokeEntriesReader();
					$hasentries = $reader->process($lenex, $itemid, $clubcode);
				} else {
					$app->enqueueMessage("De KNZB Club code is niet ingevuld. Neem contact op met de website beheerder.");
				}
			}
		}
		
		$this->setRedirect(JRoute::_('index.php?option=com_freestroke&view=meet&id=' . $itemid, $msg));
	}
	
	/**
	 * import results from lxf file
	 *
	 * @return void boolean
	 */
	function lxfimportresults() {
		$app = JFactory::getApplication();
		
		JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_freestroke/tables');
		
		$msg = '';
		$itemid = $app->input->get('itemid');
		$overwriteprogram = $app->input->get('overwriteprogram');
		$deleteresults = $app->input->get('deleteresults');
		$deletefirst = false;
		if (! empty($deleteresults)) {
			$deletefirst = true;
		}
		
		if ($file = $app->input->files->get('importfile')) {
			$handle = fopen($file ['tmp_name'], 'r');
			if (! $handle) {
			    $app->enqueueMessage(JText::_('Cannot open uploaded file.'));
				return;
			}
			fclose($handle);
			
			// parse the lenex file
			$xmlFile = $this->extractXMLfromArchive($file ['tmp_name']);
			$lenexXml = simplexml_load_file($xmlFile);
			if ($lenexXml === false) {
				$app->enqueueMessage("Dit bestand wordt niet herkend. Is het wel een LXF bestand?");
			} else {
				require_once JPATH_COMPONENT . '/logic/lenexparser.php';
				$parser = new FreestrokeLenexParser();
				$lenex = $parser->parse($lenexXml);
				
				if (! empty($overwriteprogram)) {
					require_once JPATH_COMPONENT . '/logic/invitationreader.php';
					$reader = new FreestrokeInvitationReader();
					$hasinvite = $reader->process($lenex, $itemid);
				}
				
				require_once JPATH_COMPONENT . '/logic/resultsreader.php';
				$componentParams = &JComponentHelper::getParams('com_freestroke');
				$clubcode = $componentParams->get('associationcode', null);
				$clubname = $componentParams->get('clubname', null);
				if ($clubcode != null && strlen($clubcode) > 0) {
					$reader = new FreestrokeResultsReader();
					$hasresults = $reader->process($lenex, $itemid, $clubcode, $clubname, $deletefirst);
				}
				// Flush the data from the session.
// 				$app->setUserState('com_freestroke.edit.meet.data', null);
			}
		}
		
		$this->setRedirect(JRoute::_('index.php?option=com_freestroke&view=meet&id=' . $data ["id"], $msg));
	}
	
	/**
	 * Extract the lenex xml file from the archive
	 *
	 * @param unknown $archive        	
	 * @return string
	 */
	private function extractXMLfromArchive($archive) {
		$za = new ZipArchive();
		$zaresult = $za->open($archive);
		if ($zaresult === TRUE) {
			for($i = 0; $i < $za->numFiles; $i ++) {
				$stat = $za->statIndex($i);
				JLog::add('Uploaded lenex archive contains file ' . basename($stat ['name']), JLog::WARNING, 'com_freestroke');
				
				if (preg_match('/.?\.lef$/', basename($stat ['name'])) == 1) {
					$filename = dirname($archive) . DIRECTORY_SEPARATOR . basename($stat ['name']);
					$za->extractTo(dirname($archive), basename($stat ['name']));
				}
			}
		} else {
			if ($zaresult == ZipArchive::ER_NOZIP) {
				$filename = $archive;
			} else {
				$filename = null;
			}
		}
		return $filename;
	}
}
