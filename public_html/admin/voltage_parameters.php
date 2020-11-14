<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/admin_header.php";
/*if($_SESSION['user_id']==''){
header('Location:/../index.php');
}*/

$main_menu = 'Data Settings';
$current = 'parameters';
$params=$u->getAllVoltageParams();
$voltage_params=$_POST;
if($_POST['submit']!=''){
	$data=array();
	$data[$params['no_supply']]['low']=$_POST['no_supply-low'];
	$data[$params['no_supply']]['high']=$_POST['no_supply-high'];
//	$data[$params['very_low']]['low']=$_POST['very_low-low'];
//	$data[$params['very_low']]['high']=$_POST['very_low-high'];
	$data[$params['low']]['low']=$_POST['low-low'];
	$data[$params['low']]['high']=$_POST['low-high'];
	$data[$params['normal']]['low']=$_POST['rated-low'];
	$data[$params['normal']]['high']=$_POST['rated-high'];
	$data[$params['high']]['low']=$_POST['high-low'];
	$data[$params['high']]['high']=$_POST['high-high'];
	if($_POST['submit'] =='Save'){
        	if($u->addVoltageRangeParam($_POST['title'],$data)){
                	$msg = "<span class=\"message\">Voltage range added successfully.</span>";
	        }else{
        	        $msg = "<span class=\"error\"$u->error</span>";
	        }
	}else if($_POST['submit'] =='Update'){
		$form_data=array();
		$form_data['id']=$_POST['id'];
		$form_data['title']=$_POST['title'];
		if($_POST['title']!= $_POST['title_bk']){
			$form_data['flag']=1;
		}else{
			$form_data['flag']=0;
		}
		if($u->updateVoltageRangeParam($form_data,$data)){
                	$msg = "<span class=\"message\">Voltage range updated successfully.</span>";
		}else{
			$msg = "<span class=\"error\"$u->error</span>";
		}
	}

}
if($_GET['id']!='' && $_GET['oper']=='delete'){
        if($u->deleteVoltageRangeParam($_GET['id'])){
                $msg = "<span class=\"message\">Voltage range deleted successfully.</span>";
		header( "refresh:5;url=/admin/voltage_parameters.php" );
        }
        else{
                $msg = "<span class=\"error\">$u->error</span>";
        }
}
else if($_GET['oper'] == 'update'){
	$voltage_params=$u->getVoltageParamValues($_GET['id']);
}
$voltage_ranges=$u->getVoltageRanges();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<?php include $_SERVER['DOCUMENT_ROOT']."/admin/include/html_head.php"; ?>
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

	<div class="table">
	<form method="post" action="#" > 
	<table class="listing" cellpadding="0" cellspacing="0">
	<tr>
	<td align="center">
	<table class="inputlisting">
	<tr>
	<td>Title *</td>
	<td><input type="text" name="title" size="26" value="<?php echo $voltage_params['no_supply']['title']; ?>" required > <input type="hidden" name="title_bk" size="26" value="<?php echo $voltage_params['no_supply']['title']; ?>" ><input type="hidden" name="id" size="26" value="<?php echo $voltage_params['no_supply']['voltage_range_id']; ?>" >
	</td>
	</tr>
<?php //foreach($params as $param){ ?>
	<tr>
	<td>No supply *</td>
	<td><input type="text" name="no_supply-low"  size="8" value="<?php if($voltage_params['no_supply']['low_value']!=''){echo $voltage_params['no_supply']['low_value']; } ?>" required >&nbsp;to &nbsp;<input type="text" name="no_supply-high" size="8" value="<?php if($voltage_params['no_supply']['high_value']!=''){echo $voltage_params['no_supply']['high_value']; } ?>" required>
	</td>
	</tr>
	<!--<tr>
	<td>Very low</td>
	<td><input type="text" name="very_low-low"  size="8" value="<?php if($voltage_params['very_low']['low_value']!=''){echo $voltage_params['very_low']['low_value']; } ?>"  required >&nbsp;to &nbsp;<input type="text" name="very_low-high" size="8" value="<?php if($voltage_params['very_low']['high_value']!=''){echo $voltage_params['very_low']['high_value']; } ?>"  required>
	</td>
	</tr>-->
	<tr>
	<td>Low *</td>
	<td><input type="text" name="low-low"  size="8" value="<?php if($voltage_params['low']['low_value']!=''){echo $voltage_params['low']['low_value']; } ?>" required >&nbsp;to &nbsp;<input type="text" name="low-high" size="8" value="<?php if($voltage_params['low']['high_value']!=''){echo $voltage_params['low']['high_value']; } ?>" required>
	</td>
	</tr>
	<tr>
	<td>Normal *</td>
	<td><input type="text" name="rated-low"  size="8" value="<?php if($voltage_params['normal']['low_value']!=''){echo $voltage_params['normal']['low_value']; } ?>" required >&nbsp;to &nbsp;<input type="text" name="rated-high" size="8" value="<?php if($voltage_params['normal']['high_value']!=''){echo $voltage_params['normal']['high_value']; } ?>" required>
	</td>
	</tr>
	<tr>
	<td>High *</td>
	<td><input type="text" name="high-low"  size="8" value="<?php if($voltage_params['high']['low_value']!=''){echo $voltage_params['high']['low_value']; } ?>" required >&nbsp;to &nbsp;<input type="text" name="high-high" size="8" value="<?php if($voltage_params['high']['high_value']!=''){echo $voltage_params['high']['high_value']; } ?>" required>
	</td>
	</tr>
	<!--<tr>
	<td >No data</td>
	<td >&nbsp;<input type="text" name="no_data-low"  size="8" disabled >&nbsp;to &nbsp;<input type="text" name="no_data-high" size="8" disabled>
	</td>
	</tr>-->
	<?php// } ?>
	<tr>
	<td></td>
	<td><input type="submit" name="submit" value="<?php if($_GET['oper']=='update' && $_GET['id']!=''){ echo "Update";   }else{ echo "Save"; }?>"></td>
	</td>
	</tr>
	</table>
</td></tr></table>
	</form>

	</div>
	<?php echo $msg;?>
	<div class="table" align="center">

	<table class="listing" cellpadding="0" cellspacing="0" style="width:100%; align:center;">


	<tr>
	<th class="first">Voltage Range</th>
	<th class="last">Action</th>
	</tr>
	<?php foreach($voltage_ranges as $range){?>
	<tr>
	
	<td class="first style1" style="width:auto;"> <?php echo $range['title'];?></td>
	<td style="text-align:center !important; width:auto;"><a href="voltage_parameters.php?id=<?php echo $range['id'];?>&oper=update" rel="#overlay"><img src="/img/edit-icon.gif" width="16" height="16" alt="edit" /></a></td>
	<!--<td style="text-align:center !important; width:auto;"><a href="voltage_parameters.php?id=<?php echo $range['id'];?>&oper=delete" rel="#overlay"><img src="/img/hr.gif" width="16" height="16" alt="" /></a></td>-->
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
