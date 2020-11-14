<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/php_header.php";
$details = $_POST;
$current = $_POST['current'];
//$locations = $u->getLocationsFromCriteria($details);
$locations = $u->getLocationsFromCriteria($_POST);


/*if(!empty($_POST)){
$locations = $u->getLocationsFromCriteria($_POST);
}elseif(empty($_POST)){

$locations = $u->getLocationsFromCriteria($_POST);
}*/



?>
<select class="form-control" id="<?php echo $details['location_div_id']; ?>" name="<?php echo $details['location_div_id']; ?>" <?php if($current=='location_management'){?> onchange="this.form.submit();" required <?php }?> <?php if($details['page']=='compare_locations'){?>style="width:75%;"<?php }?>>
	<option value="">-- Select  --</option>
	<?php foreach($locations as $loc){
	if($loc['status']==0){ continue;} 
	if(preg_match("/Offline/",$loc['name'])){
                $class="offline";
        }
        else{
                $class="";
        }
	?>
	<option class="<?php echo $class;?>" value="<?php echo $loc['id']; ?>"><?php echo $loc['name']; ?></option>
	<?php } ?>
	</select>

