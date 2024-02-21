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

JHtml::_('behavior.tooltip');
JHTML::_('script','system/multiselect.js',false,true);
// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_freestroke/assets/css/freestroke.css');

$user	= JFactory::getUser();
$userId	= $user->get('id');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$canOrder	= $user->authorise('core.edit.state', 'com_freestroke');
$saveOrder	= $listOrder == 'a.ordering';
?>

<form action="<?php echo JRoute::_('index.php?option=com_freestroke&view=meets'); ?>" method="post" name="adminForm" id="adminForm">
	<?php if (!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span11">
		<?php else : ?>
		<div id="j-main-container">
			<?php endif; ?>
			<div id="filter-bar" class="btn-toolbar">
				<div class="filter-search btn-group pull-left">
					<label for="filter_search"
						   class="element-invisible">
						<?php echo JText::_('JSEARCH_FILTER'); ?>
					</label>
					<input type="text" name="filter_search" id="filter_search"
						   placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>"
						   value="<?php if($this->state->get('filter.search')!=null) {echo $this->escape($this->state->get('filter.search'));} ?>"
						   title="<?php echo JText::_('JSEARCH_FILTER'); ?>"/>
				</div>
				<div class="btn-group pull-left">
					<button class="btn hasTooltip" type="submit"
							title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>">
						<i class="icon-search"></i></button>
					<button class="btn hasTooltip" id="clear-search-button" type="button"
							title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>">
						<i class="icon-remove"></i></button>
				</div>
				<div class="btn-group pull-right">
					<a class="btn btn-primary" data-toggle="modal" data-target="#importFormDiv" rel="{size: {x:500,y:150} }"><?php echo JText::_('COM_FREESTROKE_LABEL_IMPORT'); ?></a>
				</div>
			</div>
			<div class="clearfix"> </div>
			
			<table class="table table-striped">
				<thead>
					<tr>
						<th width="1%">
							<input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" />
						</th>
			
						<th class='left'>
						<?php echo JHtml::_('grid.sort',  'COM_FREESTROKE_MEETS_NAME', 'a.name', $listDirn, $listOrder); ?>
						</th>
						<th class='left'>
						<?php echo JHtml::_('grid.sort',  'COM_FREESTROKE_MEETS_POOLNAME', 'a.poolname', $listDirn, $listOrder); ?>
						</th>
						<th class='left'>
						<?php echo JHtml::_('grid.sort',  'COM_FREESTROKE_MEETS_PLACE', 'a.place', $listDirn, $listOrder); ?>
						</th>
						<th class='left'>
						<?php echo JHtml::_('grid.sort',  'COM_FREESTROKE_MEETS_MINDATE', 'a.mindate', $listDirn, $listOrder); ?>
							</th>
			                <?php if (isset($this->items[0]->id)) { ?>
			                <th width="1%" class="nowrap">
			                    <?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
			                </th>
			                <?php } ?>
					</tr>
				</thead>
				<tfoot>
					<?php 
			                if(isset($this->items[0])){
			                    $colspan = count(get_object_vars($this->items[0]));
			                }
			                else{
			                    $colspan = 10;
			                }
			            ?>
					<tr>
						<td colspan="<?php echo $colspan ?>">
							<?php echo $this->pagination->getListFooter(); ?>
						</td>
					</tr>
				</tfoot>
				<tbody>
				<?php foreach ($this->items as $i => $item) :
					$ordering	= ($listOrder == 'a.ordering');
					$canCreate	= $user->authorise('core.create',		'com_freestroke');
					$canEdit	= $user->authorise('core.edit',			'com_freestroke');
					$canCheckin	= $user->authorise('core.manage',		'com_freestroke');
					$canChange	= $user->authorise('core.edit.state',	'com_freestroke');
					?>
					<tr class="row<?php echo $i % 2; ?>">
						<td class="center">
							<?php echo JHtml::_('grid.id', $i, $item->id); ?>
						</td>
			
						<td>
						<?php if ($canEdit) : ?>
							<a href="<?php echo JRoute::_('index.php?option=com_freestroke&task=meet.edit&id='.(int) $item->id); ?>">
							<?php echo $this->escape($item->name); ?></a>
						<?php else : ?>
							<?php echo $this->escape($item->name); ?>
						<?php endif; ?>
						</td>
						<td>
							<?php echo $item->poolname; ?>
						</td>
						<td>
							<?php echo $item->place; ?>
						</td>
						<td>
							<?php echo date_format(date_create($item->mindate),  'd-m-Y'); ?>
							</td>
			                <?php if (isset($this->items[0]->id)) { ?>
						<td class="center">
							<?php echo (int) $item->id; ?>
							</td>
			                <?php } ?>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

<div class="modal hide fade modal-sm" role="dialog" id="importFormDiv" tabindex="-1">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3><?php echo JText::_('COM_FREESTROKE_MEETS_IMPORT'); ?></h3>
	</div>
	<form action="index.php" class="modal-form" method="post" id="importForm"  name="importForm" enctype="multipart/form-data" >
		<div class="modal-body">
				<?php echo JText::_( 'COM_FREESTROKE_MEETS_IMPORT_INSTRUCTIONS' ) ?>
				<label for="importfile"><?php echo JText::_( 'COM_FREESTROKE_FORM_LBL_MEETS_IMPORTFILE' ).':'; ?></label>
				<input type="file" id="importfile" accept=".csv,text/*" name="importfile" />
				<input type="hidden" name="option" value="com_freestroke" />
				<input type="hidden" name="task" value="meets.csvimport" />
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<button type="submit" class="btn btn-primary" id="meets-file-upload-submit"><?php echo JText::_('COM_FREESTROKE_IMPORT_START'); ?></button>
		</div>
	</form>
</div>