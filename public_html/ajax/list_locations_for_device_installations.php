<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/php_header.php";
$details = $_POST;
$locations = $u->getAvailableLocationsFromCriteria($details);
?>
<select class="form-control" id="<?php echo $details['location_div_id']; ?>" name="<?php echo $details['location_div_id']; ?>"  required>
	<option value="">-- Select  --</option>
	<?php foreach($locations as $loc){ ?>
	<option value="<?php echo $loc['id']; ?>"><?php echo $loc['name']; ?></option>
	<?php } ?>
	</select>

