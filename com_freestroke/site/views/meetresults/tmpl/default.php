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

// handle print popup
$isModal = JRequest::getVar('print') == 1; // 'print=1' will only be present in the url of the modal window, not in the presentation of the page
if (! $isModal) {
	$clickparms = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=1024,height=768,directories=no,location=no';
	$onclick = "window.open(this.href,'win2','" . $clickparms . "'); return false;";
	$href = JURI::current() . '?view=meetresults&tmpl=component&print=1';
}
?>
<table style="width: 99%">
<tr style="vertical-align: center">
<td><h1><?php echo $this->item->name; ?></h1></td>
<td class="fs-sm-hidden fs-md-hidden" style="text-align: right"><?php if( !$isModal) { ?>
<a class="btn btn-link"  href="<?php echo $href; ?>" onclick="<?php echo $onclick;?>">Afdrukken</a>
<?php }?>
</td>
</tr>
</table>

<?php if ($this->item) { ?>
<div id="freestroke">
<table style="width:100%">
<tr>
	<td style="padding-bottom:0;text-align:left"><?php echo $this->item->poolname . ', ' . $this->item->place; ?></td>
	<td style="padding-bottom:0;text-align:right"><?php echo strftime ( '%d-%m-%Y', strtotime ( $this->item->mindate ) ); ?></td>
</tr>
</table>

<?php include_once 'results.php'; ?>
<?php include_once 'relayresults.php'; ?>
<?php include_once 'meetstats.php'; ?>
<?php
} 
else {
    echo JText::_('COM_FREESTROKE_ITEM_NOT_LOADED');
};
?>
</div>
