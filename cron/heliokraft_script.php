<?php
chdir(dirname(__FILE__));
ini_set('memory_limit', '-1');
include "../lib/Admin.class.php";
$obj=new Admin(2);
$config=$obj->app_config;
$vendor_name='HelioKraft Technologies';
//$vendor_name='SYSLAB';
$heliocraft_devices=$obj->getDeviceCodesByVendor($vendor_name);
echo "Starting 0-padding script for Heliokraft devices.\n";
foreach($heliocraft_devices as $d){
	$days = 7;
	$latest_readings = $obj->getLatestVoltageReadingsOfDevice($d['id'], $days);
	if(count($latest_readings) == 0){
		echo "This device ".$d['id']." does not have any reading for the last $days days.\n";
		continue;
	}
	$voltage_rec=array();		
	foreach($latest_readings as $v){
	    $voltage_rec[$v['day']][$v['hour_of_day']] = array('location'=>$v['location_id'],
										'device_id' => $v['device_id'],
										'sim_card_id' => $v['sim_card_id']);
	}

	//print_r($latest_readings);
	$cnt = count($latest_readings);
	$end_datetime_str = $latest_readings[0]['day']." ".sprintf("%02d",$latest_readings[0]['hour_of_day']).":00:00";
	$start_datetime_str = $latest_readings[$cnt-1]['day']." ".sprintf("%02d", $latest_readings[$cnt-1]['hour_of_day']).":00:00";
	$end_timestamp = strtotime($end_datetime_str);
	$start_timestamp = strtotime($start_datetime_str);
	$t = $end_timestamp;
	//echo $end_timestamp . " ".$start_timestamp."\n";
	$m = 0;
	$final_array = array();
	while($t > $start_timestamp){
		$t -= 3600;	
		$dt_comp = date('Y-m-d', $t);
		$hr_comp = (int)date('H', $t);
		if(empty($voltage_rec[$dt_comp][$hr_comp])){
			echo "Adding 0 padding for device ".$d['device_id_string']. " for hr ".$dt_comp." ".$hr_comp.".\n";

            $final_array[$m]['hr']=$hr_comp;
            $final_array[$m]['location_id']=$latest_readings[0]['location_id'];
            $final_array[$m]['day']=$dt_comp;
            $final_array[$m]['device']=$latest_readings[0]['device_id'];
            $final_array[$m]['sim_card']=$latest_readings[0]['sim_card_id'];
            $final_array[$m]['device_id_string']=$d['device_id_string'];
            $m++;
		}
		else{
			// do nothing
			//echo "Found ". $dt_comp." ".$hr_comp."\n";
		}
	}// while ends
	// Do 0 padding for this device here
	//print_r($final_array);
	if(count($final_array)>0){
		$obj->addZeroVoltageReadings($final_array);
	}
}// for each Heliokraft device
?>

