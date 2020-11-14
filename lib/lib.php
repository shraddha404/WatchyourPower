<?php
chdir(dirname(__FILE__));
include 'db_connect.php';

function getConfig(){
    $select = "SELECT * FROM config";
    global $pdo;
    $stmt = $pdo->query($select);
    /*
	$res = mysql_query($select) or die($select . mysql_error());
    */
	$app_config = array();
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		$app_config[$row['param']] = $row['value'];
	}
	return $app_config;
}



function _mime_content_type($filename) {
    $result = new finfo();

    if (is_resource($result) === true) {
        return $result->file($filename, FILEINFO_MIME_TYPE);
    }

    return false;
}


function getNewDimensions($path,$max_hw=100){
#######set target here
$target=90;
$mysock = @getimagesize($path);
$width = $mysock[0];
$height = $mysock[1];
if($width > $max_hw || $height > $max_hw){
if($width > $max_hw && $width>$height){
$new_width = $max_hw;
$new_height = ($height * $new_width) / $width;
}
else if($height > $max_hw && $height > $width){
$new_height = $max_hw;
$new_width = ($width * $new_height) / $height;
}
else if($height > $max_hw && $height == $width){
$new_height = $max_hw;
$new_width = ($width * $new_height) / $height;
}
$returnArray['new_width'] = $new_width;
$returnArray['new_height'] = $new_height;
}
else{
$returnArray['new_width'] = $width;
$returnArray['new_height'] = $height;
}
return $returnArray;
}

#######create thumb################
function createthumb($name,$filename,$new_w,$new_h){
    
   // $src_img=null;
        //$system=explode(".",$name);
        if (preg_match("/jpg|jpeg|JPG/",$name)){
            $src_img=imagecreatefromjpeg($name);
	}
	if (preg_match("/png/",$name)){
	    $src_img=imagecreatefrompng($name);
	}
	if (preg_match("/gif/",$name)){
	    $src_img=imagecreatefromgif($name);
	}

	$old_x=imagesX($src_img);
	$old_y=imagesY($src_img);
	$thumb_w = (int)($new_w);
	$thumb_h = (int)($new_h);
	//$dst_img=imagecreate($thumb_w,$thumb_h);
	$dst_img=imagecreatetruecolor($thumb_w,$thumb_h);

	//imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);
	imagecopyresized($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);
	if (preg_match("/png|PNG/",$name))
	{
            imagepng($dst_img,$filename);
	} else {
            imagejpeg($dst_img,$filename, 100);
	}
	imagedestroy($dst_img);
	imagedestroy($src_img);
}



function generatePassword() {
	$chars = "abcdefghijkmnopqrstuvwxyz023456789";
    srand((double)microtime()*1000000);
    $i = 0;
    $pass = '' ;
    while ($i <= 7) {
        $num = rand() % 33;
        $tmp = substr($chars, $num, 1);
        $pass = $pass . $tmp;
        $i++;
    }
    return $pass;
}

function getEmailTemplateBody($template_file_path){
	$content = file_get_contents($template_file_path);
	return $content;
}

function getEmailBody($template_body,$arr_of_variable){
$body = $template_body;
$app_config = getConfig();

foreach($arr_of_variable as $k => $v){
//      $pattern="/\[\[$k\]\]/";
//      $v = str_replace('$', '\$', $v);
        $pattern[$k]="/\[\[$k\]\]/";
        $replacement[$k] = str_replace('$', '\$', $v);
//      $body = preg_replace($pattern,$v,$body);
        $body = preg_replace($pattern,$replacement,$body);
}
$pattern= '/\[\[server\]\]/';
$body = preg_replace($pattern,$app_config['http_host'],$body);
return $body;
}


function sanitizeInput($s){
	//return mysql_real_escape_string($s);
	return htmlentities($s, ENT_QUOTES, 'UTF-8',false);
}

function getBrowser(){
$u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }
   
    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
    {
    return  $bname = 'Internet Explorer';
            $ub = "MSIE";
    }
    elseif(preg_match('/Firefox/i',$u_agent))
    {
     return   $bname = 'Mozilla Firefox';
        $ub = "Firefox";
    }
    elseif(preg_match('/Chrome/i',$u_agent))
    {
     return $bname = 'Google Chrome';
        $ub = "Chrome";
    }
    elseif(preg_match('/Safari/i',$u_agent))
    {
      return  $bname = 'Apple Safari';
        $ub = "Safari";
    }
    elseif(preg_match('/Opera/i',$u_agent))
    {
      return   $bname = 'Opera';
        $ub = "Opera";
    }
    elseif(preg_match('/Netscape/i',$u_agent))
    {
       return $bname = 'Netscape';
        $ub = "Netscape";
    } 
}


function sendTemplateEmail($to,$subject_path,$body_path,$template_vars){
$app_config = getConfig();
$email_from_address='no-reply@baithak.com';	
include 'config.php';
$subject_path = $app_config['document_root']."/".$subject_path;
$body_path = $app_config['document_root']."/".$body_path;
//$headers = "From:$email_from_address\n";
$headers = "From:$email_from_address\n";
$email_subject_body = getEmailTemplateBody($subject_path);
$email_template_body = getEmailTemplateBody($body_path);
$email_body = getEmailBody($email_template_body,$template_vars);
$email_subject = getEmailBody($email_subject_body,$template_vars);
#echo "$email_subject";
#echo "$email_body";
#echo " $to";
mail($to, $email_subject, $email_body, $headers);
}        

function logMessage($msg){
#$logfile = $_SERVER['DOCUMENT_ROOT'].'/../logs/log.txt';
$app_config = getConfig();
$logfile = $app_config['document_root'].'/../logs/log.txt';
$fd = fopen($logfile,"a");
fwrite($fd, date('c').' | '.$msg.PHP_EOL);
fclose($fd);
}

function outputCSV($data) {
    $outstream = fopen("php://output", "w");
    function __outputCSV(&$vals, $key, $filehandler) {
        fputcsv($filehandler, $vals); // add parameters if you want
    }
    array_walk($data, "__outputCSV", $outstream);
    fclose($outstream);
}

 function trimString($string,$len=10){
                $string = strip_tags($string);
                if(strlen($string)>$len){
                $cut = substr($string,0,$len);
                $string = substr($cut,0,$len).'...';
                }
                return $string;
        }
/*
function getCronUser(){
        $select = sprintf("SELECT users.id FROM users WHERE username='cronuser'");
                if(!($res=mysql_query($select))){
                        return false;
                }
                $row = mysql_fetch_assoc($res);
                return $row['id'];
}
*/

function getFileTypeImageFromFile($file){
$file_type = array(); 
$file_extension = pathinfo($file,PATHINFO_EXTENSION);
	if(preg_match("/JPEG|jpeg|JPG|jpg/",$file_extension)){
		$file_type['image'] = "jpeg.png";
		$file_type['jsfunction'] = "loadImage";
		$file_type['file_type'] =$file_extension;
	}else if(preg_match("/PNG|png/",$file_extension)){
		$file_type['image'] = "png.png";
		$file_type['jsfunction'] = "loadImage";
		$file_type['file_type'] =$file_extension;
	}else if(preg_match("/DOC|doc|DOCX|docx/",$file_extension)){
		$file_type['image'] = "docx.png";
		$file_type['jsfunction'] = "loadImage";
		$file_type['file_type'] =$file_extension;
	}else if(preg_match("/PPT|ppt|PPTX|pptx/",$file_extension)){
		$file_type['image'] = "ppt.png";
		$file_type['jsfunction'] = "loadImage";
		$file_type['file_type'] =$file_extension;
	}else if(preg_match("/EXL|exl|EXLX|exlx/",$file_extension)){
		$file_type['image'] = "exl.png";
		$file_type['jsfunction'] = "loadImage";
		$file_type['file_type'] =$file_extension;
	}else if(preg_match("/PDF|pdf/",$file_extension)){
		$file_type['image'] = "pdf.png";
		$file_type['jsfunction'] = "loadPDF";
		$file_type['file_type'] =$file_extension;
	}else if(preg_match("/FLV|flv/",$file_extension)){
		$file_type['image'] = "flv.png";
		$file_type['jsfunction'] = "loadFlvVIDEO";
		$file_type['file_type'] =$file_extension;
	}else if(preg_match("/SWF|swf/",$file_extension)){
		$file_type['image'] = "flv.png";
		$file_type['jsfunction'] = "loadSWF";
		$file_type['file_type'] =$file_extension;
	}else if(preg_match("/MP3|mp3|m4a|M4A|wma|WMA/",$file_extension)){
		$file_type['image'] = "mp3.png";
		$file_type['jsfunction'] = "createSound";
		$file_type['file_type'] =$file_extension;
	}else if(preg_match("/MP4|mp4/",$file_extension)){
		$file_type['image'] = "flv.png";
		$file_type['jsfunction'] = "loadVIDEO";
		$file_type['file_type'] =$file_extension;
	}else if(preg_match("/OGV|ogv|OGG|ogg/",$file_extension)){
		$file_type['image'] = "flv.png";
		$file_type['jsfunction'] = "loadVIDEO";
		$file_type['file_type'] =$file_extension;
	}else if(preg_match("/WEBM|webm/",$file_extension)){
		$file_type['image'] = "flv.png";
		$file_type['jsfunction'] = "loadVIDEO";
		$file_type['file_type'] =$file_extension;
	}else{  
		$file_type['image'] = "text.png";
		$file_type['jsfunction'] = "loadImage";
		$file_type['file_type'] =$file_extension;
	}
	return $file_type;
}
function getUserAgentString(){
return true;
}

function createSearchArray(){
	
}


function get_strtotime($date){
        $date = str_replace('/','-',$date);
        return strtotime($date);
}

function secondsToTime($seconds){
    // extract hours
    $hours = floor($seconds / (60 * 60));
 
    // extract minutes
    $divisor_for_minutes = $seconds % (60 * 60);
    $minutes = floor($divisor_for_minutes / 60);
 
    // extract the remaining seconds
    $divisor_for_seconds = $divisor_for_minutes % 60;
    $seconds = ceil($divisor_for_seconds);
 
    // return the final array
    $obj = array(
        "h" => (int) $hours,
        "m" => (int) $minutes,
        "s" => (int) $seconds,
    );
   // return $obj;
	return showTime($obj);
}

function showTime($time_array){
	$hours = $time_array['h'];
	$minutes = $time_array['m'];
	$seconds = $time_array['s'];
	//$time = sprintf("%02s : %02sm %02ss",$hours,$minutes,$seconds); 
	$time = sprintf("%02s:%02s",$hours,$minutes); 
	return $time;
}

function minutesToTime($min){
    // extract hours
    $hours = floor($min/ (60));
 
    // extract the remaining minutes 
    $minutes = $min % 60;
    $seconds = 0;
 
    // return the final array
/*
    $obj = array(
        "h" => (int) $hours,
        "m" => (int) $minutes,
        "s" => (int) $seconds,
    );
    return $obj;
*/
	$time = sprintf("%02sh : %02sm",$hours,$minutes); 
	return $time;
}

function getArrayToShowColumnCharts(&$arr){
array_walk($arr,function(&$value, $key){ $total_minutes = 0;  foreach($value as $k => $v){  if($k!='date'){ $total_minutes = $total_minutes + $v; } } $value['no_data'] = 360 - $total_minutes; } ); 

array_walk($arr,function(&$value, $key){ foreach($value as $k => $v){ if($k!='date'){ $value[$k] = round($v/60,2); }  } }); 
}

function getFullColumnChartArrayofDateRange(&$column_report_data,$details){
	$from_date = new DateTime(date('Y-m-d',get_strtotime($details['from_date'])));
	$end = new DateTime(date('Y-m-d',get_strtotime($details['to_date'])));

	$end = $end->modify( '+1 day' );
	$interval = new DateInterval('P1D');
	$daterange = new DatePeriod($from_date, $interval ,$end);

	foreach($daterange as $date){
		$key = $date->format('Ymd');
		$date_value =  $date->format('Y-m-d');
		if(empty($column_report_data[$key]) || !array_key_exists($key,$column_report_data)){
			$column_report_data[$key] = array('date'=> "$date_value",'high' => '0', 'low'=>'0','normal'=>'0','no_data'=>'6','no_supply'=>'0','very_low'=>'0');
		}
	}
	ksort($column_report_data);
}

function getArrayToShowPieChart(&$pie_chart_data){

	$no_data_value = (array_sum(array_map(function($pie_chart_data) { return $pie_chart_data['value']; }, $pie_chart_data)));
	foreach($pie_chart_data as $key => $value){
	        if($value['param'] == 'no_data'){
		        $pie_chart_data[$key]['value'] = $value['total_minutes'] - $no_data_value;
	        }
	}

	
}

function getLineChartArrayToCompareLocations(&$report_data){
	foreach($report_data as $key =>  $data){
		foreach($data as $k => $value){
			if(is_array($value)){
				foreach($value as $ky => $v){
					$report_data[$key]['voltage_'.$ky] = $v;
				}
			}
		}
		unset($report_data[$key]['voltage']);
	}
}
/*
* Added by amar
*/
function getFormattedInterruptsArray($interruptions){//print_r($interruptions);
$interrupt_duration = array('less_than_15_minutes'=>0,'between_16_to_60'=>0,'between_61_to_180'=> 0,
			'more_than_180'=>0, 'supply_less_than_15_minutes'=>0,
                        'supply_between_16_to_60'=>0, 'supply_beween_61_to_180'=>0,'supply_more_than_180'=>0,
			'short_interruptions'=>0, 'long_interruptions'=>0,'interrupt_total'=>0,'supply_interrupt_total'=>0);
$i=0;
foreach($interruptions as $seconds){ 
	if($seconds <= 900 && $seconds >0){
		$interrupt_duration['less_than_15_minutes'] ++;
		$interrupt_duration['supply_less_than_15_minutes'] += $seconds;

	}elseif($seconds >= 901 && $seconds <= 3600 && $seconds > 0){

		$interrupt_duration['between_16_to_60'] ++;
		$interrupt_duration['supply_between_16_to_60'] += $seconds;
	}elseif($seconds >= 3601  && $seconds <= 10800){
		$interrupt_duration['between_61_to_180'] ++;
		 $interrupt_duration['supply_between_61_to_180'] += $seconds;
	}elseif($seconds > 10800 && $seconds <= 54000){
//echo $seconds."  <br>";
		$interrupt_duration['more_than_180'] ++;
	 $interrupt_duration['supply_more_than_180'] += $seconds;
	}
	$interrupt_duration['short_interruptions'] = $interrupt_duration['less_than_15_minutes'] + $interrupt_duration['between_16_to_60'];
	 $interrupt_duration['long_interruptions'] = $interrupt_duration['between_61_to_180'] + $interrupt_duration['more_than_180'];	
	$interrupt_duration['supply_short_interruptions'] = $interrupt_duration['supply_less_than_15_minutes'] + $interrupt_duration['supply_between_16_to_60'];
	$interrupt_duration['supply_long_interruptions'] = $interrupt_duration['supply_between_61_to_180'] + $interrupt_duration['supply_more_than_180'] ;
	$interrupt_duration['interrupt_total'] = $interrupt_duration['short_interruptions'] + $interrupt_duration['long_interruptions']; 
	$interrupt_duration['supply_interrupt_total'] = $interrupt_duration['supply_short_interruptions'] + $interrupt_duration['supply_long_interruptions'];
$i++;
}
	return $interrupt_duration;
}
function getFormattedInterruptionsArray($interruptions){
$interrupt_duration = array('less_than_15_minutes'=>0,'between_16_to_60'=>0,'between_61_to_180'=> 0,
			'more_than_180'=>0, 'supply_less_than_15_minutes'=>0,
                        'supply_between_16_to_60'=>0, 'supply_beween_61_to_180'=>0,'supply_more_than_180'=>0,
			'short_interruptions'=>0, 'long_interruptions'=>0,'interrupt_total'=>0,'supply_interrupt_total'=>0);
foreach($interruptions as $int){
	$minutes = $int['time_difference'] / 60;
	$seconds = $int['time_difference'];
	if($seconds <= 900){
		$interrupt_duration['less_than_15_minutes'] += $int['cnt'];
		$interrupt_duration['supply_less_than_15_minutes'] += $int['time_difference'] * $int['cnt'];
	}elseif($seconds >= 901 && $seconds <= 3600){
		$interrupt_duration['between_16_to_60'] += $int['cnt'];
		$interrupt_duration['supply_between_16_to_60'] += $int['time_difference'] * $int['cnt'];
	}elseif($seconds >= 3601  && $seconds <= 10800){
		$interrupt_duration['beween_61_to_180'] += $int['cnt'];
		$interrupt_duration['supply_beween_61_to_180'] += $int['time_difference'] * $int['cnt'];
	}elseif($seconds > 10800){
		$interrupt_duration['more_than_180'] += $int['cnt'];
		$interrupt_duration['supply_more_than_180'] += $int['time_difference'] * $int['cnt'];
	}
}
	
	$interrupt_duration['short_interruptions'] = $interrupt_duration['less_than_15_minutes'] + $interrupt_duration['between_16_to_60'];
	$interrupt_duration['long_interruptions'] = $interrupt_duration['beween_61_to_180'] + $interrupt_duration['more_than_180'];
	
	$interrupt_duration['supply_short_interruptions'] = $interrupt_duration['supply_less_than_15_minutes'] + $interrupt_duration['supply_between_16_to_60'];
	$interrupt_duration['supply_long_interruptions'] = $interrupt_duration['supply_beween_61_to_180'] + $interrupt_duration['supply_more_than_180'];
	$interrupt_duration['interrupt_total'] = $interrupt_duration['short_interruptions'] + $interrupt_duration['long_interruptions']; 
	$interrupt_duration['supply_interrupt_total'] = $interrupt_duration['supply_short_interruptions'] + $interrupt_duration['supply_long_interruptions'];
	return $interrupt_duration;
}

function getSummaryParameters(){
    $select = "SELECT * FROM summary_parameters ORDER BY priority"; 
		//HERE param IN ('normal','high','low','very_low','no_supply','no_data')";
    global $pdo;
    $stmt = $pdo->query($select);
        $params = array();
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
	
// for location parameters

function getParamInfo(){
#### returns all the info stored in location_other_param table
        $select = "SELECT * FROM location_other_param";
	global $pdo;
        $res = $pdo->query($select);
        $param_info = array();
        return $res->fetchAll(PDO::FETCH_ASSOC);
        //        $param_info[] = $row;
        
        //return $param_info;
}

function prepareParams($data){
### accepts an array which has param names as keys
### and returns an array of arrays which has param ids as keys 
### names are replaced with ids from the db table location_other_param
### first value of each 2 element array is text value and the second is option value (an int)
        $param_info = getParamInfo();
        $param_ids = array();
        $param_types = array();

        foreach($param_info as $p_i){
                $param_ids[$p_i['param']] = $p_i['id'];
                $param_types[$p_i['param']] = $p_i['param'];
        }
        $new_params = array();
        foreach($data as $k=>$v){
                if(isset($param_ids[$k]) && $param_types[$k] == 'text'){
                        $new_params[$param_ids[$k]][0] = $v;
                }
                elseif(isset($param_ids[$k])){
                        if(is_array($v)){
                                $v = implode(',',$v);
                        }
                        $new_params[$param_ids[$k]][1] = $v;
                        $new_params[$param_ids[$k]][0] = $data[$k.'_text_value'];
                }
        }
        return $new_params;

}

	// calls to initialization functions
$app_config = getConfig();
date_default_timezone_set('Asia/Kolkata'); 
?>
