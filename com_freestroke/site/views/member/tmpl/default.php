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

// Import CSS
$document = JFactory::getDocument ();
$document->addStyleSheet ( JUri::base().'components/com_freestroke/assets/css/freestroke.css');

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_freestroke');
if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_freestroke')) {
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>
<?php if ($this->item) : ?>

    <div class="item_fields">

        <ul class="fields_list">

            <li><?php echo JText::_('COM_FREESTROKE_FORM_LBL_MEMBER_ID'); ?>:
			<?php echo $this->item->id; ?></li>
			<li><?php echo JText::_('COM_FREESTROKE_FORM_LBL_MEMBER_FIRSTNAME'); ?>:
			<?php echo $this->item->firstname; ?></li>
			<li><?php echo JText::_('COM_FREESTROKE_FORM_LBL_MEMBER_LASTNAME'); ?>:
			<?php echo $this->item->lastname; ?></li>
			<li><?php echo JText::_('COM_FREESTROKE_FORM_LBL_MEMBER_INITIALS'); ?>:
			<?php echo $this->item->initials; ?></li>
			<li><?php echo JText::_('COM_FREESTROKE_FORM_LBL_MEMBER_NAMEPREFIX'); ?>:
			<?php echo $this->item->nameprefix; ?></li>
			<li><?php echo JText::_('COM_FREESTROKE_FORM_LBL_MEMBER_WOONPLAATS'); ?>:
			<?php echo $this->item->woonplaats; ?></li>
			<li><?php echo JText::_('COM_FREESTROKE_FORM_LBL_MEMBER_REGISTRATIONID'); ?>:
			<?php echo $this->item->registrationid; ?></li>
			<li><?php echo JText::_('COM_FREESTROKE_FORM_LBL_MEMBER_ACTIVE'); ?>:
			<?php echo $this->item->active; ?></li>
			<li><?php echo JText::_('COM_FREESTROKE_FORM_LBL_MEMBER_CREATED_BY'); ?>:
			<?php echo $this->item->created_by; ?></li>
        </ul>

    </div>
    <?php if($canEdit): ?>
		<a class="btn btn-primary" href="<?php echo JRoute::_('index.php?option=com_freestroke&task=member.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_FREESTROKE_EDIT_ITEM"); ?></a>
	<?php endif; ?>
								<?php if(JFactory::getUser()->authorise('core.delete','com_freestroke')):
								?>
									<a href="javascript:document.getElementById('form-member-delete-<?php echo $this->item->id ?>').submit()"><?php echo JText::_("COM_FREESTROKE_DELETE_ITEM"); ?></a>
									<form id="form-member-delete-<?php echo $this->item->id; ?>" style="display:inline" action="<?php echo JRoute::_('index.php?option=com_freestroke&task=member.remove'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
										<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
										<input type="hidden" name="jform[firstname]" value="<?php echo $this->item->firstname; ?>" />
										<input type="hidden" name="jform[lastname]" value="<?php echo $this->item->lastname; ?>" />
										<input type="hidden" name="jform[initials]" value="<?php echo $this->item->initials; ?>" />
										<input type="hidden" name="jform[nameprefix]" value="<?php echo $this->item->nameprefix; ?>" />
										<input type="hidden" name="jform[woonplaats]" value="<?php echo $this->item->woonplaats; ?>" />
										<input type="hidden" name="jform[registrationid]" value="<?php echo $this->item->registrationid; ?>" />
										<input type="hidden" name="jform[active]" value="<?php echo $this->item->active; ?>" />
										<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />
										<input type="hidden" name="option" value="com_freestroke" />
										<input type="hidden" name="task" value="member.remove" />
										<?php echo JHtml::_('form.token'); ?>
									</form>
								<?php
								endif;
							?>
<?php
else:
    echo JText::_('COM_FREESTROKE_ITEM_NOT_LOADED');
endif;
?>
