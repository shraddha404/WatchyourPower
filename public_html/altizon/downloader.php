<?php
chdir(dirname(__FILE__));
$directory= dirname(__FILE__);
include_once "../../lib/Admin.class.php";
/*
!! Following line needs fixing !!
Instead of new Admin(2), something like new Admin($u->getCronUserId()) is better
*/
$u=new Admin(2);

// Get site configuration
$config=getConfig();

// Altizon library functions
include_once "altizon_lib.php";

// Begin

// -- Following part is for reading the configuration specific to Altizon
// -- This may be stored in our config table of the db.
// -- Reading from a file is a little slower especially when we already have a call to getConfig()
$config_file =$directory."/download.props";

    $string = file_get_contents($config_file);
    if ($string == null) { 
        echo "\ndownload.props not found! Aborting...\n";
    }
    $props=json_decode($string,true);
    $api_host = $props["api_host"];
    $ak = $props["access_key"];
//---------------- config call ends here

// Get all sensors (devices) for which data must be requested
$sensors=get_all_sensor($ak,$api_host);
$device_details = array();
$i=0;
$date=date('Y-m-d');
// Request data of the previous day. Lag of 1 day has been added intentionally.
$base_date = date('Y/m/d', strtotime('-1 day', strtotime($date)));
#$base_date = date('Y/m/d');
$from_date = $base_date ." 00:00:00";
$to_date = $base_date ." 24:00:00";
//echo "fromDate= ".$from_date." ToDate ".$to_date;
foreach($sensors as $sensor){
	if(array_key_exists('Prayas-ID',$sensor['traits'])){
		if($sensor['traits']['Prayas-ID'] !=''){
			$device_details [$i]['sensor']=$sensor['sensor_key'];
			$device_details [$i]['device_id']=$sensor['traits']['Prayas-ID'];
			$device_details [$i]['sim_id']=$sensor['traits']['Sim-ID'];
			$device_details [$i]['lat']=$sensor['traits']['Lat'];
			$device_details [$i]['long']=$sensor['traits']['Long'];
			$device_details [$i]['fw_ver']=$sensor['traits']['FW Ver'];
			$i++;
		}
	}
}
    // create curl resource 
    //$fetched_data_array = array();
    $events = array();
//	$events = download_raw_events($ak, $device_details[2]['sensor'], $argv[1], $argv[2], $api_host);
	$final_events = array();
	$j=0;
	$k=4; 	
	$missing_hr = array();
	$x = 0;
    foreach ($device_details as $sk) {
	  //get data for a particular sensor(device) for a date range
      $events = download_raw_events($ak, $sk['sensor'], $from_date, $to_date, $api_host); 
	  if(count($events) == 0){
		 for($a =0;$a<24; $a++){
				$missing_hr[$x]['device_id']=$sk['device_id'];
				$missing_hr[$x]['date']=date('Y-m-d',strtotime($from_date));
				$missing_hr[$x]['hr']= $a;
				$missing_hr[$x]['sensor']=$sk['sensor'];
				$missing_hr[$x]['device_id']=$sk['device_id'];
				$missing_hr[$x]['lat']=$sk['lat'];
				$missing_hr[$x]['long']=$sk['long'];
				$missing_hr[$x]['sim_id']=$sk['sim_id'];
				$missing_hr[$x]['fw_ver']=$sk['fw_ver'];
				$x++;
		 }
	 	 continue;
	  }
	   $pre_hr=0; 	
	   //foreach($events as $eve){
	   for($i=0; $i<1440; $i++){
		// ignore events if the batch is not full 
		// a batch of 60 events, one per min
	   	if($i%60 == 0 && empty($events[($i+59)])){
			$first_missed_hr = date("H",strtotime($sdate));
			for($m_h = $first_missed_hr; $m_h<24; $m_h++){
				$missing_hr[$x]['device_id']=$sk['device_id'];
				$missing_hr[$x]['date']=date('Y-m-d',strtotime($from_date));
				$missing_hr[$x]['hr']= $m_h;
				$missing_hr[$x]['sensor']=$sk['sensor'];
				$missing_hr[$x]['device_id']=$sk['device_id'];
				$missing_hr[$x]['lat']=$sk['lat'];
				$missing_hr[$x]['long']=$sk['long'];
				$missing_hr[$x]['sim_id']=$sk['sim_id'];
				$missing_hr[$x]['fw_ver']=$sk['fw_ver'];
				$x++;
			}
			break;
		}
		$eve = $events[$i];
	 	$sdate= $eve[2];
		$hr = date("h",strtotime($sdate));
		$m = date("i",strtotime($sdate));
		$mon_str= sprintf("%02d", $m);
		if($hr != $pre_hr){
			$file= date("y",strtotime($sdate))."".date("m",strtotime($sdate))."".date("d",strtotime($sdate))."".date("H",strtotime($sdate));
		$file_name=$sk['device_id']."".$file.".txt";
			$j++;
	   		$final_events[$j][0]=$sk['device_id'];
			$final_events[$j][1]=$file_name;
			$final_events[$j][2]=$sk['lat'];
			$final_events[$j][3]=$sk['long'];
			$k=4;
			$pre_hr = $hr;
		}
		$val = sprintf("%03d", $eve[4]);
		$final_events[$j][$k]=$file."".$mon_str."".$val;
		if($m == 59){
	   		$final_events[$j][65]=$sk['sim_id'];
	   		$final_events[$j][66]=$sk['fw_ver'];
		}
		$k++ ;
	  }//foreach ($events as $eve)

	// write files of that device 
	// and free memory
	foreach($final_events as $csv_events){
    	write_csv_file($csv_events,$csv_events[1],$config['altizon_files_path']);
	}
	// initialize $final_events to an empty array
	$final_events = array();
  }//foreach ($device_details as ...)

//record the hours data of which was either not requested or was not complete
$u -> addAltizonDownloads($missing_hr);
?>
