<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/admin_header.php";
$location_name = $_GET['location_name'];
if(!$u->isLocationNameExists($location_name)){
$msg= "Location name allready exists";
}

 echo $msg;
?>

