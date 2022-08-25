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

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_freestroke', JPATH_ADMINISTRATOR);

$isModal = JRequest::getVar('print') == 1; 
// Import CSS
$document = JFactory::getDocument ();
$document->addStyleSheet ( JUri::base().'components/com_freestroke/assets/css/freestroke.css');

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_freestroke');
if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_freestroke')) {
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>
<?php
if (! $isModal) {
	$clickparms = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=1024,height=768,directories=no,location=no';
	$onclick = "window.open(this.href,'win2','" . $clickparms . "'); return false;";
	$href = JURI::current() . '?view=meetattendance&tmpl=component&print=1';
}
?>

<div id="freestroke">
<div style="width: 99%">
	<div style="text-align: right">
		<?php if( !$isModal) { ?>
			<a class="btn btn-link fs-md-hidden fs-sm-hidden" href="<?php echo $href; ?>" onclick="<?php echo $onclick;?>">Afdrukken</a>
			</br class="fs-md-hidden fs-sm-hidden" >
			<a class="btn btn-link" href="<?php echo JURI::current() . '?view=meetentries'?>">Overzicht op programma</a>
		<?php }?>
	</div>
	<div style="vertical-align: bottom">
		<h1><?php echo $this->item->name; ?></h1>
	</div>
</div>

<?php if ($this->item) : ?>
	<div class="item_fields">

		<table class="fixedlist">
			<tr>
				<td class="fs-label"><?php echo JText::_('COM_FREESTROKE_FORM_LBL_MEET_NAME'); ?></td>
				<td><?php echo $this->item->name; ?></td>
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
		echo strftime('%d-%m-%Y', strtotime($this->item->mindate)) . " - " . strftime('%d-%m-%Y', strtotime($this->item->maxdate));
	 else :
		echo strftime('%d-%m-%Y', strtotime($this->item->mindate));
	endif;
	?>
				</td>
			</tr>
		</table>
	</div>
   	<?php	include_once 'meetsessions.php'; ?>
<?php
else:
    echo JText::_('COM_FREESTROKE_ITEM_NOT_LOADED');
endif;
?>
</div>
