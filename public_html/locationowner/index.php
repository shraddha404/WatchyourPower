<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/php_header.php";
echo $_SESSION['user_id'];
if(!empty($_SESSION['user_id']))
{
		header('Location:export_summery_voltage_data.php');

}
if($_POST['login'] == 'Login'){//print_r($_POST);exit;
$username = $_POST['username']; 
$password = $_POST['password']; 
$captcha = $_POST['code']; 
if(!empty($_POST['username']) && $u->authenticate($username,$password,$captcha)){
	$_SESSION['user_id']=$u->user_id;
		$_SESSION['user_type']= $u->getUserType();//
		if($u->isLocationowner()){
			echo "<script>";
			    echo "window.location.replace(\"export_summery_voltage_data.php\")";
			echo "</script>";
//			header('Location:/admin/admin_landing.php');
			}
	}else{
	$error_msg = "<span class=\"error\">".$u->error."</span>";
	}
}
$main_menu = 'Admin Panel';
$current = 'Devices';

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<?php include $_SERVER['DOCUMENT_ROOT']."/admin/include/html_head.php"; ?>
</head>
<body>
<div id="main">
<div id="header"> 
<?php #include $_SERVER['DOCUMENT_ROOT']."/admin/include/menu.php"; ?>
<a href="index.php" class="logo"><img src="/img/logo2.gif" width="130" height="80" alt="" /></a>
</div>

<div id="middle">

<div id="left-column">
	<?php # include $_SERVER['DOCUMENT_ROOT']."/admin/include/left_nav.php"; ?>
</div>

<div id="center-column" style="left:100px !important;">

	<div class="top-bar"> 
	<!-- <a href="#" class="button">ADD NEW </a> -->
	<h1>Login</h1>
	<?php echo $error_msg;?>
	</div>

      <br />

	<div class="table">
		<form method="post" action="">
		<table class="listing" cellpadding="0" cellspacing="0">
		<tr>
		<td align="center">
			<table class="inputlisting" width="60%">
			<tr>
			<td width="30%">Username *</td>
			<td width="40%"><input type="text" name="username"></td>
			<td width="30%">&nbsp;</td>
			</tr>
			<tr>
			<td width="30%">Password *</td>
			<td width="40%"><input type="password" name="password"></td>
			<td width="30%">&nbsp;</td>
			</tr>
			<tr>
			<td width="30%">Security Code *</td>
			<td width="40%"><input type="text" name="code" placeholder="Please enter the code in image"></td>
			<td width="30%"><img src="/captcha.php" /></td>
			</tr>
			<tr>
			<td width="30%">&nbsp;</td>
			<td width="40%"><input type="submit" name="login" value="Login"></td>
			<td width="30%">&nbsp;</td>
			</tr>
		</table>
		</td>
		</tr>
		</table>
		</form>
	</div>
		<div class="table">
		</div>
	
</div>
</div> <!-- end div center column  -->

<div>
<?php include $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php"; ?>
</div>

</body>
</html>
