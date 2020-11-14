<?php
session_start();
ini_set('display_errors',0);
include_once $_SERVER['DOCUMENT_ROOT']."../../lib/User.class.php";
$u = new User($_SESSION['user_id']);
if($u->isAdmin()){
include_once $_SERVER['DOCUMENT_ROOT']."../../lib/Admin.class.php";
$u = new Admin($_SESSION['user_id']);
}
if($u->isLocationowner()){ 
include_once $_SERVER['DOCUMENT_ROOT']."../../lib/Location.owner.class.php";
	$u = new Locationowner($_SESSION['user_id']);
}
?>
