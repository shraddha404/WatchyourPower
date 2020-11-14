<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/admin_header.php";
global $pdo;		

if($_POST['mobile']=='Update'){
$details = $_POST;
        if($u->updateDefaultConfig($details)){
                $msg = "<span class=\"message\">Default values Updated successfully.</span>"; 
        }
        else{
                $msg = "<span class=\"error\">$u->error</span>"; 
            }
 }
if($_POST['avg']=='Update'){
$details = $_POST;
        if($u->updateDefaultConfig($details)){
                $msg = "<span class=\"message\">Default values Updated successfully.</span>"; 
        }
        else{
                $msg = "<span class=\"error\">$u->error</span>"; 
            }
 }
if($_POST['voltage']=='Update'){
$details = $_POST;
        if($u->updateDefaultConfig($details)){
                $msg = "<span class=\"message\">Default values Updated successfully.</span>"; 
        }
        else{
                $msg = "<span class=\"error\">$u->error</span>"; 
            }
 }
if($_POST['email']=='Update'){
$details = $_POST;
        if($u->updateDefaultConfig($details)){
                $msg = "<span class=\"message\">Default values Updated successfully.</span>"; 
        }
        else{
                $msg = "<span class=\"error\">$u->error</span>"; 
            }
 }
$app_config = getConfig(); 
$default_voltage_parameter=$app_config['default_voltage_parameter'];
$vol_id = $u->getConfigID($default_voltage_parameter);

$default_avg_parameter=$app_config['default_avg_parameter'];
$avg_id = $u->getConfigID($default_avg_parameter);

$default_email=$app_config['default_email'];
$email_id = $u->getConfigID($default_email);

$default_mobile=$app_config['default_mobile'];
$mobile_id = $u->getConfigID($default_mobile);
$configs = $u->getConfigValue($_GET); 
//$locations = $u->getAllLocations($_GET);
//$sim_cards = $u->getAllSimCards($_GET);
//$device_installations = $u->getAllDeviceInstallations($_GET);
//print_r($configs);

$voltage_parameters = $u->getVoltageRanges();
$voltageAvgRanges = $u->getVoltageAvgRanges(); 
$main_menu = 'Data Settings';
$current = 'default';
#[$current]['url'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<?php include $_SERVER['DOCUMENT_ROOT']."/admin/include/html_head.php"; ?>
<script>
$(function() {
$( "#installed" ).datepicker();
$("#deployed").datepicker();
});
$('#installed').on('change', function(){
      $('#installed').datetimepicker('hide');
});
$('#deployed').on('change', function(){
      $('#deployed').datetimepicker('hide');
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
	<?php //include $_SERVER['DOCUMENT_ROOT']."/admin/include/bredcrumb.php"; ?>

	<!--
	<div class="select-bar">
	<label>
	<input type="text" name="textfield" />
	</label>
	<label>
	<input type="submit" name="Submit" value="Search" />
	</label>
	</div>-->

	<div class="table">

	
	<table class="listing" cellpadding="0" cellspacing="0"width="100%">
	<tr>
        <td align="center">
        <table class="inputlisting" width="80%">
	<form method="post" action="">
              <tr>
		<td width="40%">Voltage Parameter *</td>
		<td>
		<select name="param" required>
		<?php foreach($voltage_parameters as $p){ ?>
			<option value="<?php echo $p['id'];?>" <?php if($p['id']== $default_voltage_parameter) { echo "selected";}?>><?php echo $p['title'];?></option>
		<?php } ?>
		</select>
<!--		<input type="text" name="param" value="<?php echo $default_voltage_parameter;?>" required>--></td>
<input type="hidden"name="id" value="<?php echo $vol_id['id'];?>">
		<td><input type="submit" value="Update" name="voltage"></td>
		</tr>
        </form>

	<form method="post" action="">
		<tr>
		<td>Avg Parameter *</td>
		<td>
		<select name="param" required>
		<?php foreach($voltageAvgRanges as $p){ ?>
			<option value="<?php echo $p['id'];?>" <?php if($p['id']== $default_voltage_parameter) { echo "selected";}?>><?php echo $p['title'];?></option>
		<?php } ?>
		</select>
		<!--<input type="text" name="param" value="<?php echo $default_avg_parameter;?>" required>-->
<input type="hidden"name="id"value="<?php echo $avg_id['id'];?>">
		</td>
		<td><input type="submit" value="Update" name="avg"></td>
		</tr>
        </form>

       	<form method="post" action="">
		<tr>
		<td>Email *</td>
		<td>
		<input type="text" name="param" value="<?php echo $default_email;?>" required>
<input type="hidden"name="id"value="<?php echo $email_id['id'];?>">
		</td>
		<td><input type="submit" value="Update" name="email"></td>
		</tr>
        <form>
      	<form method="post" action="">
		<tr>
		<td>Mobile *</td>
		<td>
		<input type="text" name="param" value="<?php echo $default_mobile;?>" required>
<input type="hidden"name="id"value="<?php echo $mobile_id['id'];?>">
		</td>
		<td><input type="submit" value="Update" name="mobile"></td>

		</tr>
	</form>
	</table>
	</td></tr></table>


	</div>
	<?php echo $msg;?>
	<!--div class="table">
	<img src="/img/bg-th-left.gif" width="8" height="7" alt="" class="left" /> 
	<img src="/img/bg-th-right.gif" width="7" height="7" alt="" class="right" />

	<table class="listing" cellpadding="0" cellspacing="0">

	<tr>
	<th class="first" style="width:50% !important;">Parameter</th>
	<th style="width:5% !important;">Value</th>
	<th style="width:5% !important;" class="last">Edit</th>
	</tr>

	<?php foreach($configs as $config){?>
	<tr>
	<td class="first style1"> <?php echo $config['param'];?></td>
	<td class="style1"> <?php echo $config['value'];?></td>
	<td style="text-align:center !important;"><a href="device_installation.php?installation_id=<?php echo $installation['id'];?>&oper=update" rel="#overlay"><img src="/img/edit-icon.gif" width="16" height="16" alt="edit" /></a></td>
		</tr>
	<?php }?>

	</table>
	</div-->
	

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
