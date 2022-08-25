<?php
require_once JPATH_COMPONENT . '/helpers/conversion.php';
if(count($this->relays) > 0) {
?>
<h2>Estafettes</h2>
<table class="fixedlist">
	<thead>
		<tr>
			<th>Afstand/zwemslag</th>
			<th>Zwemmers</th>
			<th style="text-align: right">Tijd</th>
			<th style="text-align: right">Plaats</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$relayid = -1;
	foreach ( $this->relays as $relay ) { ?>
		<tr>
		<?php if( $relayid != $relay->id ) { ?>
			<td><?php	echo $relay->relaycount . 'x ' . $relay->distance . 'm ' . $relay->name . ' ';?></td>
		<?php } else { ?>
			<td></td>
		<?php } ?>
			<td><?php echo $relay->firstname . ' ' . $relay->nameprefix . ' ' . $relay->lastname;?></td>
		<?php 
		if( $relayid != $relay->id ) { ?>
			<td style="text-align: right">
				<?php
				if ($relay->resulttype == "DSQ") {
						echo "DIS " . $result->comment;
					} else {
						if ($relay->resulttype == "FIN") {
							echo FreestrokeConversionHelper::formatSwimtime ( $relay->totaltime );
						}
					}
				?>
			</td>
			<td style="text-align: right">
				<?php if ($relay->resulttype != "DSQ") echo $relay->rank;	?>
			</td>
		<?php
		} else { ?>
			<td></td>
			<td></td>
		<?php }
		$relayid = $relay->id; ?>
		</tr>
	<?php 		
	}	?>
	</tbody>
</table>
<?php } ?>