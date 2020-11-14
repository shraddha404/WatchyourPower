<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/admin_header.php";

if($_POST['submit']=='Save'){
        $details = $_POST;
        if($u->addVoltageAvgRange($details)){
                $msg = "<span class=\"message\">Voltage Average Range added successfully.</span>";
        }else{
                $msg = "<span class=\"error\">$u->error</span>";
        }
}


if($_POST['submit']=='Update'){
$details = $_POST;
        if($u->updateVoltageAvgRange($details)){
                $msg = "<span class=\"message\">Voltage Average Range Updated successfully.</span>"; 
        }
        else{
                $msg = "<span class=\"error\">$u->error</span>"; } }

if($_GET['avg_id']!='' && $_GET['oper']=='delete'){
        if($u->deleteAvgVoltageRange($_GET['avg_id'])){
                $msg = "<span class=\"message\">Voltage Average Range deleted successfully.</span>";
		#header( "refresh:5;url=/admin/device_installation.php" );
        }
        else{
                $msg = "<span class=\"error\">$u->error</span>";
        }
}


if($_GET['avg_id']!='' && $_GET['oper']=='update'){
$is_update = true;
$avg_id = $_GET['avg_id'];
$VoltageAvgRange = $u->getVoltageAvgRange($avg_id);
}

$VoltageAvgRanges = $u->getVoltageAvgRanges(); 

#print_r($device_installations);

$main_menu = 'Display Settings';
$current = 'avg_vol_setting';
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


$('#installed').on('change', function(){
      $('#installed').datetimepicker('hide');
});

$('#deployed').on('change', function(){
      $('#deployed').datetimepicker('hide');
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
	<?php if($is_update){  ?>
	<input type="hidden" name="avg_id" value="<?php echo $_GET['avg_id']; ?>">
	<?php } ?>
        <table class="inputlisting">
	<tr>
	<td>Title *</td>
	<td><input type="text" name="title" value="<?php echo $VoltageAvgRange['title'];?>" required></td>
	</tr>
	</table>
        <table width="95%">

	<tr>
	<td>&nbsp;</td><td>Low Limit</td><td>High Limit</td><td>Display Color</td>
	</tr>
	<tr>
	<td width="25%">First :</td><td width="25%"><input type="number" min="0" max="24" name="low_limit" value="<?php echo $VoltageAvgRange['low_limit'];?>" required></td>
	<td width="25%"><input type="number" min="0" max="24" name="high_limit" value="<?php echo $VoltageAvgRange['high_limit'];?>" required></td>
	<td width="25%"><select name="display_color" required>
        	<option value="">-- Select  --</option>

		<option value="Red" <?php if($VoltageAvgRange['display_color'] == 'Red'){ echo 'selected'; }?>><?php echo 'Red'; ?></option>
<option value="Green" <?php if($VoltageAvgRange['display_color'] == 'Green'){ echo 'selected'; }?>><?php echo 'Green'; ?></option>
<option value="Blue" <?php if($VoltageAvgRange['display_color'] == 'Blue'){ echo 'selected'; }?>><?php echo 'Blue'; ?></option>
<option value="Yellow" <?php if($VoltageAvgRange['display_color'] == 'Yellow'){ echo 'selected'; }?>><?php echo 'Yellow'; ?></option>

		</select>
	</td>
	</tr>

	<tr>
	<td width="25%">Second :</td><td width="25%"><input type="number" min="0" max="24" name="second_low_limit" value="<?php echo $VoltageAvgRange['second_low_limit'];?>" required></td>
	<td width="25%"><input type="number" min="0" max="24" name="second_high_limit" value="<?php echo $VoltageAvgRange['second_high_limit'];?>" required></td>
	<td width="25%"><select name="second_display_color" required>
        	<option value="">-- Select  --</option>

		<option value="Red" <?php if($VoltageAvgRange['second_display_color'] == 'Red'){ echo 'selected'; }?>><?php echo 'Red'; ?></option>
<option value="Green" <?php if($VoltageAvgRange['second_display_color'] == 'Green'){ echo 'selected'; }?>><?php echo 'Green'; ?></option>
<option value="Blue" <?php if($VoltageAvgRange['second_display_color'] == 'Blue'){ echo 'selected'; }?>><?php echo 'Blue'; ?></option>
<option value="Yellow" <?php if($VoltageAvgRange['second_display_color'] == 'Yellow'){ echo 'selected'; }?>><?php echo 'Yellow'; ?></option>

		</select>
	</td>
	</tr>
	<tr>
	<td width="25%">Third :</td><td width="25%"><input type="number" min="0" max="24" name="third_low_limit" value="<?php echo $VoltageAvgRange['third_low_limit'];?>" required></td>
	<td width="25%"><input type="number" min="0" max="24" name="third_high_limit" value="<?php echo $VoltageAvgRange['third_high_limit'];?>" required></td>
	<td width="25%"><select name="third_display_color" required>
        	<option value="">-- Select  --</option>

		<option value="Red" <?php if($VoltageAvgRange['third_display_color'] == 'Red'){ echo 'selected'; }?>><?php echo 'Red'; ?></option>
<option value="Green" <?php if($VoltageAvgRange['third_display_color'] == 'Green'){ echo 'selected'; }?>><?php echo 'Green'; ?></option>
<option value="Blue" <?php if($VoltageAvgRange['third_display_color'] == 'Blue'){ echo 'selected'; }?>><?php echo 'Blue'; ?></option>
<option value="Yellow" <?php if($VoltageAvgRange['third_display_color'] == 'Yellow'){ echo 'selected'; }?>><?php echo 'Yellow'; ?></option>

		</select>
	</td>
	</tr>
	<tr>
	<td></td>
	<td><input type="submit" name="submit" value="<?php if($is_update){ echo "Update";   }else{ echo "Save"; }?>"></td>
	</tr>
	</table>
	</form>

	</div>
	<div class="table" align="center">
	<table class="listing" cellpadding="0" cellspacing="0" style="width:100%; align:center;">

	<tr>
	<th class="first">Title</th>
	<!--<th >Avg low limit</th>
	<th>Avg high limit</th>
	<th>Display Color</th>-->

	<th colspan="3" class="last">Action</th>
	</tr>

	<?php foreach($VoltageAvgRanges as $VoltageAvgRange){?>
	<tr>
	<td class="first style1" style="width:auto;"> <?php echo $VoltageAvgRange['title'];?></td>
	<!--<td class="style1" style="width:auto;"> <?php echo $VoltageAvgRange['low_limit'];?></td>
	<td class=" style1" style="width:auto;"> <?php echo $VoltageAvgRange['high_limit'];?></td>
	<td class=" style1" style="width:auto;"> <?php echo $VoltageAvgRange['display_color'];?></td>-->
	<td style="text-align:center !important; width:auto;"><a href="average_voltage.php?avg_id=<?php echo $VoltageAvgRange['id'];?>&oper=update" rel="#overlay"><img src="/img/edit-icon.gif" width="16" height="16" alt="edit" /></a></td>
	<td class="last" style="text-align:center !important; widht:auto;"><a href="average_voltage.php?avg_id=<?php echo $VoltageAvgRange['id'];?>&oper=delete" onclick="return confirm('Are you sure you want to delete?');" rel="#overlay"><img src="/img/hr.gif" width="16" height="16" alt="" /></a></td>
	</tr>
	<?php }?>

	</table>
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
