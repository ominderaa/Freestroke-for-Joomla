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
JHtml::_ ( 'behavior.tooltip' );
JHTML::_ ( 'script', 'system/multiselect.js', false, true );
// Import CSS
$document = JFactory::getDocument ();
$document->addStyleSheet ( JUri::base().'components/com_freestroke/assets/css/freestroke.css');

$user = JFactory::getUser ();
$userId = $user->get ( 'id' );
$listOrder = $this->state->get ( 'list.ordering' );
$listDirn = $this->state->get ( 'list.direction' );
$canOrder = $user->authorise ( 'core.edit.state', 'com_freestroke' );
$saveOrder = $listOrder == 'a.ordering';
?>

<div class="items">
<?php $show = false; ?>
    <table class="moduletable" style="width: 95%">
		<thead>
			<tr>
				<th>
				<?php echo JText::_('COM_FREESTROKE_MEMBERS_FIRSTNAME'); ?>
				</th>
				<th>
				<?php echo JHtml::_('grid.sort',  'COM_FREESTROKE_MEMBERS_LASTNAME', 'a.lastname', $listDirn, $listOrder); ?>
				</th>
				<th>
				<?php echo JHtml::_('grid.sort',  'COM_FREESTROKE_MEMBERS_WOONPLAATS', 'a.place', $listDirn, $listOrder); ?>
				</th>
				<th>
				<?php echo JHtml::_('grid.sort',  'COM_FREESTROKE_MEMBERS_ACTIVE', 'a.isactive', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($this->items as $item) : ?>
			<tr>
				<?php $show = true;	?>
				<td>
					<?php echo $item->firstname; ?>
				</td>
				<td>
					<?php echo $item->lastname; ?>
				</td>
				<td>
					<?php echo $item->place; ?>
				</td>
				<td>
					<?php echo $item->isactive; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="4">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
	</table>
	<?php
		if (! $show) :
			echo JText::_ ( 'COM_FREESTROKE_NO_ITEMS' );
	endif;
	?>
</div>
<?php if ($show): ?>
<div class="pagination">
	<p class="counter">
            <?php echo $this->pagination->getPagesCounter(); ?>
        </p>
        <?php echo $this->pagination->getPagesLinks(); ?>
    </div>
<?php endif; ?>


	<?php if(JFactory::getUser()->authorise('core.create','com_freestroke')): ?><a
	href="<?php echo JRoute::_('index.php?option=com_freestroke&task=member.edit&id=0'); ?>"><?php echo JText::_("COM_FREESTROKE_ADD_ITEM"); ?></a>
<?php endif; ?>