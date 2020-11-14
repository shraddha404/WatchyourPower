<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/admin_header.php";
$user_details=$_POST;
if($_POST['submit']=='Save'){
        $details = $_POST;
        if($u->addUser($details)){
                $msg = "<span class=\"message\">User added successfully.</span>";
        }else{
                $msg = "<span class=\"error\"$u->error</span>";
        }
}

if($_POST['submit']=='Update'){
$details = $_POST;
        if($u->updateUser($details)){
                $msg = "<span class=\"message\">User Updated successfully.</span>";
        }
        else{
                $msg = "<span class=\"error\">$u->error</span>";
        }
}

if($_GET['user_id']!='' && $_GET['oper']=='delete'){
        if($u->removeUser($_GET['user_id'])){
                $msg = "<span class=\"message\">User deleted successfully.</span>";
		header( "refresh:5;url=/admin/devices.php" );
        }
        else{
                $msg = "<span class=\"error\">$u->error</span>";
        }
}


if($_GET['user_id']!='' && $_GET['oper']=='update'){
$is_update=true;
$user_id=$_GET['user_id'];
$user_details = $u->getUserDetails($user_id);
}
$user_types = $u->getUserTypes();
$users = $u->getAllUsers(); 
$today = time(); 
$six_months_later = strtotime("+6 months", $today);
$main_menu = 'User Settings';
$current = 'users';
 $locations = $u->getLocationsFromCriteria($_GET);
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
	<input type="text" name="name" value="<?php if(isset($_POST['name'])){ echo $_POST['name'];}else {echo $user_details['name']; }?>" required></td>
	<input type="hidden" name="user_id" value="<?php echo $user_details['id'];?>">
	</tr>
	
	<tr>
        <td>Email *</td>
        <td>
        <input type="email" name="email" value="<?php if(isset($_POST['email'])){ echo $_POST['email'];} else {echo $user_details['email']; } ?>" required></td>
        </tr>

	<tr>
        <td>Username *</td>
        <td>
        <input type="text" name="username" value="<?php if(isset($_POST['username'])){ echo $_POST['username'];} else {echo $user_details['username']; }?>" required></td>
        </tr>
	
	<tr>
        <td>Password *</td>
        <td>
        <input type="password" name="password" value="" <?php if(!$is_update){ ?> required <?php }?>></td>
        </tr>
	<tr>
	<td>Type *</td>
	<td>
		<select name="type" required>
        	<option value="">-- Select  --</option>
       			<?php foreach($user_types as $type){ ?>
       		<option value="<?php echo $type['type_id']; ?>" <?php if($user_details['type']==$type['type_id']){ echo 'selected="selected"';} ?>><?php echo $type['user_type']; ?></option>
        		<?php } ?>
        	</select>
        </td>
	</tr>
	<tr>
	<td>Status</td>
	<td>
		<select name="status">
        	<option value="" >-- Select  --</option>
		<option value="1" <?php if($user_details['status']==1){ echo 'selected="selected"';}?> >Active</option>
       		<option value="0" <?php if($user_details['status']==0){ echo 'selected="selected"';}?>>Inactive</option>
        	</select>
        </td>
	</tr>
	<tr>
        <td>Valid Till</td>
        <td><input type="text" class="datepick" name="valid_till" id="valid_till" value="<?php if($is_update){ echo date('m/d/Y',strtotime($user_details['valid_till'])); } else { echo date('d/m/Y', $six_months_later);}?>" readonly="readonly"></td>
        </tr>
<!--tr><td>Assign Location to Owner</td>	<td>
<?php if($user_details['type']=='3' && $_GET['oper']=='update'){   $userlocations= $user_details['locations'];?> 
						<select name="location[]" size="15" multiple="multiple"  tabindex="1" required="required" id="post-list">
                                                        <?php foreach($locations as $location){ $value= $location['id'];

                                 echo "<option value='$value'".(in_array($value, explode(",", $userlocations)) ? " selected='selected'":"").">".$location['name']."</option>";

                                                         }?>
                                                  </select><?php }?></td></tr-->	
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
	<th>Username</th>
	<th>Valid Till</th>
	<th class="last" colspan="3">Action</th>
	</tr>
	<?php foreach($users as $user){?>
	<tr>
	
	<td class="first style1" style="width:auto;"> <?php echo $user['name'];?></td>
	<td class="first style1" style="width:auto;"> <?php echo $user['email'];?></td>
	<td class="first style1" style="width:auto;"> <?php echo $user['username'];?></td>
	<td class="first style1" style="width:auto;"> <?php echo date('d/m/Y',strtotime($user['valid_till']));?></td>
	<td style="text-align:center !important; width:auto;"><a href="manage_users.php?user_id=<?php echo $user['user_id'];?>&oper=update" rel="#overlay"><img src="/img/edit-icon.gif" width="16" height="16" alt="edit" /></a></td>
	<td  class="last" style="text-align:center !important; width:auto;"><a href="manage_users.php?user_id=<?php echo $user['user_id'];?>&oper=delete" rel="#overlay"><img src="/img/hr.gif" width="16" height="16" alt="" /></a></td>
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
