<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/php_header.php";


$details = $_POST;
$districts = $u->getDistrictsFromState($_POST['state'],$_POST['category_id']);
/*if(empty($_POST['state']) || $_POST['state']==" " || $_POST['category_id']==" "){
$_POST['state']=" ";
$districts = $u->getDistrictsFromState($_POST['state']);
}elseif(!empty($_POST['state'])  || $_POST['state']!=" "){


}*/
?>
<select class="form-control" id="<?php echo $details['district_div_id']; ?>" name="<?php echo $details['district_div_id']; ?>" <?php if($details['page']=='compare_locations'){?>style="width:75%;"<?php }?>>
<option value="">--Select District--</option>
<?php foreach($districts as $dist){ ?>
<option value="<?php echo $dist['district']; ?>"><?php echo $dist['district']; ?></option>
<?php } ?>
</select>
