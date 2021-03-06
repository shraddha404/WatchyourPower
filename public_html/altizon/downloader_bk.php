<?php

    function get_utc_time_str($ts) {
        $date = new DateTime($ts, new DateTimeZone('Asia/Kolkata'));
        $date->setTimezone(new DateTimeZone('UTC'));
        return $date->format('Y/m/d H:i:s');
    }
    function get_local_time_str($ts) {
        $date = new DateTime($ts, new DateTimeZone('UTC'));
        $date->setTimezone(new DateTimeZone('Asia/Kolkata'));
        return $date->format('Y/m/d H:i:s');
    }

    function get_sensor_name($ak,$sk,$api_host){
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, "https://".$api_host."/api/v1/sensors/".$sk); 
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");                                                                     
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json","X-Access-Key:".$ak));  
        $ret = curl_exec($ch);
        $response = json_decode($ret,true);
        return $response["name"];
    }

   function get_all_sensor($ak,$api_host){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://".$api_host."/api/v1/sensors");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");                                                                     
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json","X-Access-Key:".$ak));  
        $ret = curl_exec($ch);
        $response = json_decode($ret,true);
//print_r($response);
        return $response;
    }


    function get_events_csv($response,$sk,$sname) {
        $csv_rows = array();
        $event_rows = $response["time_grouped_result"];
        foreach ($event_rows as $time => $result) {
            $csv_row = array();
            array_push($csv_row,$sname);
            $timestamp = null;
            $val_row = array();
            array_push($val_row, $sk);
            array_push($val_row, get_local_time_str($time));
            array_push($val_row, $result[$sk]["data.min"]["avg"]);
            array_push($val_row, $result[$sk]["data.max"]["avg"]);
            array_push($val_row, $result[$sk]["data.avg"]["avg"]);
            $csv_row = array_merge($csv_row,$val_row);
            array_push($csv_rows,$csv_row);
        }
        return $csv_rows;
    }
    function write_csv_file($events,$filename) {
print_r($events);
exit;
        $fp = fopen($filename, 'w');
        foreach ($events as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }


    function  download_raw_events($ak,$sk,$from,$to,$api_host) {
        $sensor_name = get_sensor_name($ak,$sk,$api_host);
        echo 'Fetching Events for sensor '.$sensor_name."\n";
        $ch = curl_init(); 
        // set url 
        curl_setopt($ch, CURLOPT_URL, "https://".$api_host."/api/v1/datonis_query/sensor_event_data"); 

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json","X-Access-Key:".$ak));          
        $dr = array();
        $dr['from'] = get_utc_time_str($from);
        $dr['to'] =  get_utc_time_str($to);
        $data = array('sensor_keys' => array($sk), 'date_ranges'=> array($dr), 'time_grouping'=> "minute");
        $data_string = json_encode($data); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        $ret = curl_exec($ch);
        $retcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        $response = json_decode($ret,true);
        if ($retcode != 200) {
            echo "Failed to Download data\nServer returned error: ";
            echo $response."\n" ;
            exit(1);
        }
       //print_r($response);
 
        $events = get_events_csv($response,$sk,$sensor_name);
        return $events;
    }
    
    //begin

    if ($argc !=4) {
        echo "Usage php downloader.php <timestamp from> <timestamp to> <file>"; 
        echo "\ndate should be of format: 'yyyy/mm/dd HH:MM:SS'\n";
        return -1;
    }
    $string = file_get_contents("download.props");
    if ($string == null) { 
        echo "\ndownload.props not found! Aborting...\n";
    }
    $props=json_decode($string,true);
    $api_host = $props["api_host"];
    $ak = $props["access_key"];
	$sensors=get_all_sensor($ak,$api_host);
$device_details = array();
$i=0;
foreach($sensors as $sensor){
	if(array_key_exists('Prayas-ID',$sensor['traits'])){
		if($sensor['traits']['Prayas-ID'] !=''){
			$device_details [$i]['sensor']=$sensor['sensor_key'];
			$device_details [$i]['device_id']=$sensor['traits']['Prayas-ID'];
			$i++;
		}
	}
}
    // create curl resource 
    $events = array();
	//$eve = download_raw_events($ak, $device_details[2]['sensor'], $argv[1], $argv[2], $api_host);

    foreach ($device_details as $sk) {
           $events = array_merge($events,download_raw_events($ak, $sk['sensor'], $argv[1], $argv[2], $api_host)); 
          }
print_r($events);
exit;

    write_csv_file($eve,$argv[3]);
?>
