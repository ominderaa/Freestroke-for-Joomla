<?php 
require_once JPATH_COMPONENT . '/helpers/freestroke.php';

if(isset($this->events) && count($this->events) > 0) {?>

<h1>Programma</h1>
<div class="items">
	<table  style="width: 99%">
		<tbody>
		<?php
		$newrow = true;
		foreach ($this->events as $event) {
			if($sessionnumber == $event->sessionnumber ) {
		?>
			<?php if($newrow === true) { ?>
				<tr>
			<?php } ?> 
				<td> <?php echo $event->programnumber . '.';?></td>
				<td>
					<?php 
						if( $event->relaycount > 1 ) {
							echo $event->relaycount . 'x ';
						}
						echo $event->distance . 'm ' . $event->name . ' ';
						//echo JText::_('COM_FREESTROKE_GENDER_' . $event->gender) . ' ';
						echo FreestrokeHelper::MapAgeCategory($event->gender, $event->minage, $event->maxage);
						//echo $event->minage . '-' . $event->maxage . ' jaar';
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
<?php	} ?>