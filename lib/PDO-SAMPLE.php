<?php
include 'db_connect.php';
//session_set_cookie_params('300');
//session_regenerate_id(true);

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



function getCaseStates(){
	$select = "SELECT * FROM case_states";
    global $pdo;
	$stmt = $pdo->query($select);
	$statuses = array();
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		$statuses[$row['id']] = $row['state'];
	}
	return $statuses;
}

function getStatusId($state){
    global $pdo;
	$select = "SELECT id FROM case_states
		WHERE state = ?";
	$stmt = $pdo->prepare($select);
    $stmt->execute(array($state));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	return $row['id'];
}

function getStatusName($status_id){
    	global $pdo;
	$select = $pdo->prepare("SELECT state FROM case_states
		WHERE id = ?");
	$select->execute(array($status_id));
	$row = $select->fetch(PDO::FETCH_ASSOC);
	return $row['state'];
}

function updateCaseParameters($case_id, $params,$updated_by=null){
        global $pdo;
		try{
		$update = $pdo->prepare("UPDATE case_parameters 
			SET param_text_value = ?, param_option_value = ?, `updated_by`='$updated_by'
			WHERE case_id = ? AND param_id = ?");
		}
		catch(PDOException $e){
			$this->setError($e->getMessage());
		}

	foreach($params as $p_id=>$p_val){
		try{
		$update->execute(array(sanitizeInput($p_val[0]), sanitizeInput($p_val[1]), $case_id, $p_id));
		}
		catch(PDOException $e){
		$this->setError($e->getMessage());
		}
	}
	return true;
}

function updateCaseParametersLog($case_id, $params,$updated_by=null){
    global $pdo;
		$update = $pdo->prepare("UPDATE case_parameters_log 
			SET param_text_value = ?, param_option_value = ?
			WHERE case_id = ? AND param_id = ?");
	foreach($params as $p_id=>$p_val){
		$update->execute(array(sanitizeInput($p_val[0]), sanitizeInput($p_val[1]), $case_id, $p_id));
	}
	return true;
}

function getProfileParamInfo(){
#### returns all the info stored in params table
	$select = "SELECT * FROM user_profile_fields";
    global $pdo;
	$stmt = $pdo->query($select);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    /*
	$param_info = array();
	while($row = mysql_fetch_assoc($res)){
		$param_info[] = $row;
	}
	return $param_info;
    */
}

function prepareProfileParams($data){
### accepts an array which has param names as keys
### and returns an array of arrays which has param ids as keys 
### names are replaced with ids from the db table parameters
### first value of each 2 element array is text value and the second is option value (an int)
	$param_info = getProfileParamInfo();
	$param_ids = array();
	$param_category = array();
	$param_type = array();

	foreach($param_info as $p_i){
		$param_ids[$p_i['fieldname']] = $p_i['id'];
		$param_type[$P_i['fieldname']] = $p_i['field_type'];
		$param_category[$P_i['fieldname']] = $p_i['category'];
	}
	$new_params = array();
	foreach($data as $k=>$v){
		if(isset($param_ids[$k])){
			$new_params[$param_ids[$k]][0] = $v;
		}
	}
	return $new_params;
}

function prepareProfileParamsReverse($data){
	$param_info = getProfileParamInfo();
	$param_ids = array();
	foreach($param_info as $p_i){
		$param_ids[$p_i['id']] = $p_i['fieldname'];
	}
	$new_params = array();
	foreach($data as $k=>$v){
		if(isset($param_ids[$k])){
		$new_params[$param_ids[$k]] = $v;//this is the text value
		}
	}
	return $new_params;
}

function addProfileParameters($user_id, $params){
#params is an associative array of "prepared params" 
## get those using prepareParams()
# keys are param ids and values are user input

	$insert = "INSERT INTO user_profile_values
        (user_id, profile_field_id, value)
        VALUES ";
	foreach($params as $p_id => $p_val){
		$p_val[0] = sanitizeInput($p_val[0]);
        $insert .= "($user_id, $p_id, '$p_val[0]'),";
	}
	### remove the last comma
	$insert = chop($insert, ",");
    global $pdo;
	try{
		$pdo->query($insert);
	}
	catch(PDOException $e){
		echo $e->getMessage();
		exit;
	}
}

function updateProfileParameters($user_id, $params){
    global $pdo;
	foreach($params as $p_id=>$p_val){
		$update = $pdo->prepare("UPDATE user_profile_values 
			SET value = ? 
			WHERE user_id = ? AND profile_field_id = ?");
			/*
			mysql_real_escape_string($p_val[0]),
			mysql_real_escape_string($p_val[1]));
			*/
		$update->execute(array($p_val[0], $user_id, $p_id));
	}
	return true;
}



//returns size of the file in bytes
function getFileSizeInBytes($f){
	//return 'to be done'; // for now
	return '';
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

function getUserTypeIdfromType($userType){
#### returns all the info stored in params table
    global $pdo;
	$select = $pdo->prepare("SELECT id FROM user_types WHERE type=?");
	$select->execute(array($userType));
	list($user_type_id)=$select->fetch();
	return $user_type_id;
}

function getStatusTranslations($status){
    global $pdo;
	try{
	$select = $pdo->prepare("SELECT translation FROM case_states 
		WHERE state = ?");
	$select->execute(array($status));
	}
	catch(PDOException $e){
		echo $e->getMessage();
	}
	$row = $select->fetch(PDO::FETCH_ASSOC);
	return $row['translation'];
}

function readAuthCodefromFile(){
        $myFile = '/var/www/html/teledermatology/certs/c2dm_auth.txt';
        $fh = fopen($myFile, 'r');
        $theData = fread($fh, filesize($myFile));
        fclose($fh);
        return $theData;
}

function generatePassword($len=7) {
	$chars = "abcdefghijkmnopqrstuvwxyz023456789";
    srand((double)microtime()*1000000);
    $i = 1;
    $pass = '' ;
    while ($i <= $len) {
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
$http_host = empty($_SERVER['HTTP_HOST'])?$app_config['http_host']:$_SERVER['HTTP_HOST'];

foreach($arr_of_variable as $k => $v){
        $pattern[$k]="/\[\[$k\]\]/";
        $replacement[$k] = str_replace('$', '\$', $v);
        $body = preg_replace($pattern,$replacement,$body);
}
$pattern= '/\[\[server\]\]/';
$body = preg_replace($pattern,$http_host,$body);
return $body;
}
 
function caseTimeout($timeout_seconds){
$update = sprintf("UPDATE cases set status='1', specialist_id=NULL, 
	resident_id=NULL, updated=NOW() 
	WHERE (specialist_id IS NOT NULL OR resident_id IS NOT NULL) 
	AND status IN (16, 17, 18)
	AND updated < DATE_SUB(NOW(), INTERVAL $timeout_seconds SECOND)");
	//echo $update;	
    global $pdo;
	if($pdo->query($update)){
		return true;
	}else{
		return false;
	}
}        

function getUSStates(){
    global $pdo;
/*
	$app_config = getConfig();
	$states = file($app_config['document_root'].'/lib/states.txt');
	return $states;
*/
	$select = $pdo->prepare("SELECT id, name FROM states");
	$select->execute();
	$states = array();
	while($row = $select->fetch(PDO::FETCH_ASSOC)){
		$states[$row['id']]=$row['name'];
	}
	return $states;
}

function sanitizeInput($s){
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

function message($msg, $ref){
	$_SESSION['action_msg'] = $msg;
	header("location:$ref");
}

function showMessage(){
	if(!empty($_SESSION['action_msg'])){
		echo "<script>
		alert('$_SESSION[action_msg]');
		</script>";
		$_SESSION['action_msg'] = null; 
	}
}


function sendTemplateEmail($to,$subject_path,$body_path,$template_vars){
$app_config = getConfig();
$email_from_address='no-reply@aad.org';	
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
#echo "<!-- $to -->";
mail($to, $email_subject, $email_body, $headers);
}        

function logMessage($msg){
$app_config = getConfig();
$logfile = $app_config['document_root'].'/../logs/log.txt';
$fd = fopen($logfile,"a");
fwrite($fd, date('c').' | '.$msg.PHP_EOL);
fclose($fd);
}

function logWebServicesRequest($request){
	$app_config = getConfig();
	$logfile = $app_config['document_root'].'/../logs/ws_requests.txt';
	$fd = fopen($logfile,"a");
	fwrite($fd, date('c').' | '.$request.PHP_EOL);
	fclose($fd);
}

function isAppUpgradeRequired($api_version){
	//$supported_api_versions = array('1.0.1','2.0');
	$lowest_supported = '1.0.1';
	$current = '2.0.0';

	if((version_compare($api_version,  $lowest_supported) < 0) || (version_compare($current, $api_version) < 0)){
		return true;
	}
	return false;
}

function isDeviceSupported($data){
	return true;
}


function getCronUser(){
	$select = sprintf("SELECT id FROM users WHERE username='cronuser'");
        global $pdo;
		if(!($res=$pdo->query($select))){
			return false;
		}
		$row = $res->fetch(PDO::FETCH_ASSOC);
		return $row['id'];
}

function outputCSV($data) {
    $outstream = fopen("php://output", "w");
    function __outputCSV(&$vals, $key, $filehandler) {
        fputcsv($filehandler, $vals); // add parameters if you want
    }
    array_walk($data, "__outputCSV", $outstream);
    fclose($outstream);
}

function getUserAgentString(){
	return empty($_SERVER['HTTP_USER_AGENT'])?null:$_SERVER['HTTP_USER_AGENT'];
}
function getRemoteIPAddress(){
	return empty($_SERVER['REMOTE_ADDR'])?null:$_SERVER['REMOTE_ADDR'];
}


function generateOptions($options_array, $val){
    // The options array is like this 
    // array('Male'=>10, 'Female'=>11) etc
    echo "<option value=''>Select</option>";
    foreach($options_array as $k=>$v){
        $selected = '';
        if($val == $v){
            $selected = "selected"; 
        }
        echo "<option value=\"$v\" $selected>$k</option>";
    }
}

function generateRadio($name, $option, $val){
    $checked = '';
    if(!empty($val) && $val == $option){
        $checked = 'checked';
    }
    echo "<input type=\"radio\" name=\"$name\" value=\"$option\" $checked/>";
}    

function generateCheckbox($name, $option, $val){
    $checked = '';
    $vals = explode(",", $val);
    if(in_array($option, $vals)){
        $checked = 'checked';
    }
    echo "<input type=\"checkbox\" name=\"$name\" value=\"$option\" $checked/>";
}  

/**
@copied by: Devdatta Kulkarni
@desc: Copied from telemed2 for fixing slider for update case
@date: 11 Sep 2014
*/

function getFileTypeImageFromFile($file){
$file_type = array();
$file_extension = pathinfo($file,PATHINFO_EXTENSION);
        if(preg_match("/DOC|doc|DOCX|docx/",$file_extension)){
                $file_type['image'] = "document.jpg";
                $file_type['file_type'] =$file_extension;
        }else if(preg_match("/PPT|ppt|PPTX|pptx/",$file_extension)){
                $file_type['image'] = "ppt.jpg";
                $file_type['file_type'] =$file_extension;
        }else if(preg_match("/EXL|exl|EXLX|exlx|xls|XLS|xlsx|XLSX/",$file_extension)){
                $file_type['image'] = "excel.jpg";
                $file_type['file_type'] =$file_extension;
        }else if(preg_match("/PDF|pdf/",$file_extension)){
                $file_type['image'] = "pdf.jpg";
                $file_type['file_type'] =$file_extension;
        }else if(preg_match("/GIF|gif/",$file_extension)){
                $file_type['image'] = "gif.jpg";
        }else if(preg_match("/bmp|BMP/",$file_extension)){
                $file_type['image'] = "bmp.jpg";
                $file_type['file_type'] =$file_extension;
        }else if(preg_match("/MP3|mp3|m4a|M4A|WAV|wav/",$file_extension)){
                $file_type['image'] = "wav1.jpg";
                $file_type['file_type'] =$file_extension;
        }else if(preg_match("/MP4|mp4|mpeg|MPEG|MOV|mov|M4V|m4v/",$file_extension)){
                $file_type['image'] = "video.jpg";
                $file_type['file_type'] =$file_extension;
        }else{
         #       $file_type['image'] = "text.png";
         #       $file_type['file_type'] =$file_extension;
        }
        return $file_type;
}  

function redirectWithMessage($url, $message){
$_SESSION['confirm_msg'] = $message;//echo $url; exit;
header("Location:".$url);
die();
}

function calculateAgeFromDOB($dob){
    $dob = preg_replace("#(\d\d)/(\d\d)/(\d\d\d\d)#", "$3-$1-$2", $dob);
    $from = new DateTime($dob);
    $to = new DateTime('today');
    $age = $from->diff($to)->y + 1;
    if(empty($dob)){
        $age = 'unknown';
    }
    return $age;
}

function showMessageNew(){
if(empty($_SESSION['confirm_msg'])){
    return '';
}
$pattern = '/<script[^>]*>(.*)<\/script>/Uis';
preg_match_all($pattern,$_SESSION['confirm_msg'],$matches);
$error_msg = $matches[0][0];
$confirm_msg = str_replace($error_msg,"",$_SESSION['confirm_msg']);
echo "<div id=\"response-bar\"><div class=\"yellow-response-bar-text\">
    <p>
    $confirm_msg
    </p>
    <div class=\"button-close\">
        <a onclick=\"document.getElementById('response-bar').style.display='none';\">
        <img border=\"0\" style=\"width:15px; height:15px;\" alt=\"Close\" src=\"/images/button_close.jpg\">
        </a>
    </div>
    </div>
 </div>";
// empty the session variable
$_SESSION['confirm_msg'] = '';
}

// calls to initialization functions
//error_reporting(0);
$app_config = getConfig();
//date_default_timezone_set('America/New_York');
date_default_timezone_set($app_config['server_timezone_offset']);
?>
