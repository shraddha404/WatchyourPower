<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/admin_header.php";
$installer_details=$_POST;
if($_POST['submit']=='Save'){
        $details = $_POST;
        if($u->addInstallerPersonal($details)){
                $msg = "<span class=\"message\">Installer personal added successfully.</span>";
        }else{
                $msg = "<span class=\"error\">$u->error</span>";
        }
}

if($_POST['submit']=='Update'){
$details = $_POST;
        if($u->updateInstallerPersonal($details)){
                $msg = "<span class=\"message\">Installer personal updated successfully.</span>";
        }
        else{
                $msg = "<span class=\"error\">$u->error</span>";
        }
}

if($_GET['installer_id']!='' && $_GET['oper']=='delete'){
        if($u->removeInstallerPersonal($_GET['installer_id'])){
                $msg = "<span class=\"message\">Installer personal deleted successfully.</span>";
        }
        else{
                $msg = "<span class=\"error\">$u->error</span>";
        }
}


if($_GET['installer_id']!='' && $_GET['oper']=='update'){
$is_update=true;
$installer_id=$_GET['installer_id'];
$installer_details = $u->getInstallerDetails($installer_id);
}
$installer_personals = $u->getAllInstallerPersonals(); 
$today = time(); 
$six_months_later = strtotime("+6 months", $today);
$main_menu = 'User Settings';
$current = 'installation_presonal';
 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<?php include $_SERVER['DOCUMENT_ROOT']."/admin/include/html_head.php"; ?>
<script>
$(function() {
$("#valid_till").datetimepicker(
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


	<div class="table">
	<form method="post" action="">
	<table class="listing" cellpadding="0" cellspacing="0">
	<tr>
        <td align="center">
        <table class="inputlisting">
	<tr>
	<td>Name *</td>
	<td>
	<input type="text" name="name" value="<?php echo $installer_details['name'];?>" required></td>
	<input type="hidden" name="installer_id" value="<?php echo $installer_details['id'];?>">
	</tr>
	
	<tr>
        <td>Email *</td>
        <td>
        <input type="email" name="email" value="<?php echo $installer_details['email'];?>" required></td>
        </tr>

	<tr>
        <td>Mobile *</td>
        <td>
        <input type="text" name="mobile" value="<?php echo $installer_details['mobile'];?>" required pattern="^[1-9][0-9]{9}$" title="Please enter valid 10 digit mobile number."></td>
        </tr>
	
	<tr>
	<td></td>
	<td><input type="submit" name="submit" value="<?php if($is_update){ echo "Update";   }else{ echo "Save"; }?>"></td>
	</tr>

	</table>
	</td></tr></table>
	</form>

	</div>
	<?php echo $msg;?>
	<div class="table" align="center">
	<table class="listing" cellpadding="0" cellspacing="0" style="width:100%; align:center;">

	<tr>
	<th class="first">Name</th>
	<th>Email</th>
	<th>Mobile</th>
	<th class="last" colspan="3">Action</th>
	</tr>
	<?php foreach($installer_personals as $i_person){?>
	<tr>
	
	<td class="first style1" style="width:auto;"> <?php echo $i_person['name'];?></td>
	<td class="first style1" style="width:auto;"> <?php echo $i_person['email'];?></td>
	<td class="first style1" style="width:auto;"> <?php echo $i_person['mobile'];?></td>
	<td style="text-align:center !important; width:auto;"><a href="installation_personals.php?installer_id=<?php echo $i_person['installer_id'];?>&oper=update" rel="#overlay"><img src="/img/edit-icon.gif" width="16" height="16" alt="edit" /></a></td>
	<td  class="last" style="text-align:center !important; width:auto;"><a href="installation_personals.php?installer_id=<?php echo $i_person['installer_id'];?>&oper=delete" rel="#overlay"><img src="/img/hr.gif" width="16" height="16" alt="" /></a></td>
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
