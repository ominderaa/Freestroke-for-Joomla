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

// Import CSS
$document = JFactory::getDocument ();
$document->addStyleSheet ( JUri::base().'components/com_freestroke/assets/css/freestroke.css');
?>
<div id="freestroke">

	<form id="form-meetsession" class="form-horizontal"
		action="<?php echo JRoute::_('index.php?option=com_freestroke&task=meetsession.save'); ?>"
		method="post" class="form-validate" enctype="multipart/form-data">
		<?php echo $this->form->getInput('id'); ?>

		<div class="control-group row-fluid">
			<?php echo $this->form->getLabel('name'); ?>
			<?php echo $this->form->getInput('name'); ?>
		</div>
		<div class="control-group row-fluid">
			<?php echo $this->form->getLabel('startdate'); ?>
			<?php echo $this->form->getInput('startdate'); ?>
		</div>
		<div class="control-group row-fluid">
			<?php echo $this->form->getLabel('warmupfrom'); ?>
			<?php echo $this->form->getInput('warmupfrom'); ?>
			<?php echo "-"; ?>
			<?php echo $this->form->getInput('warmupuntil'); ?>
		</div>

		<div class="control-group row-fluid">
			<?php echo $this->form->getLabel('starttime'); ?>
			<?php echo $this->form->getInput('starttime'); ?>
		</div>

		<div class="control-group row-fluid">
			<?php echo $this->form->getLabel('officialmeeting'); ?>
			<?php echo $this->form->getInput('officialmeeting'); ?>
		</div>

		<div class="control-group row-fluid">
			<?php echo $this->form->getLabel('teamleadermeeting'); ?>
			<?php echo $this->form->getInput('teamleadermeeting'); ?>
		</div>

		<div class="control-group row-fluid">
			<?php echo $this->form->getLabel('transport'); ?>
			<?php echo $this->form->getInput('transport'); ?>
		</div>

		<div class="control-group row-fluid">
			<hr/>
			<?php echo $this->form->getInput('message'); ?></td>
		</div>

		<table class="freestroke-formtable" style="width: 100%">
				<td colspan="4">
				<div id="nepopstelling" style="color: #505050">
					<img
						style="position:absolute;z-index:99" 
						src="components/com_freestroke/assets/images/voorbeeld.png" />
								 
					<table class="freestrokecompacttable" style="width: 100%">
					<tbody>
						<tr>
							<td style="width: 50%;vertical-align: top;padding-bottom: 12px;">
								<strong>4. 25m schoolslag </strong><br/>						
								Willeke Roemoer (0:37.94)<br/>				</td>
									 
							<td style="width: 50%;vertical-align: top;padding-bottom: 12px;">
								<strong>5. 25m schoolslag </strong><br/>						
								Karin Scheer (0:30.30)<br/>				</td>
						</tr>
						<tr>
							<td style="width: 50%;vertical-align: top;padding-bottom: 12px;">
								<strong>6. 25m schoolslag </strong><br/>						
								Ger Kuijpers (0:29.21)<br/>Toon Freholt (0:29.26)<br/>Krijn Roogen (0:31.13)<br/>				</td>
									 
							<td style="width: 50%;vertical-align: top;padding-bottom: 12px;">
								<strong>7. 25m schoolslag </strong><br/>						
								Mattis Wer (0:29.68)<br/>Linus Ubunt (NT)<br/>				</td>
						</tr>
						<tr>
							<td style="width: 50%;vertical-align: top;padding-bottom: 12px;">
								<strong>8. 25m schoolslag </strong><br/>						
								Gerard Wilts (NT)<br/>				</td>
									 
							<td style="width: 50%;vertical-align: top;padding-bottom: 12px;">
								<strong>9. 25m schoolslag </strong><br/>						
								Charine Trelou (0:28.96)<br/>Hanneke van Juich (NT)<br/>Petr Plot (NT)<br/>Karina van der Geld(NT)</td>
						</tr>
						<tr>					 
							<td style="width: 50%;vertical-align: top;padding-bottom: 12px;">
								<strong>10. 25m schoolslag </strong><br/>						
								Hans Troon (0:31.16)<br/>
							</td>
						</tr>
					</tbody>
					</table>
					</div>
				</td>
			</tr>
		</table>

		<table>
			<tr>
				<td style="vertical-align: top" valign="top">
					<span style="font-weight: bold"><?php echo JText::_('COM_FREESTROKE_MEETS_SESSION_TEAMLEAD'); ?>:</span>
				</td>
				<td style="vertical-align: top" valign="top">
					<?php echo $this->form->getInput('teamlead'); ?>
				</td>
				
			</tr>
			<tr>
				<td style="padding-bottom: 12px;vertical-align: top" valign="top">
					<span style="font-weight: bold"><?php echo JText::_('COM_FREESTROKE_MEETS_SESSION_CANCELLING'); ?>:</span>
				</td>
				<td style="vertical-align: top" valign="top">
					<?php echo $this->form->getInput('cancelling'); ?>
				</td>
			<tr>
				<td style="padding-bottom: 12px;vertical-align: top" colspan="2" valign="top">
					<span style="font-weight: bold">Bij te laat afmelden of niet verschijnen, wordt een boete van €5,- opgelegd.</span>
				</td>
			<tr>
			</tr>
		</table>
		<table style="width: 100%">
			<tr>
				<td style="font-weight: bold; width: 50%; vertical-align: top;border-top: 1px solid #000000;;">
					<?php echo JText::_('COM_FREESTROKE_MEETS_SESSION_JUDGES'); ?>
				</td>
				<td style="font-weight: bold; width: 50%; vertical-align: top;border-top: 1px solid #000000;;">
				    <?php echo JText::_('COM_FREESTROKE_MEETS_SESSION_CARPOOL'); ?>
				</td>
			</tr>
			<tr>
				<td style="width: 50%; vertical-align: top">
					<?php echo $this->form->getInput('judges'); ?>
				</td>
				<td style="width: 50%; vertical-align: top;">
			        <?php echo $this->form->getInput('carpool'); ?>
				</td>
			</tr>
		</table>
		
		
		
		<div>
			<button type="submit" class="validate">
				<span><?php echo JText::_('JSUBMIT'); ?></span>
			</button>

			<a
				href="<?php echo JRoute::_('index.php?option=com_freestroke&task=meetsessionform.cancel'); ?>"
				title="<?php echo JText::_('JCANCEL'); ?>"><?php echo JText::_('JCANCEL'); ?></a>

			<input type="hidden" name="option" value="com_freestroke" /> <input
				type="hidden" name="task" value="meetsessionform.save" />
            <?php echo JHtml::_('form.token'); ?>
        </div>
	</form>
</div>
<script type="text/javascript">
<!--
document.getElementById("jform_starttime").placeholder = "hh:mm";
document.getElementById("jform_warmupfrom").placeholder = "hh:mm";
document.getElementById("jform_warmupuntil").placeholder = "hh:mm";
document.getElementById("jform_officialmeeting").placeholder = "hh:mm";
document.getElementById("jform_teamleadermeeting").placeholder = "hh:mm";
document.getElementById("jform_message").placeholder = "Extra mededeling indien nodig";
document.getElementById("jform_teamlead").placeholder = "Wie gaat er mee als ploegleiding";
document.getElementById("jform_cancelling").placeholder = "Bij wie kan er afgemeld worden en tot wanneer";
document.getElementById("jform_judges").placeholder = "Wie gaat er mee als official";
document.getElementById("jform_transport").placeholder = "Van waar en hoe laat wordt er verzameld voor vervoer";
document.getElementById("jform_carpool").placeholder = "Wie wordt er verzocht te rijden";
//-->
</script>
