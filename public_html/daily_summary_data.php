<?php
ob_start();
include $_SERVER['DOCUMENT_ROOT']."/includes/php_header.php";
session_start();
$details= $_GET;

### Code added to download the existing csv daily_summary report file.
$details_date = preg_replace("/\//","_",$details['to_date']);
$datewise_filename="/home/watchyou/public_html/daily_summary/".$details_date."Daily_summary.csv";
$download_filename=$details_date."Daily_summary.csv";
if (file_exists($datewise_filename)) {
        header("location:/daily_summary/$download_filename");
        exit;
}
### Code Ends

$raw_data = $u->getDailySummary($details);
$locations = $u->getAllLocations();
$new_data=array();
$new=array();
foreach($raw_data as $r){
        $new_data['date'] =$details['to_date'];
	$new_data[$r['param']] =$r['total'];
	if (count($new_data)!=6) {
	continue;
	}
	$new[$details['to_date']] = $new_data;	
}
//print_r($raw_data);
//exit;
$new_summary=array();
$det=array();

##============= SKK
$det['from_date']=$details['to_date'];
$det['to_date']=$details['to_date'];
$evening_data_together = $u->getDailySummaryForEveningTogether($det);
$interrupts_together = $u->getInterruptsTogether($det);
#### SKK ends
/*
$exe_time_start = microtime(true); 
	$interrupts = $interrupts_together('238'); ##SKK
	$final_interrups =$u->getInterruptsTable($interrupts);
$exe_time_end = microtime(true);
//dividing with 60 will give the execution time in minutes otherwise seconds
$execution_time = ($exe_time_end - $exe_time_start)/60;
//execution time of the script
echo '<b>Total Execution Time:</b> '.$execution_time.' Mins';
exit;
*/

foreach($raw_data as $r){
	$det['from_date']=$details['to_date'];
	$det['to_date']=$details['to_date'];
	$det['location_id']=$r['location_id'];
	// FOr evening supply hours
	#$evening_data = $u->getDailySummaryForEvening($det,$r['location_id']);
	$evening_data = $evening_data_together[$r['location_id']]; ##SKK
	$evening_supply = 0;
	foreach($evening_data as $ev){
	$evening_supply = $ev['normal'] + $ev['low'] + $ev['high'];
	}

	//$average_supply_minutes = $u->getEveningAverageAvailibility($evening_data);
	//$average_supply_hours = minutesToTime($average_supply_minutes);
	// end for evening supply hours
	#$interrupts = $u->getInterrupts($det);
	$interrupts = $interrupts_together[$r['location_id']]; ##SKK
	$final_interrups =$u->getInterruptsTable($interrupts);
	$interrupt_duration = getFormattedInterruptsArray($final_interrups);
	$new_summary[$r['location_id']]['location_id']=$r['location_id'];
	$new_summary[$r['location_id']]['location']=$r['location_name'];
	$new_summary[$r['location_id']]['short_interruptions']=$interrupt_duration['short_interruptions'];
	$new_summary[$r['location_id']]['long_interruptions']=$interrupt_duration['long_interruptions'];
	$new_summary[$r['location_id']]['supply_short_interruptions']=$interrupt_duration['supply_short_interruptions'];
	$new_summary[$r['location_id']]['supply_long_interruptions']=$interrupt_duration['supply_long_interruptions'];
	$new_summary[$r['location_id']]['supply_interrupt_total']=$interrupt_duration['supply_interrupt_total'];
	$new_summary[$r['location_id']]['average_supply_hours']=$average_supply_hours;
	$new_summary[$r['location_id']][$r['param']]=$r['total'];
	$new_summary[$r['location_id']]['status']=$r['status'];
	$new_summary[$r['location_id']]['total_minutes']=$r['total_minutes'];
	$new_summary[$r['location_id']]['evening_supply']=$evening_supply;
}

if(empty($raw_data)){
echo "<script type=\"text/javascript\"> alert ('No data found for selected date'); window.history.go(-1);</script>";
}else{
$filename=$details['to_date']."Daily_summary.csv";
header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
$content = '';
$content2 = '';
$title = '';
$title2 = '';
$new_arr=array();
$new_avg_arr=array();
foreach($new_summary as $data){
/*$new_arr['to_date']=$_GET['to_date'];
$new_arr['low']=$data['low'];
$new_arr['high']=$data['high'];
$new_arr['normal']=$data['normal'];
$new_avg_arr[$_GET['to_date']]=$new_arr;*/
$total_minutes = $data['total_minutes'];
$data_minutes = $data['low']+$data['high']+$data['normal']+$data['no_supply'];
$data['no_data'] = $total_minutes - $data_minutes;
$content .=''. stripslashes($data['location_id']). ',';
$content .=''. stripslashes($data['location']). ',';
$content .=''. stripslashes($data['normal']). ',';
$content .=''. stripslashes($data['low']). ',';
$content .=''. stripslashes($data['high']). ',';
$content .=''. stripslashes($data['no_supply']). ',';
$content .=''. stripslashes($data['no_data']). ',';
$content .=''. stripslashes($data['short_interruptions']). ',';
$content .=''. stripslashes($data['long_interruptions']). ',';
$content .=''. stripslashes($data['supply_short_interruptions']/60). ',';
$content .=''. stripslashes($data['supply_long_interruptions']/60). ',';
if($data['status']==0){
$data['status']= 'Testing';
}else{
$data['status']= 'Deployed';
}
$content .=''. stripslashes($data['status']). ',';
$content .=''. stripslashes($data['evening_supply']). ',';
$content .= "\n";
}

$sumarry_locations = array();
foreach($new_summary as $new_sum){
array_push($sumarry_locations,$new_sum['location']);
}

foreach($locations as $loc){
if(in_array($loc['name'],$sumarry_locations)){
continue;
}
$content2 .=','. stripslashes($loc['id']). ',';
$content2 .=''. stripslashes($loc['name']). ',';
$content2 .= "\n";
}
$title.="Data from Electricity Supply Monitoring Initiative (ESMI) by Prayas (Energy Group), Pune India"."\n\n";
$title.="DISCLAIMER"."\n\n";
$title.="Data downloaded from www.watchyourpower.org  and is subject to TERMS OF USE mentioned thereon. More details and explanation about the data can be found in FAQ section.(DDLV 1.8)"."\n\n\n";

$title .= "Location id, Location name,Normal, Low, High, No Supply, No Data, Number of Short interruptions, Number of Long interruptions, Short Interruptions Duration, Long Interruption Duration, Installation Status, Evening Supply Hours"."\n";
echo $title;
echo $content;
echo "\n\n";

$data_title2 .=", , , Summary not generated for following locations"."\n";
$title2 .=", Location id, Location_name"."\n";
echo $data_title2;
echo $title2;
echo $content2;
}
?>


