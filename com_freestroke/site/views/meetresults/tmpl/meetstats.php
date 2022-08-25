<hr>
<p>
Totaal <?php echo $this->countofresults; ?> persoonlijke uitslagen. <?php echo $this->amountofpersonalrecords; ?> nieuwe persoonlijke records.

<?php echo $this->goldcount;?> keer <img src="components/com_freestroke/assets/images/award_star_gold.png" title="Goud!" />
<?php echo $this->silvercount;?> keer <img src="components/com_freestroke/assets/images/medal_silver.png" title="Goud!" />
<?php echo $this->bronzecount;?> keer <img src="components/com_freestroke/assets/images/medal_bronze.png" title="Goud!" />

<br/>
Gemiddelde prestatie: <?php echo $this->agerageimprovement?>%.<br/>
Grootste verbetering: <?php echo $this->bestimprovedresult->firstname . ' ' . $this->bestimprovedresult->nameprefix . ' ' . $this->bestimprovedresult->lastname . ' ';
   echo $this->bestimprovedresult->distance . 'm ' . $this->bestimprovedresult->name . ', ' .
        FreestrokeConversionHelper::formatSwimtime ( $this->bestimprovedresult->totaltime ) .
		' ' . $this->bestimprovedresult->difference . '%';  ?>
</p>
