<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/admin_header.php";
/*if($_SESSION['user_id']==''){
header('Location:/../index.php');
}*/
$vendor_contact_details= $_POST;
if($_POST['submit']=='Save'){
        $details = $_POST;
        if($u->addVendorContact($details)){
                $msg = "<span class=\"message\">Vendor Contact added successfully.</span>";
        }else{
                $msg = "<span class=\"error\">$u->error</span>";
        }
}
if($_POST['submit']=='Update'){
$details = $_POST;
        if($u->updateVendorContact($details)){
                $msg = "<span class=\"message\">Vendor Contact Updated successfully.</span>";
        }
        else{
                $msg = "<span class=\"error\">$u->error</span>";
        }
}

if($_GET['vendor_contact_id']!='' && $_GET['oper']=='delete'){
        if($u->removeVendorContact($_GET['vendor_contact_id'])){
                $msg = "<span class=\"message\">Vendor Contact deleted successfully.</span>";
		//header( "refresh:5;url=/admin/devices.php" );
        }
        else{
                $msg = "<span class=\"error\">$u->error</span>";
        }
}


if($_GET['vendor_id']!="" && $_GET['vendor_contact_id']==""){
$vendor_contact_details = $u->getVendorContactDetails($_GET['vendor_id']);
}
elseif($_GET['vendor_contact_id']){
$vendor_contact_details = $u->getVendorContactDetail($_GET['vendor_contact_id']);
}
$main_menu = 'Settings';
$current = 'vendors';
$vendor_contacts = $u->getAllVendorContacts($_GET['vendor_id']);
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

	<form method="post" action="" name="frmvendor">

	<table class="listing" cellpadding="0" cellspacing="0">
	<tr>
        <td align="center">
        <table border="0" width="80%">
	<!--<tr>
	<td style="border:none !important;" width="20%">Vendor Code:</td>
	<td style="border:none !important;" width="80%"><input type="text" name="code" value="<?php echo $vendor_contact_details['vendor_id'];?>" required></td>
	</tr>-->
	<tr>
	<td style="border:none !important;" width="20%">Name:</td>
	<td style="border:none !important;" width="80%"><input type="text" name="name"value="<?php echo $vendor_contact_details['name'];?>" required></td>
       <input type="hidden" name="vendor_contact_id"value="<?php echo $vendor_contact_details['id'];?>">
       <input type="hidden" name="vendor_id"value="<?php echo $_GET['vendor_id'];?>">
	</tr>
	
	<tr>
	<td style="border:none !important;" width="20%">Display Order :</td>
	<td style="border:none !important;" width="80%" align="left"><input type="text" align="left"name="display_order" value="<?php echo $vendor_contact_details['display_order'];?>" required></td>
	</tr>

       
	
        <tr>
	<td style="border:none !important;" width="20%">Phone:</td>
	<td style="border:none !important;" width="80%"><input type="text" name="phone" value="<?php echo $vendor_contact_details['phone'];?>" required ></td>
	</tr>
	
        <tr>
	<td style="border:none !important;" width="20%">Mobile Number:</td>
	<td style="border:none !important;" width="80%"><input type="text" name="mobile" value="<?php echo $vendor_contact_details['mobile'];?>" required pattern="^[1-9][0-9]{9}$" title="Please enter valid 10 digit mobile number."></td>
	</tr>
	<tr>
        <td style="border:none !important;" width="20%">Email:</td>
	<td style="border:none !important;" width="80%"><input type="email" name="email" value="<?php echo $vendor_contact_details['email'];?>" required></td></tr>
	<tr>
	<td style="border:none !important;" width="20%">&nbsp;</td>

<td style="border:none !important;" width="80%"><input type="submit" name="submit" value="<?php if($_GET['oper']=='update' && $_GET['vendor_id']!=''){ echo 'Update';   }else{ echo 'Save'; }?>"></td>
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
	<th class="first" width="177">Vendor code</th>
	<th>Display Order</th>
	<th>Name</th>
	<th >Email</th>
	<th colspan="3"class="last">Action</th>
	</tr>
	<?php foreach($vendor_contacts as $contacts){?>
	<tr>
	<td class="first style1"><?php echo $contacts['vendor_id']; ?> </td>
	<td><?php echo $contacts['display_order']; ?></td>
	<td><?php echo $contacts['name']; ?></td>
	<td><?php echo $contacts['email']; ?></td>
	<td class="last"><?php echo $contacts['phone']; ?></td>
<td><a href="vendor_contacts.php?vendor_contact_id=<?php echo $contacts['id'];?>&vendor_id=<?php echo $contacts['vendor_id'];?>&oper=update" rel="#overlay"><img src="/img/edit-icon.gif" width="16" height="16" alt="edit" /></a></td>
	<td><a href="vendor_contacts.php?vendor_contact_id=<?php echo $contacts['id'];?>&vendor_id=<?php echo $contacts['vendor_id'];?>&oper=delete" rel="#overlay"><img src="/img/hr.gif" width="16" height="16" alt="" /></a></td>
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
