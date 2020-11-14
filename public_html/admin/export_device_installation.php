<?php
ob_start();
include $_SERVER['DOCUMENT_ROOT']."/includes/admin_header.php";
$device_installations = $u->getAllDeviceInstallations();
#print_r($device_installations);
#exit;
$filename=date('Y-m-d')."_device_installation_details.csv";
header("Content-type: application/csv");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
$content = '';
$title = '';
foreach($device_installations as $device){
$content .= stripslashes($device['device_id_string']). ',';
$content .= stripslashes($device['location_name']). ',';
$content .= stripslashes($device['location_id']). ',';
$content .= stripslashes($device['sim_card_number']). ',';
$content .= stripslashes($device['company']). ',';
$content .= stripslashes($device['installer']). ',';
$content .= stripslashes($device['installed']). ',';
$content .= stripslashes($device['software_version']). ',';
if($device['installation_status']==1){
$device['installation_status']='Deployed';
}else{
$device['installation_status']='Testing';
}
$content .= stripslashes($device['installation_status']). ',';
$content .= "\n";
}
$title .= "Device Code,Location,Location Id,Sim Card Number,Sim Card Vendor,Installed By,Installed Date,Software Version,Status"."\n";
echo $title;
echo $content;
?>


