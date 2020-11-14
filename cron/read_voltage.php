<?php
chdir(dirname(__FILE__));
ini_set('memory_limit', '-1');
include "../lib/Admin.class.php";
echo "\r\nScript execution started at ".date('H:i:s')."\n\n";
//$obj=new User();
$obj=new Admin(2);
$config=$obj->app_config;
$data_files=$obj->getDataFiles();
echo "There are ".count($data_files)." data files.\n";

echo "\r\nNow, it is ".date('H:i:s')."\n\n";
//$devices=$obj->getDeviceDetailsDeviceCodeWise();
$devices=$obj->getDevicesCodeWise();
echo "\r\nGot device codes. Now, it is ".date('H:i:s')."\n\n";
$device_codes_array=array();
foreach($devices as $k => $v){
	array_push($device_codes_array ,$k);
}
$installed_devices=$obj->getInstalledDeviceCodeWise();
$installed_codes_array=array();
foreach($installed_devices as $k => $v){
	array_push($installed_codes_array ,$k);
}
echo "\r\nGot installed devices. Now, it is ".date('H:i:s')."\n\n";
$data_cnt=0;
$j=0;
$dataid='';
$voltage_readings='';
$voltage_data=array();
$validation_params = $obj->getValidationParameters();
$all_location_validation_params = $obj->getAllLocationValidationParameters();
echo "\r\nGot validation parameters. Now, it is ".date('H:i:s')."\n\n";

foreach($data_files as $file){
	$data_cnt=0;
	$voltage_readings='';
	$file_name=explode(".",$file['filename']);
	$volt_data=$obj->validateContentByLocationInCron($file['filename'],$file['content'],$installed_devices,$device_codes_array,$installed_codes_array,$validation_params, $all_location_validation_params, '0');
echo "\r\nDone validation by location for device/file ".$file['filename'].". Now, it is ".date('H:i:s')."\n\n";
	if($volt_data['error'] =='0'){
	$device_code= substr($file_name[0], 0, 7);
	$device_details=$installed_devices[$device_code];
		$data=explode(",",$file['content']);
		$record_date=rtrim(chunk_split((substr($file_name[0],7,6)), 2, '-'),'-');
		$hr_of_day=substr($file_name[0],13,2);
		for($i=4; $i<64; $i++){
			$voltage=substr($data[$i],10,3);
			$voltage_next=substr($data[$i+1],10,3);
			$voltage_prev=substr($data[$i-1],10,3);
                        if($voltage < 110){
                                $voltage=0;
                        }
                        if($voltage >350){
                                $voltage='';
                        }
			
			if($voltage ==0 && $i >4 && $i< 63){
	                        if($voltage_prev!=0 && $voltage_next !=0){
        	                        $voltage=$voltage_prev;
                	        }
	                }

			$voltage_readings.=$voltage.',';
			$data_cnt++;
		}
		$voltage_readings=rtrim($voltage_readings,',');
		//$voltage_readings=$volt_data['voltage_reading'];
		$voltage_data[$j]['id']=$file['id'];
		$voltage_data[$j]['day']=$record_date;
		$voltage_data[$j]['hour_of_day']=$hr_of_day;
		//$voltage_data[$j]['readings']=$volt_data['voltage_reading']; // commented for temporary reason
		$voltage_data[$j]['readings']=$voltage_readings;
		$voltage_data[$j]['location_id']=$device_details['location_id'];
		$voltage_data[$j]['device_id']=$device_details['device_id'];
		$voltage_data[$j]['sim_card_id']=$device_details['sim_card_id'];
		$j++;		
		$dataid.=$file['id'].',';
		if($j >=10000){
			break;
		}
	}else{
		$obj->updateDataError($file['id'],$volt_data['error']);
		echo "\r\nUpdate data error. Now, it is ".date('H:i:s')."\n\n";
	}
}

if(!empty($voltage_data)){
	
		echo "\r\nAdding voltage readings. Now, it is ".date('H:i:s')."\n\n";
		if($obj->addVoltageReadings($voltage_data)){
	//		$obj->markProcessed(rtrim($dataid,','));
		}
}
//print_r($voltage_data);

echo "\r\nScript execution completed at ".date('H:i:s');
?>

