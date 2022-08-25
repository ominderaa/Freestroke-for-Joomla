<?php 
require_once JPATH_COMPONENT . '/helpers/conversion.php';
require_once JPATH_COMPONENT . '/helpers/freestroke.php';
if(isset($this->members)) {?>
<hr />
<?php if(isset($meetsession->message) && strlen($meetsession->message) > 0) { ?>
<div class="fs-sessionmessage"><?php echo $meetsession->message;?></div>
<?php } ?>
<div class="fs-itemtable">
		<?php $row = 0; $show = false;
		foreach ($this->members as $member) { ?>
				<div class="fs-itemcontainer">
					<div class="fs-itemheader">
							<?php echo $member->firstname . ' ' . $member->nameprefix . ' ' . $member->lastname . ' '.$member->registrationid; ?>				
					</div>
					
					<div class="fs-itemcontent">
						<table>
					<?php foreach($member->events as $event) {
					if($sessionnumber == $event->sessionnumber) { ?>
						<tr>
							<td width="25px">&nbsp;<?php echo $event->eventnumber . '.';?></td>
							<td><?php 
								if( $event->relaycount > 1 ) echo $event->relaycount . 'x ';
								echo $event->distance . 'm ' . $event->name . ' ';
								if( $event->relaycount > 1 ) echo 'pos. ' . $event->ordernumber;
							?>
							</td>
							<td nowrap>
							<?php 
								if($event->entrytime == 0) {
									echo 'NT';
								} else {
									echo FreestrokeConversionHelper::formatSwimtime($event->entrytime) . ' ';
									echo FreestrokeConversionHelper::formatDate($event->entrytimedate);
								} ?>
							</td>
						</tr>
					<?php } ?>
				<?php } ?>
					
					</table>
				</div>
			</div>
		<?php } ?>
</div>
<?php } else { ?>
<p>Van deze wedstrijd is nog geen opstelling bekend.</p>
<?php } ?>
