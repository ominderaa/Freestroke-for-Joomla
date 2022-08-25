<div  style="display:none">
	<div id="importInvitationFormDiv">
		<form action="<?php echo JRoute::_('index.php?option=com_freestroke&task=meetscalendar.lxfimportinvitation'); ?>" method="post" id="importForm"  name="importForm" enctype="multipart/form-data">
			<fieldset>
				<legend><?php echo JText::_('COM_FREESTROKE_MEETS_IMPORTINVITE_TITLE'); ?></legend>
				<table class="adminformlist">
					<tr>
						<td><label for="importfile"><?php echo JText::_( 'COM_FREESTROKE_ENTRIES_IMPORTLFX_IMPORTFILE' ).':'; ?></label></td>
						<td>
							<input type="file" id="importfile" accept=".lxf,.lef,application/zip,text/*" name="importfile" />
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<input type="submit" class="btn btn-primary" id="meets-file-upload-submit" value="<?php echo JText::_('COM_FREESTROKE_IMPORT_START'); ?>" />
						</td>
					</tr>
				</table>
			</fieldset>
			<input type="hidden" name="option" value="com_freestroke" />
		</form>
	</div>
</div>