<?php 
require_once JPATH_COMPONENT . '/helpers/conversion.php';
require_once JPATH_COMPONENT . '/helpers/freestroke.php';
if(isset($meetsession->events)) {?>
<hr />
<?php if(isset($meetsession->message) && strlen($meetsession->message) > 0) { ?>
<div class="fs-sessionmessage"><?php echo $meetsession->message;?></div>
<?php } ?>
<div class="fs-itemtable">
		<?php
		$show = false;
		$newrow = true;
		foreach ($meetsession->events as $event) {		?>
			<div class="fs-itemcontainer">
				<div class="fs-itemheader">
					<?php
					echo $event->programnumber . '. ';
					if( $event->relaycount > 1 ) {
							echo $event->relaycount . 'x ';
						}
						echo $event->distance . 'm ' . $event->name . ' ';
						echo FreestrokeHelper::MapAgeCategory($event->gender, $event->minage, $event->maxage);
					?>
				</div>
				<div class="fs-itemcontent">
					<?php 
					if($event->relaycount == 1) {
						if(isset($event->entries)) {
							foreach($event->entries as $entry ) { 
								echo $entry->firstname . ' ' . $entry->nameprefix . ' ' . $entry->lastname . ' ';
								if($entry->entrytime == 0) {
									echo '(NT)';
								} else {
									echo '(' . FreestrokeConversionHelper::formatSwimtime($entry->entrytime);
									echo '&nbsp;&nbsp;' . FreestrokeConversionHelper::formatDate($entry->entrytimedate) . ')';
								}
								echo '<br/>';
							}
						} 
					} else {
					    if(isset($event->teams)) {
    						foreach($event->teams as $team ) {
    							echo '<strong>Team ' . $team->teamnumber . '</strong><br/>';
    							foreach($team->entries as $entry ) { 
    								echo $entry->ordernumber . '. ' . $entry->firstname . ' ' . $entry->nameprefix . ' ' . $entry->lastname . ' ';
    								echo '<br/>';
    							} 
    						}
					    }
					} ?>
				</div>
			</div>
		<?php }			?>
</div>
<?php } else { ?>
<p>Van deze wedstrijd is nog geen opstelling bekend.</p>
<?php } ?>
