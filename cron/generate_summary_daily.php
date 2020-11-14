<?php
chdir(dirname(__FILE__));
ini_set('memory_limit', '-1');
include "../lib/Admin.class.php";
$obj=new Admin(2);
$voltages=$obj->getUnPublishedVoltages();
$summary_data=array();
$interrupts=array();
$i=0;
$j=0;
$voltage_ids='';
foreach($voltages as $voltage){
	//$voltage_data=explode(",",$voltage['readings']);
$voltagedate=$voltage['day']." ".$voltage['hour_of_day'].":00:00";	
//$summary_data[$i][0]=$obj->generateVoltageSummary($voltage['readings']);
$summary=$obj->generateVoltageSummary($voltage['readings'],$voltage['day'],$voltage['hour_of_day'],$voltage['location_id']);
$summary_data[$i][0]=$summary['data'];
if(count($summary['interrupt']) >0){
	$interrupts[$j]=$summary['interrupt'];
	$j++;
}
$summary_data[$i][1]=array('location_id' =>$voltage['location_id'], 'hour_of_day' => $voltagedate);
$voltage_ids .=$voltage['id'].",";
$i++;
}
if(count($voltages) > 0){
	if($obj->addSummaryData($summary_data)){
		if(count($interrupts)>0){
			$obj->addIntrrupts($interrupts);
		}
		$obj->markVoltagePublished(rtrim($voltage_ids,","));
	}
}
echo "\r\n script execution completed";
?>
