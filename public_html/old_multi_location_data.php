<?php
ob_start();
include $_SERVER['DOCUMENT_ROOT']."/includes/php_header.php";
session_start();
$details= $_GET;

$location_array = $_GET['locations'];
$location_string="";
foreach($location_array as $key => $value)
{
    $location_string .= $value.",";
}
    $location_string = rtrim($location_string, ",");
$details['locations']= $location_string;
$raw_data = $u->getRawDataForMultiLocations($details);
$loc_arr = $_GET['locations'];
$new_loc_arr = array();
foreach($loc_arr as $k=> $v){
	$new_loc_arr[$v]['from_date']=$details['from_date'];
        $new_loc_arr[$v]['to_date']=$details['to_date'];
        $new_loc_arr[$v]['location_id']=$v;

}
$interruptions = array();
if(empty($raw_data)){
echo "<script type=\"text/javascript\"> alert ('No data found for selected date'); window.history.go(-1);</script>";
}else{
foreach($new_loc_arr as $new_loc){
        $interrupts = $u->getInterrupts($new_loc);
        $final_interrups =$u->getInterruptsTable($interrupts);
        $interrupt_duration[$new_loc['location_id']] = getFormattedInterruptsArray($final_interrups);
	$interrupt_duration[$new_loc['location_id']]['location_id']= $new_loc['location_id'];
	$location_name = $u->getLocationNameById($new_loc['location_id']);
	$interrupt_duration[$new_loc['location_id']]['location_name']= $location_name;
}
$interruptions = $interrupt_duration;
$filename=date('Y-m-d')."Multi_location_data.csv";
header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
$content = '';
$title = '';
$content1 = '';
$title1 = '';
$pre_loc='';
foreach($raw_data as $data){
if($data['published']==1){
$data['published']='Yes';
}else{
$data['published']='No';
}
if($pre_loc != $data['location_id']){
$content .="\n Location id,Location name, Number of Short interruptions, Number of Long interruptions, Short Interruptions Duration, Long Interruption Duration"."\n";
$long_interruptions = $interruptions[$data['location_id']]['supply_long_interruptions'];
$short_interruptions = $interruptions[$data['location_id']]['supply_short_interruptions'];
$long_interruptions = $long_interruptions/60;
$short_interruptions = $short_interruptions/60;
$content .=''.$interruptions[$data['location_id']]['location_id'].',';
$content .=''.$interruptions[$data['location_id']]['location_name'].',';
$content .=''.$interruptions[$data['location_id']]['short_interruptions'].',';
$content .=''.$interruptions[$data['location_id']]['long_interruptions'].',';
$content .=''.$short_interruptions.',';
$content .=''.$long_interruptions.',';
//$content .=''.$interruptions[$data['location_id']]['supply_interrupt_total'].',';
$content .= "\n";
$content .= "Location_name,Date,Hour of day,Published,Readings,"."\n";
}
$content .="\n". stripslashes($data['location_name']). ",";
$content .=''. stripslashes($data['date']). ',';
$content .=''. stripslashes($data['Hour']). ',';
$content .=''. stripslashes($data['published']). ',';
$content .=''. stripslashes($data['readings']). ',';
$pre_loc = $data['location_id'];
}
echo $content;
}
?>


