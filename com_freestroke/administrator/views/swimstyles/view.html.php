<?php
/**
 * @version     1.0.0
 * @package     com_freestroke
 * @copyright   Copyright (C) 2013. Alle rechten voorbehouden.
 * @license     GNU General Public License versie 2 of hoger; Zie LICENSE.txt
 * @author      O.Minderaa <ominderaa@gmail.com> - http://
 */

// No direct access
defined('_JEXEC') or die();

jimport('joomla.application.component.view');

/**
 * View class for a list of Freestrokeswimstyles.
 */
class FreestrokeViewSwimstyles extends JViewLegacy {
	protected $items;
	protected $pagination;
	protected $state;
	
	/**
	 * Display the view
	 */
	public function display($tpl = null) {
		$this->state = $this->get('State');
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new Exception(implode("\n", $errors));
		}
		
		$this->addToolbar();
		
		$input = JFactory::getApplication()->input;
		$view = $input->getCmd('view', '');
		FreestrokeHelper::addSubmenu($view);
		
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}
	
	/**
	 * Add the page title and toolbar.
	 *
	 * @since 1.6
	 */
	protected function addToolbar() {
		require_once JPATH_COMPONENT . '/helpers/freestroke.php';
		
		$state = $this->get('State');
		$canDo = FreestrokeHelper::getActions($state->get('filter.category_id'));
		
		JToolBarHelper::title(JText::_('COM_FREESTROKE_TITLE_SWIMSTYLES'), 'swimstyles.png');
		
		// Check if the form exists before showing the add/edit buttons
		$formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/swimstyle';
		if (file_exists($formPath)) {
			
			if ($canDo->get('core.create')) {
				JToolBarHelper::addNew('swimstyle.add', 'JTOOLBAR_NEW');
			}
			
			if ($canDo->get('core.edit') && isset($this->items [0])) {
				JToolBarHelper::editList('swimstyle.edit', 'JTOOLBAR_EDIT');
			}
		}
		
		if ($canDo->get('core.edit.state')) {
			if (isset($this->items [0])) {
				// If this component does not use state then show a direct delete button as we can not trash
				JToolBarHelper::deleteList('', 'swimstyles.delete', 'JTOOLBAR_DELETE');
			}
		}
		
		// Show trash and delete for components that uses the state field
		if (isset($this->items [0]->state)) {
			if ($state->get('filter.state') == - 2 && $canDo->get('core.delete')) {
				JToolBarHelper::deleteList('', 'swimstyles.delete', 'JTOOLBAR_EMPTY_TRASH');
				JToolBarHelper::divider();
			} else if ($canDo->get('core.edit.state')) {
				JToolBarHelper::trash('swimstyles.trash', 'JTOOLBAR_TRASH');
				JToolBarHelper::divider();
			}
		}
		
		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_freestroke');
		}
		
		// Set sidebar action - New in 3.0
		JHtmlSidebar::setAction('index.php?option=com_freestroke&view=swimstyles');
	}
}
