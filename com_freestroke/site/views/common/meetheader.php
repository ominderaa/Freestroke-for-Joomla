<div class="item_fields">
    <table class="table table-condensed">
        <tr>
            <td><?php echo JText::_('COM_FREESTROKE_LABEL_MEET_NAME'); ?></td>
            <td><?php echo $this->item->name; ?></td>
        </tr>
        <tr>
            <td><?php echo JText::_('COM_FREESTROKE_LABEL_MEET_POOLNAME'); ?></td>
            <td><?php echo $this->item->poolname; ?></td>
        </tr>
        <tr>
            <td><?php echo JText::_('COM_FREESTROKE_LABEL_MEET_PLACE'); ?></td>
            <td><?php echo $this->item->place; ?></td>
        </tr>
        <tr>
            <td><?php echo JText::_('COM_FREESTROKE_MEETS_DATES'); ?></td>
            <td><?php
                if ($this->item->maxdate && $this->item->maxdate != '0000-00-00' && $this->item->maxdate != $this->item->mindate) :
                    echo date_format(date_create($this->item->mindate), 'd-m-Y') . " - " . date_format(date_create($this->item->maxdate), 'd-m-Y');
                else :
                    echo date_format(date_create($this->item->mindate), 'd-m-Y');
                endif;?>
            </td>
        </tr>
    </table>
</div>
