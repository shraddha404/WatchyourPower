<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/admin_header.php";
/*if($_SESSION['user_id']==''){
header('Location:/../index.php');
}*/
$device_details=$_POST;
if($_POST['submit']=='Save'){
        $details = $_POST;

        if($u->addDevice($details)){
                $msg = "<span class=\"message\">Device added successfully.</span>";
        }else{
                $msg = "<span class=\"error\">$u->error</span>";
        }
}

if($_POST['submit']=='Update'){

$details = $_POST;
        if($u->updateDevice($details)){
                $msg = "<span class=\"message\">Device Updated successfully.</span>";
        }
        else{
                $msg = "<span class=\"error\">$u->error</span>";
        }
}

if($_GET['device_id']!='' && $_GET['oper']=='delete'){
        if($u->deleteDevice($_GET['device_id'])){
                $msg = "<span class=\"message\">Device deleted successfully.</span>";
		//header( "refresh:3;url=/admin/devices.php" );
		header( "location:/admin/devices.php" );
        }
        else{
                $msg = "<span class=\"error\">$u->error</span>";
        }
}


if($_GET['device_id']!='' && $_GET['oper']=='update'){
$is_update = true;
$device_id = $_GET['device_id'];
$device_details = $u->getDeviceDetails($device_id);
}
$vendors = $u->getVendors();
$device_status = $u->getDeviceStatus();
$devices = $u->getAllDevices(); 
$remark = $u->getRemark($device_id,'devices');
$main_menu = 'Data Settings';
$current = 'device';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<?php include $_SERVER['DOCUMENT_ROOT']."/admin/include/html_head.php"; ?>
<script>
$(function() {
$( "#installed" ).datetimepicker(
{
format:'d/m/Y',
formatDate:'d/m/Y',
timepicker:false,
closeOnDateSelect:true
});

$( "#date_tested" ).datetimepicker(
{
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
	<form method="post" action="">
	<table class="listing" cellpadding="0" cellspacing="0">
	<tr>
        <td align="center">
        <table class="inputlisting">
	<tr>
	<td>Device Id *</td>
	<td>
	<input type="text" name="device_id_string" value="<?php echo $device_details['device_id_string'];?>" required></td>
	<input type="hidden" name="device_id" value="<?php echo $device_details['id'];?>">
	<input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id'];?>">

	</tr>

	<tr>
	<td>Vendor Name *</td>
	<td>
		<select name="vendor_id" required>
        	<option value="">-- Select  --</option>
       			<?php foreach($vendors as $vendor){ ?>
       		<option value="<?php echo $vendor['vendor_id']; ?>" <?php if($device_details['vendor_id']==$vendor['vendor_id']){ echo 'selected="selected"';} ?>><?php echo $vendor['name']; ?></option>
        		<?php } ?>
        	</select>
	</td>
	</tr>


	<tr>
	<td>Status *</td>
	<td>
		<select name="status" required>
        	<option value="">-- Select  --</option>
       			<?php foreach($device_status as $status){ ?>
       		<option value="<?php echo $status['status_id']; ?>" <?php if($device_details['status']==$status['status_id']){ echo 'selected="selected"';} ?>><?php echo $status['status']; ?></option>
        		<?php } ?>
        	</select>
        </td>
	</tr>
	<tr>
        <td>Date of Receipt *</td>
        <td><input type="text" class="datepick" name="installed" id="installed" value="<?php if($is_update){ echo date('m/d/Y',strtotime($device_details['installed'])); }?>" required></td>
        </tr>
	<tr>
	<td>Software Version *</td>
	<td>	<input type="hidden" name="old_software_version" value="<?php echo $device_details['software_version'];?>">
	<input type="text" name="software_version" value="<?php echo $device_details['software_version'];?>" required></td>
	</tr>
	<tr>
	<td>Tested OK date</td>
	<td>
	<input type="text" class="datepick" id="date_tested" name="date_tested" value="<?php if($is_update){ echo date('m/d/Y',strtotime($device_details['date_tested'])); }?>" ></td>
	</tr>
	<tr>
	<td>Remark</td>
	<td><textarea id="remark" name="remark" class="form-control" rows="5" cols="32" ><?php echo $remark['remark'];?></textarea>
	<!--input type="text" name="remark" value="<?php echo $device_details['remark'];?>" required--></td>
	</tr>

	<tr>
	<td>&nbsp;</td>
	<td><input type="submit" name="submit" value="<?php if($is_update){ echo "Update";   }else{ echo "Save"; }?>"></td>
	</tr>

	</table>
	</td></tr></table>
	</form>

	</div>
	<div class="table" align="center">
	<span style="float:right; margin-bottom:10px; font-weight:bold; font-color:#43729f;"><a href="export_devices.php">Export All</a></span>
	</br>
	<table class="listing" style="width:70%;" align="center">
        <tr>
        <td style="border:none; width:30%;">
        <input type="text" id="device_name" name="device_name" title="Enter device code and press enter to view details" placeholder="Enter device code."/>
        </td>
        <td style="border:none; width:10%;" align="center">OR</td>
        <td style="border:none; width:30%;">
                <select name="device_status" id="device_status" onChange="if($(this).val()==''){ $('#device_data').hide(); } else{ getDeviceDetails($(this).val()); $('#device_data').show();}" >
                <option value="">--Select Status--</option>
                        <?php foreach($device_status as $status){ ?>
                <option value="<?php echo $status['status']; ?>" ><?php echo $status['status']; ?></option>
                        <?php } ?>
		</select>
        </td>
        </table>
	</div>
	
	<div class="table" align="center" id="device_data">
        </div>


	<div class="table">

      </div> <!-- end table class div -->

</div> <!-- end div center column  -->

</div>
<?php include $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php"; ?>
</div>
</body>
</html>
