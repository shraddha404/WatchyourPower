<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/admin_header.php";
/*if($_SESSION['user_id']==''){
header('Location:/../index.php');
}*/
$vendor_contact_details= $_POST;
$vendor_details= $_POST;
if($_POST['submit']=='Save'){
        $details = $_POST;
        if($u->addVendor($details)){
                $msg = "<span class=\"message\">Vendor added successfully.</span>";
        }else{
                $msg = "<span class=\"error\"$u->error</span>";
        }
}
if($_POST['submit']=='Update'){
$details = $_POST;
        if($u->updateVendor($details)){
                $msg = "<span class=\"message\">Vendor Updated successfully.</span>";
        }
        else{
                $msg = "<span class=\"error\">$u->error</span>";
        }
}

if($_GET['vendor_id']!='' && $_GET['oper']=='delete'){
        if($u->deleteVendor($_GET['vendor_id'])){
                $msg = "<span class=\"message\">Vendor deleted successfully.</span>";
		//header( "refresh:5;url=/admin/devices.php" );
        }
        else{
                $msg = "<span class=\"error\">$u->error</span>";
        }
}


if($_GET['vendor_id']!='' && $_GET['oper']=='update'){
$is_update=true;
$vendor_id=$_GET['vendor_id'];
$vendor_details = $u->getVendorDetails($vendor_id);
$vendor_contact_details = $u->getVendorContactDetails($_GET['vendor_id']);
}

$main_menu = 'Data Settings';
$current = 'vendors';
$vendors = $u->getVendors();?>

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
        <table class="inputlisting" width="80%">
	<tr>
	<td>Name *</td>
	<td colspan="1">
	<input type="text" name="name"value="<?php echo $vendor_details['name'];?>" required></td>
        <td>Category *</td>
	<td colspan="1">
 	<select name="cat" required>
        <option value="">-- Select  --</option>
	<option value="Device" <?php if($vendor_details['category']=='Device'){ echo 'selected="selected"'; }?>>Device</option>
	<option value="IT" <?php if($vendor_details['category']=='IT'){ echo 'selected="selected"'; }?>>IT</option>
	<option value="Agency" <?php if($vendor_details['category']=='Agency'){ echo 'selected="selected"'; }?>>Agency</option>
	<option value="Sim Card" <?php if($vendor_details['category']=='Sim Card'){ echo 'selected="selected"'; }?>>Sim Card</option>
        </select>
	</td>
	</tr>
	
	<tr>
	<td>Address *</td>
	<td>
	<input type="text" align="left"name="address" value="<?php echo $vendor_details['address'];?>" required>
	</td>
	<td >Pin *</td>
	<td>
	<input type="text" name="pincode" value="<?php echo $vendor_details['pincode'];?>" pattern="^[1-9][0-9]{5}$" title="A six digit number that doesn't begin with zero." required></td>
	</tr>

        <tr>
	<td >City *</td>
	<td>
	<input type="text" name="city" value="<?php echo $vendor_details['city'];?>" required></td>
	<td>State *</td>
	<td>
	<input type="text" name="state" value="<?php echo $vendor_details['state'];?>"required>
	</td>
	</tr>


        <tr>
	<td>Phone *</td>
	<td>
	<input type="text" name="phone" value="<?php echo $vendor_contact_details['phone'];?>" required <?php if($_GET['oper']=='update') { echo "disabled"; }?> >
	</td>
	<td>Mobile Number *</td>
	<td>
	<input type="text" name="mobile" value="<?php echo $vendor_contact_details['mobile'];?>" required pattern="^[1-9][0-9]{9}$" title="Please enter valid 10 digit mobile number." <?php if($_GET['oper']=='update') { echo "disabled"; }?>>
	</td>
	</tr>
	<tr>
	<!--<td>Vendor Code *</td>
	<td>
	<input type="hiden" name="contact_id" value="<?php if($_GET['oper']=='update') { echo $vendor_contact_details['id']; }?>" required>-->
	<!--<input type="text" name="code" value="<?php echo $vendor_details['code'];?>" required>
	</td>-->
	<input type="hidden" name="vendor_id"value="<?php echo $vendor_details['id'];?>">
        <td>Email *</td>
	<td colspan="1">
	<input type="email" name="email" value="<?php echo $vendor_contact_details['email'];?>"required <?php if($_GET['oper']=='update') { echo "disabled"; }?>></td>
	<td colspan="2">&nbsp;</td>
	<td>&nbsp;</td><td>&nbsp;</td><td colspan="2">&nbsp;</td>
</tr>
	<tr>
	<td >Remark</td>
	<td ><textarea id="remark" name="remark" class="form-control" rows="5" cols="32" ><?php echo $vendor_details['remark'];?></textarea>
</td>
	</tr>

	<td>&nbsp;</td>
	<td colspan="2">
	<input type="submit" name="submit" value="<?php if($is_update){ echo 'Update';   }else{ echo 'Save'; }?>"></td>
	</tr>

	</table>
	</td>
	</tr>
	</table>
	</form>


	</div>
	<?php echo $msg;?>
	<div class="table" align="center">
	<table class="listing" cellpadding="0" cellspacing="0">

	<tr>
	<th class="first" width="177">Vendor Id</th>
	<th>Name</th>
	<th>Category</th>
	<th >City</th>
	<th colspan="3"class="last">Action</th>
	</tr>
	<?php foreach($vendors as $vendor){?>
	<tr>
	<td class="first style1" style="width:auto;"><?php echo $vendor['id']; ?> </td>
	<td class="style1" style="width:auto;"><?php echo $vendor['name']; ?></td>
	<td class="style1" style="width:auto;"><?php echo $vendor['category']; ?></td>
	<td class="style1" style="width:auto;"><?php echo $vendor['city']; ?></td>
	<td style="width:5%;"><a href="vendor_form.php?vendor_id=<?php echo $vendor['id'];?>&oper=update" rel="#overlay"><img src="/img/edit-icon.gif" width="16" height="16" alt="edit" /></a>
	</td>
	<td style="width:5%;"><a href="vendor_form.php?vendor_id=<?php echo $vendor['id'];?>&oper=delete" rel="#overlay"><img src="/img/hr.gif" width="16" height="16" alt="" /></a>
	</td>
	<td style="width:18%;" class="last"><a href="vendor_contacts.php?vendor_id=<?php echo $vendor['id'];?>" rel="#overlay">Manage contact Details</a></td>
	
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
