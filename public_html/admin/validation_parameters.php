<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/admin_header.php";
/*if($_SESSION['user_id']==''){
header('Location:/../index.php');
}*/
$ValidationParameter= $_POST;
if($_POST['submit']=='Save'){
        $details = $_POST;
        if($u->addValidationParameter($details)){
                $msg = "<span class=\"message\">Validation Parameter added successfully.</span>";
        }else{
                $msg = "<span class=\"error\"$u->error</span>";
        }
}
if($_POST['submit']=='Update'){
$details = $_POST;
        if($u->updateValidationParameter($details)){
                $msg = "<span class=\"message\">Validation Parameter Updated successfully.</span>";
        }
        else{
                $msg = "<span class=\"error\">$u->error</span>";
        }
}

if($_GET['val_param_id']!='' && $_GET['oper']=='delete'){
        if($u->removeValidationParameter($_GET['val_param_id'])){
                $msg = "<span class=\"message\">Validation Parameter deleted successfully.</span>";
		//header( "refresh:5;url=/admin/devices.php" );
        }
        else{
                $msg = "<span class=\"error\">$u->error</span>";
        }
}


if($_GET['val_param_id']!="" && $_GET['oper']=='update'){
$ValidationParameter = $u->getValidationParameter($_GET['val_param_id']);
}

$main_menu = 'Settings';
$current = 'parameters';
$parameters = $u->getAllValidationParameter($_GET);
//print_r($vendor_contacts);?>

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

	<form method="post" action="" name="frmvendor">

	<table class="listing" cellpadding="0" cellspacing="0">
	<tr>
        <td align="center">
        <table border="0" width="80%"><tr>
	<td style="border:none !important;" width="20%">Validation Parameter:</td>
	<td style="border:none !important;" width="80%"><input type="text" name="param" value="<?php echo $ValidationParameter['param'];?>" required></td>
	</tr>
	<tr>
	<td style="border:none !important;" width="20%">description:</td>
	<td style="border:none !important;" width="80%"><input type="text" name="description"value="<?php echo $ValidationParameter['desc'];?>" required></td>


	</tr>
	
	<tr>
	<td style="border:none !important;" width="20%"> Method Name:</td>
	<td style="border:none !important;" width="80%"><input type="text" name="method_name"value="<?php echo $ValidationParameter['method'];?>" required></td>
       <input type="hidden" name="val_param_id"value="<?php echo $ValidationParameter['id'];?>">

	</tr>
	<tr>
	<td style="border:none !important;" width="20%">&nbsp;</td>

<td style="border:none !important;" width="80%"><input type="submit" name="submit" value="<?php if($_GET['oper']=='update' && $_GET['val_param_id']!=''){ echo 'Update';   }else{ echo 'Save'; }?>"></td>
	</tr>
	</table>
	</td></tr></table>
	</form>


	</div>
	<?php echo $msg;?>
	<div class="table">

	<img src="/img/bg-th-left.gif" width="8" height="7" alt="" class="left" /> 
	<img src="/img/bg-th-right.gif" width="7" height="7" alt="" class="right" />

	<table class="listing" cellpadding="0" cellspacing="0">

	<tr>
	<th class="first" width="177">Validation Parameter</th>
	<th>Description</th>
	<th>Method</th>
	<th colspan="3"class="last">Action</th>
	</tr>
	<?php foreach($parameters as $parameter){?>
	<tr>
	<td class="first style1"><?php echo $parameter['param']; ?> </td>
	<td><?php echo $parameter['desc']; ?></td>
	<td><?php echo $parameter['method']; ?></td>

<td><a href="validation_parameters.php?val_param_id=<?php echo $parameter['id'];?>&oper=update" rel="#overlay"><img src="/img/edit-icon.gif" width="16" height="16" alt="edit" /></a></td>
	<td><a href="validation_parameters.php?val_param_id=<?php echo $parameter['id'];?>&oper=delete" rel="#overlay"><img src="/img/hr.gif" width="16" height="16" alt="" /></a></td>
	</tr>
	<?php }?>
	</table>

	</div>
	

	<div class="table">
      </div> <!-- end table class div -->

</div> <!-- end div center column  -->

<div id="right-column">
<?php include $_SERVER['DOCUMENT_ROOT']."/admin/include/right_nav.php"; ?>
</div>

</div>
<?php include $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php"; ?>
</div>

<!-- <div align=center>This template  downloaded form <a href='#'>free website templates</a></div> -->

</body>
</html>
