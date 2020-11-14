<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/admin_header.php";
$error_details=$_POST;
if($_POST['submit']=='Save'){
        $details = $_POST;
        if($u->addErrorCode($details)){
                $msg = "<span class=\"message\">Error code added successfully.</span>";
        }else{
                $msg = "<span class=\"error\"$u->error</span>";
        }
}
if($_POST['submit']=='Update'){
$details = $_POST;
        if($u->updateErrorCode($details)){
                $msg = "<span class=\"message\">Error code Updated successfully.</span>";
        }
        else{
                $msg = "<span class=\"error\">$u->error</span>";
        }
}

if($_GET['error_id']!='' && $_GET['oper']=='delete'){
        if($u->deleteErrorCode($_GET['error_id'])){
                $msg = "<span class=\"message\">Error code deleted successfully.</span>";
        }
        else{
                $msg = "<span class=\"error\">$u->error</span>";
        }
}

if($_GET['error_id']!="" && $_GET['oper']=='update'){
$is_update=true;
$error_id=$_GET['error_id'];
$error_details = $u->getError($error_id);
}

$errors = $u->getAllErrors();
$main_menu = 'Error Settings';
$current = 'error_code';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<!--script type="text/javascript">
     
function validate_form ( )
{
	  if ( document.frmvendor.error_code.value == "" )
	{
	alert ( "Please fill 'Error code'." );
	return false;
	}
	if ( document.frmvendor.error_description.value == "" )
	{
	alert ( "Please fill 'Error description'." );
	return false;
	}

alert("sucessfully Submitted");
	return true;
 
 
}
</script-->

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
	

<!--	<div class="table">

	<form method="post" action="" name="frmvendor">

	<table class="listing" cellpadding="0" cellspacing="0">
	<tr>
        <td align="center">
        <table class="inputlisting"><tr>
	<td>Error Code</td>
	<td><input type="text" name="error_code" value="<?php echo $error_details['error_code'];?>" required></td>
	</tr>
	<tr>
	<td>Name</td>
	<td><input type="text" name="error_description"value="<?php echo $error_details['err_str'];?>" required></td>
       <input type="hidden" name="error_id"value="<?php echo $error_details['id'];?>">

	</tr>
	
	
	<tr>
	<td></td>
	<td><input type="submit" name="submit" value="<?php if($is_update){ echo 'Update';   }else{ echo 'Save'; }?>" disabled="true"></td>
	</tr>

	</table>
	</td></tr></table>
	</form>


	</div>-->
	<?php echo $msg;?>
	<div class="table" align="center">
	<table class="listing" cellpadding="0" cellspacing="0" style="width:100%; align:center;">

	<tr>
	<th class="first">Error code</th>
	<th class="last">Description</th>
	
	<!--<th colspan="3"class="last">Action</th>-->
	</tr>
	<?php foreach($errors as $error){?>
	<tr>
	<td class="first style1" style="width:40%;"><?php echo $error['error_code']; ?> </td>
	<td class="last" style="width:60%"><?php echo $error['err_str']; ?></td>
	

	<!--<td><a href="error_code.php?error_id=<?php echo $error['id'];?>&oper=update" rel="#overlay"><img src="/img/edit-icon.gif" width="16" height="16" alt="edit" /></a></td>
	<td><a href="error_code.php?error_id=<?php echo $error['id'];?>&oper=delete" rel="#overlay"><img src="/img/hr.gif" width="16" height="16" alt="" /></a></td>-->
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
