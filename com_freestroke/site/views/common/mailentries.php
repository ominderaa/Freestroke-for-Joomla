<div  style="display:none">
<div id="mailEntriesFormDiv">
	<form action="<?php echo JRoute::_('index.php?option=com_freestroke&task=meet.mailentries'); ?>" method="post" id="importForm"  name="importForm" enctype="multipart/form-data">
	<fieldset>
	<legend><?php echo JText::_('COM_FREESTROKE_MEETS_IMPORTENTRIES_TITLE'); ?></legend>
			<?php echo sprintf(JText::_( 'COM_FREESTROKE_MEETS_MAILENTRIES_INSTRUCTIONS' ), $this->item->name);?>
			<table class="adminformlist">
				<tr>
					<td><label for="reminder"><?php echo JText::_( 'COM_FREESTROKE_MEETS_MAILENTRIES_REMINDER' ).':'; ?></label></td>
					<td>
						<input type="checkbox" id="reminder"  name="reminder" value="reminder"  />
					</td>
				</tr>
				<tr>
					<td><label for="correction"><?php echo JText::_( 'COM_FREESTROKE_MEETS_MAILENTRIES_CORRECTION' ).':'; ?></label></td>
					<td>
						<input type="checkbox" id="correction"  name="correction" value="correction"  />
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<input type="submit" class="btn" id="sendmail-submit" value="<?php echo JText::_('COM_FREESTROKE_MAILENTRIES_SEND'); ?>" />
						<span id="upload-clear"></span>
					</td>
				</tr>
			</table>
		<input type="hidden" name="jform[id]" value="<?php echo $meetsession->id; ?>" />
		<input type="hidden" name="option" value="com_freestroke" />
	</fieldset>
	</form>
</div>
</div>	