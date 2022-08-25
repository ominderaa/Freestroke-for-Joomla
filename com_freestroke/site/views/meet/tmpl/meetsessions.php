<?php 
require_once JPATH_COMPONENT . '/helpers/conversion.php';
if(isset($this->meetsessions) && count($this->meetsessions) > 0) { ?>
	<?php
	$show = false;
	foreach ($this->meetsessions as $meetsession) {
		$show = true;
		if ($meetsession->name) {?>
		<table style="width:100%">
			<tr>
			<td><h2><?php echo $meetsession->name; ?></h2></td>
			<?php if($canEdit) { ?>
				<td id="freestroke-toolbar" class="freestroke-toolbar">
					<a class="btn fs-btn"
						href="<?php echo JRoute::_('index.php?option=com_freestroke&task=meetsession.edit&layout=edit'.'&return=meet&id='.$meetsession->id);?>" 
						rel="{size: {x:650,y:450} }"
						title="<?php echo JText::_('COM_FREESTROKE_EDIT_ITEM');?>">
						<?php echo JText::_('COM_FREESTROKE_EDIT_ITEM'); ?></a>
				</td>		
			<?php } ?>
			</tr>
		</table>
		<?php } ?>
	
		<table class="freestrokecompacttable" style="width:100%">
			<tr>
				<td class="fs-label"><?php echo JText::_('COM_FREESTROKE_MEETS_SESSION_STARTDATE'); ?></td>
				<td><?php echo strftime('%d-%m-%Y', strtotime($meetsession->startdate)); ?></td>
			</tr>
			<tr>
				<td class="fs-label"><?php echo JText::_('COM_FREESTROKE_MEETS_SESSION_STARTTIME'); ?></td>
				<td><?php echo $meetsession->starttime; ?></td>
			</tr>
			<?php if(!empty($meetsession->officialmeeting)) { ?>
			<tr>
				<td class="fs-label"><?php echo JText::_('COM_FREESTROKE_MEETS_SESSION_OFFICIALMEETING'); ?></td>
				<td><?php echo $meetsession->officialmeeting . '&nbsp;'; ?></td>
			</tr>
			<?php } ?>
			<?php if(!empty($meetsession->teamleadermeeting)) { ?>
			<tr>
				<td class="fs-label"><?php echo JText::_('COM_FREESTROKE_MEETS_SESSION_TEAMLEADERMEETING'); ?></td>
				<td><?php echo $meetsession->teamleadermeeting . '&nbsp;'; ?></td>
			</tr>
			<?php } ?>
			<?php if(!empty($meetsession->warmupfrom)) { ?>
			<tr>
				<td class="fs-label"><?php echo JText::_('COM_FREESTROKE_MEETS_SESSION_WARMUPFROM'); ?></td>
				<td><?php echo $meetsession->warmupfrom . 
							(!empty($meetsession->warmupuntil) ? ' - ' .$meetsession->warmupuntil : ''); ?></td>
			</tr>
			<?php } ?>
		</table>
		<?php
			$sessionnumber = $meetsession->sessionnumber; 
			include 'events.php'; 
		?>
		<hr/>
		<?php
	}?>

<?php } else { ?>
<p>Van deze wedstrijd zijn geen sessies bekend.</p>
<hr/>
<?php } ?>
