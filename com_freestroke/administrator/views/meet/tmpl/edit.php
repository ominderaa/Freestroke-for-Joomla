<?php
/**
 * @version     1.0.0
 * @package     com_freestroke
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      O.Minderaa <ominderaa@gmail.com> - http://
 */
// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_freestroke/assets/css/freestroke.css');
?>
<script type="text/javascript">
	js = jQuery.noConflict();
	js(document).ready(function () {
		
	});

	Joomla.submitbutton = function (task) {
		if (task == 'meet.cancel') {
			Joomla.submitform(task, document.getElementById('meet-form'));
		}
		else {
			
			if (task != 'meet.cancel' && document.formvalidator.isValid(document.id('meet-form'))) {
				
				Joomla.submitform(task, document.getElementById('meet-form'));
			}
			else {
				alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_freestroke&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="meet-form" class="form-validate">
    <div class="form-horizontal">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_FREESTROKE_LEGEND_MEET'); ?></legend>
   				<?php echo $this->form->renderField('id'); ?>
				<?php echo $this->form->renderField('name'); ?>
				<?php echo $this->form->renderField('poolname'); ?>
				<?php echo $this->form->renderField('place'); ?>
				<?php echo $this->form->renderField('mindate'); ?>
				<?php echo $this->form->renderField('maxdate'); ?>
				<?php echo $this->form->renderField('created_by'); ?>
        </fieldset>
    </div>

    <input type="hidden" name="task" value="" />
    
    <?php echo JHtml::_('form.token'); ?>
    <div class="clr"></div>
</form>
