<?php
include_once("../lib/Admin.class.php");
$date = $argv[1];
$vendor_name = 'Altizon Systems';
$voltage_readings = 0;

$u = new Admin(1);

$vendors = $u->getVendors();
foreach($vendors as $vendor){
	if($vendor_name == $vendor['name']){
		$vendor_id = $vendor['id'];
	}
}
$devices = $u->getAllVendorDevices($vendor_id);
#print_r($devices);
foreach($devices as $device){
	$readings = $u->deleteDeviceVoltageReadings($device['id'],$date);
	#$voltage_readings += count($readings); 
	$voltage_readings += $readings; 
}
#print_r($readings);
echo "Total Devices found for Altizon: ".count($devices)."\n";
echo "Total Deleted Voltage Readings: ".$voltage_readings."\n";


?>
