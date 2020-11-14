<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/php_header.php";
if($u->isDeviceVoltagePresent($_POST['device_id'])){
	echo '1';
}else{
	echo '0';
}
