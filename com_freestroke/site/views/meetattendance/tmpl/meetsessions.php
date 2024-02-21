<?php 
require_once JPATH_COMPONENT . '/helpers/conversion.php';
if(isset($this->meetsessions) && count($this->meetsessions) > 0) { ?>
<div class="items">
<?php
	$show = false;
	foreach ($this->meetsessions as $meetsession) {
		$show = true; ?>
		<hr />
			<table class="fixedlist" style="width: 100%">
				<tr>
					<td style="width: 10%;">
						<span class="fs-label"><?php echo JText::_('COM_FREESTROKE_MEETS_SESSION_STARTDATE'); ?></span>
						<div class="fs-lg-hidden fs-md-hidden"><?php echo date_format(date_create($meetsession->startdate), 'd-m-Y' ); ?></div>
					</td>
					<td class="fs-sm-hidden" style="width: 30%;">
						<?php echo date_format(date_create($meetsession->startdate), 'd-m-Y' ); ?>
					</td>
					<td style="width: 10%;">
						<span class="fs-label"><?php echo JText::_('COM_FREESTROKE_MEETS_SESSION_WARMUPFROM'); ?></span>
						<div class="fs-lg-hidden fs-md-hidden"><?php echo $meetsession->warmupfrom;
								if (! empty($meetsession->warmupuntil)) {
									echo ' - ' . $meetsession->warmupuntil;
						}?></div>
					</td>
					<td class="fs-sm-hidden" style="width: 30%;">
						<?php echo $meetsession->warmupfrom;
								if (! empty($meetsession->warmupuntil)) {
									echo ' - ' . $meetsession->warmupuntil;
						}?>
					</td>
				</tr>
				<tr>
					<td style="width: 10%;">
						<span class="fs-label"><?php echo JText::_('COM_FREESTROKE_MEETS_SESSION_STARTTIME'); ?></span>
						<div class="fs-lg-hidden fs-md-hidden"><?php echo $meetsession->starttime; ?></div>
					</td>
					<td class="fs-sm-hidden" style="width: 30%;">
						<?php echo $meetsession->starttime; ?>
					</td>
					<td style="width: 10%;">
						<span class="fs-label"><?php echo JText::_('COM_FREESTROKE_MEETS_SESSION_TRANSPORT'); ?></span>
						<div class="fs-lg-hidden fs-md-hidden"><?php echo str_replace("\n", '<br/>', $meetsession->transport ); ?></div>
					</td>
					<td class="fs-sm-hidden" style="width: 30%;">
						<?php echo str_replace("\n", '<br/>', $meetsession->transport ); ?>
					</td>
				</tr>
			</table>
		<?php
			$sessionnumber = $meetsession->sessionnumber; 
			include 'memberentries.php'; 
		?>
		<table style="width: 100%">
			<tr>
				<td colspan="2">
					<span style="font-weight: bold"><?php echo JText::_('COM_FREESTROKE_MEETS_SESSION_TEAMLEAD'); ?>:</span>
					<?php echo str_replace("\n", '<br/>', $meetsession->teamlead); ?>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="padding-bottom: 12px">
					<span style="font-weight: bold"><?php echo JText::_('COM_FREESTROKE_MEETS_SESSION_CANCELLING'); ?>:</span>
					<?php echo str_replace("\n", '<br/>', $meetsession->cancelling); ?>
				</td>
			<tr>
				<td colspan="2" style="padding-bottom: 12px">
					<span style="font-weight: bold">Bij te laat afmelden of niet verschijnen, wordt een boete van &euro;5,- opgelegd.</span>
				</td>
			<tr>
			</tr>
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
					<?php echo str_replace("\n", '<br/>', $meetsession->judges); ?>
				</td>
				<td style="width: 50%; vertical-align: top;">
			        <?php echo str_replace("\n", '<br/>', $meetsession->carpool); ?>
				</td>
			</tr>
		</table>
	<?php }	?>
</div>
<?php } else { ?>
<p>Van deze wedstrijd zijn nog geen sessies bekend.</p>
<?php } ?>
