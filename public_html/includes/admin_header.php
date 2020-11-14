<?php session_start();
ini_set('display_errors',0);
if($_SESSION['user_id']==''){
header('Location:/../index.php');
}

include_once $_SERVER['DOCUMENT_ROOT']."../../lib/Admin.class.php";
$u = new Admin($_SESSION['user_id']);



?>
