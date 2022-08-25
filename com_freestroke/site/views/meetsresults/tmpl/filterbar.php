<div style="text-align: right">
	<label for="filter_season"><?php echo JText::_('COM_FREESTROKE_SEASON')?></label>
        <select name="filter_season" id="filter_season" class="inputbox" onchange="this.form.submit()">
        	<option value=""><?php echo JText::_('COM_FREESTROKE_SEASON_SELECT_LABEL')?></option>
            <?php echo JHtml::_('select.options', $seasonOptions, 'id', 'name', $this->state->get('filter.season'));?>
	</select>
</div>
