<div  style="display:none">
	<div id="#meetsessionFormDiv">
	    <form id="form-meetssession" action="<?php echo JRoute::_('index.php?option=com_freestroke&task=meetssession.save'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
			<fieldset>
			<legend><?php echo JText::_('COM_FREESTROKE_MEETS_IMPORTENTRIES_TITLE'); ?></legend>
		    <ul>
	            	<li><?php echo $this->form->getLabel('id'); ?>
					<?php echo $this->form->getInput('id'); ?></li>
					<li><?php echo $this->form->getLabel('name'); ?>
					<?php echo $this->form->getInput('name'); ?></li>
					<li><?php echo $this->form->getLabel('startdate'); ?>
					<?php echo $this->form->getInput('startdate'); ?></li>
					<li><?php echo $this->form->getLabel('starttime'); ?>
					<?php echo $this->form->getInput('starttime'); ?></li>
					<li><?php echo $this->form->getLabel('officialmeeting'); ?>
					<?php echo $this->form->getInput('officialmeeting'); ?></li>
					<li><?php echo $this->form->getLabel('teamleadermeeting'); ?>
					<?php echo $this->form->getInput('teamleadermeeting'); ?></li>
					<li><?php echo $this->form->getLabel('warmupfrom'); ?>
					<?php echo $this->form->getInput('warmupfrom'); ?></li>
					<li><?php echo $this->form->getLabel('warmupuntil'); ?>
					<?php echo $this->form->getInput('warmupuntil'); ?></li>
					<li><?php echo $this->form->getLabel('judges'); ?>
					<?php echo $this->form->getInput('judges'); ?></li>
					<li><?php echo $this->form->getLabel('transport'); ?>
					<?php echo $this->form->getInput('transport'); ?></li>
					<li><?php echo $this->form->getLabel('carpool'); ?>
					<?php echo $this->form->getInput('carpool'); ?></li>
					<li><?php echo $this->form->getLabel('teamlead'); ?>
					<?php echo $this->form->getInput('teamlead'); ?></li>
			</ul>
	
	        <div>
	            <button type="submit" class="btn" class="validate"><span><?php echo JText::_('JSUBMIT'); ?></span></button>
	            <?php echo JText::_('or'); ?>
	            <a href="<?php echo JRoute::_('index.php?option=com_freestroke&task=member.cancel'); ?>" title="<?php echo JText::_('JCANCEL'); ?>"><?php echo JText::_('JCANCEL'); ?></a>
	
	            <input type="hidden" name="option" value="com_freestroke" />
	            <input type="hidden" name="task" value="memberform.save" />
	            <?php echo JHtml::_('form.token'); ?>
	        </div>
	        </fieldset>
	    </form>	
	</div>
</div>