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
		if (task == 'member.cancel') {
			Joomla.submitform(task, document.getElementById('member-form'));
		}
		else {
			
			if (task != 'member.cancel' && document.formvalidator.isValid(document.id('member-form'))) {
				
				Joomla.submitform(task, document.getElementById('member-form'));
			}
			else {
				alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_freestroke&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="member-form" class="form-validate">
    <div class="form-horizontal">
		    <fieldset class="adminform">
            <legend><?php echo JText::_('COM_FREESTROKE_LEGEND_MEMBER'); ?></legend>

                <?php echo $this->form->renderField('id'); ?>
                <?php echo $this->form->renderField('firstname'); ?>

				<?php echo $this->form->renderField('lastname'); ?>
				<?php echo $this->form->renderField('nameprefix'); ?>
				<?php echo $this->form->renderField('initials'); ?>
				<?php echo $this->form->renderField('birthdate'); ?>
				<?php echo $this->form->renderField('gender'); ?>
				
				<?php echo $this->form->renderField('street'); ?>
				<?php echo $this->form->renderField('zip'); ?>
				<?php echo $this->form->renderField('place'); ?>

				<?php echo $this->form->renderField('phonenumber'); ?>
				<?php echo $this->form->renderField('mobile'); ?>
				<?php echo $this->form->renderField('email'); ?>

				<?php echo $this->form->renderField('entrydate'); ?>
				<?php echo $this->form->renderField('exitdate'); ?>

				<?php echo $this->form->renderField('isactive'); ?>
				<?php echo $this->form->renderField('isjudge'); ?>
				<?php echo $this->form->renderField('competence'); ?>
        </fieldset>
    </div>

    

    <input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
    <div class="clr"></div>

    <style type="text/css">
        /* Temporary fix for drifting editor fields */
        .adminformlist li {
            clear: both;
        }
    </style>
</form>