<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/php_header.php";
$details = $_POST;
$districts = $u->getDistrictsFromState($details['state']);
?>
<select class="form-control" id="<?php echo $details['district_div_id']; ?>" name="<?php echo $details['district_div_id']; ?>">
<option value="">--Select District--</option>
<?php foreach($districts as $dist){ ?>
<option value="<?php echo $dist['district']; ?>"><?php echo $dist['district']; ?></option>
<?php } ?>
</select>
