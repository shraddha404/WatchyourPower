<?php
// start of some library functions for Altizon
//-------------------------------------------------------------------
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
        //curl_setopt($ch, CURLOPT_URL, "https://".$api_host."/api/v1/sensors/".$sk); 
        curl_setopt($ch, CURLOPT_URL, "https://".$api_host."/api/v3/things/".$sk); 
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");                                                                     
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json","X-Access-Key:".$ak));  
        $ret = curl_exec($ch);
        $response = json_decode($ret,true);
//print_r($response);
        return $response['thing']["name"];
    }

   function get_all_sensor($ak,$api_host){
        $ch = curl_init();
        //curl_setopt($ch, CURLOPT_URL, "https://".$api_host."/api/v1/sensors");
        //curl_setopt($ch, CURLOPT_URL, "https://".$api_host."/api/v2/sensors?per=320");
        curl_setopt($ch, CURLOPT_URL, "https://".$api_host."/api/v3/things?per=500&page=1");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");                                                                     
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json","X-Access-Key:".$ak));  
        //curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json","X-Auth-Token:".$ak));  
        $ret = curl_exec($ch);
//print_r($ret);
        $response = json_decode($ret,true);
//print_r($response);
        //return $response;
        return $response['things'];
    }


    function get_events_csv($response,$sk,$sname) {
        $csv_rows = array();
        $event_rows = $response["time_grouped_result"];
//print_r($event_rows);
        foreach ($event_rows as $time => $result) {
            $csv_row = array();
            array_push($csv_row,$sname);
            $timestamp = null;
            $val_row = array();
            array_push($val_row, $sk);
            array_push($val_row, get_local_time_str($time));
/*
            array_push($val_row, $result[$sk]["data.min"]["avg"]);
            array_push($val_row, $result[$sk]["data.max"]["avg"]);
            array_push($val_row, $result[$sk]["data.avg"]["avg"]);
*/
            array_push($val_row, $result[$sk]["min"]["avg"]);
            array_push($val_row, $result[$sk]["max"]["avg"]);
            array_push($val_row, $result[$sk]["avg"]["avg"]);
            $csv_row = array_merge($csv_row,$val_row);
            array_push($csv_rows,$csv_row);
        }
        return $csv_rows;
    }
    function write_csv_file($events,$filename,$data_derectory) {
	$filename=$data_derectory."".$filename;
        $fp = fopen($filename, 'w');
        /*foreach ($events as $fields) {
            fputcsv($fp, $fields);
        }*/
            fputcsv($fp, $events);
        fclose($fp);
		echo "Wrote ".count($events). " fields in $filename\n";
    }


    function  download_raw_events($ak,$sk,$from,$to,$api_host) {
	$events ='';
        $sensor_name = get_sensor_name($ak,$sk,$api_host);
        echo "\nFetching Events for sensor ".$sensor_name." from $from to $to.\n";
        $ch = curl_init(); 
        // set url 
        //curl_setopt($ch, CURLOPT_URL, "https://".$api_host."/api/v1/datonis_query/sensor_event_data"); 
	/* The API updated by altizon to remove unwanted 0 from avg field*/
	curl_setopt($ch, CURLOPT_URL, "https://".$api_host."/api/v3/query/thing_aggregated_data");

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json","X-Access-Key:".$ak));          
        $dr = array();
        $dr['from'] = get_utc_time_str($from);
        $dr['to'] =  get_utc_time_str($to);
        //$data = array('sensor_keys' => array($sk), 'date_ranges'=> array($dr), 'time_grouping'=> "minute");
        $data = array('thing_keys' => array($sk), 'date_ranges'=> array($dr), 'time_grouping'=> "minute");
        $data_string = json_encode($data); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        $ret = curl_exec($ch);
        $retcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        $response = json_decode($ret,true);
        if ($retcode != 200) {
            echo "Failed to download data for $sensor_name. \nServer returned error: ";
            //echo $response."\n" ;
			print_r($response)."\n";
            //exit(1);
        }else{
	if($response !=''){ 
	//	print_r($response);
	        $events = get_events_csv($response,$sk,$sensor_name);
		        return $events;
		}
	}
    }
//------------------------------------------------------------------------    

