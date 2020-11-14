<?php
ob_start();
include $_SERVER['DOCUMENT_ROOT']."/includes/php_header.php";
session_start();
$details= $_POST;
$location_array = $_POST['locations'];
$location_string="";
foreach($location_array as $key => $value)
{
    $location_string .= $value.",";
}
    $location_string = rtrim($location_string, ",");
if($details['from_date']=='' || $details['to_date']==''){
$details['from_date'] = date('d/m/Y',strtotime('-7 day'));
$details['to_date'] = date('d/m/Y',strtotime('-1 day'));
}
$details['locations']= $location_string;
$raw_data = $u->getRawDataForMultiLocations($details);
$loc_arr = $_POST['locations'];
$new_loc_arr = array();
foreach($loc_arr as $k=> $v){
	$new_loc_arr[$v]['from_date']=$details['from_date'];
        $new_loc_arr[$v]['to_date']=$details['to_date'];
        $new_loc_arr[$v]['location_id']=$v;

}
$new_summary=array();
$interruptions = array();
if(empty($raw_data)){
echo "<script type=\"text/javascript\"> alert ('No data found for selected date'); window.history.go(-1);</script>";
}else{
foreach($new_loc_arr as $new_loc){
	
$evening_data = $u->getDailySummaryForEveningMultiLocation($new_loc);
//$evening_data = $u->getDailySummaryForMultiLocation($new_loc);
$report_data = $u->getLocationSummaryReport($details, $new_loc['location_id']);
//$column_report_data =$u->getColumnChartDataForEvening($details,$new_loc['location_id']);
//$column_report_data = array_values($column_report_data);
//$i=count($column_report_data);
$from_date = new DateTime(date('Y-m-d',get_strtotime($details['from_date'])));
$to_date = new DateTime(date('Y-m-d',get_strtotime($details['to_date'])));
$diff = date_diff($from_date,$to_date);
$column_report_data =$u->getColumnChartDataForEvening($details, $new_loc['location_id']);
 $average_supply_minutes = $u->getEveningAverageAvailibility($column_report_data,$diff);
$average_divisior = 0;
foreach($column_report_data as $c_data){
if($c_data['no_data']==6){
continue;
}
$average_divisior++;
}
 $evening_supply = 0;
        foreach($evening_data as $ev){
        $evening_supply = $ev['normal'] + $ev['low'] + $ev['high'];
        }
 $average_availability = floor(($average_supply_minutes * 60 ) / $average_divisior);
$h= secondsToTime($average_availability);
$hrs=explode(':', $h);
$hrsmins=$hrs[0] * 60;  
 $mins=$hrsmins+$hrs[1];
		//Initialize to zero
		$normal = 0; $low = 0; $high = 0; $no_supply = 0; $no_data = 100;

       foreach($report_data as $re){
	      if($re['param']=='normal') {$normal = round($re['value']*100/$re['total_minutes'], 2);}
	      if($re['param']=='low') {$low = round($re['value']*100/$re['total_minutes'], 2);}
	      if($re['param']=='high') {$high = round($re['value']*100/$re['total_minutes'], 2);}
	      if($re['param']=='no_supply') {$no_supply = round($re['value']*100/$re['total_minutes'], 2);}
	      if($re['param']=='no_data') {$no_data = 100-round($normal+$low+$high+$no_supply, 2);} 
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
        $new_summary[$new_loc['location_id']]['less_than_15_minutes']=$interrupt_duration['less_than_15_minutes'];
        $new_summary[$new_loc['location_id']]['between_16_to_60']=$interrupt_duration['between_16_to_60'];
        $new_summary[$new_loc['location_id']]['between_61_to_180']=$interrupt_duration['between_61_to_180'];
        $new_summary[$new_loc['location_id']]['more_than_180']=$interrupt_duration['more_than_250']+$interrupt_duration['more_than_180'];
       // $new_summary[$new_loc['location_id']]['more_than_180']=$interrupt_duration['more_than_180'];
        $new_summary[$new_loc['location_id']]['supply_less_than_15_minutes']=secondsToTime($interrupt_duration['supply_less_than_15_minutes']);
        $new_summary[$new_loc['location_id']]['supply_between_16_to_60']=secondsToTime($interrupt_duration['supply_between_16_to_60']);
        $new_summary[$new_loc['location_id']]['supply_between_61_to_180']=secondsToTime($interrupt_duration['supply_between_61_to_180']);
        //$new_summary[$new_loc['location_id']]['supply_more_than_180']=secondsToTime($interrupt_duration['supply_more_than_180']);
        $new_summary[$new_loc['location_id']]['supply_more_than_180']=secondsToTime($interrupt_duration['supply_more_than_250']+$interrupt_duration['supply_more_than_180']);
        $new_summary[$new_loc['location_id']]['evening_supply']=$mins;
        $new_summary[$new_loc['location_id']]['normal']=$normal;
        $new_summary[$new_loc['location_id']]['low']=$low;
        $new_summary[$new_loc['location_id']]['high']=$high;
        $new_summary[$new_loc['location_id']]['no_supply']=$no_supply;
        $new_summary[$new_loc['location_id']]['no_data']=$no_data;
    $new_summary[$new_loc['location_id']]['form_date']=$details['from_date'];
        $new_summary[$new_loc['location_id']]['to_date']=$details['to_date'];
}
//print_r($new_summary);
//exit;
echo file_get_contents('title.html');
if($details['report_option']=='voltage'){
$filename=date('Y-m-d')."Multi_location_voltage_data.csv";
}else{
$filename=date('Y-m-d')."Multi_location_interrupts_data.csv";
}
header("Content-type: text/csv");
//header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
$content = '';
$title = '';
$content1 = '';
$title1 = '';
$pre_loc='';
if($details['report_option']=='interrupts'){
foreach($new_summary as $data){
$long_interruptions = $data['supply_long_interruptions'];
$short_interruptions = $data['supply_short_interruptions'];
$long_interruptions = $long_interruptions/60;
$short_interruptions = $short_interruptions/60;
$content1 .=''.$data['location_id'].',';
$content1 .=''.$data['location'].',';
$content1 .=''.$data['form_date'].',';
$content1 .=''.$data['to_date'].',';
$content1 .=''.$data['normal'].'%'.',';
$content1 .=''.$data['low'].'%'.',';
$content1 .=''.$data['high'].'%'.',';
$content1 .=''.$data['no_supply'].'%'.',';
$content1 .=''.$data['no_data'].'%'.',';
$content1 .=''.$data['evening_supply'].',';
$content1 .=''.$data['short_interruptions'].',';
$content1 .=''.$data['long_interruptions'].',';
$content1 .=''.$short_interruptions.',';
$content1 .=''.$long_interruptions.',';
$content1 .=''.$data['less_than_15_minutes'].',';
$content1 .=''.$data['between_16_to_60'].',';
$content1 .=''.$data['between_61_to_180'].',';
$content1 .=''.$data['more_than_180'].',';
$content1 .=''.$data['supply_less_than_15_minutes'].',';
$content1 .=''.$data['supply_between_16_to_60'].',';
$content1 .=''.$data['supply_between_61_to_180'].',';
$content1 .=''.$data['supply_more_than_180'].',';
$content1 .="\n";
}
//$table = '<html><body><strong>Data from Electricity Supply Monitoring Initiative (ESMI) by Prayas (Energy Group), Pune India</strong><br/><strong>Data downloaded from www.wathcyourpower.org  and is subject to ‘Terms of Use’ mentioned thereon. More details and explanation about the data can be found in FAQ section.  (DDLV 1.8)</strong></body></html>';


$title1.="Data from Electricity Supply Monitoring Initiative (ESMI) by Prayas (Energy Group), Pune India"."\n\n";
$title1.="DISCLAIMER"."\n\n";
$title1.="Data downloaded from www.watchyourpower.org  and is subject to TERMS OF USE mentioned thereon. More details and explanation about the data can be found in FAQ section.(DDLV 1.8)"."\n\n\n";

$title1 .="Location id,Location name, Form date,To Date,Normal, Low, High, No Supply, No data,Evening Supply, Number of Short interruptions, Number of Long interruptions, Short Interruptions Duration, Long Interruption Duration, Less than Fifteen Minutes, 15 Minutes to 1 Hour, One to three hours, More than three hours, No Supply Less than Fifteen Minutes (HH:MM), No Supply 15 Minutes to 1 Hour (HH:MM), No Supply One to three hours (HH:MM), No Supply More than three hours (HH:MM)"."\n";
//$title1 .="Location id,Location name, Normal, Low, High, No Supply, No data, Evening Supply,Number of Short interruptions, Number of Long interruptions, Short Interruptions Duration, Long Interruption Duration"."\n";

echo $title1;
echo $content1;
}else{
$content = "Location Id,Location Name,Date,Hour of day,Form date,To Date,Readings,"."\n";
foreach($raw_data as $data){
if($data['published']==1){
$data['published']='Yes';
}else{
$data['published']='No';
continue;// we don't want to show unpublished data
}
$content .="". stripslashes($data['location_id']). ",";
$content .="". stripslashes($data['location_name']). ",";
$content .=''. stripslashes($data['date']). ',';
$content .=''. stripslashes($data['Hour']). ',';
$content .=''. stripslashes($details['from_date']). ',';
$content .=''. stripslashes($details['to_date']). ',';
$content .=''. stripslashes($data['readings']). "\n";
}
}
echo $content;exit;
}
?>


