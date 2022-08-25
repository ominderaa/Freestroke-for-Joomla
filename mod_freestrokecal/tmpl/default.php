<?php
/**
 * @version     1.0.0
* @package     com_freestroke
* @copyright   Copyright (C) 2013. All rights reserved.
* @license     GNU General Public License version 2 or later; see LICENSE.txt
* @author      O.Minderaa <ominderaa@gmail.com> - http://
*/
defined ( '_JEXEC' ) or die('Restricted access');
$dateformat = $params->get('dateformat', 'l, d-m-Y');
?>
<?php if (count($items)) { ?>
	<ul>
	<?php foreach ($items as $item) {  
		if($item->startdate) {?>
		<li>
			<?php echo $item->startdate->format($dateformat, false); ?>
			<br/>
	
		<?php }
			if ($params->get('linkmeet') == 1 and $item->link ) { ?>
			<a href="<?php echo $item->link;?>">
				<?php echo $item->text; ?>
			</a>
			<?php } else {
				echo $item->text;
			}
			?>
		</li>
	<?php } ?>
	</ul>
<?php } ?>
