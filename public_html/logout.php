<?php   
session_start(); //to ensure you are using same session
include $_SERVER['DOCUMENT_ROOT']."/includes/php_header.php";
//header('Location: /admin/');
if($u->logout()){
session_destroy(); //destroy the session
echo "<script>";
    echo "window.location.replace(\"/index.php\")";
echo "</script>";
//header('Location:/index.php'); //to redirect back to "index.php" after logging out
}else{
	echo "fail";
}
?>
