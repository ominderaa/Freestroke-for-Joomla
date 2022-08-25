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

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

?>

<script type="text/javascript">
    function getScript(url,success) {
        var script = document.createElement('script');
        script.src = url;
        var head = document.getElementsByTagName('head')[0],
        done = false;
        // Attach handlers for all browsers
        script.onload = script.onreadystatechange = function() {
            if (!done && (!this.readyState
                || this.readyState == 'loaded'
                || this.readyState == 'complete')) {
                done = true;
                success();
                script.onload = script.onreadystatechange = null;
                head.removeChild(script);
            }
        };
        head.appendChild(script);
    }
    getScript('//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js',function() {
        js = jQuery.noConflict();
        js(document).ready(function(){
            js('#form-meet').submit(function(event){
                 
            }); 
        
            
        });
    });
    
</script>

<div id="freestroke-form" class="meet-edit">
    <?php if (!empty($this->item->id)): ?>
        <h1>Bewerken wedstrijd <?php echo $this->item->id; ?></h1>
    <?php else: ?>
        <h1>Toevoegen wedstrijd</h1>
    <?php endif; ?>

    <form id="form-meet"
		action="<?php echo JRoute::_('index.php?option=com_freestroke&task=meet.save'); ?>"
		method="post" class="form-validate" enctype="multipart/form-data">
		<table class="freestroke-formtable">
			<tr>
				<td><?php echo $this->form->getLabel('id'); ?></td>
				<td><?php echo $this->form->getInput('id'); ?></td>
			</tr>
			<tr>
				<td><?php echo $this->form->getLabel('name'); ?></td>
				<td><?php echo $this->form->getInput('name'); ?></td>
			</tr>
			<tr>
				<td><?php echo $this->form->getLabel('poolname'); ?></td>
				<td><?php echo $this->form->getInput('poolname'); ?></td>
			</tr>
			<tr>
				<td><?php echo $this->form->getLabel('place'); ?></td>
				<td><?php echo $this->form->getInput('place'); ?></td>
			</tr>
			<tr>
				<td><?php echo $this->form->getLabel('teamlead'); ?></td>
				<td><?php echo $this->form->getInput('teamlead'); ?></td>
			</tr>
			<tr>
				<td><?php echo $this->form->getLabel('mindate'); ?></td>
				<td><?php echo $this->form->getInput('mindate'); ?></td>

				<td><?php echo $this->form->getLabel('maxdate'); ?></td>
				<td><?php echo $this->form->getInput('maxdate'); ?></td>
			</tr>

		</table>

		<div>
			<button type="submit" class="btn btn-primary validate">
				<span><?php echo JText::_('JSUBMIT'); ?></span>
			</button>

			<a
				href="<?php echo JRoute::_('index.php?option=com_freestroke&task=meet.cancel'); ?>"
				title="<?php echo JText::_('JCANCEL'); ?>"><?php echo JText::_('JCANCEL'); ?></a>

			<input type="hidden" name="option" value="com_freestroke" /> <input
				type="hidden" name="task" value="meetform.save" />
            <?php echo JHtml::_('form.token'); ?>
        </div>
	</form>
</div>
