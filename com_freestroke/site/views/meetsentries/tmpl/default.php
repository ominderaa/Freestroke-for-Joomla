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
<?php 
	echo  $this->params->def('introtext', ''); 
?>
	<table class="table table-condensed table-hover">
		<thead>
			<tr>
				<th>
					<?php echo JText::_('COM_FREESTROKE_MEETS_DATES'); ?>
				</th>
				<th>
					<?php echo JText::_('COM_FREESTROKE_MEETS_NAME'); ?>
				</th>
				<th class="fs-sm-hidden fs-md-hidden">
					<?php echo JText::_('COM_FREESTROKE_MEETS_POOLNAME'); ?>
				</th>
				<th class="fs-sm-hidden">
					<?php echo JText::_('COM_FREESTROKE_MEETS_PLACE'); ?>
				</th>
			</tr>
		</thead>
		<tbody>
	<?php $show = false; ?>
    <?php foreach ($this->items as $item) : ?>
          	<?php $show = true; $mindate = new JDate($item->mindate);
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
					<a href="<?php echo JRoute::_('index.php?option=com_freestroke&view=meetattendance&id='.(int)$item->id); ?>">
						<?php echo $item->name; ?>
					</a>
					<span class="fs-lg-hidden fs-md-hidden"><br/><?php echo $item->poolname; ?></span>
					<span class="fs-lg-hidden fs-md-hidden"><br/><?php echo $item->place; ?></span>
					<?php if ($item->maxdate && $item->maxdate != '0000-00-00' && $item->maxdate != $item->mindate) :?>
						<div class="until-date">
							<?php echo '(t/m '.FreestrokeConversionHelper::formatDate($item->maxdate).')' ?>
						</div>
					<?php endif; ?>
				</td>
				<td class="fs-sm-hidden fs-md-hidden">
					<?php echo $item->poolname; ?>
				</td>
				<td class="fs-sm-hidden">
					<span class="fs-lg-hidden"><?php echo $item->poolname; ?><br/></span>
					<?php echo $item->place; ?>
				</td>
			</tr>
<?php endforeach; ?>
        </tbody>
	</table>

<?php if ($show): ?>
<div class="pagination">
	<p class="counter">
            <?php echo $this->pagination->getPagesCounter(); ?>
        </p>
        <?php echo $this->pagination->getPagesLinks(); ?>
    </div>
<?php endif; ?>
</div>
