<?php
ob_start();
include $_SERVER['DOCUMENT_ROOT']."/includes/admin_header.php";
$locations = $u->getAllLocationsForExport(array('restore_all'=> 1));
$filename=date('Y-m-d')."_location_details.csv";
header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
$content = '';
$title = '';
foreach($locations as $location){
$location_id = $location['location_id'];
$location_params = $u->getLocationParams($location_id);
array_push($location,$location_params);
$address = preg_replace('/[,]+/','',trim($location['address']));
$content .= stripslashes($location['location_id']). ',';
$content .= stripslashes($location['location_name']). ',';
$content .= stripslashes($location['district']). ',';
$content .= stripslashes($location['town']). ',';
$content .= stripslashes($location['state']). ',';
$content .= stripslashes($address). ',';
$content .= stripslashes($location['category']). ',';
$content .= stripslashes($location['latitude']). ',';
$content .= stripslashes($location['longitude']). ',';
$content .= stripslashes($location[0]['promised_supply_hr']). ',';
$content .= stripslashes($location['supply_utility']). ',';
$content .= stripslashes($location[0]['sustained_power_outage']). ',';
$content .= stripslashes($location[0]['inverter_backup']). ',';
$content .= stripslashes($location['connection_type']). ',';
if($location['connection_type']=='Domestic'){
$content .= stripslashes($location[0]['family_members_no']). ',';
$content .= stripslashes($location[0]['house_rooms']). ',';
$content .= stripslashes($location[0]['main_occupation']). ',';
$content .= stripslashes($location[0]['house_category']). ',';
$content .= stripslashes($location[0]['has_bpl_card']). ',';
$content .= stripslashes('---,---,---,---,---'). ',';
}else if($location['connection_type']=='Non Domestic'){
$content .= stripslashes('---,---,---,---,---'). ',';
$content .= stripslashes($location[0]['non_domestic_type']). ',';
$content .= stripslashes('---,---,---,---'). ',';
}else{
$content .= stripslashes('---,---,---,---,---,---'). ',';
$content .= stripslashes(preg_replace('/[,]+/',' & ',trim($location[0]['crops_grown']))). ',';
$content .= stripslashes($location[0]['irrigated_area']). ',';
$content .= stripslashes(preg_replace('/[,]+/',' & ',trim($location[0]['water_source']))). ',';
$content .= stripslashes($location[0]['loadshading_hr']). ',';
}
$l_params = $u->getLocationParams($location['location_id']);
$content .= stripslashes($l_params['report_contact_person']). ',';
$content .= stripslashes($l_params['report_phone']). ',';
$content .= "\n";
}
$title .= "Location ID,Location Name,District,Town,Sate,Address,Category,Latitude,Longitude,Promised hours of supply,Supply Utility,Sustained Power Outage,Location has Inverter Backup,Connection Type, Number of Family Members, Number of Rooms, Main Occupation, House Category, BPL/Ration Card, Non Domestic Type, Crops Grown, Irrigated Area, Water Source, Loadshading Hours, Contact Person,Contact Number"."\n";
echo $title;
echo $content;
?>


