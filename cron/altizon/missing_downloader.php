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
$d_file="test"; // where is this variable used?
$config=getConfig();

// Altizon library functions
include_once "altizon_lib.php";

//Begin

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
$i=0;
$missed_files= $u->getAltizonMissingHr();
    // create curl resource 
    $fetched_data_array = array();
    $events = array();
//	$events = download_raw_events($ak, $device_details[2]['sensor'], $argv[1], $argv[2], $api_host);
	$final_events = array();
	$j=0;
	$k=4; 
	$missed_file_ids='';	
	$files_written = 0;
	$to_be_ignored = array();
    foreach ($missed_files as $sk) {
	if(empty($sk['hr'])) continue;
	$from_date = $sk['dt'] ." ".$sk['hr'].":00:00";
	$to_date = $sk['dt'] ." ".($sk['hr']+1).":00:00";
//echo "f=$from_date  t=$to_date sesnsor=$sk[sensor] ak=$ak";
//exit;
	if(in_array($sk['sensor'], $to_be_ignored)){continue;}

    $events = download_raw_events($ak, $sk['sensor'], $from_date, $to_date, $api_host); 
	  if(!is_array($events)){
	  	//events is not an array
		//not expected format
		//can not proceed
		$to_be_ignored[] = $sk['sensor'];
		continue;
	  }
	  else if(count($events) != 60){
	 	echo "There are ".count($events)." events. Ignoring.\n"; 
		$to_be_ignored[] = $sk['sensor'];
	  	continue;// there should be exactly 60 events
	  } 
	   $pre_hr=0; 	
	   foreach($events as $eve){
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
			//$m=0;
		}
		$val = sprintf("%03d", $eve[4]);
		$final_events[$j][$k]=$file."".$mon_str."".$val;
		if($m == 59){
	   		$final_events[$j][65]=$sk['sim_id'];
	   		$final_events[$j][66]=$sk['fw_ver'];
		}
		$k++ ;
	  }
	// write events and free memory
	foreach($final_events as $csv_events){
   	 write_csv_file($csv_events,$csv_events[1],$config['altizon_files_path']);
	 $files_written++;
		$final_events = array();
		// remove record from missed downloads db table - altizon_downloads
		$u -> removeAltizonDownloads($sk['id']);
	}
    }
echo "Number of new files: $files_written\n";
//$fetch_array = array();
//$u -> removeAltizonDownloads($missed_file_ids);
?>
