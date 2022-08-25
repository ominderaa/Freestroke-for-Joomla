<?php
require_once JPATH_COMPONENT . '/helpers/conversion.php';
?>
<hr />

<table class="fixedlist" style="width: 99%">
	<thead>
		<tr>
			<th>Zwemmer</th>
			<th class="fs-sm-hidden fs-md-hidden">Gbjr.</th>
			<th>Afstand/zwemslag</th>
			<th style="text-align: right">Tijd</th>
			<th class="fs-sm-hidden" style="text-align: right">Plaats</th>
			<th style="text-align: right"></th>
			<th class="fs-sm-hidden" style="text-align: right">Oude PR</th>
			<th class="fs-sm-hidden" style="text-align: right">Verschil</th>
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
		<td class="fs-sm-hidden fs-md-hidden"><?php echo strftime ( '%Y', strtotime ( $member->birthdate ) ) ?></td>
		<?php
		$resultidx = 0; 
		foreach ($member->results as $result ) { 
			if($resultidx > 0) { ?>
			<tr>
				<td></td>
				<td class="fs-sm-hidden fs-md-hidden"></td>
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
						if ($result->resulttype == "FIN" || $result->resulttype == "EXH") {
							echo FreestrokeConversionHelper::formatSwimtime($result->totaltime);
						}
					}
				?>
				<span class="fs-lg-hidden fs-md-hidden">(<?php if ($result->resulttype == "FIN") echo $result->rank;	?>)</span>
			</td>
			<td class="fs-sm-hidden"  style="text-align: right" >
				<?php if ($result->resulttype == "FIN") echo $result->rank;	?>
			</td>
			<td>
				<?php if ($result->resulttype == "FIN" && $result->rank == 1) {?>
					<img src="components/com_freestroke/assets/images/award_star_gold.png" title="Goud!" />
				<?php } ?>
				<?php if ($result->resulttype == "FIN" && $result->rank == 2) {?>
					<img src="components/com_freestroke/assets/images/medal_silver.png" title="Zilver!" />
				<?php } ?>
				<?php if ($result->resulttype == "FIN" && $result->rank == 3) {?>
					<img src="components/com_freestroke/assets/images/medal_bronze.png" title="Brons!" />
				<?php } ?>
			</td>
			<td class="fs-sm-hidden" style="text-align: right">
				<?php 
						if(isset($result->besttime) && $result->besttime != 0) {
							echo FreestrokeConversionHelper::formatSwimtime($result->besttime);
						} else if (isset($result->entrytime) && $result->entrytime != 0) {
							echo FreestrokeConversionHelper::formatSwimtime($result->entrytime);
						} else {
							echo "--:--";
						}
				?>
			</td>
			<td class="fs-sm-hidden" style="text-align: right">
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
