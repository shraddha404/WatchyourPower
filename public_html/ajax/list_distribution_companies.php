<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/php_header.php";
$details = $_POST;
$consumer_type= $details['consumer_type'];
$district= $details['district'];
$distribution_types = $u->getDistributionCompanies($consumer_type,$district);
?>
<select class="form-control" id="<?php echo $details['distribution_div_id']; ?>" name="<?php echo $details['distribution_div_id']; ?>" >
	<option value="">-- Select  --</option>
	<?php foreach($distribution_types as $dist_type){
	if($dist_type['supply_utility']==''){ continue; }?>
	<option value="<?php echo $dist_type['supply_utility']; ?>"><?php echo $dist_type['supply_utility']; ?></option>
	<?php } ?>
	</select>

