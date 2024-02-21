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
JHTML::_('behavior.modal');

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_freestroke');
// Import CSS
$document = JFactory::getDocument ();
$document->addStyleSheet ( JUri::base().'components/com_freestroke/assets/css/freestroke.css');

if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_freestroke')) {
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}?>
<?php if ($this->item) : ?>
<div id="freestroke">
    <div class="item_fields">
		<table class="freestrokecompacttable" width="100%">
			<tr>
				<td class="fs-label"><?php echo JText::_('COM_FREESTROKE_FORM_LBL_MEET_NAME'); ?></td>
				<td><?php echo $this->item->name; ?></td>
				<?php if($canEdit) { ?>
				<td rowspan="4" id="freestroke-toolbar" class="freestroke-toolbar">
					<a class="btn fs-btn modal" href="#importInvitationFormDiv"
						rel="{size: {x:650,y:240} }"
						title="<?php echo JText::_('COM_FREESTROKE_MEETS_IMPORTINVITE_TITLE');?>"><?php echo JText::_('COM_FREESTROKE_MEETS_IMPORTINVITE'); ?></a>
					<a class="btn fs-btn modal" href="#importEntriesFormDiv"
						rel="{size: {x:650,y:240} }"
						title="<?php echo JText::_('COM_FREESTROKE_MEETS_IMPORTENTRIES_TITLE');?>"><?php echo JText::_('COM_FREESTROKE_MEETS_IMPORTENTRIES'); ?></a>
					<a class="btn fs-btn modal" href="#importResultsFormDiv"
						rel="{size: {x:650,y:240} }"
						title="<?php echo JText::_('COM_FREESTROKE_MEETS_IMPORTRESULTS_TITLE');?>"><?php echo JText::_('COM_FREESTROKE_MEETS_IMPORTRESULTS'); ?></a>
				</td>
				<?php } ?>
			</tr>
			<tr>
				<td class="fs-label"><?php echo JText::_('COM_FREESTROKE_FORM_LBL_MEET_POOLNAME'); ?></td>
				<td><?php echo $this->item->poolname; ?></td>
			</tr>
			<tr>
				<td class="fs-label"><?php echo JText::_('COM_FREESTROKE_FORM_LBL_MEET_PLACE'); ?></td>
				<td><?php echo $this->item->place; ?></td>
			</tr>
			<tr>
				<td class="fs-label"><?php echo JText::_('COM_FREESTROKE_MEETS_DATES'); ?></td>
				<td><?php
				if ($this->item->maxdate && $this->item->maxdate != '0000-00-00' && $this->item->maxdate != $this->item->mindate) :
				echo date_format(date_create($this->item->mindate), 'd-m-Y') . " - " . date_format(date_create($this->item->maxdate), 'd-m-Y');
	 else :
		 echo date_format(date_create($this->item->mindate), 'd-m-Y');
	endif;
	?>
				</td>
			<tr>
				<td class="fs-label"><?php echo JText::_('COM_FREESTROKE_FORM_LBL_MEET_TEAMLEAD'); ?></td>
				<td><?php echo $this->item->teamlead; ?></td>
			</tr>
			</tr>
		</table>
	</div>

    <?php if($canEdit): ?>
		<a class="btn fs-btn"
		href="<?php echo JRoute::_('index.php?option=com_freestroke&task=meet.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_FREESTROKE_EDIT_ITEM"); ?></a>
	<?php endif; ?>
			<?php if(JFactory::getUser()->authorise('core.delete','com_freestroke')):
			?>
				<a class="btn fs-btn"
		href="javascript:document.getElementById('form-meet-delete-<?php echo $this->item->id ?>').submit()"><?php echo JText::_("COM_FREESTROKE_DELETE_ITEM"); ?></a>
	<form id="form-meet-delete-<?php echo $this->item->id; ?>"
		style="display: inline"
		action="<?php echo JRoute::_('index.php?option=com_freestroke&task=meet.remove'); ?>"
		method="post" class="form-validate" enctype="multipart/form-data">
		<input type="hidden" name="jform[id]"
			value="<?php echo $this->item->id; ?>" /> <input type="hidden"
			name="jform[name]" value="<?php echo $this->item->name; ?>" /> <input
			type="hidden" name="jform[poolname]"
			value="<?php echo $this->item->poolname; ?>" /> <input type="hidden"
			name="jform[place]" value="<?php echo $this->item->place; ?>" /> <input
			type="hidden" name="jform[mindate]"
			value="<?php echo $this->item->mindate; ?>" /> <input type="hidden"
			name="jform[maxdate]" value="<?php echo $this->item->maxdate; ?>" />
		<input type="hidden" name="jform[created_by]"
			value="<?php echo $this->item->created_by; ?>" /> <input
			type="hidden" name="option" value="com_freestroke" /> <input
			type="hidden" name="task" value="meet.remove" />
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
<hr />

<?php 	include_once 'meetsessions.php';?>
</div>

<?php require_once JPATH_COMPONENT_SITE.'/views/common/importinvitation.php';?>
<?php require_once JPATH_COMPONENT_SITE.'/views/common/importentries.php';?>
<?php require_once JPATH_COMPONENT_SITE.'/views/common/importresults.php';?>
