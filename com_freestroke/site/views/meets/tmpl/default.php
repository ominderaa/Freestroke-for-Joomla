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
?>

<div id="freestroke">
<div class="items">
    <table class="moduletable" style="width:95%">
		<thead>
			<tr>
				<th>
				<?php echo JText::_('COM_FREESTROKE_MEETS_MINDATE'); ?>
				</th>
				<th>
				<?php echo JText::_('COM_FREESTROKE_MEETS_NAME'); ?>
				</th>
				<th>
				<?php echo JText::_('COM_FREESTROKE_MEETS_POOLNAME'); ?>
				</th>
				<th>
				<?php echo JText::_('COM_FREESTROKE_MEETS_PLACE'); ?>
				</th>
			</tr>
		</thead>
		<tbody>
	<?php $show = false; ?>
        <?php foreach ($this->items as $item) : ?>

            	<?php
						$show = true;
						?>
							<tr>
								<td>
									<?php 
										if($item->maxdate != '0000-00-00'):
											echo $item->mindate . " - " . $item->maxdate;
										else :
											echo $item->mindate;
										endif;
									?>
								</td>
								<td>
								<a href="<?php echo JRoute::_('index.php?option=com_freestroke&view=meet&id=' . (int)$item->id); ?>"><?php echo $item->name; ?></a>
								</td>
								<td>
									<?php echo $item->poolname; ?>
								</td>
								<td>
									<?php echo $item->place; ?>
								</td>
																								
								<?php
									if(JFactory::getUser()->authorise('core.delete','com_freestroke.meets')):
									?>
										<a class="btn btn-primary" href="javascript:document.getElementById('form-meet-delete-<?php echo $item->id; ?>').submit()"><?php echo JText::_("COM_FREESTROKE_DELETE_ITEM"); ?></a>
										<form id="form-meet-delete-<?php echo $item->id; ?>" style="display:inline" action="<?php echo JRoute::_('index.php?option=com_freestroke&task=meet.remove'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
											<input type="hidden" name="jform[id]" value="<?php echo $item->id; ?>" />
											<input type="hidden" name="jform[name]" value="<?php echo $item->name; ?>" />
											<input type="hidden" name="jform[poolname]" value="<?php echo $item->poolname; ?>" />
											<input type="hidden" name="jform[place]" value="<?php echo $item->place; ?>" />
											<input type="hidden" name="jform[mindate]" value="<?php echo $item->mindate; ?>" />
											<input type="hidden" name="jform[maxdate]" value="<?php echo $item->maxdate; ?>" />
											<input type="hidden" name="jform[created_by]" value="<?php echo $item->created_by; ?>" />
											<input type="hidden" name="option" value="com_freestroke" />
											<input type="hidden" name="task" value="meet.remove" />
											<?php echo JHtml::_('form.token'); ?>
										</form>
									<?php
									endif;
								?>
							</tr>

<?php endforeach; ?>
        <?php
			if (!$show):
	        echo JText::_('COM_FREESTROKE_NO_ITEMS');
	        endif;
        ?>
        </tbody>
    </table>
</div>
<?php if ($show): ?>
    <div class="pagination">
        <p class="counter">
            <?php echo $this->pagination->getPagesCounter(); ?>
        </p>
        <?php echo $this->pagination->getPagesLinks(); ?>
    </div>
<?php endif; ?>

	<?php if(JFactory::getUser()->authorise('core.create','com_freestroke.meets')): ?><a href="<?php echo JRoute::_('index.php?option=com_freestroke&task=meet.edit&id=0'); ?>"><?php echo JText::_("COM_FREESTROKE_ADD_ITEM"); ?></a>
	<?php endif; ?>
</div>
	