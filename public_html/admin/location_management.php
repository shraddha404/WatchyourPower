<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/admin_header.php";

//$installer_details=$_POST;
if($_POST['submit'] == 'Save'){
$details = $_POST ;
	if(!empty($details['location_parameters'])){
		if ($u->addLocationValidationParameters($details)){
			$msg = "<span class=\"message\">Location Parameters Saved successfully.</span>";
		}else{
			$msg = "<span class=\"error\">$u->error</span>";
		}
               if ($u->addlocation_voltage_parameter($details)){
			$msg = "<span class=\"message\">Location Parameters Saved successfully.</span>";
		}else{
			$msg = "<span class=\"error\">$u->error</span>";
		}
	}else{
	$msg = "<span class=\"error\">Please select atleast one Parameter Set.</span>";
	}
}
$details = $_GET;
$voltage_parameters = $u->getVoltageRanges();
$voltageAvgRanges = $u->getVoltageAvgRanges(); 
$validation_parameters = $u->getValidationParameters($details);
$location_parameters = $u->getLocationValidationParameters($_GET['location_id']);
$location_voltage_parameters = $u->getlocation_voltage_parameters($_GET['location_id']);
//$locations = $u->getAllLocations($details);
$locations = $u->getLocationsFromCriteria($details);
$loc_states = $u->getAllStatesFromLocations();
$districts = $u->getDistrics();
$main_menu = 'Management';
$current = 'location_management';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<?php include $_SERVER['DOCUMENT_ROOT']."/admin/include/html_head.php"; ?>
<script>
$(document).on("change","#state, #category_id", function(){
	var page = '<?php echo $current;?>';
        var obj ={};
        obj.state = $('#state').val();
        obj.location_div_id = $('#location_id').attr('id');
	obj.current = page;
	if(obj.state!=''){
        getDistricts(obj,'dist');
        }
        getLocations(obj,'location_id');
});
$(document).on("change","#district", function(){
	var page = '<?php echo $current;?>';
        var obj ={};
        obj.state = $('#state').val();
        obj.category_id = $('#category_id').val();
        obj.district = $('#district').val();
        obj.location_div_id = $('#location_id').attr('id');
        getLocations(obj,'location_id');
});

$(function() {
   $("#listing").tablesorter({debug: true});
});

</script>
</head>
<body>
<div id="main">

<div id="header"> 
<?php include $_SERVER['DOCUMENT_ROOT']."/admin/include/menu.php"; ?>

</div>

<div id="middle">

<div id="left-column">
	<?php include $_SERVER['DOCUMENT_ROOT']."/admin/include/left_nav.php"; ?>
</div>

<div id="center-column">
	
	<?php include $_SERVER['DOCUMENT_ROOT']."/admin/include/bredcrumb.php"; ?>

	<form method="get" action="">

	<div class="table">
	<table class="listing" cellpadding="0" cellspacing="0">
        <tr>
        <td align="center">

        <table class="inputlisting">
	<tr>
        <td>State</td>
        <td><select name="state" id="state">
                        <option value="">-- Select  --</option>
                <?php foreach($loc_states as $l_state){ ?>
                        <option value="<?php echo $l_state['state']; ?>" ><?php echo $l_state['state']; ?></option>
                <?php } ?>
                </select>
        </td>
	<tr>
        <td>District</td>
        <td id="dist"> <select id="district" name="district" >
                        <option value="">-- Select  District --</option>
                        <?php foreach($districts as $dist){ ?>
                        <option value="<?php echo $dist['district']; ?>"><?php echo $dist['district']; ?></option>
                        <?php } ?>
                        </select>
                </td>
        </tr>

        <tr>
        <td>Location *</td>
        <td id="location_id">
		<select name="location_id"  onchange="this.form.submit();"required>
		<option value="" required>- Select Location -</option>
		<?php foreach($locations as $location) {?>
		<option value="<?php echo $location['id']; ?>" <?php if($_GET['location_id'] == $location['id']){ echo "selected"; }?>><?php echo $location['name']; ?></option>
		<?php } ?>
		</select>
	</td>
        </tr>
	</table>

	</td>
	</tr>
	</table>
	</form>

	</div>

	<?php echo $msg;?>

	<?php if(!empty($validation_parameters)){ ?>
	<form method="post" action="">
	<input type="hidden" name="location_id" value="<?php echo $_GET['location_id']; ?>">
	<div class="table" align="left">
<table><tr><td valign="top" width="55%">
	<table class="listing tablesorter" id="listing" cellpadding="0" cellspacing="0" style="width:90%; align:center;">
	<thead>
	<tr>
	<th class="first">Validation Settings</th>
	<th class="last">Apply</th>
	</tr>
	</thead>

	<tbody>
	<?php foreach($validation_parameters as $parameter){?>
	<tr>
	<td class="first style1" style="width:auto;"> <?php echo $parameter['param']; ?> </td>
	<td class="last" style="width:10%;">
	<input type="checkbox" name="location_parameters[]" value="<?php echo $parameter['id'];?>" <?php if($location_parameters[$parameter['id']]){ echo 'checked'; } if($parameter['compulsory']){ echo 'checked disabled=\"true\"';} ?>>
	</td>
	</tr>
	<?php } ?>

	

	</tbody>
	</table>
</td><td valign="top">
<table class="listing tablesorter" id="listing" cellpadding="0" cellspacing="0" style="width:100%; align:center; float:left;">
	<thead>
	<tr>
	<th class="first">Voltage Range Settings</th>
	<th class="last">Apply</th>
	</tr>
	</thead>

	<tbody>
	<?php foreach($voltage_parameters as $volparameter){?>
	<tr>
	<td class="first style1" style="width:auto;"> <?php echo $volparameter['title']; ?> </td>
	<td class="last" style="width:auto;"><?php echo $location_parameters['voltage_range_id'];?>
	<input type="radio" name="voltage_range_id" value="<?php echo $volparameter['id'];?>" <?php if($location_voltage_parameters['voltage_range_id']==$volparameter['id']){ echo 'checked'; } ?>>
	</td>
	</tr>
	<?php } ?>

	

	</tbody>
	</table>
<table class="listing tablesorter" id="listing" cellpadding="0" cellspacing="0" style="width:100%; align:center; float:right;margin-top:20px; ">
	<thead>
	<tr>
	<th class="first">Pin Colour Settings</th>
	<th class="last">Apply</th>
	</tr>
	</thead>

	<tbody>
	<?php foreach($voltageAvgRanges as $volavgparameter){?>
	<tr>
	<td class="first style1" style="width:auto%;"> <?php echo $volavgparameter['title'];  ?></td>
	<td class="last" style="width:auto%;">
		<input type="radio" name="avg_voltage_id" value="<?php echo $volavgparameter['id'];?>"  <?php if($location_voltage_parameters['voltage_average_id']==$volavgparameter['id']){ echo 'checked'; } ?>>
	</td>
	</tr>
	<?php } ?>

	
<tr>
	</tr>
	</tbody>
	</table>

</td></tr></table>
	</div>
	<input style="margin-left:400px !important;"align="center" type="submit" name="submit" value="<?php if($is_update){ echo "Update";   }else{ echo "Save"; } ?>">
	</form>
	<?php } ?>

	<div class="table">

      </div> <!-- end table class div -->

</div> <!-- end div center column  -->

<!--<div id="right-column">
<?php include $_SERVER['DOCUMENT_ROOT']."/admin/include/right_nav.php"; ?>
</div>-->

</div>
<?php include $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php"; ?>
</div>

<!-- <div align=center>This template  downloaded form <a href='#'>free website templates</a></div> -->

</body>
</html>
