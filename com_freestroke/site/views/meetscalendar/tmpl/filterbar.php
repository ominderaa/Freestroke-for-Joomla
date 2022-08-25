<div style="text-align: right">
    <select name="filter_season" id="filter_season" class="inputbox" onchange="this.form.submit()">
       	<option value=""><?php echo JText::_('COM_FREESTROKE_SEASON_SELECT_LABEL')?></option>
        <?php echo JHtml::_('select.options', $seasonOptions, 'id', 'name', $this->state->get('filter.season'));?>
	</select>
</div>
<div style="text-align: right">
	<a class="btn fs-btn modal" href="#importInvitationFormDiv"
		rel="{size: {x:650,y:240} }"
		title="<?php echo JText::_('COM_FREESTROKE_MEETS_IMPORTINVITE_TITLE');?>"><?php echo JText::_('COM_FREESTROKE_MEETS_IMPORTINVITE'); ?></a>
	<a class="btn fs-btn modal" href="#importEntriesFormDiv"
		rel="{size: {x:650,y:240} }"
		title="<?php echo JText::_('COM_FREESTROKE_MEETS_IMPORTENTRIES_TITLE');?>"><?php echo JText::_('COM_FREESTROKE_MEETS_IMPORTENTRIES'); ?></a>
	<a class="btn fs-btn modal" href="#importResultsFormDiv"
		rel="{size: {x:650,y:240} }"
		title="<?php echo JText::_('COM_FREESTROKE_MEETS_IMPORTRESULTS_TITLE');?>"><?php echo JText::_('COM_FREESTROKE_MEETS_IMPORTRESULTS'); ?></a>
</div>
