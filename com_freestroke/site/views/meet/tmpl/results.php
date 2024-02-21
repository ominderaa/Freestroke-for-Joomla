<?php
require_once JPATH_COMPONENT . '/helpers/conversion.php';
if(isset($this->members) && count($this->members) > 0) {?>
<table class="freestrokecompacttable" style="width: 99%">
	<thead>
		<tr>
			<th>Zwemmer</th>
			<th>Gbjr.</th>
			<th>Afstand/zwemslag</th>
			<th style="text-align: right">Tijd</th>
			<th style="text-align: right">Plaats</th>
			<th style="text-align: right">Inschrijftijd</th>
			<th style="text-align: right">Verschil</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
	<?php
	foreach ( $this->members as $member ) {	?>
	<tr>
		<td>
			<?php
				echo $member->firstname . ' ' . $member->nameprefix . ' ' . $member->lastname;
				?>
		</td>
		<td><?php echo date_format(date_create($member->birthdate), 'Y' ) ?></td>
		<?php
		$resultidx = 0; 
		foreach ($member->results as $result ) { 
			if($resultidx > 0) { ?>
				<tr><td colspan="2"></td>
		<?php } ?>
			<td>
				<?php
					if ($result->relaycount > 1) {
						echo $result->relaycount . 'x ';
					}
					echo $result->distance . 'm ' . $result->name . ' ';
				?>
			</td>
			<td style="text-align: right">
				<?php
				if ($result->resulttype == "DSQ") {
						echo "DIS " . $result->comment;
					} else {
						if ($result->resulttype == "FIN") {
							echo FreestrokeConversionHelper::formatSwimtime ( $result->totaltime );
						}
					}
				?>
			</td>
			<td style="text-align: right">
				<?php if ($result->resulttype != "DSQ") echo $result->rank;	?>
			</td>
			<td style="text-align: right">
				<?php echo ($result->entrytime == 0) ? '--.--' : FreestrokeConversionHelper::formatSwimtime($result->entrytime);	?>
			</td>
			<td style="text-align: right">
				<?php echo ($result->entrytime == 0) ? '' : $result->difference . '%' ;	?>
			</td>
			<td>
				<?php echo ($result->personalrecord) ? 'PR' : '' ;	?>
			</td>
		</tr>
	<?php  		$resultidx++;
		} 
	}?>
	</tbody>
</table>
<hr /><?php } else { ?>
<p>Van deze wedstrijd zijn geen uitslagen bekend.</p>
<hr/>
<?php } ?>
