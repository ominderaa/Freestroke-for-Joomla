<?php 
require_once JPATH_COMPONENT . '/helpers/conversion.php';
if(isset($this->evententries) and isset($sessionnumber)) {?>
<h1>Opstelling</h1>
<div class="items">
	<table class="freestrokecompacttable">
		<tbody>
		<?php
		$show = false;
		$newrow = true;
		foreach ($this->evententries as $event) {
			if($sessionnumber == $event->sessionnumber) {
		?>
			<?php if($newrow === true) { ?>
				<tr>
			<?php }?> 
				<td style="width: 50%;vertical-align: top;padding-bottom: 12px;">
					<?php
					echo '<strong>';
					echo $event->eventnumber . '. ';
					if( $event->relaycount > 1 ) {
							echo $event->relaycount . 'x ';
						}
						echo $event->distance . 'm ' . $event->name . ' ';
					echo '</strong>';
					echo '<br/>';
					?>
						
					<?php 
					if($event->relaycount == 1) {
						foreach($event->entries as $entry ) { 
							echo $entry->firstname . ' ' . $entry->nameprefix . ' ' . $entry->lastname . ' ';
							if($entry->entrytime == 0) {
								echo '(NT)';
							} else {
								echo '(' . FreestrokeConversionHelper::formatSwimtime($entry->entrytime) . ')';
							}
							echo '<br/>';
						} 
					} else {
						foreach($event->teams as $team ) {
							echo '<strong>Team ' . $team->teamnumber . '</strong><br/>';
							foreach($team->entries as $entry ) { 
								echo $entry->ordernumber . '. ' . $entry->firstname . ' ' . $entry->nameprefix . ' ' . $entry->lastname . ' ';
								echo '<br/>';
							} 
						}
					}
					?>
				</td>
			<?php if(!$newrow) {   ?>
				</tr>
			<?php	}
				$newrow = !$newrow;						
		  		}
			} ?>
		</tbody>
	</table>
</div>
<hr /><?php } else { ?>
<p>Van deze wedstrijd is geen opstelling bekend.</p>
<hr/>
<?php } ?>
