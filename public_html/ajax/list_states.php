<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/php_header.php";

$details = $_POST;
$states = $u->getStatesFromCategory($_POST['category_id']);
/*if(empty($_POST['category_id'])  || $_POST['category_id']==" "){
$_POST['category_id']=" ";
$states = $u->getStatesFromCategory($_POST['category_id']);
}elseif(!empty($_POST['category_id'])  || $_POST['category_id']!=" "){
$states = $u->getStatesFromCategory($_POST['category_id']);
}/*/
?>
<select class="form-control" id="<?php echo $details['state_div_id']; ?>" name="<?php echo $details['state_div_id']; ?>" <?php if($details['page']=='compare_locations' ){?> style="width:75%;"<?php }?>>
<option value="">--Select State--</option>
<?php foreach($states as $state){ ?>
<option value="<?php echo $state['state']; ?>"><?php echo $state['state']; ?></option>
<?php } ?>
</select>
