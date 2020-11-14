<?php
ob_start();
session_start();
$user = $argv[1];
$report_type = $argv[2];
chdir(dirname(__FILE__));
ini_set('memory_limit', '-1');
ini_set('error_reporting', '1');
include "../lib/User.class.php";
$u = new User($user);
$to_date = date('d/m/Y');
$from_date = date("d/m/Y",strtotime("-36 Months"));
$locations = $u->getAllLocationIds($details);
//$details= $_POST;
//$location_array = $_POST['locations'];
//$location_string="";
//foreach($location_array as $key => $value)
//{
  //  $location_string .= $value.",";
//}
  //  $location_string = rtrim($location_string, ",");
//$details['locations']= $location_string;
$details['to_date'] = $to_date;  
$details['from_date'] = $from_date;  
$raw_data = $u->getRawDataForMultiLocations($details);
//$loc_arr = $_POST['locations'];
$new_loc_arr = array();
foreach($locations as $k=> $v){
	$new_loc_arr[$v['id']]['from_date']=$from_date;
        $new_loc_arr[$v['id']]['to_date']=$to_date;
    	$new_loc_arr[$v['id']]['location_id']=$v['id'];
	
}

$new_summary=array();
$interruptions = array();
foreach($new_loc_arr as $new_loc){
	
	$evening_data = $u->getDailySummaryForEveningMultiLocation($new_loc);
        $evening_supply = 0;
        foreach($evening_data as $ev){
        $evening_supply = $ev['normal'] + $ev['low'] + $ev['high'];
        }
	$interrupts = $u->getInterrupts($new_loc);
        $final_interrups =$u->getInterruptsTable($interrupts);
        $interrupt_duration = getFormattedInterruptsArray($final_interrups);
        $new_summary[$new_loc['location_id']]['location_id']=$new_loc['location_id'];
	$location_name = $u->getLocationNameById($new_loc['location_id']);
        $new_summary[$new_loc['location_id']]['location']=$location_name;
        $new_summary[$new_loc['location_id']]['short_interruptions']=$interrupt_duration['short_interruptions'];
        $new_summary[$new_loc['location_id']]['long_interruptions']=$interrupt_duration['long_interruptions'];
        $new_summary[$new_loc['location_id']]['supply_short_interruptions']=$interrupt_duration['supply_short_interruptions'];
        $new_summary[$new_loc['location_id']]['supply_long_interruptions']=$interrupt_duration['supply_long_interruptions'];
        $new_summary[$new_loc['location_id']]['supply_interrupt_total']=$interrupt_duration['supply_interrupt_total'];
        $new_summary[$new_loc['location_id']]['evening_supply']=$evening_supply;
        $new_summary[$new_loc['location_id']]['normal']=$ev['normal'];
        $new_summary[$new_loc['location_id']]['low']=$ev['low'];
        $new_summary[$new_loc['location_id']]['high']=$ev['high'];
        $new_summary[$new_loc['location_id']]['no_supply']=$ev['no_supply'];
        $new_summary[$new_loc['location_id']]['no_data']=$ev['no_data'];
}
if($report_type == 'v'){
$filename=date('Y-m-d')."Multi_location_voltage_data.csv";
}else{
$filename=date('Y-m-d')."Multi_location_interrupts_data.csv";
}
header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
$content = '';
$title = '';
$content1 = '';
$title1 = '';
if($report_type == 'i'){

foreach($new_summary as $data){
$long_interruptions = $data['supply_long_interruptions'];
$short_interruptions = $data['supply_short_interruptions'];
$long_interruptions = $long_interruptions/60;
$short_interruptions = $short_interruptions/60;
$content1 .=''.$data['location_id'].',';
$content1 .=''.$data['location'].',';
/*$content1 .=''.$data['normal'].',';
$content1 .=''.$data['low'].',';
$content1 .=''.$data['high'].',';
$content1 .=''.$data['no_supply'].',';
$content1 .=''.$data['no_data'].',';
$content1 .=''.$data['evening_supply'].',';*/
$content1 .=''.$data['short_interruptions'].',';
$content1 .=''.$data['long_interruptions'].',';
$content1 .=''.$short_interruptions.',';
$content1 .=''.$long_interruptions.',';
$content1 .="\n";
}
$title1 .="Location id,Location name, Number of Short interruptions, Number of Long interruptions, Short Interruptions Duration, Long Interruption Duration"."\n";
//$title1 .="Location id,Location name, Normal, Low, High, No Supply, No data, Evening Supply,Number of Short interruptions, Number of Long interruptions, Short Interruptions Duration, Long Interruption Duration"."\n";
//echo $title1;
//echo $content1;
}else{
foreach($raw_data as $data){
if($data['published']==1){
$data['published']='Yes';
}else{
$data['published']='No';
}
$content .="". stripslashes($data['location_id']). ",";
$content .="". stripslashes($data['location_name']). ",";
$content .=''. stripslashes($data['date']). ',';
$content .=''. stripslashes($data['Hour']). ',';
$content .=''. stripslashes($data['published']). ',';
$content .=''. stripslashes($data['readings']). ',';
$content .="\n";
}
}
$title .= "Location Id,Location Name,Date,Hour of day,Published,Readings"."\n";
//echo $title;
//echo $content;
if($report_type == 'v'){
file_put_contents('../reports/'.$filename, $title.$content);
}else{
file_put_contents('../reports/'.$filename, $title1.$content1);
}
?>


