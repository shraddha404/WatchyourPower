<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/php_header.php";
$details = $_POST;
$locations = $u->getLocationsFromCriteria($details);
?>
<select class="form-control" id="<?php echo $details['location_div_id']; ?>" name="location_to_select"  multiple size="10" >
	<?php foreach($locations as $loc){
	if($loc['status']==0){ continue;} ?>
	<option value="<?php echo $loc['id']; ?>"><?php echo $loc['name']; ?></option>
	<?php } ?>
	</select>

