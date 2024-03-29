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

jimport('joomla.application.component.controller');

/**
 * Member controller class.
 */
class FreestrokeControllerMeetsession extends JControllerLegacy {
	
	/**
	 * Method to check out an item for editing and redirect to the edit form.
	 *
	 * @since 1.6
	 */
	public function edit() {
		$app = JFactory::getApplication();
		
		// Get the previous edit id (if any) and the current edit id.
// 		$previousId = ( int ) $app->getUserState('com_freestroke.edit.meetsession.id');
		$editId = JFactory::getApplication()->input->getInt('id', null, 'array');
		
		// Set the user id for the user to edit in the session.
		$app->setUserState('com_freestroke.edit.meetsession.id', $editId);
		
		// Get the model.
// 		$model = $this->getModel('Meetsession', 'FreestrokeModel');
		
		$returnView = JRequest::getVar('return', null, 'default', 'base64');
		if(empty($returnView)) {
			$returnView = "meet";
		}
		$app->setUserState('com_freestroke.edit.meetsession.return', $returnView);
		

		// Redirect to the edit screen.
		$this->setRedirect(JRoute::_('index.php?option=com_freestroke&view=meetsessionform&layout=edit', false));
	}
	
	/**
	 * Method to save a member to the database.
	 *
	 * @return void
	 * @since 1.6
	 */
	public function save() {
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		// Initialise variables.
		$app = JFactory::getApplication();
		$model = $this->getModel('Meetsession', 'FreestrokeModel');
		
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
			$app->setUserState('com_freestroke.edit.meetsession.data', JRequest::getVar('jform'), array ());
			
			// Redirect back to the edit screen.
			$id = ( int ) $app->getUserState('com_freestroke.edit.meetsession.id');
			$this->setRedirect(JRoute::_('index.php?option=com_freestroke&view=meetsession&layout=edit&id=' . $id, false));
			return false;
		}
		
		// Attempt to save the data.
		$return = $model->save($data);
		
		// Check for errors.
		if ($return === false) {
			// Save the data in the session.
			$app->setUserState('com_freestroke.edit.meetsession.data', $data);
			
			// Redirect back to the edit screen.
			$id = ( int ) $app->getUserState('com_freestroke.edit.meetsession.id');
			$this->setMessage(JText::sprintf('Save failed', $model->getError()), 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_freestroke&view=meetsession&layout=edit&id=' . $id, false));
			return false;
		}
		
	
		// Clear the profile id from the session.
		$app->setUserState('com_freestroke.edit.meetsession.id', null);
		
		// Redirect to the list screen.
		$this->setMessage(JText::_('COM_FREESTROKE_ITEM_SAVED_SUCCESSFULLY'));
		$menu = & JSite::getMenu();
		$item = $menu->getActive();
		$this->setRedirect(JRoute::_($item->link, false));
		
		// Flush the data from the session.
		$app->setUserState('com_freestroke.edit.meetsession.data', null);
	}
	
	/**
	 * 
	 */
	function cancel() {
		$menu = & JSite::getMenu();
		$item = $menu->getActive();
		$this->setRedirect(JRoute::_($item->link, false));
	}
}