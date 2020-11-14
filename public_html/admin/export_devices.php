<?php
ob_start();
include $_SERVER['DOCUMENT_ROOT']."/includes/admin_header.php";
$devices = $u->getAllDevices();
$filename=date('Y-m-d')."_device_details.csv";
header("Content-type: application/csv");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
$content = '';
$title = '';
foreach($devices as $device){
$content .= stripslashes($device['device_id_string']). ',';
$content .= stripslashes($device['installed']). ',';
$content .= stripslashes($device['software_version']). ',';
$content .= stripslashes($device['status']). ',';
$content .= stripslashes($device['date_tested']). ',';
$content .= stripslashes($device['name']). ',';
$content .= stripslashes($device['code']). ',';
$content .= "\n";
}
$title .= "Device Code,Received Date,Software Version,Status,Tested OK Date,Vendor Name,Vendor Code"."\n";
echo $title;
echo $content;
?>


