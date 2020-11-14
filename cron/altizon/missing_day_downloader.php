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
//$sensors=get_all_sensor($ak,$api_host);
$missing_days= $u->getAltizonMissingDays();
    $events = array();
	$final_events = array();
	$j=0;
	$k=4; 	
	$missing_hr = array();
	$x = 0;
    foreach ($missing_days as $sk) {
	  //get data for a particular sensor(device) for a date range
	  $from_date = $sk['date'].' 00:00:00';
	  $to_date = $sk['date'].' 23:59:59';
      $events = download_raw_events($ak, $sk['sensor'], $from_date, $to_date, $api_host); 
	  if(count($events) == 0){
		echo "No data for Device ID ".$sk['device_code']." for the day ".$sk['date'].". Download will be attempted tomorrow.\n";
	 	continue;
	  }
	   $pre_hr=0; 	
	   for($i=0; $i<1440; $i++){
		// ignore events if the batch is not full 
		// a batch of 60 events, one per min
	   	if($i%60 == 0 && empty($events[($i+59)])){
			$first_missed_hr = date("H",strtotime($sdate));
			for($m_h = $first_missed_hr; $m_h<24; $m_h++){
				$missing_hr[$x]['device_id']=$sk['device_code'];
				$missing_hr[$x]['date']=date('Y-m-d',strtotime($from_date));
				$missing_hr[$x]['hr']= $m_h;
				$missing_hr[$x]['sensor']=$sk['sensor'];
				$missing_hr[$x]['device_id']=$sk['device_code'];
				$missing_hr[$x]['lat']=$sk['lat'];
				$missing_hr[$x]['long']=$sk['long'];
				$missing_hr[$x]['sim_id']=$sk['sim_id'];
				$missing_hr[$x]['fw_ver']=$sk['fw_ver'];
				echo "Incomplete or no hourly data of hr ".$m_h. " of ".$missing_hr[$x]['date']." for Device ID ".$sk['device_code'].". Will be marked as missing hr.\n";
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
		$file_name=$sk['device_code']."".$file.".txt";
			$j++;
	   		$final_events[$j][0]=$sk['device_code'];
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
	$u -> removeAltizonDownloads($sk['id']);
  }//foreach ($device_details as ...)

//record the hours data of which was either not requested or was not complete
$u -> addAltizonDownloads($missing_hr);
?>
