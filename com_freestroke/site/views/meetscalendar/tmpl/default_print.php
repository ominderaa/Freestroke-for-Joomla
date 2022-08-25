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
?>

<div id="freestroke"> 

<?php if ($this->params->def( 'show_page_heading', 1 )) { ?>
<h1>
	<?php echo $this->params->def('page_heading', 'not there!');?>
</h1>
<?php } ?>
<?php 
	echo  $this->params->def('introtext', ''); 
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
			</tr>
		</thead>
		<tbody>
			<?php $show = false; ?>
		    <?php foreach ($this->items as $item) : ?>
		   	<?php	$show = true;
		   			$mindate = new JDate($item->mindate);
		   	?>
			<tr>
				<td><?php 
					echo FreestrokeConversionHelper::formatDate($item->mindate);
					if ($item->maxdate && $item->maxdate != '0000-00-00' && $item->maxdate != $item->mindate) :
						echo '<br/>(t/m '.FreestrokeConversionHelper::formatDate($item->maxdate).')' ;
					endif; ?>
				</td>
				<td>
					<?php echo $item->name; ?>
					<br/><?php echo $item->teamlead; ?>
				</td>
				<td>
					<?php echo $item->poolname; ?>
				</td>
				<td>
					<?php echo $item->place; ?>
				</td>
			</tr>
<?php endforeach; ?>
        </tbody>
	</table>

<?php if(JFactory::getUser()->authorise('core.create','com_freestroke')): ?><a
	href="<?php echo JRoute::_('index.php?option=com_freestroke&task=meet.edit&id=0'); ?>"><?php echo JText::_("COM_FREESTROKE_ADD_ITEM"); ?></a>
<?php endif; ?>
</div>
<script>windows.print();</script>