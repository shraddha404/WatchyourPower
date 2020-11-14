<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/php_header.php";
$details = $_POST;
$locations = $u->getLocationsFromCriteria($details);
?>
<select class="form-control" id="location" name="location_name" onChange="getLocationDetails($(this).val()); if($(this).val()==''){$('#location_data').hide(); }else{ $('#location_data').show();}">
	<option value="">--Select--</option>
	<?php foreach($locations as $loc){ ?>
	<option value="<?php echo $loc['name']; ?>"><?php echo $loc['name']; ?></option>
	<?php } ?>
	</select>

