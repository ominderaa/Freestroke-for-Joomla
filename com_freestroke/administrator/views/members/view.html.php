<?php
/**
 * @version     1.0.0
 * @package     com_freestroke
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      O.Minderaa <ominderaa@gmail.com> - http://
 */

// No direct access
defined ( '_JEXEC' ) or die ();

jimport ( 'joomla.application.component.view' );

/**
 * View class for a list of Freestroke.
 */
class FreestrokeViewMembers extends JViewLegacy {
	protected $items;
	protected $pagination;
	protected $state;
	
	/**
	 * Display the view
	 */
	public function display($tpl = null) {
		$this->state = $this->get ( 'State' );
		$this->items = $this->get ( 'Items' );
		$this->pagination = $this->get ( 'Pagination' );
		
		// Check for errors.
		if (count ( $errors = $this->get ( 'Errors' ) )) {
			throw new Exception ( implode ( "\n", $errors ) );
		}
		
		$this->addToolbar ();
		
		$input = JFactory::getApplication ()->input;
		$view = $input->getCmd ( 'view', '' );
		FreestrokeHelper::addSubmenu ( $view );
		
		$this->sidebar = JHtmlSidebar::render();
		parent::display ( $tpl );
	}
	
	/**
	 * Add the page title and toolbar.
	 *
	 * @since 1.6
	 */
	protected function addToolbar() {
		require_once JPATH_COMPONENT . '/helpers/freestroke.php';
		
		$state = $this->get ( 'State' );
		$canDo = FreestrokeHelper::getActions ( $state->get ( 'filter.category_id' ) );
		
		JToolBarHelper::title ( JText::_ ( 'COM_FREESTROKE_TITLE_MEMBERS' ), 'members.png' );
		
		// Check if the form exists before showing the add/edit buttons
		$formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/member';
		if (file_exists ( $formPath )) {
			if ($canDo->get ( 'core.create' )) {
				JToolBarHelper::addNew ( 'member.add', 'JTOOLBAR_NEW' );
			}
			
			if ($canDo->get ( 'core.edit' ) && isset ( $this->items [0] )) {
				JToolBarHelper::editList ( 'member.edit', 'JTOOLBAR_EDIT' );
			}
		}
		
		if ($canDo->get ( 'core.edit.state' )) {
			if (isset ( $this->items [0] )) {
				// If this component does not use state then show a direct delete button as we can not trash
				JToolBarHelper::deleteList ( '', 'members.delete', 'JTOOLBAR_DELETE' );
			}
		}
		
		if ($canDo->get ( 'core.admin' )) {
			JToolBarHelper::preferences ( 'com_freestroke' );
		}

		// Set sidebar action - New in 3.0
		JHtmlSidebar::setAction('index.php?option=com_freestroke&view=meets');
	}
}
