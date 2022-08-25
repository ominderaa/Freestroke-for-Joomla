<?php
/**
 * @version     1.0.0
 * @package     com_freestroke
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      O.Minderaa <ominderaa@gmail.com> - http://
 */
// no direct access
defined ( '_JEXEC' ) or die ();
require_once JPATH_COMPONENT . '/helpers/conversion.php';

// Import CSS
$document = JFactory::getDocument ();
$document->addStyleSheet ( JUri::base().'components/com_freestroke/assets/css/freestroke.css');

// handle print popup
$clickparms = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=1024,height=768,directories=no,location=no';
$onclick = "window.open(this.href,'win2','" . $clickparms . "'); return false;";
$href = JURI::current() . '?tmpl=component&print=1';
?>

<script type="text/javascript">
function deleteMeet( meetname, meetid )
{
	var ans = confirm("Weet je zeker dat je wedstrijd '" + meetname + "' wilt verwijderen?");
	if (ans == true) {
	    var idElement = document.getElementById("jform[id]");
	    idElement.value = meetid;
	    document.getElementById("deleteMeetForm").submit();
	}
}

</script>
<div id="freestroke"> 
<?php 
	echo  $this->params->def('introtext', ''); 
?>
<div style="text-align:right; width:100%">
<a class="btn btn-link" style="text-align: right" href="<?php echo $href; ?>" onclick="<?php echo $onclick;?>">Afdrukken</a>
</div>

<form id="adminForm" method="post" name="adminForm">
<?php if (JFactory::getUser ()->authorise ( 'core.delete', 'com_freestroke' )) { 
	//Get season options
	JFormHelper::addFieldPath(JPATH_COMPONENT . '/models/fields');
	$seasons = JFormHelper::loadFieldType('season', false);
	if($seasons) {
		$seasonOptions=$seasons->getOptions();
		include_once 'filterbar.php';
	}
}
?>
<p>Wedstrijden tussen <?php echo $this->startdate->format('d-m-Y'); ?> en <?php echo $this->enddate->format('d-m-Y'); ?></p>

	<table class="table table-condensed table-hover">
		<thead>
			<tr>
				<th>
					<?php echo JText::_('COM_FREESTROKE_MEETS_DATES'); ?>
				</th>
				<th>
					<?php echo JText::_('COM_FREESTROKE_MEETS_NAME'); ?>
				</th>
				<th class="hidden-phone hidden-tablet">
					<?php echo JText::_('COM_FREESTROKE_MEETS_POOLNAME'); ?>
				</th>
				<th class="hidden-phone">
					<?php echo JText::_('COM_FREESTROKE_MEETS_PLACE'); ?>
				</th>
				<th class="hidden-phone">&nbsp;</th>
				<?php if (JFactory::getUser ()->authorise ( 'core.delete', 'com_freestroke' )) { ?>
					<th>&nbsp;</th>
				<?php } ?>
			</tr>
		</thead>
		<tbody>
			<?php $show = false; ?>
		    <?php foreach ($this->items as $item) : ?>
		   	<?php	$show = true;
		   			$mindate = new JDate($item->mindate);
		   	?>
			<tr>
				<td class="agenda-date">
					<div class="agenda-date">
						<div class="dayofweek"><?php echo $mindate->format('l'); ?></div>
						<div class="dayofmonth"><?php echo $mindate->format('j'); ?></div>
						<div class="shortdate"><?php echo  $mindate->format('F'); ?></div>
					</div>
				</td>
				<td>
					<a href="<?php echo JRoute::_('index.php?option=com_freestroke&view=meet&id=' . (int)$item->id); ?>">
					<?php echo $item->name;	?></a>
					<?php if ($item->teamlead) { ?>
						<br/>Ploegleiding: <?php echo $item->teamlead; ?>
					<?php }?>
					<span class="hidden-desktop hidden-tablet" style="word-break:keep-all"><?php echo $item->poolname.'&nbsp;'; ?></span>
					<span class="hidden-desktop hidden-tablet" style="word-break:keep-all"><?php echo $item->place; ?></span>
					<?php if ($item->maxdate && $item->maxdate != '0000-00-00' && $item->maxdate != $item->mindate) :?>
						<div class="until-date">
							<?php echo '(t/m '.FreestrokeConversionHelper::formatDate($item->maxdate).')' ?>
						</div>
					<?php endif; ?>
				</td>
				<td class="hidden-phone hidden-tablet">
					<?php echo $item->poolname; ?>
				</td>
				<td class="hidden-phone">
					<span class="hidden-desktop"><?php echo $item->poolname; ?><br/></span>
					<?php echo $item->place; ?>
				</td>
				<td class="hidden-phone">
					<?php if($item->hasinvite) { ?>
						<a class="btn-link" href="<?php echo JRoute::_('index.php?option=com_freestroke&view=meet&id=' . (int)$item->id . '&meet.id=' . (int)$item->id ); ?>">
							<img src="components/com_freestroke/assets/images/program-24x24.png" 
								 title="Programma inzien" />
						</a>
					<?php } ?>
					<?php if($item->hasentries) { ?>
							<a class="btn-link" href="<?php echo JRoute::_('index.php?option=com_freestroke&view=meetattendance&id=' . (int)$item->id . '&meet.id=' . (int)$item->id ); ?>">
								<img src="components/com_freestroke/assets/images/lineup-24x24.png" 
								     title="Opstelling inzien" />
							</a>
					<?php } ?>
					<?php if($item->hasresults) { ?>
							<a class="btn-link" href="<?php echo JRoute::_('index.php?option=com_freestroke&view=meetresults&id=' . (int)$item->id . '&meet.id=' . (int)$item->id ); ?>">
								<img src="components/com_freestroke/assets/images/stopwatch-24x24.png" 
									 title="Uitslagen inzien" />
							</a>
					<?php } ?>
				</td>
				<?php if (JFactory::getUser ()->authorise ( 'core.delete', 'com_freestroke' )) { ?>
				<td>
					<a href="javascript:deleteMeet('<?php echo $item->name;	?>', <?php echo $item->id; ?>)">
						<img src="components/com_freestroke/assets/images/recycle_bin_empty.png" 
							 title="Verwijderen <?php echo $item->name;?>"/>
					</a>
				</td>
				<?php } ?>
				</tr>

<?php endforeach; ?>
        </tbody>
	</table>
	<input type="hidden" name="filter_order" value="<?php echo $this->sortColumn; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->sortDirection; ?>" />
</form>

<?php if(JFactory::getUser()->authorise('core.create','com_freestroke')): ?><a
	href="<?php echo JRoute::_('index.php?option=com_freestroke&task=meet.edit&id=0'); ?>"><?php echo JText::_("COM_FREESTROKE_ADD_ITEM"); ?></a>
<?php endif; ?>
</div>
<form id="deleteMeetForm" name="deleteMeetForm"
		style="display: inline"
		action="<?php echo JRoute::_('index.php?option=com_freestroke&task=meet.remove'); ?>"
		method="post" class="form-validate" enctype="multipart/form-data">
		<input type="hidden" name="jform[id]" id="jform[id]" value="-1" /> 
		<input type="hidden" name="option" value="com_freestroke" /> 
		<input type="hidden" name="task" value="meet.remove" />
			<?php echo JHtml::_('form.token'); ?>
</form>

<?php require_once JPATH_COMPONENT_SITE.'/views/meetscalendar/tmpl/importinvitation.php';?>
<?php require_once JPATH_COMPONENT_SITE.'/views/meetscalendar/tmpl/importentries.php';?>
<?php require_once JPATH_COMPONENT_SITE.'/views/meetscalendar/tmpl/importresults.php';?>
