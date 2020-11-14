<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/php_header.php";
	$username = $_POST['username'];
	$password = $_POST['password'];
	$captcha = $_POST['code'];
	$header='';
	$error=false;
	$error_msg='';

	if(!empty($_POST['username']) && $u->authenticate($username,$password,$captcha)){
                $_SESSION['user_id']=$u->user_id;
                if($u->isAdmin()){
		  $error = false;
                  $header='/admin/admin_landing.php';
                }
                else{
                  $header ='/submit_request.php';
                }
        }
        else{
		$error = true;
		$error_msg = "<span class=\"error\">$u->error</span>"; 
        }
$response = array('error'=>$error,'header'=>$header,'msg'=>$error_msg);
echo json_encode($response);

?>

