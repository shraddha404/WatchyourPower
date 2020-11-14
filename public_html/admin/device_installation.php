<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/admin_header.php";
$device_installation=$_POST;
if($_POST['submit']=='Save'){
        $details = $_POST;
        if($u->addDeviceInstallation($details)){
                $msg = "<span class=\"message\">Device installation added successfully.</span>";
        }else{
                $msg = "<span class=\"error\">$u->error</span>";
		$details=$_POST;
		$is_update=true;
        }
}


if($_POST['submit']=='Update'){
$details = $_POST;
        if($u->updateDeviceInstallation($details)){
                $msg = "<span class=\"message\">Device installation Updated successfully.</span>"; 
        }
        else{
                $msg = "<span class=\"error\">$u->error</span>"; } }

if($_GET['installation_id']!='' && $_GET['oper']=='delete'){
        if($u->removeDeviceInstallation($_GET['installation_id'])){
                $msg = "<span class=\"message\">Device installation deleted successfully.</span>";
        }
        else{
                $msg = "<span class=\"error\">$u->error</span>";
		$details=$_POST;
		$is_update=true;
        }
}


if($_GET['installation_id']!='' && $_GET['oper']=='update'){
$is_update = true;
$installation_id = $_GET['installation_id'];
$device_installation = $u->getDeviceInstallationDetails($installation_id);
}
$devices = $u->getAvailableDevices();
$sim_cards = $u->getAvailableSimCards();
$locations = $u->getAvailableLocations();

if($is_update){
$device_array = array('device_id'=>$device_installation['device_id'],'device_id_string'=>$device_installation['device_id_string'] );

$sim_array = array('id'=>$device_installation['sim_card_id'],'sim_no'=>$device_installation['sim_no'],'company'=>$device_installation['company']);
$location_array = array('id'=>$device_installation['location_id'],'name'=>$device_installation['name']);

array_push($locations,$location_array);
array_push($sim_cards,$sim_array);
array_push($devices,$device_array);
}

#print_r($device_installation);
$loc_states = $u->getAllStatesFromLocations();
$districts = $u->getDistrics();
$device_installations = $u->getAllDeviceInstallations($_GET);
$installer_personals = $u->getAllInstallerPersonals();
#print_r($location_array);
$main_menu = 'Management';
$current = 'installation_table';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<?php include $_SERVER['DOCUMENT_ROOT']."/admin/include/html_head.php"; ?>
<script>
$(document).on("change","#state, #category_id", function(){
        var obj ={};
        obj.state = $('#state').val();
	obj.district = $('#district').val();
	obj.district_div_id = $('#district').attr('id');
        obj.location_div_id = $('#location_id').attr('id');
	if(obj.state!=''){
        getDistricts(obj,'dist');
        }
        getLocForDevInstall(obj,'location_id');
});
$(document).on("change","#district", function(){
        var obj ={};
        obj.state = $('#state').val();
        obj.district = $('#district').val();
        obj.location_div_id = $('#location_id').attr('id');
        getLocForDevInstall(obj,'location_id');
});
$(document).on("change","#sim_card_company", function(){
	var oper = '<?php echo $_GET['oper'];?>';
        var obj ={};
        obj.sim_card_company = $('#sim_card_company').val();
        obj.simcard_div_id = $('#sim_card_id').attr('id');
	obj.oper = oper;
        getSimcards(obj,'sim_card_id');
});


$(function() {
$( "#installed" ).datetimepicker(
{formatTime:'H:i',
format:'d/m/Y',
formatDate:'d/m/Y',
timepicker:false,
closeOnDateSelect:true
});

$( "#deployed" ).datetimepicker(
{formatTime:'H:i',
format:'d/m/Y',
formatDate:'d/m/Y',
timepicker:false,
closeOnDateSelect:true
});
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
	<?php echo $msg;?>
	<div class="table">
	<form method="post" action="device_installation.php">
	<!--<form method="post" action="device_installation.php" onsubmit="return isDeviceDataPresent();">-->
	<?php if($is_update){  ?>
	<input type="hidden" name="installation_id" value="<?php echo $_GET['installation_id']; ?>">
	<?php } ?>
	<table class="listing" cellpadding="0" cellspacing="0">
	<tr>	<input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id'];?>">
        <td align="center">
        <table class="inputlisting">

	<!--<tr>
	<td >Name *</td>
	<td >
	<input type="text" name="name" value="<?php echo $device_installation['installation_name'];?>" required></td>
	</tr>-->

	<tr>
	<td >Device *</td>
	<td>
	<input type="hidden"  name="old_device_id" value="<?php if($is_update) { echo $device_installation['device_id']; } ?>" />
<select name="device_id" id="device_id" required>
                        <option value="">-- Select Device  --</option>
                <?php foreach($devices as $device){ ?>
                        <option value="<?php echo $device['device_id']; ?>" <?php if($is_update && $device_installation['device_id'] == $device['device_id']){ echo 'selected'; }?> ><?php echo $device['device_id_string']; ?></option>
                <?php } ?>
                </select>
        </td>

	</tr>
	
	<tr>
        <td>State</td>
        <td><select name="state" id="state">
                        <option value="">-- Select  --</option>
                <?php foreach($loc_states as $l_state){ ?>
                        <option value="<?php echo $l_state['state']; ?>" <?php if($is_update && $device_installation['state'] == $l_state['state']){ echo 'selected'; }?> ><?php echo $l_state['state']; ?></option>
                <?php } ?>
                </select>
        </td>
        </tr>
	
	<tr>
	<td>District</td>
	<td id="dist"> <select id="district" name="district" >
                        <option value="">-- Select  District --</option>
                        <?php foreach($districts as $dist){ ?>
                        <option value="<?php echo $dist['district']; ?>" <?php if($is_update && $device_installation['district'] == $dist['district']){ echo 'selected'; }?>><?php echo $dist['district']; ?></option>
                        <?php } ?>
                        </select>
                </td>
	</tr>

	<tr>
	<td >Location *</td>
	<td id="location_id">
	<input type="hidden"  name="is_changed" value="" />
	<input type="hidden"  name="old_location_id" value="<?php if($is_update) { echo $device_installation['location_id']; } ?>" />
		<select name="location_id" id="location_id" required>
        	<option value="">-- Select  --</option>
			<?php foreach($locations as $location){ ?>
		<option value="<?php echo $location['id']; ?>" <?php if($location['id'] == $device_installation['location_id']){ echo 'selected'; }?>><?php echo $location['name']; ?></option>
			<?php } ?>
		</select>
	</td>
	</tr>

	<tr>
	<td >Sim Card Comapny</td>
	<td >
		<select name="sim_card_company" id="sim_card_company">
		<option value="">-- Select --</option>
                <option value="Airtel" <?php if($device_installation['company']=='Airtel'){ echo 'selected';}?>>Airtel</option>
                <option value="Idea" <?php if($device_installation['company']=='Idea'){ echo 'selected';}?>>Idea</option>
                <option value="Tata Docomo" <?php if($device_installation['company']=='Tata Docomo'){ echo 'selected';}?>>Tata Docomo</option>
                <option value="Reliance" <?php if($device_installation['company']=='Reliance'){ echo 'selected';}?>>Reliance</option>
                <option value="Vodafone" <?php if($device_installation['company']=='Vodafone'){ echo 'selected';}?>>Vodafone</option>
		</select>
	</td>
	</tr>
	<tr>
        <td >Sim Card *</td>
        <td id="sim_card_id">
                <select name="sim_card_id" required>
                <option value="">-- Select --</option>
                        <?php foreach($sim_cards as $sim){ ?>
                <option value="<?php echo $sim['id']; ?>" <?php if($sim['id'] == $device_installation['sim_card_id']){ echo 'selected'; }?>><?php echo $sim['sim_no']; ?></option>
                        <?php } ?>
                </select>
        </td>
        </tr>


	<tr>
	<td >Installed On *</td>
	<td >
	<input type="text" name="installed"  class="datepick" value="<?php if($is_update){ echo date('m/d/Y',strtotime($device_installation['installed'])); }?>" id="installed" required readonly="readonly"></td> 
	</tr>

	<tr>
	<td >Deployed On</td>
	<td >
	<input type="text" name="deployed" class="datepick" id="deployed" readonly="readonly" value="<?php if($is_update){ echo date('m/d/Y',strtotime($device_installation['deployed'])); }?>"></td>
	</tr>

	<tr>
	<td >Status *</td>
	<td >
		<select name="status" required>
		<option value="">-- Select --</option>
		<option value="1" <?php if($device_installation['installation_status'] == '1'){ echo 'selected'; }?>>Deployed</option>
		<option value="0" <?php if($device_installation['installation_status'] == '0'){ echo 'selected'; }?>>Testing</option>
		</select>
	</td>
	</tr>
	<tr>
        <td >Installation Personnel *</td>
        <td >
                <select name="installed_by" required>
                <option value="">-- Select --</option>
		<?php foreach($installer_personals as $i_person){ ?>	
                <option value="<?php echo $i_person['id']?>" <?php if($device_installation['installed_by'] == $i_person['id']){ echo 'selected'; }?>><?php echo $i_person['name'];?></option>
		<?php }?>
                </select>
        </td>
        </tr>

	<tr>
	<td >Remark *</td>
	<td ><textarea id="remark" name="remark" class="form-control" rows="5" cols="32" required ><?php echo $device_installation['remark'];?></textarea>
	<!--input type="text" name="remark" value="<?php echo $device_installation['remark'];?>" required--></td>
	</tr>

	<tr>
	<td >&nbsp;</td>
	<td ><input type="submit" name="submit" value="<?php if($is_update){ echo "Update";   }else{ echo "Save"; }?>"></td>
	</tr>
	</table>
	</td></tr></table>
	</form>

	</div>
	<div class="table" align="center">
        <span style="float:right; margin-bottom:10px; font-weight:bold; font-color:#43729f;"><a href="export_device_installation.php">Export All</a></span>
        </br>
        <table class="inputlisting" style="width:70%;" align="center">
	<tr>
        <td style="border:none; width:40%;">
	<input type="text" id="criteria" name="criteria" title="Enter state,district or location name and press enter to view details" placeholder="Enter state/district/location name."/>
        </td>
	<td>OR</td>
        <td style="border:none; width:20%;" align="center">
                <select name="installation_status" id="installation_status" onChange="if($(this).val()==''){ $('#installation_data').hide(); } else{ getDeviceInstallationDetails($(this).val()); $('#installation_data').show();}" >
		<option value="">-- Select Status--</option>
		<option value="1">Deployed</option>
		<option value="0">Testing</option>
                </select>
        </td>
        </table>
        </div>

	<div class="table" align="center" id="installation_data">
	</div>
	

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
