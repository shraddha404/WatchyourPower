<?php
//ini_set('display_errors',0);
chdir(dirname(__FILE__));
include_once 'lib.php';
include_once 'db_connect.php';
//include_once "db_connect.php";
/*
include_once "PHPMailer/class.phpmailer.php";
include_once "PHPMailer/class.smtp.php";
*/
/*include_once "PHPMailer/PHPMailerAutoload.php";
include_once "config.php";
*/
class User{
	//class variables
	var $user_id = null;
	var $org_id = null;
    	var $org_id_str = null;
	var $user_type = null;
	var $ua = null; //user agent
	var $ua_long = null; // full user agent string
	var $remote_ip = null;
	var $error = null;
	var $error_code = null;
	var $user_profile = null;
	var $app_config = null;
	var $timezone_offset = null;
    	var $pu; // patient user object

    public function __construct($user_id=null){
        $this->user_id = $user_id;
		if(!empty($user_id)){
			$this->user_type = $this->getUserType();
			$this->user_profile = $this->getUserDetails($user_id);
	    try{
	    }
		catch(Exception $e){
			// do nothing
		}
		
		}
		//some initialization stuff here
		$this->app_config = getConfig();
		$this->setTimezoneOffset();
    }

	function __call($functionName, $argumentsArray ){
		$log = debug_backtrace();
		$this->createActionLog($log,0);
		$this->setStdError('undefined_function');
	}
	/* set user's timezone offset
	*/

	function setTimezoneOffset($offset=null){
		if(!empty($offset)){
			$this->timezone_offset = $offset;
		}
		else if(!empty($_SESSION['timezone_offset'])){
			$this->timezone_offset = $_SESSION['timezone_offset'];
		}
		else{
			// don't set any offset 
			// default is server time
		}
	}
	/* set session timeout error for mobile client */
	function sessionTimeoutError(){
		echo "<ResponseHeader>
		<ResponseCode>1000</ResponseCode>
		<ResponseMessage>Session timeout</ResponseMessage>
		</ResponseHeader>";
		session_destroy();
		exit;
	}

/*
function getError() 
	return the class variable $error. 
*/
    /*function getError(){
            $this->error;
    }
*/
/*
function setError() 
	assign error to the class variable $error.
*/
    function setError($error){
            $this->error = $error;
    }

/* 
	The following function sets the error_code
*/
	function setErrorCode($error_code){
		$this->error_code = $error_code;
	}

/* 
Following function sets predefined standard errors 
along with the associated error code.
The error codes are defined in the database.
*/
	function setStdError($error_short_name){
		$select = "SELECT err_str, error_code FROM error_codes
			WHERE err_str = ?";
        global $pdo;
		$stmt = $pdo->prepare($select);
		$stmt->execute(array($error_short_name));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->setError($row['err_str']);
		$this->setErrorCode($row['error_code']);
	}

/*
function getError() 
	return true if class varible has some error value else return false. 
*/
	function hasError(){
		if(empty($this->error)){
			return false;
		}
		return true;
	}

public	function authenticate($username, $password,$captcha){
	if(isset($_SESSION['code']) && $captcha != $_SESSION['code']){
		$this->setError("You have entered wrong code.");
		return false;
	}
		global $pdo;
	try{
		$select = $pdo->prepare("SELECT id FROM users WHERE username = ? AND password = ? AND status = 1");
		$select->execute(array($username, md5($password)));
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	$row = $select->fetch(PDO::FETCH_ASSOC);
	if($row['id']){
		$this->user_id = $row['id'];
		$this->user_type = $this->getUserType();
		$action_details=array();
		$action_details['table_name']='users';
		$action_details['row_id']=$this->user_id;
		$action_details['operation']='Logged In';
		$this->createActionLog($action_details);
		return true;
	}
	$this->setError("Invalid username or password.");
	return false;
	}

/*
function authenticate($username,$password); 
	validate user entered username and password with database username and password 
	if it matches return user_id, user_type and set user_id in session
	other wise it return false with error message.
public function authenticate($username, $password){
        global $pdo;
        $stmt = $pdo->prepare("SELECT `id`, `status` FROM users 
            WHERE username = ? AND password = ?");
                
    	$stmt->execute(array($username, $this->getPasswordHashOfUser($username,$password)));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if(empty($row['id'])){
	    $userid = $this->getUserIdFromUsername($username);
	    $user_details = $this->getUserDetails($userid);
            $this->setStdError("auth_failure");
	    $this->logAuthenticationAttempt(array('user_id'=>$userid, 'result'=>0));
		//disable account if the attempts have exceeded the allowed limit
		if($this->getCountAuthFailures($userid) > $this->app_config['allowed_auth_failures']){
			//disable account
			$this->disableAccountForAuthFailures($userid);
            $this->setError('Your account has been disabled. Please contact your administrator.');
		}
            return false;
        }
	if($row['id'] > 0){
	    if($row['status'] == 0){
		//disabled account
		$this->setStdError('disabled_account');
		return false;
	    }
            $this->user_id = $row['id'];
            $this->user_profile = $this->getUserDetails($row['id']);
            $this->org_id = $this->user_profile['org_id'];
	    // we don't need to keep track of failed attempts 
   	    // which are essential for disabling an account
	    $this->removeFailedLoginAttempts();
			$this->user_type = $this->getUserType();
			$this->_setUserProfile();
		    $log = debug_backtrace();
		    $this->createActionLog($log);
		$this->logAuthenticationAttempt(array('user_id'=>$row['id'], 'result'=>1));
            return true;
        }
        else{
            $this->setStdError("auth_failure");
            return false;
        }
}
*/

/*
	Get user id from username
*/
public function getUserIdFromUsername($username){
	global $pdo;
	try{
	$select = $pdo->prepare("SELECT id FROM users WHERE username = ?");
	$select->execute(array($username));
	}
	catch(PDOException $e){
	$this->setError($e->getMessage());
	return false;
	}
	$row = $select->fetch(PDO::FETCH_ASSOC);
	return $row['id'];
}

/*
Log authentication attempts
*/
public function logAuthenticationAttempt($data){
	global $pdo;
	try{
	$insert = $pdo->prepare("INSERT INTO `authentication_log`
		(`user_id`, `result`, `attempted`)
		VALUES(?, ?, NOW())");
	$insert->execute(array($data['user_id'], $data['result']));
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
	
}

/* 
Get count of unsuccessful athentication attempts 
in the last xxx min
xxx is set in the app_config
*/
public function getCountAuthFailures($userid){
	global $pdo;
	if(empty($this->app_config['account_reactivation_time_min'])){
		$this->setError('Account reactivation time is not set.');
		return false;
	}
	try{
	$select = $pdo->prepare("SELECT count(*) AS count FROM `authentication_log`
		WHERE `attempted` > DATE_SUB(NOW(), INTERVAL ".$this->app_config['account_reactivation_time_min']. " MINUTE)
		AND `result` = 0 AND `user_id` = ?");
	$select->execute(array($userid));
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}	
	$row = $select->fetch(PDO::FETCH_ASSOC);
//print_r($row);
	return $row['count'];
}

public function removeFailedLoginAttempts(){
	global $pdo;
	try{
	$delete = $pdo->prepare("DELETE FROM authentication_log
		WHERE `result` = 0 AND `user_id` = $this->user_id");
	$delete->execute();	
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
}

/*
Disable account due to auth failures
*/
public function disableAccountForAuthFailures($user_id){
	global $pdo;
	try{
	$update = $pdo->prepare("UPDATE users 
	SET `status_reason` = ?, `status` = 0
	WHERE id = ?");
	$update->execute(array('auth_failure', $user_id));
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	// Also remove failed login attempts
	// from the log table
	$this->user_id = $user_id;
        $this->removeFailedLoginAttempts();
	$this->user_id = null;
	return true;
}

/* function logout
	added for activity logging purpose
*/
	function logout(){
		$action_details=array();
		$action_details['table_name']='users';
		$action_details['row_id']=$this->user_id;
		$action_details['operation']='Logged Out';
		$this->createActionLog($action_details);
		//$_SESSION['user_id'] = null;
		return true;
	}

/*
function authenticateMD5() 
	function written for authentication of mobile device users.
OBSOLETE NOW
NOT TO BE USED
*/
public function authenticateDevice($username, $password, $app_token=null){
		if(($this->ua == 'mobile') && (!$this->isValidToken($app_token))){
			$this->setStdError('invalid_app_token');
			return false;
		}
        global $pdo;
	if($this->authenticate($username, $password)){
	//update device determined by app_token
		if($this->ua == 'mobile')
		$this->updateMyDevice($app_token);

		$this->user_type = $this->getUserType();
		$this->_setUserProfile();

		$log = debug_backtrace();
		$this->createActionLog($log);
            return true;
        }
        else{
            $this->setStdError("auth_failure");
            return false;
        }
}

	function _setUserProfile(){
		$this->user_profile = $this->getUserDetails($this->user_id);
	}

/*
function isAdmin() 
	return true if logged in user type is Admin other wise return false with error message.
*/
	function isAdmin(){

		if($this->user_type == 'Admin'){
			return true;
		}
		return false;
	}
/* Added by Rupali*/
function isLocationowner(){
		if($this->user_type == 'LocationOwner'){
			return true;
		}
		return false;
	}
/*

/* Added by Rupali*/
function isSpecial(){
		if($this->user_type == 'Special'){
			return true;
		}
		return false;
	}
/*



    Check if the logged-in user is SuperAdmin
*/

public function isSuperAdmin(){
    if($this->user_type == 'SuperAdmin'){
        return true;
    }
    return false;
}


/*
function getUserType() 
	return user type of logged in user.
*/
	function getUserType(){
		$select = "SELECT user_types.type FROM user_types 
			LEFT JOIN users ON user_types.id = users.type
			WHERE users.id = $this->user_id";

        global $pdo;
        $stmt = $pdo->query($select);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row['type'];
	}

/*
function _loginRedirect() 
	function redirect user to the index page. 
*/
    function _loginRedirect(){
        	// send user to the login page
        	header("Location:/index.php");
    }
    
/*
function getUserDetails($user_id) 
	function accept the user id as parameter and return the users details of that user id. 
*/
    function getUserDetails($user_id){
	
		$select = "SELECT users.*, user_types.type as user_type from users 
			LEFT JOIN user_types ON user_types.id = users.type
			WHERE users.id = ?";
		
        global $pdo;
		$stmt = $pdo->prepare($select);
		$stmt->execute(array($user_id));
		$result_array = $stmt->fetch(PDO::FETCH_ASSOC);
		return $result_array;
	}
	
       function getLoctionOwnerLocations($user_id){
	
		$select = "SELECT * from location_owner_locations WHERE user_id = ?";
		
        global $pdo;
		$stmt = $pdo->prepare($select);
		$stmt->execute(array($user_id));
		$result_array = $stmt->fetch(PDO::FETCH_ASSOC);
		return $result_array;
	}     
/*
function getUsers($user_type) 
	function accept the user type as parameter and return the all user details of that user type. 
*/
         function getUsers($user_type){
            global $pdo;
			$select = $pdo->prepare("SELECT * ,users.id AS userId 
            FROM users 
			LEFT JOIN user_types ON users.type = user_types.id
			WHERE user_types.type = ?");
			//$res = mysql_query($select);
            $select->execute(array($user_type));
		    return $select->fetchAll(PDO::FETCH_ASSOC);
		}

/*
function updateUser($data) 
	function accept the data array as parameter and update user info.
	return true if user info is updated successfully else return false with error message. 
*/
	function updateUser($data){
                global $pdo;
                if($data['pwd']!=''){
		$update = $pdo->prepare("UPDATE users 
                SET `first_name`= ?,`email`= ?, `password`= ?, 
                    `username`= ?, `type`= ?, `role`= ? 
                    WHERE id= ?");
                $update->execute(array($data['name'], $data['email'], $this->getPasswordHashOfUser($data['userName'],$data['pwd']), 
                $data['userName'], $data['userType'], $data['role'], $data['userId']));

                //update the password log
                $this->updatePasswordLog($this->getPasswordHashOfUser($data['userName'],$data['pwd']));

                }else{
                 $update = $pdo->prepare("UPDATE users 
                    SET `first_name`= ?,`email`= ?,  
                     `username`= ?, `type`= ?, `role`= ? 
                     WHERE id= ?");
                 $update->execute(array($data['name'], $data['email'],$data['userName'], 
                    $data['userType'], $data['role'], $data['userId']));
                }
					$log = debug_backtrace();
					$this->createActionLog($log);
					return true;
	}
	
/*
function updateMyProfile($data) 
	function accept the data array as parameter and update the logged in user information
	with data in parameter array and return true if update user information successfully else return false with error message. 
*/
		function updateMyProfile($data){
        global $pdo;
		$update = $pdo->prepare("UPDATE users SET `email`= ? WHERE id= ?");
        $update->execute(array($data['email'], $this->user_id));
		$preparedProfileParam = prepareProfileParams($data);
		updateProfileParameters($this->user_id,$preparedProfileParam);
		$log = debug_backtrace();
		$this->createActionLog($log);
		return true;
	}


/*
function generateToken($length) 
	function accept length as parameter and generate Token of that length and return that token. 
*/
    function generateToken($length){
        $token = "";
        $possible = "0123456789bcdfghjkmnpqrstvwxyz";
        $i = 0;
        while ($i < $length) {
            $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
                if (!strstr($token, $char)) {
                    $token .= $char;
                    $i++;
                }
        }
        return $token;
    }

/*
function getDeviceToken($device_id) 
	function accept device id as parameter and return token of that device id. 
*/
	function getDeviceToken($device_id){
        global $pdo;
		$select = $pdo->prepare("SELECT token FROM device_registry
			WHERE device_id = ?");
		$select->execute(array($device_id));
        $row = $select->fetch(PDO::FETCH_ASSOC);
		return $row['token'];
	}
	
/*
function updateDeviceTokens($token, $data) 
	function accept two parameters token and data array and update device_registry details of that token. 
*/
	function updateDeviceTokens($token, $data){
        global $pdo;
		$update = $pdo->prepare("UPDATE device_registry 
			SET apn_token = ?, c2dm_device_id = ?, wp7_channel_url = ?
			WHERE token=?");
        $update->execute(array($data['apn_token'], $data['c2dm_device_id'], $data['wp7_channel_url'], $token));
		$log = debug_backtrace();
		$this->createActionLog($log);
	}

/*
function isValidToken($token) 
	function accept token as parameter and check that token is present in device registry table 
	and return true if present in device registry otherwise return false. 
*/
	function isValidToken($token){
        global $pdo;
		$select = $pdo->prepare("SELECT 1 FROM device_registry 
			WHERE token = ?");
		$select->execute(array($token));
        	if($select->fetchColumn()){
			return true;
		}
		return false;
	}
        
        function notifyUser($user_id=null,$from,$to,$subject,$message,$message_data=null,$alert_str=null){
                $device_token="";
		$headers = "MIME-Version: 1.0" . "\n";
		$headers .= "Content-type:text/html;charset=iso-8859-1" . "\n";
		$headers .= "From: $from" . "\n";
           //$toAddress="";
                //$mail = new PHPMailer();
                //$mail->IsSMTP();  // telling the class to use SMTP
                //$mail->IsMail();
                //$mail->Host = "localhost"; // SMTP server
                //$mail->From = $from;
                //$mail->FromName = "Vignet";
           if(is_array($to)){
              
                foreach($to as $mailto){
                    
                    $toAddress.=$mailto." ,";
                   // $mail->AddAddress($mailto);
                }
           }else{
               $toAddress=$to;
               //$mail->AddAddress($to);
           }
		//$mail->AddBCC('admin@iven360.com', 'Admin BCC');
                //$mail->Subject  = $subject;
                //$mail->Body = $message;

#               $mail->WordWrap = 50;
                //$mail->Send();
           $device_details=$this->getDeviceDetailsfunction($user_id);
           $device_token=$device_details['apn_token'];
           if($device_token!=""){
           $notify_st = $this->apnsNotify($device_token, $message_data,$alert_str);
           }
          // echo "testing success";
                if(mail($toAddress,$subject,$message,"From:$from")){
                    
                    
                    return true;
                }else{
                    return false;
                }
        }
        
/*
function googleAuthenticate(four parameters) 
*/
  function googleAuthenticate($username, $password, $source="Company-AppName-Version", $service="ac2dm") {    

    include $_SERVER['DOCUMENT_ROOT'].'/includes/session_start.php';
    if( isset($_SESSION['google_auth_id']) && $_SESSION['google_auth_id'] != null)
        return $_SESSION['google_auth_id'];

    // get an authorization token
    $ch = curl_init();
    if(!ch){
        return false;
    }

    curl_setopt($ch, CURLOPT_URL, "https://www.google.com/accounts/ClientLogin");
    $post_fields = "accountType=" . urlencode('HOSTED_OR_GOOGLE')
        . "&Email=" . urlencode($username)
        . "&Passwd=" . urlencode($password)
        . "&source=" . urlencode($source)
        . "&service=" . urlencode($service);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);    
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    // for debugging the request
    //curl_setopt($ch, CURLINFO_HEADER_OUT, true); // for debugging the request

    $response = curl_exec($ch);

    //var_dump(curl_getinfo($ch)); //for debugging the request
    //var_dump($response);

    curl_close($ch);

    if (strpos($response, '200 OK') === false) {
        return false;
    }

    // find the auth code
    preg_match("/(Auth=)([\w|-]+)/", $response, $matches);

    if (!$matches[2]) {
        return false;
    }

    $_SESSION['google_auth_id'] = $matches[2];
    return $matches[2];
}

/*
The following is for sending a C2DM message
Google will be using GCM (Google Cloud Messaging soon.
Following function will not be used
*/
function sendMessageToPhone($deviceRegistrationId, $title, $messageText) {
    $authCode=trim(readAuthCodefromFile());
        $data = array(
            'registration_id' => $deviceRegistrationId,
            'collapse_key' =>  'ck_' . 'col_key',
		'data.title'=> $title,
            'data.message' => $messageText //TODO Add more params with just simple data instead           
        );

	$fields = trim(http_build_query($data));
//print $fields;
/*print $msgType."\n";
print $messageText."\n";
#print $deviceRegistrationId."\n";
print $fields."\n";
print strlen($fields);
*/
        //$headers = array('Authorization: GoogleLogin auth=' . $authCode,'Content-length:'.strlen($fields));
        $headers = array('Authorization: GoogleLogin auth=' . $authCode);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://android.apis.google.com/c2dm/send");
        if ($headers)
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

//curl_setopt($ch, CURLINFO_HEADER_OUT, true); //for debugging the request
//var_dump(curl_getinfo($ch,CURLINFO_HEADER_OUT)); //for debugging the request
        $response = curl_exec($ch);

        curl_close($ch);

		logMessage("$deviceRegistrationId | $messageText");
        return $response;
    }

	function sendGCMNotification($gcm_device_ids, $message) {
		 /*
		 $gcm_device_ids is an array 
		 $message also is an array
		 e.g.
		 $gcm_device_ids = array('device_id1','device_id2');
		 $mssage = array('message'=>'test message');
		 */
        // Set POST variables
        $url = 'https://android.googleapis.com/gcm/send';
 
        $fields = array(
            'registration_ids' => $gcm_device_ids,
            'data' => $message,
        );
 
        $headers = array(
            'Authorization: key=' . $this->app_config['google_api_key'],
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();
 
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
 
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
 
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            //die('Curl failed: ' . curl_error($ch));
			logMessage('Curl failed: ' . curl_error($ch));
        }
	else{
			logMessage('GCM message - '.$message[0]);
	}
 
        // Close connection
        curl_close($ch);
        return $result;
    }
 
/*
function get_email_of_user($userName) 
	function accept username as parameter and retuns the users email address.
*/
    function get_email_of_user($userName){
        global $pdo;
        $select = $pdo->prepare("SELECT id,email FROM users 
            WHERE username = ?");
        $select->execute(array($userName));
		return $select->fetchAll(PDO::FETCH_ASSOC);
    }
    
/*
function resetPassword($username,$userEmail)
	function accept three parameters username password and email address
	and reset the user password and return true after successfully password reset other wise retun false 
	with error message.
*/
public function resetPassword($userName,$userEmail){
    global $pdo;
    $code = generatePassword(64);
	$select = $pdo->prepare("SELECT * FROM users 
        WHERE username = ? AND email = ?");
    $select->execute(array($userName, $userEmail));
    $row = $select->fetch(PDO::FETCH_ASSOC);
		if(count($row)!= 0){
		   $update=$pdo->prepare("UPDATE users 
                   SET `password`=?  
                   WHERE username=? AND email = ?");
        	        if($update->execute(array($this->getPasswordHashOfUser($userName,$code), $userName, $userEmail))){
						$log = debug_backtrace();
						$this->createActionLog($log);

                        // add to Reset Queue
                        $data['user_id'] = $this->getUserIdFromUsername($userName);
                        $data['code'] = $code;
                        $this->addToPasswordResetQueue($data);
                        //template var data
                        $userid = $this->getUserIdFromUsername($userName);
                        $user_details = $this->getUserDetails($userid);
                        $data = array(
                                'userName'=>$userName,
                                'pwd'=>$code,
                                'name'=> $user_details['first_name'],
                                'last_name'=> $user_details['last_name']
                            );
                        $this->sendTemplateEmail($userEmail, $this->app_config['password_retrieval_subject_path'],
                            $this->app_config['password_retrieval_body_path'],$data);
               	        return true;
	                }else{
                        $this->setError($update);
               	         return false;
	                }
		 }# if of not empty array check
	         else{
        	       $this->setStdError("reset_password_error");
                       return false;
	        }
    }

/*
    Add to password_reset_queue
*/
public function addToPasswordResetQueue($data){
    global $pdo;
    try{
    $insert = $pdo->prepare("INSERT INTO password_reset_queue
        (`user_id`, `code`, `link_sent`, `link_expires`)
        VALUES(?,?, NOW(), 
        DATE_ADD(NOW(), INTERVAL ".$this->app_config['reset_password_link_expiry_min']." MINUTE))");
    $insert->execute(array($data['user_id'], $data['code']));
    }
    catch(PDOException $e){
        $this->setError($e->getMessage());
        return false;
    }
    return true;
}
    
/*
function apnsNotify(four parameters) 
*/
    function apnsNotify($device_token, $message_data,$alert){
            //message data will be an array
            // device_token will be a simple string
	include 'config.php';
        $certs_dir = $this->app_config['document_root'].'/../certs';
        // Apns config
	$is_production_mode = ($this->app_config['apns_mode'] == 'production')? 1 : 0;

        // true - use apns in production mode
        // false - use apns in dev mode
        define("PRODUCTION_MODE",$is_production_mode);

        $serverId = 1;
//	$serverName = 'aad2.vignetcorp.com';
        $serverName = $this->app_config['http_host'];

        if(PRODUCTION_MODE) {
        $apnsHost = 'gateway.push.apple.com';
        } else {
        $apnsHost = 'gateway.sandbox.push.apple.com';
        }

        $apnsPort = 2195;
        if(PRODUCTION_MODE) {
        // Use a development push certificate 
        //$apnsCert = 'path to production certificate';
//        $apnsCert = $certs_dir.'/apns_prod/AccessDermPUSHCertKey.pem';
        $apnsCert = $certs_dir.'/'.$this->app_config['apns_production_certificate'];
		if(!file_exists($apnsCert)){
			logMessage("Certificate file - ".$apnsCert." is not found");
		}
        } else {
        // Use a production push certificate// reversed
//	$apnsCert = $certs_dir.'/AccessDermCK.pem';
        $apnsCert = $certs_dir.'/'.$this->app_config['apns_development_certificate'];
		if(!file_exists($apnsCert)){
			logMessage("Certificate file - ".$apnsCert." is not found");
		}
        }

        // --- Sending push notification ---
        // Notification content
        $payload = array();
        //Basic message
        $payload['aps'] = array(
        'alert' => $alert, 
        'badge' => 1, 
        'sound' => 'default',
        );
	/*
        $payload['server'] = array(
        'serverId' => $serverId,
         'name' => $serverName
        );
        // Add some custom data to notification
        /*$payload['data'] = array(
        'foo' => "bar"
        );
         * *
        
        $payload['data']=$message_data;
         */
        $payload = json_encode($payload);

        $streamContext = stream_context_create();
        stream_context_set_option($streamContext, 'ssl', 'local_cert', $apnsCert);
        stream_context_set_option($streamContext, 'ssl', 'passphrase', $this->app_config['apns_passphrase']);

//echo $this->app_config['apns_passphrase'];

        $apns = stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $streamContext);
	if(!$apns){
		$this->setError('Could not connect: '. $error.' ' . $errorString);
		logMessage("$error | $errorString");
	}

        //$deviceToken = str_replace(" ","",substr($device_token,1,-1)); //this was for removing those < and >
	// we don't need that
        $deviceToken = str_replace(" ","",$device_token); 

#echo "<!-- $deviceToken -->";
        //$apnsMessage = chr(0) . chr(0) . chr(32) . pack('H*', str_replace(' ', '', $deviceToken)) . chr(0) . chr(strlen($payload)) . $payload;
	// Build the binary notification
	$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

        $result = fwrite($apns, $msg);
#echo "<!-- result = $result -->";
	if(!$result){
		$this->setStdError('push_failed');
		return false;
	}
        //socket_close($apns);
        fclose($apns);
	logMessage($apnsHost.' | '. $deviceToken.' | '.$alert.' | '. $result);
	return true;
    }

	function wp7Notify($wp7_channel_url, $title, $message){
		include_once 'WP7.class.php';
		$wp7_client = new WindowsPhonePushClient($wp7_channel_url);
		$result = $wp7_client->send_toast($title, $message);	
		logMessage("$wp7_channel_url | $title | $message");
		return $result;
	}	

/*
function getDeviceDetailsfunction($user_id)
	function accept user id as parameter and return the device details of that user id. 
*/
    function getDeviceDetailsfunction($userId){
        global $pdo;	
		$select = $pdo->prepare("SELECT device_registry.*  
                    FROM users 
                   LEFT JOIN device_registry ON users.device=device_registry.id 
				   WHERE users.id = ?");
		$select->execute(array($userId));
		$result_array = $select->fetch(PDO::FETCH_ASSOC);
		return $result_array;
	}


public function matchPasswordForUsername($username, $code){
	global $pdo;
	try{
	$select = $pdo->prepare("SELECT 1 FROM `password_reset_queue` 
		WHERE `code` = ? AND `user_id` = (SELECT id FROM users 
            WHERE `username` = ?)");
	$select->execute(array($code, $username));
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	if($select->fetchColumn() > 0){
		return true;
	}
	return false;	
}

/*
Check validity of the reset link
*/
public function isPasswordResetLinkValid($data){
    global $pdo;
    try{
    $select = $pdo->prepare("SELECT 1 FROM `password_reset_queue`
        WHERE `link_expires` > NOW() AND `code` = ? AND `user_id` = (SELECT id FROM users 
            WHERE `username` = ?)");
    $select->execute(array($data['code'], $data['userName']));
    }
    catch(PDOException $e){
        $this->setError($e->getMessage());
        return false;
    }
    if($select->fetchColumn()>0){
        return true;
    }
    return false;
}

/*
Remove entries from password_reset_queue
*/
public function updatePasswordResetQueue($user_id){
    global $pdo;
    try{
    $delete = $pdo->prepare("DELETE FROM `password_reset_queue`
            WHERE `user_id` = ?");
    $delete->execute(array($user_id));
    }
    catch(PDOException $e){
        $this->setError($e->getMessage());
        return false;
    }
    return true;
}

/*
function changePassword($data)
	function accept data array as parameter and change the password in users table for the user id in the parameter array.
*/
    function changePassword($data){
        if(!empty($this->user_id) || $this->matchPasswordForUsername($data['userName'],$data['code'])){
            if(!empty($this->user_id)){
                $data['userName'] = $this->user_profile['username'];
            }
            if($data['newpwd']==$data['newpwdagn']){

			// check if the password is strong
			if(!$this->isPasswordStrong($data['newpwd'])){
				$this->setStdError('weak_password');
				return false;
			}
            // if the password is one of last n passwords
            if($this->isOneOfLastNPasswords($data['newpwd'])){
                $this->setError('Your password is one of last '.$this->app_config['password_no_repeat'].' passwords.');
                return false;
            }

            global $pdo;
                    try{
                    $update=$pdo->prepare("UPDATE users SET `password`=?, `status` = 1 WHERE id= ?");
                    $userid = $this->getUserIdFromUsername($data['userName']);
                    $update->execute(array($this->getPasswordHashOfUser($data['userName'],$data['newpwd']), $userid));
                    //update the password log
                    if(empty($this->user_id)){
                        $this->user_id = $userid;
                    }
                    $this->updatePasswordLog($this->getPasswordHashOfUser($data['userName'],$data['newpwd']));

                    $this->updatePasswordResetQueue($userid);
					$log = debug_backtrace();
					$this->createActionLog($log);
                        return true;
                }
                catch(PDOException $e){
                        $this->setError($e->getMessage());
                         return false;
                 }

            }else{
                    $this->setError("Passwords entered do not match. Please check and type again.");
                    return false;
            }
        }
        else{
            $this->setError("Password was not reset.");
            return false;
        }
    }

/*
	To enforce stronger passwords, we use this function
	isPasswordStrong($password)
*/

	function isPasswordStrong($password){
		if(strlen($password) < 8)
		return false;
		if(!preg_match("#[0-9]+#",$password))
		return false;
		if(!preg_match("#[A-Z]+#",$password))
		return false;
		if(!preg_match("#[a-z]+#",$password))
		return false;
		if(!preg_match("#[\W_]+#",$password))
		return false;

		return true;
	}


	function aasort (&$array, $key){
		$sorter=array();
		$ret=array();
		reset($array);
		foreach ($array as $ii => $va){
			$sorter[$ii]=$va[$key];
		}
		asort($sorter);
		foreach ($sorter as $ii => $va){
		$ret[$ii]=$array[$ii];
		}
		$array=$ret;
	}


	function createActionLog($details){
            global $pdo;
		$insert = "INSERT INTO action_log 
				(`table_name`,`row_id`,`operation`,`created_on`,`created_by`)
				VALUES(?,?,?,NOW(),?)";
		$insert_args = array($details['table_name'],$details['row_id'],$details['operation'],$this->user_id);
        try{
		    $stmt=$pdo->prepare($insert);		
		    $stmt->execute($insert_args);
        }
        catch(PDOException $e){
				$this->setError($e->getMessage());
				return false;
			}	
			return true;
	}

	// auxiliary function
// return user's local time
function getUserLocalTime($datetime, $format='M d g:i a'){
    $server_dt = new DateTime($datetime, new DateTimeZone($this->app_config['server_timezone_offset']));
	// if timezone is not set
	// return server time
	if(empty($this->timezone_offset)){
		$local_dt_string = $server_dt->format($format);
		return $local_dt_string;
	}

	// example value ot $this->timezone_offset
    //$this->timezone_offset = '+0800';
    $user_offset = $this->timezone_offset;

    $offset_hr = intval($user_offset/100);
    $offset_min = $user_offset%100;
    $offset_sec = $offset_hr * 3600 + $offset_min * 60;

    //get offset
    $gmt_offset = $server_dt->getOffset();
    //modify time to first get GMT
    $server_dt->modify("-$gmt_offset second");

    // now modify time again to get local time 

    $server_dt->modify("$offset_sec second");

    //formatted string
    $local_dt_string = $server_dt->format($format);
    return $local_dt_string;
}

function getAllUsers(){
	$per_page=10;
	$p =1;
	$offset = ($p - 1) * $per_page;
	$select = "SELECT * ,users.id as user_id, user_types.type AS user_type FROM users 
		LEFT JOIN user_types ON user_types.id=users.type WHERE username !='cron'";
		//LIMIT $offset, $per_page";
		global $pdo;
		$res = $pdo->query($select);
		return $res->fetchAll(PDO::FETCH_ASSOC);
}
function getAllLocationOwnerUsers(){
	$per_page=10;
	$p =1;
	$offset = ($p - 1) * $per_page;
	$select = "SELECT * , users.id AS user_id, user_types.type AS user_type, users.name AS name
FROM users
LEFT JOIN user_types ON user_types.id = users.type
WHERE username != 'cron'
AND users.type = '3'";
		//LIMIT $offset, $per_page";
		global $pdo;
		$res = $pdo->query($select);
		return $res->fetchAll(PDO::FETCH_ASSOC);
}

function getUserTypes(){
	$select = "SELECT * ,user_types.id as type_id, user_types.type AS user_type FROM user_types";
                global $pdo;
                $res = $pdo->query($select);
                return $res->fetchAll(PDO::FETCH_ASSOC);
}

/* function get get send notification time and autounlock time depending on severity of case */ 
	function getTimer(){
        global $pdo;
		$select = $pdo->prepare("SELECT * FROM manage_timers");
		if(!($res = $select->execute())){
			$this->setError($select);
			return false;
		}
		$time_config = array();
		while($row = $select->fetch(PDO::FETCH_ASSOC)){
			$time_config[$row['urgency']] = $row;
		}
		return $time_config;
	}

function sendTemplateEmail($to,$subject_path,$body_path,$template_vars){
//$app_config = getConfig();
$app_config = $this->app_config;
$email_from_address='no-reply@aad.org';
//include 'config.php';
$subject_path = $app_config['document_root']."/../".$subject_path;
$body_path = $app_config['document_root']."/../".$body_path;
//$headers = "From:$email_from_address\n";
$headers = "From:$email_from_address\n";
$email_subject_body = getEmailTemplateBody($subject_path);
$email_template_body = getEmailTemplateBody($body_path);
$email_body = $this->getEmailBody($email_template_body,$template_vars);
$email_subject = $this->getEmailBody($email_subject_body,$template_vars);
$this->sendSMTPEmail($to, $email_subject, $email_body);
}

public function getEmailBody($template_body,$arr_of_variable){
$body = $template_body;
//$subdomain = $this->getMySubdomain().'.'.$this->app_config['http_host'];
#$http_host = empty($_SERVER['HTTP_HOST'] || preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\z/', $_SERVER['HTTP_HOST']))?$subdomain:$_SERVER['HTTP_HOST'];
//$http_host = !empty($this->getMySubdomain())?$subdomain:$_SERVER['HTTP_HOST'];

foreach($arr_of_variable as $k => $v){
        $pattern[$k]="/\[\[$k\]\]/";
        $replacement[$k] = str_replace('$', '\$', $v);
        $body = preg_replace($pattern,$replacement,$body);
}
$pattern= '/\[\[server\]\]/';
$body = preg_replace($pattern,$http_host,$body);
return $body;
}


public function sendSMTPEmail($to, $email_subject, $email_body){
if(empty($to)){return false;}
############ Send mail by SMTP
//$app_config = getConfig();
$app_config = $this->app_config;
$mail = new PHPMailer(true);
$mail->IsSMTP(); // set mailer to use SMTP
//$mail->SMTPDebug = 1;  // debugging: 1 = errors and messages, 2 = messages only
$mail->SMTPAuth = true;
$mail->SMTPKeepAlive = true;
$mail->SMTPSecure = $app_config['smtp_protocol'];
$mail->Port = $app_config['smtp_port'];
//echo $app_config['smtp_host'];
$mail->Host = $app_config['smtp_host']; // specify main and backup server
$mail->Username = $app_config['smtp_username'];
$mail->Password = $app_config['smtp_password'];
$mail->From = $app_config['smtp_from'];
$mail->FromName = $app_config['smtp_fromname'];
$mail->AddAddress($to);   // name is optional
$mail->IsHTML(false);    // set email format to HTML
$mail->Subject = $email_subject;
$mail->Body = $email_body;
try{
    $mail->Send();
}
catch(Exception $e){
	logMessage($to.' | '.$email_subject. ' | '.$e->getMessage());
	return false;
}
/*
if(!$mail->Send())
{
    //echo "Mailer Error: " . $mail->ErrorInfo;
}
else
{
    //echo 'Mail sent!';
}
*/
return true;
}

/*
Functions related to parameter tables
*/
function getParamInfo($type='case'){ // default type = case
    global $pdo;
#### returns all the info stored in params table
	if($type == 'all'){
		$select = "SELECT * FROM parameters 
            WHERE `org_id` = $this->org_id";
        $args = array();
	}
	else{
		$select = "SELECT parameters.* FROM parameters, parameter_classes 
		WHERE parameter_classes.id = parameters.class 
        AND `org_id` = $this->org_id 
		AND parameter_classes.name = ?";
        $args = array($type);
	}

	try{
	$stmt = $pdo->prepare($select);
    	$stmt->execute($args);
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
	}
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/*
prepareParams(data)
accepts an array which has param names as keys
and returns an array of arrays which has param ids as keys 
names are replaced with ids from the db table parameters
first value of each 2 element array is text value and the second is option value (an int)
*/
function prepareParams($data){
	$param_info = $this->getParamInfo();
	$param_ids = array();
	$param_types = array();

	foreach($param_info as $p_i){
		$param_ids[$p_i['param']] = $p_i['id'];
		$param_types[$p_i['param']] = $p_i['param_type'];
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
/*
Does reverse of prepareParams.
*/
function prepareParamsReverse($data){
	$param_info = $this->getParamInfo();
	$param_ids = array();
	foreach($param_info as $p_i){
		$param_ids[$p_i['id']] = $p_i['param'];
	}
	$new_params = array();
	foreach($data as $k=>$v){
		if(isset($param_ids[$k])){
		#	$new_params[$param_ids[$k]] = $v;//this is the text value
			$new_params[$param_ids[$k]][0] = $v[0];//this is the text value
			$new_params[$param_ids[$k]][1] = $v[1];//this is the option value
		}
	}
	return $new_params;
}

/*
    Create password hash of user
*/
public function getPasswordHashOfUser($username,$password){
	$options = array('cost'=>12, 'salt'=>md5(strtolower($username)));
	return password_hash($password, PASSWORD_DEFAULT, $options);
}

public function updatePasswordLog($password_hash){
    global $pdo;
    try{
    $update = $pdo->prepare("INSERT INTO `password_log` 
        (`user_id`, `password`, `reset`)
        VALUE(?, ?, NOW())");
    $update->execute(array($this->user_id, $password_hash));
    }
    catch(PDOException $e){
        $this->setError($e->getMessage());
        return false;
    }
    return true;
}

/*
    Check whether the password was set recently
*/
public function isPasswordSetRecently(){
    global $pdo;
    try{
        $select = $pdo->prepare("SELECT 1 FROM password_log
            WHERE user_id = ?
            AND password_log.reset > DATE_SUB(NOW(), 
            INTERVAL ".$this->app_config['force_password_reset_days']." DAY)");
        $select->execute(array($this->user_id));
    }
    catch(PDOException $e){
        $this->setError($e->getMessage());
        return false;
    }
    if($select->fetchColumn()>0){
        return true;
    }
    return false;
}

/*
    Is this password one of the last n passwords?
*/
public function isOneOfLastNPasswords($password){
    global $pdo;
    try{
    $select = $pdo->prepare("SELECT `password` FROM `password_log`
        WHERE `user_id` = ? ORDER BY `id` DESC 
        LIMIT 0, ".$this->app_config['password_no_repeat']);
    $select->execute(array($this->user_id));
    }
    catch(PDOException $e){
        $this->setError($e->getMessage());
        return false;
    }
    while($row = $select->fetch(PDO::FETCH_ASSOC)){
    /*
    echo $this->getPasswordHashOfUser($this->user_profile['username'], $password)."\n";
    echo $password."\n";
    echo $this->user_profile['username']."\n";
    echo $row['password']."\n";
    */
        if($this->getPasswordHashOfUser($this->user_profile['username'], $password) == $row['password']){
        //$this->setError('Your password is one of your last '.$this->app_config['password_no_repeat'].' passwords.');
        return true;
        }
    }
    return false;
}

/*
Inform user that their password has been updated
*/
public function notifyPasswordUpdate($data){
	$this->sendTemplateEmail($data['email'], $this->app_config['password_reset_notification_subject'], $this->app_config['password_reset_notification_body'], $data);
}



public function getDeviceDetailsFromDeviceCode($device_code){
    global $pdo;
    $select = $pdo->prepare("SELECT * FROM devices LEFT JOIN device_installations 
    		ON devices.id=device_installations.device_id WHERE device_id_string =?");
    	$select->execute(array($device_code));
	$row = $select->fetch(PDO::FETCH_ASSOC);
	return $row;
}
public function getDevicesCodeWise(){
    global $pdo;
    $select = $pdo->prepare("SELECT * FROM devices ");
    	$select->execute();
	$devices=array();
	while($row = $select->fetch(PDO::FETCH_ASSOC)){
		$devices[$row['device_id_string']]=$row;
	}
	return $devices;
}
public function getDeviceDetailsDeviceCodeWise(){
    global $pdo;
    $select = $pdo->prepare("SELECT * FROM devices LEFT JOIN device_installations 
    		ON devices.id=device_installations.device_id ");
    	$select->execute();
	$devices=array();
	while($row = $select->fetch(PDO::FETCH_ASSOC)){
		$devices[$row['device_id_string']]=$row;
	}
	return $devices;
}

public function getInstalledDeviceCodeWise(){
    global $pdo;
    $select = $pdo->prepare("SELECT * FROM devices INNER JOIN device_installations 
    		ON devices.id=device_installations.device_id ");
    	$select->execute();
	$devices=array();
	while($row = $select->fetch(PDO::FETCH_ASSOC)){
		$devices[$row['device_id_string']]=$row;
	}
	return $devices;
}
public function isDevicePresent($device_code){
	global $pdo;
	$select=$pdo->prepare("SELECT 1 FROM devices WHERE device_id_string =?");
    	$select->execute(array($device_code));
	if($select->fetchColumn() > 0){
		return true;
	}else{
		return false;
	}
}
public function getVoltageRangesData(){
    $select = "SELECT * FROM voltage_parameters 
		LEFT JOIN voltage_parameters_values ON 
		voltage_parameters.id=voltage_parameters_values.parameter_id";
    global $pdo;
    $stmt = $pdo->query($select);
	$voltage_strings = array();
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		$voltage_strings[$row['param']]['id'] = $row['parameter_id'];
		$voltage_strings[$row['param']]['low_value'] = $row['low_value'];
		$voltage_strings[$row['param']]['high_value'] = $row['high_value'];
	}
	return $voltage_strings;
}
//To get State added by ====Rupali
	public function getStateLocation(){
	    $select="SELECT DISTINCT(`state`) FROM locations ORDER BY state";
	    global $pdo;
	    $stmt = $pdo->query($select);
		$params = array();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$params[] = $row;
		}
		return $params;
	}


//To get City added by ====Rupali
	public function getCityLocation($state){
	    $select="SELECT DISTINCT(`town`) FROM locations Where `state`='$state' ORDER BY town";
	    global $pdo;
	    $stmt = $pdo->query($select);
		$params = array();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$params[] = $row;
		}
		return $params;
	}
//To get location name added by ====Rupali
	public function getLocationName($city){
	    $select="SELECT DISTINCT(`name`) FROM locations Where `town`='$city' ORDER BY name";
	    global $pdo;
	    $stmt = $pdo->query($select);
		$params = array();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$params[] = $row;
		}
		return $params;
	}
	public function getLocationNameById($location_id){
            $select="SELECT name FROM locations Where id=$location_id";
            global $pdo;
            $stmt = $pdo->query($select);
                return $row = $stmt->fetchColumn();
        }


	public function getDistrics(){
	    $select="SELECT DISTINCT(`district`) FROM locations ORDER BY district";
	    global $pdo;
	    $stmt = $pdo->query($select);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);

	}
	public function getSupplyUtilities(){
            $select="SELECT DISTINCT(`supply_utility`) FROM locations ORDER BY supply_utility";
            global $pdo;
            $stmt = $pdo->query($select);
                return $stmt->fetchAll(PDO::FETCH_ASSOC);

        }


	public function getDistrictsFromState($state,$category=null){//echo $state;
		global $pdo;
		if(empty($state) || $state==" "){
                #$sel = "SELECT DISTINCT(`district`) FROM locations ORDER BY district";
                $sel = "SELECT DISTINCT(`district`) FROM locations 
			LEFT JOIN device_installations ON location_id=locations.id 
			WHERE locations.name NOT LIKE '%Offline%' AND status=1 ORDER BY district";
		}elseif((!empty($state) || $state!=" ") && empty($category)){
		#$sel = "SELECT DISTINCT(`district`) FROM locations WHERE state LIKE ? ORDER BY district";
                $sel = "SELECT DISTINCT(`district`) FROM locations 
			LEFT JOIN device_installations ON location_id=locations.id 
			WHERE state LIKE ? 
			AND locations.name NOT LIKE '%Offline%' AND status=1 ORDER BY district";
		} //echo $sel;
		elseif((!empty($state) || $state!=" ") && !empty($category)){
                $sel = "SELECT DISTINCT(`district`) FROM locations 
			LEFT JOIN device_installations ON location_id=locations.id 
			WHERE state LIKE ? AND revenue_classification = $category 
			AND locations.name NOT LIKE '%Offline%' AND status=1 ORDER BY district";
		}
		try{	
		$select = $pdo->prepare($sel);
		$select->execute(array('%'.$state.'%'));
		return $select->fetchAll(PDO::FETCH_ASSOC);
		}catch(PDOException $e){
			$this->setError($e->getMessage());
			return false;
		}
	}

	public function getDistributionCompanies($consumer_type, $district){
                global $pdo;
                try{
                $sel = "SELECT DISTINCT(`supply_utility`) FROM locations WHERE connection_type = ? AND district = ?";
                $select = $pdo->prepare($sel);
                $select->execute(array($consumer_type,$district));
                return $select->fetchAll(PDO::FETCH_ASSOC);
                }catch(PDOException $e){
                        $this->setError($e->getMessage());
                        return false;
                }
        }
	

	public function getStatesFromCategory($category){ 
                global $pdo;
		if(empty($category) || $category==" "){//echo $category."if";
                #$sel = "SELECT DISTINCT(`state`) FROM locations ORDER BY state ";
                $sel = "SELECT DISTINCT(`state`) FROM locations 
			LEFT JOIN device_installations ON location_id=locations.id 
			WHERE locations.name NOT LIKE '%Offline%' AND status=1 ORDER BY state ";
		}elseif(!empty($category)){//echo $category."elseif";
                #$sel = "SELECT DISTINCT(`state`) FROM locations WHERE revenue_classification = ? ORDER BY state";
                $sel = "SELECT DISTINCT(`state`) FROM locations 
			LEFT JOIN device_installations ON location_id=locations.id 
			WHERE revenue_classification = ? AND locations.name NOT LIKE '%Offline%' AND status=1 ORDER BY state ";
		} //echo $sel;
                try{
                $select = $pdo->prepare($sel);
                $select->execute(array($category));
                return $select->fetchAll(PDO::FETCH_ASSOC);
                }catch(PDOException $e){
                        $this->setError($e->getMessage());
                        return false;
                }
        }

//get all voltage parameter values ==Rupali
	public function getAllVoltageParam(){
	    $select="SELECT * FROM voltage_parameters";
	    global $pdo;
	    $stmt = $pdo->query($select);
		$params = array();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$params[] = $row;
		}
		return $params;

	}

function getAllLocations($details){
	global $pdo;
	$per_page= $this->app_config['per_page'];
	$p = ($details['p']) ? $details['p'] : 1;
	$offset = ($p - 1) * $per_page;
	$limit = 'LIMIT '.$offset.' , '.$per_page;
	if($details['restore_all']){
		$limit = '';
	}
	try{
		$select = "SELECT * FROM locations ";
		//$select = "SELECT * FROM locations $limit";
		$res = $pdo->query($select);
		return $res->fetchAll(PDO::FETCH_ASSOC);
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
}

function getAllLocationIds($details){
        global $pdo;
        $per_page= $this->app_config['per_page'];
        $p = ($details['p']) ? $details['p'] : 1;
        $offset = ($p - 1) * $per_page;
        $limit = 'LIMIT '.$offset.' , '.$per_page;
        if($details['restore_all']){
                $limit = '';
        }
        try{
                $select = "SELECT id FROM locations ";
                //$select = "SELECT * FROM locations $limit";
                $res = $pdo->query($select);
                return $res->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
}


function getAllLocationsByCriteria($criteria){
        global $pdo;
        try{
                $select = "SELECT * FROM locations WHERE name LIKE '$criteria%'";
		$res = $pdo->query($select);
                return $res->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
}



function getDeviceInstalledLocations($details){
	global $pdo;
	$per_page= $this->app_config['per_page'];
	$p = ($details['p']) ? $details['p'] : 1;
	$offset = ($p - 1) * $per_page;
	$limit = 'LIMIT '.$offset.' , '.$per_page;
	if($details['restore_all']){
		$limit = '';
	}
	try{
		$select = "SELECT DISTINCT(locations.id) AS location_id,device_installations.status AS status, locations.* FROM locations 
		INNER JOIN device_installations ON device_installations.location_id = locations.id  $limit";
		$res = $pdo->query($select);
		return $res->fetchAll(PDO::FETCH_ASSOC);
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
}

function getAllLocationsForExport($details){
        global $pdo;
        $per_page= $this->app_config['per_page'];
        $p = ($details['p']) ? $details['p'] : 1;
        $offset = ($p - 1) * $per_page;
        $limit = 'LIMIT '.$offset.' , '.$per_page;
        if($details['restore_all']){
                $limit = '';
        }
        try{
                $select = "SELECT *, locations.id AS location_id,locations.name AS location_name, 
			revenue_classification.name AS category
			FROM locations 
			LEFT JOIN revenue_classification ON locations.revenue_classification = revenue_classification.id 
			GROUP BY state, location_name, town $limit";
                $res = $pdo->query($select);
                return $res->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
}



function getLocationsFromStateAndCategory($state,$category_id){
	$details['state'] = $state;
	$details['category_id'] = $category_id;
	return $this->getLocationsFromCriteria($details);
	global $pdo;
	try{
		$sel = "SELECT * FROM locations WHERE state = ? AND revenue_classification = ? ORDER BY name ASC";
		$select = $pdo->prepare($sel);
		$select->execute(array($state, $category_id));
		return $select->fetchAll(PDO::FETCH_ASSOC);
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
}


function getRevenueClassIdFromName($name){
	global $pdo;
	$select ="SELECT id FROM revenue_classification WHERE name=?";
	try{
	$select = $pdo->prepare($select);
	$select->execute(array($name));
	return $select->fetchAll(PDO::FETCH_ASSOC);
	}
	catch(PDOException $e){
		$this->seterror($e->getMessage());
		return false;
	}
}

function getLocationsOwnerFromCriteria($details){
	global $pdo;
$r=$this->getLoctionOwnerLocations($_SESSION['user_id']);
	$where = "";
	$select_args = array();
	if($details['state']){
		$where .= " AND state LIKE '%$details[state]%'";
		#array_push($select_args, $details['state']);
	}
	if($details['category_id']==''||$details['state']==''||$details['district']==''){
	$where .= "";
	}
	if(!empty($details['category_id'])){
		$dist_hq_id=$this->getRevenueClassIdFromName('District Headquarter');
		$state_cp_id=$this->getRevenueClassIdFromName('State Capital');
		if($details['category_id']==$dist_hq_id[0]['id']){
			$where .=" AND revenue_classification = ? OR revenue_classification = ?";
			array_push($select_args, $details['category_id']);
			array_push($select_args,$state_cp_id[0]['id'] );
		}else{
			$where .= " AND revenue_classification = ?";
			array_push($select_args, $details['category_id']);
		}
	}
	if(!empty($details['district'])){
		$where .= " AND district = ?";
		array_push($select_args, $details['district']);
	}
	if($details['consumer_type']){
                $where .= " AND connection_type = ?";
                array_push($select_args, $details['consumer_type']);
        }
	if($details['dist_type']){
                $where .= " AND supply_utility = ?";
                array_push($select_args, $details['dist_type']);
        }
	
	try{
		$sel = "SELECT device_installations.status AS status,locations.* FROM locations 
		INNER JOIN device_installations ON device_installations.location_id = locations.id
		WHERE 1  ".$where." AND locations.id IN (".$r['location_ids'].") AND status=1 ORDER BY locations.name ASC";
//echo $sel;
		$select = $pdo->prepare($sel);
		$select->execute($select_args);
		return $select->fetchAll(PDO::FETCH_ASSOC);
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
}
function getLocationsFromCriteria($details){
	global $pdo;
	$where = "";
	$select_args = array();
	//print_r($details);

 $strlencategory=strlen($details['category_id']);
 $strlenstate=strlen($details['state']);
 $strlendistrict=strlen($details['district']);


	if(($strlencategory==0 ) && ($strlenstate==0) && ($strlendistrict==0)){ 
	$where .= "";
	}
	if($strlenstate!='0'){ //echo "hiii";
		$where .= " AND state LIKE '%$details[state]%'";
		#array_push($select_args, $details['state']);
	}
	if($strlencategory!='0'){
		$dist_hq_id=$this->getRevenueClassIdFromName('District Headquarter');
		$state_cp_id=$this->getRevenueClassIdFromName('State Capital');
		if($details['category_id']==$dist_hq_id[0]['id']){
			$where .=" AND revenue_classification = ? OR revenue_classification = ?";
			array_push($select_args, $details['category_id']);
			array_push($select_args,$state_cp_id[0]['id'] );
		}else{
			$where .= " AND revenue_classification = ?";
			array_push($select_args, $details['category_id']);
		}
	}
	if($strlendistrict!=0){
		$where .= " AND district = ?";
		array_push($select_args, $details['district']);
	}
	if($details['consumer_type']){
                $where .= " AND connection_type = ?";
                array_push($select_args, $details['consumer_type']);
        }
	if($details['dist_type']){
                $where .= " AND supply_utility = ?";
                array_push($select_args, $details['dist_type']);
        }
	
	try{
		$sel = "SELECT device_installations.status AS status,locations.* FROM locations 
		INNER JOIN device_installations ON device_installations.location_id = locations.id
		WHERE 1  ".$where." AND locations.name NOT LIKE '%Offline%' AND status=1 
		ORDER BY locations.name ASC";
//echo $sel;
		$select = $pdo->prepare($sel);
		$select->execute($select_args);
		return $select->fetchAll(PDO::FETCH_ASSOC);
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
}


function getAvailableLocationsFromCriteria($details){
        global $pdo;
        $where = "";
        $select_args = array();
        if($details['state']){
                $where .= " AND state = ?";
                array_push($select_args, $details['state']);
        }
        if($details['category_id']){
                $where .= " AND revenue_classification = ?";
                array_push($select_args, $details['category_id']);
        }
        if($details['district']){
                $where .= " AND district = ?";
                array_push($select_args, $details['district']);
        }
        try{
                $sel = "SELECT * FROM locations WHERE 1 = 1 AND id NOT IN(SELECT location_id FROM device_installations)".$where;
                $select = $pdo->prepare($sel);
                $select->execute($select_args);
                return $select->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
}

function getAvailableDistrictsFromCriteria(){

    $select="SELECT DISTINCT(`district`) 
		FROM locations RIGHT JOIN device_installations ON device_installations.location_id = locations.id  
		WHERE device_installations.status=1 
		AND locations.name NOT LIKE '%Offline%'
		ORDER BY district ASC";
	    global $pdo;
	    $stmt = $pdo->query($select);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
       
}
function getLocationDetails($location_id){
	global $pdo;

	try{
		$select = $pdo->prepare("SELECT *, locations.id AS location_id FROM locations WHERE id = ?");
		$select->execute(array($location_id));
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	$result = $select->fetch(PDO::FETCH_ASSOC);
	return $result;
}

function getLocationSummaryReport($details,$location_id){
	global $pdo;
	//$from_date = date('Y-m-d H:i:s', get_strtotime($details['from_date']));
	$from_date = date('Y-m-d 00:00:00', get_strtotime($details['from_date']));
	$to_date = date('Y-m-d 23:59:59', get_strtotime($details['to_date']));
/*
echo "SELECT summary_parameters.param,summary_parameters.desc,SUM(val) AS value,
                                summary_parameters.graph_display_color,
                                (TIMESTAMPDIFF(MINUTE,'.$from_date.','.$to_date.')+1) AS total_minutes
                                FROM location_summary 
                                INNER JOIN summary_parameters ON summary_parameters.id = location_summary.param_id 
                                WHERE location_id = '.$location_id.' and hour_of_day  between '.$from_date.' and '.$to_date.' GROUP BY param ORDER BY priority";
*/
	try{
		$select = $pdo->prepare("SELECT summary_parameters.param,summary_parameters.desc,SUM(val) AS value,
				summary_parameters.graph_display_color,
				(TIMESTAMPDIFF(MINUTE,?,?)+1) AS total_minutes
				FROM location_summary 
				INNER JOIN summary_parameters ON summary_parameters.id = location_summary.param_id 
				WHERE location_id = ? and hour_of_day  between ? and ? GROUP BY param ORDER BY priority");	
		$select->execute(array($from_date, $to_date, $location_id, $from_date, $to_date));
	
		return $select->fetchAll(PDO::FETCH_ASSOC);
	}catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
}


function getRawData($details){
	$from_date = date('Y-m-d', get_strtotime($details['from_date']));
        $to_date = date('Y-m-d', get_strtotime($details['to_date']));
	global $pdo;
        try{
	          /* by amar$select =$pdo->prepare("SELECT *, locations.name AS location_name, voltage_readings.day AS date, 
					hour_of_day AS Hour, voltage_readings.readings AS readings FROM voltage_readings 
					LEFT JOIN locations ON locations.id = voltage_readings.location_id 
					WHERE locations.state = ? AND locations.revenue_classification = ? AND location_id = ?
					AND (date(day) BETWEEN ? and ?) GROUP BY day, hour");*/
	          $select =$pdo->prepare("SELECT *, locations.name AS location_name, voltage_readings.day AS date, 
					hour_of_day AS Hour, voltage_readings.readings AS readings FROM voltage_readings 
					LEFT JOIN locations ON locations.id = voltage_readings.location_id 
					WHERE location_id = ?
					AND (? <= date(day) AND ? >= date(day)) and voltage_readings.published =1 GROUP BY day, hour");
		$select->execute(array( $details['location_id'], $from_date, $to_date));
	return $select->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
		echo $e->getMessage();
//	$this->setError($e->getMessage());
        return false;
	}
}

function getRawDataForMultiLocations($details){
        $from_date = date('Y-m-d', get_strtotime($details['from_date']));
        $to_date = date('Y-m-d', get_strtotime($details['to_date']));
        global $pdo;
        try{
		if(isset($details['locations'])){
                 /* $select =$pdo->prepare("SELECT *, locations.name AS location_name, voltage_readings.day AS date, 
                                        hour_of_day AS Hour, voltage_readings.readings AS readings FROM voltage_readings 
                                        LEFT JOIN locations ON locations.id = voltage_readings.location_id 
                                        WHERE locations.id IN($details[locations]) AND (date(day) BETWEEN ? and ?) ");*/
$select =$pdo->prepare("SELECT *, locations.name AS location_name, voltage_readings.day AS date, 
                                        hour_of_day AS Hour, voltage_readings.readings AS readings FROM voltage_readings 
                                        LEFT JOIN locations ON locations.id = voltage_readings.location_id 
                                        WHERE locations.id IN($details[locations]) AND (? <= date(day) AND ? >= date(day)) ");
			}else{
		 /* $select =$pdo->prepare("SELECT *, locations.name AS location_name, voltage_readings.day AS date, 
                                        hour_of_day AS Hour, voltage_readings.readings AS readings FROM voltage_readings 
                                        LEFT JOIN locations ON locations.id = voltage_readings.location_id 
                                        WHERE (date(day) BETWEEN ? and ?) ");*/
 $select =$pdo->prepare("SELECT *, locations.name AS location_name, voltage_readings.day AS date, 
                                        hour_of_day AS Hour, voltage_readings.readings AS readings FROM voltage_readings 
                                        LEFT JOIN locations ON locations.id = voltage_readings.location_id 
                                        WHERE (? <= date(day) AND ? >= date(day)) ");

		}
		
                $select->execute(array($from_date, $to_date));
        return $select->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
                echo $e->getMessage();
        return false;
        }
}

/*function getRawDataForAllLocations($details){
        $from_date = date('Y-m-d', get_strtotime($details['from_date']));
        $to_date = date('Y-m-d', get_strtotime($details['to_date']));
        global $pdo;
        try{
                  $select =$pdo->prepare("SELECT *, locations.name AS location_name, voltage_readings.day AS date, 
                                        hour_of_day AS Hour, voltage_readings.readings AS readings FROM voltage_readings 
                                        LEFT JOIN locations ON locations.id = voltage_readings.location_id 
                                        WHERE (date(day) BETWEEN ? and ?) ");
                $select->execute(array($from_date, $to_date));
        return $select->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
                echo $e->getMessage();
        return false;
        }
}*/

function getRawInterruptsData($details){
        $from_date = date('Y-m-d', get_strtotime($details['from_date']));
        $to_date = date('Y-m-d', get_strtotime($details['to_date']));
        global $pdo;
        try{
                  $select =$pdo->prepare("SELECT * FROM interrupts 
                                        WHERE location_id = ?
                                        AND (date(down_date) BETWEEN ? and ?)  ");
                $select->execute(array( $details['location_id'], $from_date, $to_date));
        return $select->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
}

function getLocationIdFromDeviceIdString($device_id_string){
                global $pdo;
		try{
                $select = $pdo->prepare("SELECT location_id FROM device_installations 
					INNER JOIN devices ON devices.id = device_installations.device_id WHERE devices.device_id_string=? ");
                $select->execute(array($device_id_string));
		$location_id = $select->fetchColumn();
		return $location_id;

		}catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        	}

        }


function getRawSummaryData($details){
        $from_date = date('Y-m-d', get_strtotime($details['from_date']));
        $to_date = date('Y-m-d', get_strtotime($details['to_date']));
	if($details['location_id']==''){
	$location_id = $this->getLocationIdFromDeviceIdString($details['device_id_string']);
	}else{
	$location_id = $details['location_id'];
	}
        global $pdo;
        try{
                  $select =$pdo->prepare("SELECT *, locations.name AS location_name, location_summary.hour_of_day AS date 
					FROM location_summary 
                                        LEFT JOIN locations ON locations.id = location_summary.location_id 
                                        LEFT JOIN summary_parameters ON summary_parameters.id = location_summary.param_id 
                                        WHERE location_summary.location_id = ?
                                        AND (? <= date(hour_of_day) AND ? >= date(hour_of_day))  ");
                $select->execute(array( $location_id, $from_date, $to_date));
        return $select->fetchAll(PDO::FETCH_ASSOC);
	/*$report_data = array();
	while($row = $select->fetchAll(PDO::FETCH_ASSOC)){
		foreach($row as $r){	
			$report_data[] = $r;
			}
                }
                return $report_data;*/
        }catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
}


function getRawDataByDevice($details){
	$from_date = date('Y-m-d', get_strtotime($details['from_date']));
        $to_date = date('Y-m-d', get_strtotime($details['to_date']));
	global $pdo;
        try{
	        $select =$pdo->prepare("SELECT *, locations.name AS location_name, voltage_readings.day AS date, 
					hour_of_day AS Hour, voltage_readings.readings AS readings FROM voltage_readings 
					LEFT JOIN locations ON locations.id = voltage_readings.location_id
					LEFT JOIN devices ON devices.id = voltage_readings.device_id 
					WHERE devices.device_id_string = ?
					AND (date(day) BETWEEN ? and ?) and voltage_readings.published =1 GROUP BY day, hour");
		$select->execute(array($details['device_id'], $from_date, $to_date));
	return $select->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
	$this->setError($e->getMessage());
        return false;
	}
}

function getRawFileData($details){
	global $pdo;
        try{
		$select="SELECT *, LEFT(filename,7) AS device, error_codes.err_str AS error, 
                                        datafiles.imported AS imported_date FROM datafiles 
                                        LEFT JOIN error_codes ON datafiles.error_code = error_codes.id WHERE 1";
		if($details['device_id']!=''){
			$select .=" AND  LEFT(filename,7) = '$details[device_id]'";
		}
		if($details['from_date'] !='' && $details['to_date']!=''){
		$from_date = date('Y-m-d', get_strtotime($details['from_date']));
	        $to_date = date('Y-m-d', get_strtotime($details['to_date']));
			$select .=" AND (date(event_date) BETWEEN '$from_date' and '$to_date')";
		}
	 /*       $select =$pdo->prepare("SELECT *, LEFT(filename,7) AS device, error_codes.err_str AS error, 
					datafiles.imported AS imported_date FROM datafiles 
					LEFT JOIN error_codes ON datafiles.error_code = error_codes.id
					WHERE (date(event_date) BETWEEN ? and ?) AND LEFT(filename,7) = ?");*/
		$select=$pdo->prepare($select);
		$select->execute();
		//$select->execute(array($from_date, $to_date, $details['device_id']));
	return $select->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
	$this->setError($e->getMessage());
        return false;
	}
}

// status -1 = deployed
public function getDeployedDevices(){
    global $pdo;
    $select = $pdo->prepare("SELECT * FROM devices LEFT JOIN device_installations 
    		ON devices.id=device_installations.device_id WHERE device_installations.status=1");
    	$select->execute();
	$devices=array();
	while($row = $select->fetch(PDO::FETCH_ASSOC)){
		$devices[$row['device_id_string']]=$row;
	}
	return $devices;

}
function getColumnChartDataForEvening($details,$location_id){
	global $pdo;
	$from_date = date('Y-m-d', get_strtotime($details['from_date']));
	$to_date = date('Y-m-d', get_strtotime($details['to_date']));
	$from_time = "17:00:00";
	$to_time = "22:59:00";
/*
echo "SELECT DATE(hour_of_day) AS date, TIME(hour_of_day) AS time, 
                                summary_parameters.param,SUM(val) AS value FROM location_summary 
                                INNER JOIN summary_parameters ON summary_parameters.id = location_summary.param_id 
                                WHERE location_id = '.$location_id.' and (date(hour_of_day)  between '.$from_date.' and '.$to_date.' ) 
                                and (time(hour_of_day) between '.$from_time.' and '.$to_time.' ) 
                                GROUP BY date(hour_of_day),param ORDER BY hour_of_day ";
*/
	try{
		$select = $pdo->prepare("SELECT DATE(hour_of_day) AS date, TIME(hour_of_day) AS time, 
				summary_parameters.param,SUM(val) AS value FROM location_summary 
				INNER JOIN summary_parameters ON summary_parameters.id = location_summary.param_id 
				WHERE location_id = ? and (date(hour_of_day)  between ? and ? ) 
				and (time(hour_of_day) between ? and ? ) 
				GROUP BY date(hour_of_day),param ORDER BY hour_of_day ");	
		$select->execute(array($location_id, $from_date, $to_date, $from_time, $to_time));
		$report_data = array();
		while($row = $select->fetch(PDO::FETCH_ASSOC)){
			$date = date('Ymd',get_strtotime($row['date']));
			$report_data[$date]['date']= $row['date'];
			$report_data[$date][$row['param']] = $row['value']; 
		}
		return $report_data;
	}catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
}

function getLineChartDetails($details,$location_id){
	global $pdo;
	$from_date = date('Y-m-d', get_strtotime($details['from_date']));
	#$to_date = date('Y-m-d', strtotime('+4 day',get_strtotime($details['from_date'])));
	$to_date = date('Y-m-d', get_strtotime($details['to_date']));
/*echo "SELECT * FROM voltage_readings WHERE location_id = $location_id 
                                AND day BETWEEN $from_date AND $to_date AND voltage_readings.published =1 ORDER BY day, hour_of_day ";
*/
	try{
		$select = $pdo->prepare("SELECT * FROM voltage_readings WHERE location_id = ? 
				AND day BETWEEN ? AND ? AND voltage_readings.published =1 ORDER BY day, hour_of_day ");
		$select->execute(array($location_id, $from_date, $to_date));
		$report_data = array();
		while($row = $select->fetch(PDO::FETCH_ASSOC)){
		$minutes_readings = explode(',',$row['readings']);
			for($i=0;$i<60;$i++){
			$key = date('D M d Y H:i:s',get_strtotime($row['day']." ".$row['hour_of_day'].":$i:00"));
			$report_data[$key]['date'] = $key;
			$report_data[$key]['voltage'] = $minutes_readings[$i];
			}
		}
		return array_values($report_data);
	}catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
}
// Changes made by jagdish to get data of third location
function getLineChartDetailsToCompare($details,$first_location,$second_location,$third_location){
	global $pdo;
	$from_date = date('Y-m-d', get_strtotime($details['from_date']));
	#$to_date = date('Y-m-d', strtotime('+0 day',get_strtotime($details['from_date'])));
	$to_date = date('Y-m-d', get_strtotime($details['to_date']));
	try{
		$select = $pdo->prepare("SELECT * FROM voltage_readings WHERE location_id IN (?, ?, ?)
				AND day BETWEEN ? AND ? AND voltage_readings.published =1 ORDER BY day, hour_of_day");
		$select->execute(array($first_location, $second_location,$third_location, $from_date, $to_date));
		$report_data = array();
		while($row = $select->fetch(PDO::FETCH_ASSOC)){
		$minutes_readings = explode(',',$row['readings']);
			for($i=0;$i<60;$i++){
			$key = date('D M d Y H:i:s',get_strtotime($row['day']." ".$row['hour_of_day'].":$i:00"));
			$report_data[$key]['date'] = $key;
			$report_data[$key]['voltage'][$row['location_id']] = $minutes_readings[$i];
			}
		}
		return array_values($report_data);
	}catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
}

/*
* added by amar
*/
function getInterrupts($details){ 
	global $pdo;
	$from_date = date('Y-m-d', get_strtotime($details['from_date']));
	$to_date = date('Y-m-d', get_strtotime($details['to_date']));
/*
echo "SELECT *, HOUR(down_date) as dn_hr, MINUTE(down_date) as dn_min,DATE(down_date) as dn_date
                                FROM interrupts WHERE location_id =  '$details[location_id]' AND (DATE(down_date) BETWEEN '$from_date' AND '$to_date') ORDER BY down_date";
*/
	try{
		$select = $pdo->prepare("SELECT *, HOUR(down_date) as dn_hr, MINUTE(down_date) as dn_min,DATE(down_date) as dn_date
				FROM interrupts WHERE location_id = ? AND (DATE(down_date) BETWEEN ? AND ?) ORDER BY down_date");
		$select->execute(array($details['location_id'], $from_date, $to_date));
		$report_data = array();
		return $select->fetchAll(PDO::FETCH_ASSOC);
	}catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
}
####### SKK

function getInterruptsTogether($details){
        global $pdo;
        $from_date = date('Y-m-d', get_strtotime($details['from_date']));
        $to_date = date('Y-m-d', get_strtotime($details['to_date']));
        try{
                $select = $pdo->prepare("SELECT *, HOUR(down_date) as dn_hr, MINUTE(down_date) as dn_min,DATE(down_date) as dn_date
                                FROM interrupts WHERE (DATE(down_date) BETWEEN ? AND ?) ORDER BY down_date");
                $select->execute(array($from_date, $to_date));
                $report_data = array();
		while($row = $select->fetch(PDO::FETCH_ASSOC)){
                        $report_data[$row['location_id']] = $row;
                }
                return $report_data;

                #return $select->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
}

#####SKK ends
function getInterruptions($details){
	global $pdo;
	$from_date = date('Y-m-d', get_strtotime($details['from_date']));
	$to_date = date('Y-m-d', get_strtotime($details['to_date']));
	try{
		$select = $pdo->prepare("SELECT COUNT(id) AS `cnt`, TIMESTAMPDIFF(SECOND,down_date,up_date) AS time_difference 
				FROM interrupts WHERE location_id = ? AND (DATE(down_date) BETWEEN ? AND ?) 
				AND (DATE(up_date) BETWEEN ? AND ?) GROUP BY time_difference");
		$select->execute(array($details['location_id'], $from_date, $to_date, $from_date, $to_date));
		$report_data = array();
		return $select->fetchAll(PDO::FETCH_ASSOC);
	}catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
}

function getRevenueClassification(){
	$select = "SELECT *, revenue_classification.id AS revenue_class_id , revenue_classification.name AS name FROM revenue_classification ORDER BY name ASC";
	global $pdo;
	$res = $pdo->query($select);
	return $res->fetchAll(PDO::FETCH_ASSOC);
}

function getAllStatesFromLocations(){
	#$select = "SELECT DISTINCT(state) FROM locations ORDER BY state";
	$select = "SELECT DISTINCT(state) FROM locations LEFT JOIN device_installations ON location_id=locations.id WHERE locations.name NOT LIKE '%Offline%' AND status=1 ORDER BY state";
	global $pdo;
	$res = $pdo->query($select);
	return $res->fetchAll(PDO::FETCH_ASSOC);
}

function getAllStatesFromLocationsWeb(){
	$orig_states = $this->getAllStatesFromLocations();
	$states = array();
	foreach($orig_states as $state_val){
		if(preg_match('#\/#', $state_val['state'])){
		$two_states = explode('/', $state_val['state']);
		$states[] = ltrim(rtrim($two_states[0]));	
		$states[] = ltrim(rtrim($two_states[1]));	
		}
		else{
			$states[] = ltrim(rtrim($state_val['state']));
		}
	}
	asort($states);
	$states = array_unique($states);
	return $states;
}

/*added by Rupali*/
function submit_request($details){
    global $pdo;
    $param=$details['name'].",".$details['email'].",".$details['organization'].",".$details['contact_number'].",".$details['location'].",".$details['report_type'].",".$details['from_date'].",".$details['to_date'];
    try{
    $update = $pdo->prepare("INSERT INTO `report_requests` 
        (`user_id`,`requested`,`report_params`)
        VALUE(?, NOW(),?)");
    $update->execute(array($details['user_id'],$param));
    }
    catch(PDOException $e){
        $this->setError($e->getMessage());
        return false;
    }
    return true;
}
/*added by Rupali*/
function createUser($data){
		//enforce strong password
        
		global $pdo;
        try{
	$stmt = "INSERT INTO users
            (`name`,`email`, `password`, `username`, `status`, `type`, `user_type`)
            VALUES(?,?,?,?,1,2,2)";
	    $stmt = $pdo->prepare($stmt);
            $stmt->execute(array($data['name'],$data['email'], md5($data['password']), $data['userName']));
        }
        catch(PDOException $e){
            // check if username already exists in the system
            //$this->setError($e->getCode());
            if($e->getCode() == 23000){
                $this->setStdError('user_exists');
            }
            else{
                //$this->setError($e->getMessage());
                $this->setStdError('user_not_created');
            }
            return false;
        }
}

function getStatesList($file){
$data=file_get_contents($file, true);
$states= array();
$states=explode(',',$data);
return $states;

}

function getEveningAverageAvailibility($data){ 
$availability = 0;
$availability_array = array('high','normal','low','very_low');
foreach($data as $key => $value){
        foreach($value as $k => $v){
        if(in_array($k,$availability_array)){
                $availability += $v;
        }
        }
}
        return $availability;
}

function sendResetPasswordNotification($user_id){

	$user_details = $this->getUserDetails($user_id);
	$this->resetPassword($user_details['username'],$user_details['email']);
         $template_vars = $user_details;
	$template_vars['server'] = $_SERVER['HTTP_HOST'];
	sendTemplateEmail($user_details['email'],$this->app_config['password_reset_notification_subject'],$this->app_config['password_reset_notification_body'],$template_vars);

}
public function isEmailRegistered($email){
    global $pdo;
   //echo "SELECT id AS user_id from users WHERE email=$email";exit;
try{ $select = $pdo->prepare("SELECT id AS user_id from users WHERE email=?");
    $select->execute($email);
    $row = $select->fetch(PDO::FETCH_ASSOC);
    return $row['user_id'];
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
}

/*function getCategoryStateDistrictFromLocation($location){
	global $pdo;
	try{
                $select = $pdo->prepare("SELECT category,state,district FROM locations WHERE name = ?");
                $select->execute(array($location));
                $report_data = array();
                return $select->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }

}*/

function getDateRangeForAvailableData($location_id){
        global $pdo;
	try{
	$select = $pdo->prepare("SELECT MIN(hour_of_day) as min_date , MAX(hour_of_day) as max_date FROM location_summary WHERE location_id=?");
	$select->execute(array($location_id)); 
        return $select->fetch();
	}catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }

}
	public function getVoltageParamValues($voltage_range_id){
	    $select="SELECT * FROM voltage_parameters_values 
			LEFT JOIN voltage_ranges 
	                ON voltage_ranges.id=voltage_parameters_values.voltage_range_id
			LEFT JOIN voltage_parameters 
			ON voltage_parameters.id = voltage_parameters_values.parameter_id
			WHERE voltage_parameters_values.voltage_range_id=$voltage_range_id";
	    global $pdo;
	    $stmt = $pdo->query($select);
		$params = array();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$params[$row['param']] = $row;
		}
		return $params;
	}

/*Added By Rupali*/
function getLocationCount(){
		$select = "SELECT count(*) AS cnt FROM locations";
		global $pdo;
		$res = $pdo->query($select);
		$row = $res->fetch(PDO::FETCH_ASSOC);
		return $row['cnt'];
	}

	
	

/*Added By Rupali*/
function getStatesCount(){
	#$select = "SELECT count(DISTINCT(state)) AS cnt FROM locations";
	$select = "SELECT count(distinct(state)) as cnt  FROM device_installations left join locations on location_id=locations.id WHERE locations.name not like '%Offline%' and status=1";
	global $pdo;
		$res = $pdo->query($select);
		$row = $res->fetch(PDO::FETCH_ASSOC);
		return $row['cnt'];
}
/*Added By Rupali*/
function getDistricsCount(){
	#$select="SELECT count(DISTINCT(district)) AS cnt FROM locations";
	$select = "SELECT count(distinct(district)) as cnt  FROM device_installations left join locations on location_id=locations.id WHERE locations.name not like '%Offline%' and status=1";
	    global $pdo;
		$res = $pdo->query($select);
		$row = $res->fetch(PDO::FETCH_ASSOC);
		return $row['cnt'];

	}
/*Added By Rupali*/
function getCitiesCount(){
	    $select="SELECT count(DISTINCT(town)) AS cnt FROM locations";
	    global $pdo;
		$res = $pdo->query($select);
		$row = $res->fetch(PDO::FETCH_ASSOC);
		return $row['cnt'];

	}
/*Added By Rupali*/
function getLocationHours(){
	    $select="SELECT count(*) AS cnt from voltage_readings; ";
	    global $pdo;
		$res = $pdo->query($select);
		$row = $res->fetch(PDO::FETCH_ASSOC);

		return abs($row['cnt']/1000);

	}
/*Added By Rupali*/
function getLocations(){
                global $pdo;

                $select = $pdo->prepare("SELECT * FROM locations ORDER BY name ASC ");
                $select->execute();
                return $select->fetchAll(PDO::FETCH_ASSOC);
        }
public function getInterruptsTable($interrupts){
	$down_sec=0;
	$up_sec=0;
	$final_interrups=array();

	$i=0;
	$j=0;
	$pre=0;
	$dd='';
	$flag=0;
	for($i=0;$i<count($interrupts);$i++){
	if(($pre == '0' && $flag=='0') ){
		$down_sec=strtotime($interrupts[$i]['down_date']);
		$dd=$interrupts[$i]['down_date'];
		$d_date=$interrupts[$i]['dn_date'];
		$dn_hr=$interrupts[$i]['dn_hr'];
		$pre=$i;
		$flag=1;
	}
	if($interrupts[$i]['up_date']=='0000-00-00 00:00:00'){
		if(($i+1) == count($interrupts)){ // IF last record 
			if($pre < $i && $i >0){	
			       
				//if(($interrupts[$i]['dn_date'] - $interrupts[$i-1]['dn_date']) > 1){ // commented by amar on 22 March
				if( abs(strtotime($interrupts[$i]['dn_date']) - strtotime($interrupts[$i-1]['dn_date'])) > 0){
					$up=$d_date." ".$interrupts[$i-1]['dn_hr'].":59:00"; 
					//$up=$interrupts[$i-1]['dn_date']." ".$interrupts[$i-1]['dn_hr'].":59:00"; 
					$up_sec=strtotime($up)+60;	
			
				}
				else if($interrupts[$i]['dn_min'] >0 ){
					if(($interrupts[$i]['dn_hr'] - $dn_hr) > 1){
						$up=$d_date." ".$interrupts[$i-1]['dn_hr'].":59:00"; 
						$up_sec=strtotime($up)+60;	
					}else{
						$up=$d_date." ".$interrupts[$i-1]['dn_hr'].":59:00"; 
						$up_sec=strtotime($up);	
					}
				$duration=($up_sec-$down_sec);
				$final_interrups[$j]=$duration;
				$j++;
				$down_sec=strtotime($interrupts[$i]['down_date']);
				$dd=$interrupts[$i]['down_date'];
				$d_date=$interrupts[$i]['dn_date'];
				$dn_hr=$interrupts[$i]['dn_hr'];
				}
			}
			$up=$interrupts[$i]['dn_date']." ".$interrupts[$i]['dn_hr'].":59:00";
			$up_sec=strtotime($up)+60;	
			$duration=$up_sec-$down_sec;
			$final_interrups[$j]=$duration;
			$j++;
			$duration=0;
			$pre=0;	
			$flag=0;
		}
	else if(($i>0) && $pre < $i &&  abs(strtotime($interrupts[$i]['dn_date']) - strtotime($interrupts[$i-1]['dn_date'])) > 0){
                //$up=$d_date." ".$dn_hr.":59:00";
		$up=$d_date." ".$interrupts[$i-1]['dn_hr'].":59:00"; 
                $up_sec=strtotime($up)+60;      
                $duration=$up_sec-$down_sec;
                $final_interrups[$j]=$duration;
                $j++;
                /*$duration=0;
                $pre=0; 
                $flag=0;*/
//added new
		$down_sec=strtotime($interrupts[$i]['down_date']);
		$dd=$interrupts[$i]['down_date'];
		$dn_hr=$interrupts[$i]['dn_hr'];
		$d_date=$interrupts[$i]['dn_date'];
		$duration=0;
		$pre=$i;
		$flag=1;

//added new end
        }
	  else if($interrupts[$i]['dn_min'] >0  && $pre < $i && $i >0){
		if(($interrupts[$i]['dn_hr'] - $dn_hr) > 1){
		$up=$d_date." ".$interrupts[$i-1]['dn_hr'].":59:00"; 
		$up_sec=strtotime($up)+60;	
		}else{
			//$up=$interrupts[$i]['dn_date']." ".$interrupts[$i]['dn_hr'].":00:00"; // commented by amar on 22 March
			$up=$d_date." ".$interrupts[$i-1]['dn_hr'].":59:00"; 
			$up_sec=strtotime($up);	
		}
		$duration=($up_sec-$down_sec);
		$final_interrups[$j]=$duration;
		$j++;
		$down_sec=strtotime($interrupts[$i]['down_date']);
		$dd=$interrupts[$i]['down_date'];
		$dn_hr=$interrupts[$i]['dn_hr'];
		$d_date=$interrupts[$i]['dn_date'];
		$duration=0;
		$pre=$i;
		$flag=1;
	}
	else if($pre < $i && $i >0 && (($interrupts[$i]['dn_hr'] - $interrupts[$i-1]['dn_hr']) > 1)){
		$up=$interrupts[$i-1]['dn_date']." ".$interrupts[$i-1]['dn_hr'].":59:00"; 
                $up_sec=strtotime($up)+60;
		$duration=($up_sec-$down_sec);
		$final_interrups[$j]=$duration;
		$j++;
		$down_sec=strtotime($interrupts[$i]['down_date']);
		$dd=$interrupts[$i]['down_date'];
		$dn_hr=$interrupts[$i]['dn_hr'];
		$d_date=$interrupts[$i]['dn_date'];
		$duration=0;
		$pre=$i;
		$flag=1;
	}
	  else if($interrupts[$i]['dn_min'] > 0 && (abs(strtotime($interrupts[$i]['dn_date']) - strtotime($interrupts[$i-1]['dn_date'])) > 0) && (($interrupts[$i]['dn_hr'] - $interrupts[$i-1]['dn_hr']) > 1)){

//echo "<br />SKK ".$interrupts[$i]['dn_date']." pre: ".$pre." i: ".$i." dn_min: ".$interrupts[$i]['dn_min']." dn_hr: ".$interrupts[$i]['dn_hr']."<br />";


		if(($interrupts[$i]['dn_hr'] - $dn_hr) > 1){
			$up=$d_date." ".$interrupts[$i-1]['dn_hr'].":59:00"; 
			 $up_sec=strtotime($up)+60;	
		}else{  //echo "else"."  <br>";
				if(empty($interrupts[$i-1]['dn_hr'])){
				$interrupts[$i-1]['dn_hr']='00';
				}
			 $up=$d_date." ".$interrupts[$i-1]['dn_hr'].":59:00"; 
			 $up_sec=strtotime($up);	
		}		
			//echo $duration=$down_sec-$up_sec;
		$duration=($up_sec-$down_sec);
		 $final_interrups[$j]=$duration;
		$j++;
		$down_sec=strtotime($interrupts[$i]['down_date']);
		$dd=$interrupts[$i]['down_date'];
		$dn_hr=$interrupts[$i]['dn_hr'];
		$d_date=$interrupts[$i]['dn_date'];
		$duration=0;
		$pre=1;
		$flag=1;		


			
				


        }
}else{
	  if($interrupts[$i]['dn_min'] >0 &&  $pre < $i && $i >0){
		  //if($interrupts[$i]['dn_min'] >0 && $pre !=0 && $pre < $i && $i >0){ // commented by amar on 13 March
		
		if(abs(strtotime($interrupts[$i]['dn_date']) - strtotime($interrupts[$i-1]['dn_date'])) > 0){
			//$up=$d_date." ".$interrupts[$i-1]['dn_hr'].":59:00";
			$up=$d_date." ".$interrupts[$i-1]['dn_hr'].":59:00"; 
                        $up_sec=strtotime($up)+60;

		}
		else if(($interrupts[$i]['dn_hr'] - $dn_hr) > 1){
			//$up=$d_date." ".$dn_hr.":59:00"; commented by amar on 14 March
			$up=$d_date." ".$interrupts[$i-1]['dn_hr'].":59:00"; 
			$up_sec=strtotime($up)+60;	
		}else{
			$up=$interrupts[$i]['dn_date']." ".$interrupts[$i]['dn_hr'].":00:00";
			$up_sec=strtotime($up);	
		}
		$duration=($up_sec-$down_sec);
		 $final_interrups[$j]=$duration;
		$j++;
		$duration=0;
		$down_sec=strtotime($interrupts[$i]['down_date']);
		$dd=$interrupts[$i]['down_date'];
		$dn_hr=$interrupts[$i]['dn_hr'];
		$d_date=$interrupts[$i]['dn_date'];
		$up_sec=strtotime($interrupts[$i]['up_date']);
		$duration=($up_sec-$down_sec);
		 $final_interrups[$j]=$duration;
		$j++;
		$duration=0;
		$pre=0;
		$flag=0;
		
	}else{
		 if($pre < $i && $i>0 && ((($interrupts[$i]['dn_hr'] - $interrupts[$i-1]['dn_hr']) > 1) || abs(strtotime($interrupts[$i]['dn_date']) - strtotime($interrupts[$i-1]['dn_date'])) > 0)){
			$up=$d_date." ".$interrupts[$i-1]['dn_hr'].":59:00"; 
			$up_sec=strtotime($up)+60;	
			$duration=($up_sec-$down_sec);
                       	$final_interrups[$j]=$duration;
                        $j++;
                        $down_sec=strtotime($interrupts[$i]['down_date']);
                        $dd=$interrupts[$i]['down_date'];
                        $dn_hr=$interrupts[$i]['dn_hr'];
			$d_date=$interrupts[$i]['dn_date'];
		}
		$up_sec=strtotime($interrupts[$i]['up_date']);
		$duration=($up_sec-$down_sec);
		 $final_interrups[$j]=$duration;
		$j++;
		$duration=0;
		$pre=0;
		$flag=0;
	  }	
	}	
    }
#print_r($final_interrups);
	return $final_interrups;
 }


function getRevenueClassName($id){
	global $pdo;
	$select ="SELECT name FROM revenue_classification WHERE id=?";
	try{
	$select = $pdo->prepare($select);
	$select->execute(array($id));
		$row = $select->fetch(PDO::FETCH_ASSOC);
	return $row['name'];
	}
	catch(PDOException $e){
		$this->seterror($e->getMessage());
		return false;
	}
}

function getDailySummary($details){
	$date = date('Y-m-d', get_strtotime($details['to_date']));
	$from_date = date('Y-m-d 00:00:00', get_strtotime($details['to_date']));
        $to_date = date('Y-m-d 23:59:59', get_strtotime($details['to_date']));
	global $pdo;
	try{
                  $select =$pdo->prepare("SELECT sum(val) AS total, locations.name AS location_name, hour_of_day AS date,param_id,
					locations.id AS location_id, summary_parameters.param AS param, device_installations.status,
					(TIMESTAMPDIFF(MINUTE,?,?)+1) AS total_minutes 
					FROM location_summary 
					INNER JOIN locations ON locations.id = location_summary.location_id
					INNER JOIN summary_parameters ON summary_parameters.id = location_summary.param_id
					INNER JOIN device_installations ON device_installations.location_id = location_summary.location_id
					WHERE date(hour_of_day) >=? and date(hour_of_day)<=? GROUP BY location_id,param_id");
                $select->execute(array($from_date,$to_date,$date,$date));
        return $select->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
                echo $e->getMessage();
        return false;
        }
}

function getDailySummaryForEvening($details, $location_id){
	global $pdo;
        $from_date = date('Y-m-d', get_strtotime($details['from_date']));
        $to_date = date('Y-m-d', get_strtotime($details['to_date']));
        $from_time = "17:00:00";
        $to_time = "22:59:00";
        try{
                $select = $pdo->prepare("SELECT DATE(hour_of_day) AS date, TIME(hour_of_day) AS time, 
                                summary_parameters.param,SUM(val) AS value FROM location_summary 
                                INNER JOIN summary_parameters ON summary_parameters.id = location_summary.param_id 
                                WHERE location_id = ? and date(hour_of_day) >=? and date(hour_of_day)<=? 
                                and (time(hour_of_day) between ? and ? ) 
                                GROUP BY date(hour_of_day),param ORDER BY hour_of_day ");
                $select->execute(array($location_id, $from_date, $to_date, $from_time, $to_time));
                $report_data = array();
                while($row = $select->fetch(PDO::FETCH_ASSOC)){
                        $date = date('Ymd',get_strtotime($row['date']));
                        $report_data[$date]['date']= $row['date'];
                        $report_data[$date][$row['param']] = $row['value'];
                }
                return $report_data;
        }catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }

}

##SKK
function getDailySummaryForEveningTogether($details){
        global $pdo;
        $from_date = date('Y-m-d', get_strtotime($details['from_date']));
        $to_date = date('Y-m-d', get_strtotime($details['to_date']));
        $from_time = "17:00:00";
        $to_time = "22:59:00";
        try{
                $select = $pdo->prepare("SELECT location_id, DATE(hour_of_day) AS date, TIME(hour_of_day) AS time,
                                summary_parameters.param,SUM(val) AS value FROM location_summary
                                INNER JOIN summary_parameters ON summary_parameters.id = location_summary.param_id
                                WHERE val != 0 and date(hour_of_day) >=? and date(hour_of_day)<=?
                                and (time(hour_of_day) between ? and ? )
                                GROUP BY location_id,param ");
                $select->execute(array($from_date, $to_date, $from_time, $to_time));
                $report_data = array();
                while($row = $select->fetch(PDO::FETCH_ASSOC)){
                        $date = date('Ymd',get_strtotime($row['date']));
                        $report_data[$row['location_id']][$date]['date']= $row['date'];
                        $report_data[$row['location_id']][$date][$row['param']] = $row['value'];
                }
                return $report_data;
        }catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
}
##SKKends

function getDailySummaryForEveningMultiLocation($details){
        global $pdo;
        $from_date = date('Y-m-d', get_strtotime($details['from_date']));
        $to_date = date('Y-m-d', get_strtotime($details['to_date']));
        $from_time = "17:00:00";
        $to_time = "22:59:00";
        try{
                $select = $pdo->prepare("SELECT DATE(hour_of_day) AS date, TIME(hour_of_day) AS time, 
                                summary_parameters.param,SUM(val) AS value FROM location_summary 
                                INNER JOIN summary_parameters ON summary_parameters.id = location_summary.param_id 
                                WHERE location_id = ? and date(hour_of_day) >=? and date(hour_of_day)<=? 
                                and (time(hour_of_day) between ? and ? ) 
                                GROUP BY date(hour_of_day),param ORDER BY hour_of_day ");
                $select->execute(array( $details['location_id'], $from_date, $to_date, $from_time, $to_time));
                $report_data = array();
                while($row = $select->fetch(PDO::FETCH_ASSOC)){
                        $date = date('Ymd',get_strtotime($row['date']));
                        $report_data[$date]['date']= $row['date'];
                       $report_data[$date][$row['param']] = $row['value'];
                }
                return $report_data;
        }catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }

}
function getDailySummaryForMultiLocation($details){
        global $pdo;
        $from_date = date('Y-m-d', get_strtotime($details['from_date']));
        $to_date = date('Y-m-d', get_strtotime($details['to_date']));
        //$from_time = "17:00:00";
       // $to_time = "22:59:00";
        try{
                $select = $pdo->prepare("SELECT DATE(hour_of_day) AS date, TIME(hour_of_day) AS time, 
                                summary_parameters.param,SUM(val) AS value FROM location_summary 
                                INNER JOIN summary_parameters ON summary_parameters.id = location_summary.param_id 
                                WHERE location_id = ? and date(hour_of_day) >=? and date(hour_of_day)<=? 
                                GROUP BY date(hour_of_day),param ORDER BY hour_of_day ");
                $select->execute(array( $details['location_id'], $from_date, $to_date));
                $report_data = array();
                while($row = $select->fetch(PDO::FETCH_ASSOC)){
                        $date = date('Ymd',get_strtotime($row['date']));
                        $report_data[$date]['date']= $row['date'];
                       $report_data[$date][$row['param']] = $row['value'];
                }
                return $report_data;
        }catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }

}

function getNoSummaryLocations($details){
        global $pdo;
	$date = date('Y-m-d', get_strtotime($details['to_date']));
        try{
                $sel = "SELECT * FROM locations WHERE id NOT IN(SELECT location_id FROM location_summary)";
			//AND WHERE date(hour_of_day) >=? and date(hour_of_day)<=? ";
                $select = $pdo->prepare($sel);
                $select->execute(array($date,$date));
                return $select->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
}


function getSummaryInterrupts($details){
        global $pdo;
        $to_date = date('Y-m-d', get_strtotime($details['to_date']));
        try{
                $select = $pdo->prepare("SELECT *, HOUR(down_date) as dn_hr, MINUTE(down_date) as dn_min,DATE(down_date) as dn_date
                                FROM interrupts WHERE DATE(down_date)<=  ? AND DATE(down_date)>=? ORDER BY location_id,down_date");
                $select->execute(array($to_date, $to_date));
                $report_data = array();
                return $select->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
}

function getSpecialRawSummaryData($details){
        $to_date = date('Y-m-d', get_strtotime($details['to_date']));
        global $pdo;
        try{
                  $select =$pdo->prepare("SELECT *, locations.name AS location_name, location_summary.hour_of_day AS date 
                                        FROM location_summary 
                                        LEFT JOIN locations ON locations.id = location_summary.location_id 
                                        LEFT JOIN summary_parameters ON summary_parameters.id = location_summary.param_id 
                                        WHERE date(hour_of_day) >= ? and date(hour_of_day)<= ?  ");
                $select->execute(array($to_date, $to_date));
        return $select->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
}
function getDeployedLocationCount(){
		#$select = "SELECT count(distinct(location_id)) AS cnt FROM device_installations WHERE status=1 ";
		$select = "SELECT count(distinct(location_id)) as cnt FROM device_installations left join locations on location_id=locations.id WHERE locations.name not like '%Offline%' and status=1";
		global $pdo;
		$res = $pdo->query($select);
		$row = $res->fetch(PDO::FETCH_ASSOC);
		return $row['cnt'];
	}
function getDeviceCodesByVendor($vendor){
        global $pdo;
        try{
                  $select =$pdo->prepare("SELECT devices.id, device_id_string FROM devices LEFT JOIN vendors ON 
                devices.vendor_id=vendors.id WHERE vendors.name like '%$vendor%'");
                $select->execute();
        return $select->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
}
function getDataFilesOfVendor($device_codes){
		global $pdo;
		//AND date(subdate(NOW(),1)) = date(event_date)
        try{
	$select=$pdo->prepare(" SELECT DATE(SUBDATE(NOW(),1)),HOUR(event_date) as hr ,filename,event_date,
		left(filename,7) as dev FROM datafiles ORDER BY dev DESC ,event_date desc WHERE dev in (?) ");
                $select->execute(array($device_codes));
        return $select->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
}

function getVoltagesByVendor($vendor, $x_days_ago=1){
		global $pdo;
		//AND date(subdate(NOW(),1)) = date(event_date)
        try{
	$select=$pdo->prepare(" SELECT voltage_readings.sim_card_id as sim_card_id,device_id_string, 
		voltage_readings.device_id as device_id ,location_id,day, hour_of_day FROM voltage_readings 
		LEFT JOIN devices on voltage_readings.device_id=devices.id 
		LEFT JOIN vendors on devices.vendor_id = vendors.id 
		WHERE vendors.name LIKE '%$vendor%' AND voltage_readings.day =subdate(current_date, $x_days_ago); 
		ORDER BY location_id asc, day asc, hour_of_day asc ");
                $select->execute();
        return $select->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
}

function getAltizonMissingHr(){
	global $pdo;
	$hr = date('H');
	try{
		$select=$pdo->prepare("SELECT *,DATE_FORMAT(date,'%Y/%m/%d') AS dt
			 FROM altizon_downloads WHERE hr=$hr AND date > date((DATE_SUB(NOW(), INTERVAL 7 DAY)))
			 ORDER BY date, hr");
		/*
		$select=$pdo->prepare("SELECT *,DATE_FORMAT(date,'%Y/%m/%d') AS dt
			 FROM altizon_downloads WHERE date > date((DATE_SUB(NOW(), INTERVAL 7 DAY)))
			 ORDER BY date, hr");
		 */
		$select->execute();
		return $select->fetchAll(PDO::FETCH_ASSOC);
	}catch(PDOException $d){
		$this->setError($d->getMesage());
		return false;
	}	
}

function getAltizonMissingDays(){
	global $pdo;
	try{
		$select=$pdo->prepare("select *,DATE_FORMAT(date,'%Y/%m/%d') as dt
			 from altizon_downloads where hr IS NULL AND date > date((DATE_SUB(NOW(), INTERVAL 7 DAY)))");
		$select->execute();
		return $select->fetchAll(PDO::FETCH_ASSOC);
	}catch(PDOException $d){
		$this->setError($d->getMesage());
		return false;
	}	
}

public function isdatafilePresent($file_name){
        global $pdo;
        $select=$pdo->prepare("SELECT 1 FROM datafiles WHERE left(filename,15) =? ");
        $select->execute(array($file_name));
        if($select->fetchColumn() > 1){
                return true;
        }else{
                return false;
        }
}

public function getLatestVoltageReadingsOfDevice($device_id, $interval_days){
	global $pdo;
	try{
		$select = $pdo->prepare("
		SELECT * FROM voltage_readings WHERE device_id=? and day > date((DATE_SUB(NOW(), INTERVAL $interval_days DAY))) 
		ORDER BY day DESC, hour_of_day DESC
		");
		$select->execute(array($device_id));
		return $select->fetchAll(PDO::FETCH_ASSOC);
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
}

/* Added By Rupali */
function uploadedreportlist(){
   $select = "SELECT * FROM reports ORDER BY date_published DESC";
                global $pdo;
                $res = $pdo->query($select);
                return $res->fetchAll(PDO::FETCH_ASSOC);
 }
}//User class ends here
?>
