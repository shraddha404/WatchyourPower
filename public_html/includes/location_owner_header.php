<?php
session_start();
ini_set('display_errors',0);

include_once $_SERVER['DOCUMENT_ROOT']."../../lib/Location.owner.class.php";
	$u = new LocationOwner($_SESSION['user_id']);

?>
