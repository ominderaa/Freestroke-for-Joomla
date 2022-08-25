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

jimport( 'joomla.html.pagination' );

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

<form method="post" name="adminForm" id="adminForm" action="<?php echo JFactory::getURI()->toString();?>">
<?php
	//Get season options
	JFormHelper::addFieldPath(JPATH_COMPONENT . '/models/fields');
	$seasons = JFormHelper::loadFieldType('season', false);
	if($seasons) {
		$seasonOptions=$seasons->getOptions();
		include_once 'filterbar.php';
	}
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
		<tfoot>
            <tr>
               	<td colspan="4">
			    <div class="pagination">
   			        <?php echo $this->pagination->getPagesLinks(); ?>
			    </div>
			    </td>
           </tr>
        </tfoot>
		<tbody>
			<?php $show = false; ?>
		    <?php foreach ($this->items as $item) : ?>
		   	<?php $show = true;$mindate = new JDate($item->mindate);	?>
			<tr>
				<td class="agenda-date">
					<div class="agenda-date">
						<div class="dayofweek"><?php echo $mindate->format('l'); ?></div>
						<div class="dayofmonth"><?php echo $mindate->format('j'); ?></div>
						<div class="shortdate"><?php echo  $mindate->format('F'); ?></div>
					</div>
				</td>
				<td>
					<a href="<?php echo JRoute::_('index.php?option=com_freestroke&view=meetresults&id=' . (int)$item->id); ?>">
					<?php
						if(!empty($item->sessionname) && strlen($item->sessionname) > 0) {
							echo $item->sessionname; 
						} else {
							echo $item->name;
						}
						?></a>
					<span class="fs-lg-hidden fs-md-hidden"><br/><?php echo $item->poolname; ?></span>
					<span class="fs-lg-hidden fs-md-hidden"><br/><?php echo $item->place; ?></span>
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
</form>
</div>