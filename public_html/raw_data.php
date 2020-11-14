<?php
ob_start();
include $_SERVER['DOCUMENT_ROOT']."/includes/php_header.php";
session_start();
/*if($_SESSION['user_id']==''){
	header('Location:/index.php');
}*/
$details= $_GET;
if($details['device_id']!=''){
$raw_data = $u->getRawDataByDevice($details);
}elseif($details['data_type']=='summary'){
$raw_data = $u->getRawSummaryData($details);
	$summary_data=array();
foreach($raw_data as $sum){
	if($sum['param'] =='high'){
		$summary_data[$sum['date']]['high']=$sum['val'];
	}
	if($sum['param'] =='low'){
		$summary_data[$sum['date']]['low']=$sum['val'];
	}
	if($sum['param'] =='normal'){
		$summary_data[$sum['date']]['normal']=$sum['val'];
	}
	if($sum['param'] =='no_supply'){
		$summary_data[$sum['date']]['no_supply']=$sum['val'];
	}
	if($sum['param'] =='no_data'){
		$summary_data[$sum['date']]['no_data']=$sum['val'];
	}
		$summary_data[$sum['date']]['location']=$sum['name'];
}
/*if(count($summary_data ) >0){
	$interrupts_data= $u->getRawInterruptsData($details);

}*/

}else{
$raw_data = $u->getRawData($details);
}
if($details['data_type']!='summary'){
if(empty($raw_data)){
echo "<script type=\"text/javascript\"> alert ('No data found for selected date range'); window.history.go(-1);</script>";
}else{
$filename=date('Y-m-d')."raw_data.csv";
header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
$content = '';
$title = '';
$count=0;
foreach($raw_data as $data){
/*if($count==0){
$content .=''. stripslashes($data['location_name']). ',';
}else{
$content .=''.stripslashes($data['location_name'] = '-----'). ',';
}
$count++;*/
$content .=''. stripslashes($data['location_name']). ',';
$content .=''. stripslashes($data['date']). ',';
$content .=''. stripslashes($data['Hour']). ',';
$content .=''. stripslashes($details['from_date']). ',';
$content .=''. stripslashes($details['to_date']). ',';
$content .=''. stripslashes($data['readings']). ',';
$content .= "\n";
}
$data_title1.="Data from Electricity Supply Monitoring Initiative (ESMI) by Prayas (Energy Group), Pune India"."\n\n";
$data_title1.="DISCLAIMER"."\n\n";
$data_title1.="Data downloaded from www.watchyourpower.org  and is subject to TERMS OF USE mentioned thereon. More details and explanation about the data can be found in FAQ section.(DDLV 1.8)"."\n\n\n";
$title .= "Location_name,Date,Hour of day,From date,To date,Readings,"."\n";
echo $data_title1;
echo $title;
echo $content;
}
}else{
if(empty($raw_data)){
echo "<script type=\"text/javascript\"> alert ('No data found for selected date range'); window.history.go(-1);</script>";
}else{
$filename=date('Y-m-d')."raw_summary_data.csv";
header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
$content = '';
$title = '';
$content2 = '';
$title2 = '';
$data_title1 = '';
$data_title2 = '';
$count=0;
$location='';
foreach($summary_data as $k => $data){
/*if(!(array_key_exists('normal',$data)) || !(array_key_exists('low',$data)) || !(array_key_exists('high',$data)) || !(array_key_exists('no_supply',$data)) || !(array_key_exists('no_data',$data))){
$data[$data['normal']]='0';
$data[$data['low']]='0';
$data[$data['high']]='0';
$data[$data['no_supply']]='0';
$data[$data['no_data']]='0';
}*/
/*$location=$data['location'];
if($count==0){
$content .=''. stripslashes($data['location']). ',';
}else{
$content .=''.stripslashes($data['location'] = '-----'). ',';
}
$count++;*/
$content .=''. stripslashes($data['location']). ',';
$content .=''. stripslashes($k). ',';
$content .=''. stripslashes($data['normal']). ',';
$content .=''. stripslashes($data['low']). ',';
$content .=''. stripslashes($data['high']). ',';
$content .=''. stripslashes($data['no_supply']). ',';
$content .=''. stripslashes($details['from_date']). ',';
$content .=''. stripslashes($details['to_date']). ',';
$content .=''. stripslashes($data['no_data']). ',';
$content .= "\n";
}
/*$count=0;
foreach($interrupts_data as $i_data){
if($count==0){
$content2 .=''. stripslashes($location). ',';
}else{
$content2 .='-----,';
}
$count++;
//$content2 .=''.stripslashes($location). ',';
$content2 .=''.stripslashes($i_data['down_date']). ',';
$content2 .=''.stripslashes($i_data['up_date']). ',';
$content2 .= "\n";
}
*/
$data_title1.="Data from Electricity Supply Monitoring Initiative (ESMI) by Prayas (Energy Group), Pune India"."\n\n";
$data_title1.="DISCLAIMER"."\n\n";
$data_title1.="Data downloaded from www.watchyourpower.org  and is subject to TERMS OF USE mentioned thereon. More details and explanation about the data can be found in FAQ section.(DDLV 1.8)"."\n\n\n";

$data_title1 .=", , , Summary Data"."\n";
$title .= "Location_name,Hour,Normal Voltage,Low Voltage,High Voltage,No Supply,From date,To date,No Data"."\n";
echo $data_title1;
echo $title;
echo $content;
echo "\n";
/*$data_title2 .=", , , Interrupts Data"."\n";
$title2 .="Location_name,Down Date,Up Date"."\n";
echo $data_title2;
echo $title2;
echo $content2;*/

}
}
?>


