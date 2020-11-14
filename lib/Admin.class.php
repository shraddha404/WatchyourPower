<?php
chdir(dirname(__FILE__));
include_once('User.class.php');
class Admin extends User{

    public function __construct($user_id){
        parent::__construct($user_id);
	
        if(!$this->isAdmin()){ 
            $this->_loginRedirect();
	//log failure
            $log = debug_backtrace();
            $this->createActionLog($log,0);
	    throw new Exception('No privileges');
        }
		
    }
	
		
// Added functions by vinayak

### USER FUNCTIONS
	function addUser($data){
		$valid_till = date('Y-m-d', get_strtotime($data['valid_till']));
		global $pdo;
		$password = md5($data['password']);
	try{
                $insert = $pdo->prepare("INSERT INTO users (`name`,`email`,`username`, 
                        `password`, `type`, `status`,`created`,`valid_till`) VALUES (?, ?, ?, ?, ?, ?,NOW(),?)");
                $insert_args = array($data['name'], $data['email'], $data['username'],
                                $password, $data['type'], $data['status'], $valid_till);
                $insert->execute($insert_args);
		$user_id = $pdo->lastInsertId('id');
		$action_details=array();
		$action_details['table_name']='users';
		$action_details['row_id']=$user_id;
		$action_details['operation']='New user added';
		$this->createActionLog($action_details);
        }
        catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
        return true;
	}

	function updateUser($data){ 
		$valid_till = date('Y-m-d', get_strtotime($data['valid_till']));
				if(!empty($data['location'])) {
				   $locations = implode(",",$data['location']);
				}
		global $pdo;
	try{
		if($data['password']!=''){
		$password = md5($data['password']);
		/*$update = $pdo->prepare("UPDATE users SET `name` = ?, `email` = ?, `username` = ?, `password` = ?,`type` = ?, `status` =?, `valid_till`=? ,`locations`=? WHERE `id` =? ");
		$update->execute(array($data['name'], $data['email'], $data['username'], $password, $data['type'], $data['status'], $valid_till,$locations ,$data['user_id']));*/
$update = $pdo->prepare("UPDATE users SET `name` = ?, `email` = ?, `username` = ?, `password` = ?,`type` = ?, `status` =?, `valid_till`=?  WHERE `id` =? ");
		$update->execute(array($data['name'], $data['email'], $data['username'], $password, $data['type'], $data['status'], $valid_till,$data['user_id']));
		} else{
		/*$update = $pdo->prepare("UPDATE users SET `name` = ?, `email` = ?, `username` = ?, `type` = ?, `status` =?, `valid_till`=? ,`locations`=? WHERE `id` =? ");
		$update->execute(array($data['name'], $data['email'], $data['username'], $data['type'], $data['status'], $valid_till, $locations ,$data['user_id']));*/
$update = $pdo->prepare("UPDATE users SET `name` = ?, `email` = ?, `username` = ?, `type` = ?, `status` =?, `valid_till`=? WHERE `id` =? ");
		$update->execute(array($data['name'], $data['email'], $data['username'], $data['type'], $data['status'], $valid_till,$data['user_id']));
		$action_details=array();
		$action_details['table_name']='users';
		$action_details['row_id']=$data['user_id'];
		$action_details['operation']='User updated';
		$this->createActionLog($action_details);

		}
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
	}
function addOwnerUserLocations($data){ 
		$valid_till = date('Y-m-d', get_strtotime($data['valid_till']));
				if(!empty($data['location'])) {
				   $locations = implode(",",$data['location']);
				}
		global $pdo;
	try{
                $insert = $pdo->prepare("INSERT INTO location_owner_locations (`user_id`,`location_ids`) VALUES (?, ?)");
                $insert_args = array($data['user_id'], $locations);
                $insert->execute($insert_args);
		$id = $pdo->lastInsertId('id');
		$action_details=array();
		$action_details['table_name']='users';
		$action_details['row_id']=$id;
		$action_details['operation']='Owner User Locations added';
		$this->createActionLog($action_details);
        }
        catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
        return true;
	}
function updateOwnerUserLocations($data){ 
		$valid_till = date('Y-m-d', get_strtotime($data['valid_till']));
				if(!empty($data['location'])) {
				   $locations = implode(",",$data['location']);
				}
		global $pdo;
	try{
              $update = $pdo->prepare("UPDATE location_owner_locations SET `user_id` = ?, `location_ids` = ? WHERE `id` =? ");
		$update->execute(array($data['user_id'],$locations,$data['id']));
          $action_details=array();
		$action_details['table_name']='users';
		$action_details['row_id']=$data['user_id'];
		$action_details['operation']='User updated';
		$this->createActionLog($action_details);
}
        catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
        return true;
	}


	function removeUser($user_id){
		global $pdo;
	try{
		$update = $pdo->prepare("DELETE FROM users WHERE `id` = ?");
		$update->execute(array($user_id));
		$action_details=array();
		$action_details['table_name']='users';
		$action_details['row_id']=$user_id;
		$action_details['operation']='User deleted';
		$this->createActionLog($action_details);
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
	}

### Installer personal functions
	function addInstallerPersonal($data){
                global $pdo;
        try{
                $insert = $pdo->prepare("INSERT INTO installer_directory (`name`,`email`,`mobile`) VALUES (?, ?, ?)");
                $insert_args = array($data['name'], $data['email'], $data['mobile']);
                $insert->execute($insert_args);
        }
        catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
        return true;
        }

	function updateInstallerPersonal($data){
                global $pdo;
        try{
                $update = $pdo->prepare("UPDATE installer_directory SET `name` = ?, `email` = ?, `mobile` = ? WHERE `id` =? ");
                $update->execute(array($data['name'], $data['email'], $data['mobile'], $data['installer_id']));
        }
        catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
        return true;
        }

	function removeInstallerPersonal($installer_id){
                global $pdo;
        try{
                $update = $pdo->prepare("DELETE FROM installer_directory WHERE `id` = ?");
                $update->execute(array($installer_id));
        }
        catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
        return true;
        }

	function getInstallerDetails($installer_id){
        	$select = "SELECT installer_directory.*, id AS installer_id from installer_directory WHERE id = ?";
        	global $pdo;
                $stmt = $pdo->prepare($select);
                $stmt->execute(array($installer_id));
                $result_array = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result_array;
        }

	function getAllInstallerPersonals(){
        $select = "SELECT * ,installer_directory.id as installer_id FROM installer_directory";
                global $pdo;
                $res = $pdo->query($select);
                return $res->fetchAll(PDO::FETCH_ASSOC);
	}
	

### LOCATION FUNCTIONS  
	function addLocations($data){
#print_r($data);
		global $pdo;
	try{
		$insert = $pdo->prepare("INSERT INTO locations (`name`,`alias`,`latitude`,`longitude`,`town`,`district`,`state`, 
				`revenue_classification`, `address`, `country`, `pincode`, `connection_type`, 
				`type_of_supplier`, `is_RGGV`, `RGGV_year`, `tower_id`,`supply_utility`,`feeder`, `category`) 
				VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$insert_args = array($data['name'],$data['alias'], $data['latitude'], $data['longitude'], 
			$data['town'], $data['district'], $data['state'], $data['revenue_classification'], $data['address'], 
			$data['country'], $data['pincode'], $data['connection_type'], $data['type_of_supplier'], $data['is_RGGV'],
			$data['RGGV_year'], $data['tower_id'], $data['supply_utility'],	$data['feeder'], $data['category']);
		$insert->execute($insert_args);
		$location_id = $pdo->lastInsertId('id'); 
               	$data['remark']=$data['other_info'];
		$this->addRemarks($location_id,$data,'locations');
		$prepared_params = prepareParams($data);
        	//add location parameters
        	$this->addLocationParameters($location_id, $prepared_params);
		$action_details=array();
		$action_details['table_name']='locations';
		$action_details['row_id']=$location_id;
		$action_details['operation']='New Location added';
		$this->createActionLog($action_details);

	}
	catch(PDOException $e){
		if ($e->errorInfo[1] == 1062) {
		$this->setError("Location name already exists.");
   		} else {
		$this->setError($e->getMessage());
   		}
		return false;
	}
	return $location_id;
	
	}

	function set_location_default_voltage_param($location_id){
		$location_id;
		$app_config = $this->app_config;
		global $pdo;
	try{
               	$insert = $pdo->prepare("INSERT INTO location_voltage_parameters (`location_id`,`voltage_range_id`,`voltage_average_id`)
					 VALUES(?, ?, ?)");
                $insert_args = array($location_id, $app_config['default_voltage_parameter'], $app_config['default_avg_parameter']);
                $insert->execute($insert_args);
        	}
        catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
        return true;

	}
	
	function addLocationDocuments($location_id,$data){
		foreach($data as $key => $value){
			if(!empty($data[$key]['tmp_name'])){
                        $data['document_type'] = $key;
			$data['file_name'] = $data[$key]['name'];
                        $data['file_data'] = file_get_contents($data[$key]['tmp_name']);
                        $data['file_type'] = _mime_content_type($data[$key]['tmp_name']);
			$this->deleteLocationDocument($location_id, $key);
                global $pdo;
        try{
                $insert = $pdo->prepare("INSERT INTO location_documents (`location_id`,`document_type`,`file_type`,`file_name`,`file_data`) 
                                VALUES(?, ?, ?, ?, ?)");
                $insert_args = array($location_id, $data['document_type'], $data['file_type'], $data['file_name'], $data['file_data']);
                $insert->execute($insert_args);
        }
        catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
			}
        //return true;
        	}

	}
	 
	function deleteLocationDocument($location_id, $document_type){
		global $pdo;
	try{
                $delete = $pdo->prepare("DELETE FROM location_documents WHERE location_id = ? AND document_type=?");
                $delete->execute(array($location_id,$document_type));
        }
        catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
        return true;

	}
	

	function updateLocations($data){
		global $pdo;
		$location_id =$data['id'];
	try{
		$update = $pdo->prepare("UPDATE locations SET `name`= ? , `alias`= ?, `latitude` = ?, `longitude` = ? ,`town` = ?,
			`district` = ?, `state`= ?, `revenue_classification` = ?, `address` = ?, `country` = ?, `pincode` = ?, 
			`connection_type`=?, `type_of_supplier`=?, `is_RGGV`=?, `RGGV_year`=?, `tower_id`= ?,	
			`supply_utility` = ?, `feeder`=?, `category` =? WHERE id = ?");
		$update_args = array($data['name'], $data['alias'], $data['latitude'], $data['longitude'], 
			$data['town'], $data['district'], $data['state'], $data['revenue_classification'], $data['address'], 
			$data['country'], $data['pincode'], $data['connection_type'], $data['type_of_supplier'], 
			$data['is_RGGV'], $data['RGGV_year'], $data['tower_id'], $data['supply_utility'],  
			$data['feeder'], $data['category'], $data['id']);
		$update->execute($update_args);
                $data['remark']=$data['other_info'];
		$this->addRemarks($data['id'],$data,'locations');
		$prepared_params = prepareParams($data);
                $this->updateLocationParameters($location_id, $prepared_params);
		$action_details=array();
		$action_details['table_name']='locations';
		$action_details['row_id']=$data['id'];
		$action_details['operation']='Location updated';
		$this->createActionLog($action_details);
               
	}
	catch(PDOException $e){
		if ($e->errorInfo[1] == 1062) {
                $this->setError("Location name already exists.");
                } else {
                $this->setError($e->getMessage());
                }
		return false;
	}
	return true;
	}

	function deleteLocations($location_id){
		$this->deleteAllLocationDocuments($location_id);
		$this->deleteAllLocationParams($location_id);
		global $pdo;
	try{
		$delete = $pdo->prepare("DELETE FROM locations WHERE id = ? ");
		$delete->execute(array($location_id));
		$action_details=array();
		$action_details['table_name']='locations';
		$action_details['row_id']=$location_id;
		$action_details['operation']='Location deleted';
		$this->createActionLog($action_details);
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
	}
	
	function deleteAllLocationDocuments($location_id){
                global $pdo;
        try{
                $delete = $pdo->prepare("DELETE FROM location_documents WHERE location_id = ? ");
                $delete->execute(array($location_id));
        }
        catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
        return true;
        }
	
	function deleteAllLocationParams($location_id){
                global $pdo;
        try{
                $delete = $pdo->prepare("DELETE FROM location_other_values WHERE location_id = ? ");
                $delete->execute(array($location_id));
        }
        catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
        return true;
        }


	function getLocationDocument($location_id,$type){
		global $pdo;
	try{
		$select = $pdo->prepare("SELECT *, location_documents.id AS document_id FROM location_documents 
					WHERE location_id = ? AND document_type = ? ");	
		$select->execute(array($location_id,$type));
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
		$results = $select->fetch(PDO::FETCH_ASSOC);
		return $results;
	}

	function getAllLocationDocuments($location_id){
                global $pdo;
        try{
                $select = "SELECT location_documents.id AS document_id,location_documents.document_type AS document_type, 
			location_documents.file_name AS filename 
			FROM location_documents WHERE location_id = $location_id ";
		$res = $pdo->query($select);
                return $res->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
        }
	
## VENDOR FUNCTIONS
	/*function addVendor($data){
		global $pdo;
	try{
		$insert = $pdo->prepare("INSERT INTO vendors `code`, `name`, `category`, `address`, 
			`city`, `state`, `pincode`
			VALUES( ?, ?, ?, ?, ?, ?, ?)");
		$insert_args = array($data['vendor_code'], $data['vendor_name'], $data['category'], 
			$data['address'], $data['city'], $data['state'], $data['pincode']);
		$insert->execute($insert_args);
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;	
	}*/

	function updateVendor($data){
		global $pdo; 
	try{
		$update = $pdo->prepare("UPDATE vendors SET `name` = ?, `category` = ?, `address` = ?, 
			`city` = ?, `state` = ?, `pincode` = ?, `remark` = ? WHERE `id` = ?");
		$update_args = array($data['name'], $data['cat'], 
			$data['address'], $data['city'], $data['state'], $data['pincode'],$data['remark'],$data['vendor_id']);
		$update->execute($update_args); 
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
	}

	function deleteVendor($vendor_id){
		global $pdo;
	try{
		$delete = $pdo->prepare("DELETE FROM vendors WHERE id = ?");
		$delete->execute(array($vendor_id));
	}
	catch(PDOException $e){
		if ($e->errorInfo[1] == 1451) {
                $this->setError("You can not delete vendor, you have to delete vendor contact first and then the vendor. Click on add vendor contact to delete the vendor contacts.");
                } else {
                $this->setError($e->getMessage());
                }
		return false;
	}
	return true;
	}


## VENDOR CONTACT FUNCTIONS
function addVendorContact($data){
		global $pdo;
	try{
		$insert = $pdo->prepare("INSERT INTO vendor_contacts (`vendor_id`,`display_order`,`name`,`email`,`phone`,`mobile`)
			VALUES ( ?, ?, ?, ?, ? ,?)");
		$insert->execute(array($data['vendor_id'], $data['display_order'], $data['name'], 
				$data['email'], $data['phone'], $data['mobile']));	
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
	}

	function updateVendorContact($data){
		global $pdo;
	try{
		$update = $pdo->prepare("UPDATE vendor_contacts SET `vendor_id` = ?, `display_order` = ?, `name` = ?, 
					`email` = ?, `phone` = ?, `mobile` = ? WHERE id = ?");
		$update->execute(array($data['vendor_id'], $data['display_order'], $data['name'], 
			$data['email'], $data['phone'],$data['mobile'], $data['vendor_contact_id']));	
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
	}

	function removeVendorContact($vendor_contact_id){
		global $pdo;
	try{
		$delete = $pdo->prepare("DELETE FROM vendor_contacts WHERE id = ?");
		$delete->execute(array($vendor_contact_id));	
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
	}

## SIM CARD FUCNTIONS

	function addSimCard($data){
		global $pdo;
	try{
		$activation_date = date('Y-m-d h:i:s', strtotime($data['activation_date']));
		//$billling_date = date('Y-m-d h:i:s', strtotime($data['billing_due_date']));
		$insert = $pdo->prepare("INSERT INTO sim_cards (`sim_no`, `mobile_no`, `company`,`currently_with`,`plan_data_size`,
				`plan_cost`, `billing_cycle`,`status`, `activation_date`, `billing_due_date`)
				 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$insert->execute(array($data['sim_no'], $data['mobile_no'], $data['company'], $data['currently_with'], 
				$data['plan_data_size'],$data['plan_cost'],$data['billing_cycle'], 
				$data['status'] ,$activation_date, $data['billing_due_date']));
	}
	catch(PDOException $e){
		if($e->errorInfo[1] == 1062){
		$this->setError("Duplicate Entry for sim number and mobile number.");
		}else{
		$this->setError($e->getMessage());
	}
		return false;
	}
	return true;
	}

	function updateSimCard($data){
		global $pdo;
	try{
		$activation_date = date('Y-m-d h:i:s', strtotime($data['activation_date']));
		//$billling_date = date('Y-m-d h:i:s', strtotime($data['billing_due_date']));
		$update = $pdo->prepare("UPDATE sim_cards SET `sim_no` = ?, `mobile_no` = ?, `company` = ?, 
				`currently_with`=?,`plan_data_size`=?,`plan_cost`=?, billing_cycle = ?,`status` = ?,
				 `activation_date`= ?, `billing_due_date`=?  WHERE id = ?");
		$update->execute(array($data['sim_no'], $data['mobile_no'], $data['company'],  $data['currently_with'], 
				$data['plan_data_size'],$data['plan_cost'], $data['billing_cycle'], $data['status'], 
				$activation_date, $data['billing_due_date'], $data['sim_card_id']));
	}
	catch(PDOException $e){
		if($e->errorInfo[1] == 1062){
		$this->setError("Duplicate Entry for sim number and mobile number.");
		}else{
		$this->setError($e->getMessage());
		}
		return false;
	}
	return true;
	}

	function removeSimCard($sim_card_id){
		global $pdo;
	try{
		$delete = $pdo->prepare("DELETE FROM sim_cards WHERE id = ?");
		$delete->execute(array($sim_card_id));
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
	}

	function getSimCardDetails($sim_card_id){
		global $pdo; 
		try{
		$select = "SELECT * FROM sim_cards WHERE id = ?"; 
		$res = $pdo->prepare($select);
		$res->execute(array($sim_card_id));
		return $res->fetch(PDO::FETCH_ASSOC);
		}
		catch(PDOException $e){
			$this->setError($e->getMessage());
			return false;
		}
	}

	function getAllSimCards($details){
		global $pdo; 
		$per_page= $this->app_config['per_page'];
		$p = ($details['p']) ? $details['p'] : 1; 
		$offset = ($p - 1) * $per_page;
		try{
		if($details['flag']=='1'){
			$select = "SELECT * FROM sim_cards "; /*LIMIT $offset, $per_page"; */
		
		}else{
			$select = "SELECT * FROM sim_cards WHERE status='active'"; /*LIMIT $offset, $per_page"; */
		}
		$res = $pdo->query($select);
		return $res->fetchAll(PDO::FETCH_ASSOC);
		}
		catch(PDOException $e){
			$this->setError($e->getMessage());
			return false;
		}
	}

## SIM CARD BILLING FUNCTIONS

	function addSimCardBilling($data){
		global $pdo;
	try{
		 $insert = $pdo->prepare("INSERT INTO sim_card_billing (`sim_card_id`,`billing_date`,`payment_due`) 
				VALUES (?, ?, ?)");
		$insert->execute(array($data['sim_card_id'], $data['billing_date'], $data['payment_due']));
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
	}

	function updateSimCardBilling($data){
		global $pdo;
	try{
		 $update = $pdo->prepare("UPDATE sim_card_billing SET `sim_card_id` = ?, `billing_date` = ?, 
			`payment_due` = ? WHERE id = ?");
		$update->execute(array($data['sim_card_id'], $data['billing_date'], $data['payment_due']));
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
	}

	function removeSimCardBilling($sim_card_billing_id){
		global $pdo;
	try{
		 $delete = $pdo->prepare("DELETE FROM sim_card_billing WHERE id = ?");
		$delete->execute(array($sim_card_billing_id));
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
	}

## DEVICE FUNCTIONS 

	function addDevice($data){
		$device_code=$data['device_id_string'];
	// function to mark files as not processed called here 
		$this->markFileAsNotProcessed($device_code);
		$installed = date('Y-m-d', get_strtotime($data['installed']));
		$date_tested = date('Y-m-d', get_strtotime($data['date_tested']));
		global $pdo;
	try{
		$insert = $pdo->prepare("INSERT INTO devices (`device_id_string`,`installed`,`vendor_id`, 
			`transfer_type`, `status`, `software_version`, `date_tested`) VALUES (?, ?, ?, ?, ?, ?, ?)");
		$insert_args = array($data['device_id_string'], $installed, $data['vendor_id'], $data['transfer_type'], 
				$data['status'], $data['software_version'], $date_tested); 
		$insert->execute($insert_args);
		$lastId = $pdo->lastInsertId();
		$this->addRemarks($lastId,$data,'devices');
		$action_details=array();
		$action_details['table_name']='devices';
		$action_details['row_id']=$lastId;
		$action_details['operation']='New Device Added';
		$this->createActionLog($action_details);
	}
	catch(PDOException $e){
		if ($e->errorInfo[1] == 1062) {
                $this->setError("Device already exists.");
                } else {
                $this->setError($e->getMessage());
                }
		return false;
	}
	
	return true;
	}
/*Added by Rupali */
function getRemark($param_id,$table_name){
		global $pdo; 
		try{
		$select = "SELECT * FROM remarks WHERE param_id = ? AND table_name= ?"; 
		$res = $pdo->prepare($select);
		$res->execute(array($param_id,$table_name));
		return $res->fetch(PDO::FETCH_ASSOC);
		}
		catch(PDOException $e){
			$this->setError($e->getMessage());
			return false;
		}
	}	
// function to mark files as not processed if any of the file matches with the device code being inserted
	function markFileAsNotProcessed($device_code){
		global $pdo;
        try{
                $update = $pdo->prepare("UPDATE datafiles SET `is_processed` = 0  WHERE LEFT(filename,7) = ?");
                $update->execute(array($device_code));
        }
        catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
        return true;
        }

	
	function updateDevice($data){
		$installed = date('Y-m-d', get_strtotime($data['installed']));
                $date_tested = date('Y-m-d', get_strtotime($data['date_tested']));

		global $pdo;
	try{
		$update = $pdo->prepare("UPDATE devices SET `device_id_string` = ? ,`vendor_id` = ?, 
			`transfer_type` = ?, `status` = ?, `installed` = ?, `software_version` = ?, `date_tested`= ? WHERE id = ? ");
		$update_args = array($data['device_id_string'], $data['vendor_id'], $data['transfer_type'], 
				$data['status'], $installed, $data['software_version'], $date_tested, $data['device_id']);
		$update->execute($update_args);

		$this->addRemarks($data['device_id'],$data,'devices');
                
                if($data['software_version']!=$data['old_software_version'])
                  {
                $insert_version_logs = $pdo->prepare("INSERT INTO version_logs (`device_id`,`version`,`date`, 
			`created_by`) VALUES (?, ?, NOW(), ?)");
		$insert_args_version_logs  = array($data['device_id'],$data['software_version'], $data['user_id']); 
		$insert_version_logs->execute($insert_args_version_logs);
                }

		$action_details=array();
		$action_details['table_name']='devices';
		$action_details['row_id']=$data['device_id'];
		$action_details['operation']='Device Updated';
		$this->createActionLog($action_details);
		
	}
	catch(PDOException $e){
		if ($e->errorInfo[1] == 1062) {
                $this->setError("Device already exists.");
                } else {
                $this->setError($e->getMessage());
                }
		return false;
	}
	return true;
	}
/**************added by Rupali************************/
	
	function updateAnalysisReport($details,$files){
	$allowedExts = array("pdf", "doc", "docx"); 
$extension = end(explode(".", $files["file_path"]["name"]));

$file =$files['file_path']['name'];
$file_loc = $files['file_path']['tmp_name'];
$file_size = $files['file_path']['size'];
$file_type = $files['file_path']['type'];
$folder=$_SERVER['DOCUMENT_ROOT']."/reports/";
//($files["file_path"]["size"] >1000000)
	 if($details['date_published']!= ' ')
	{
		$date =  date('Y-m-d %H:%i:%s', get_strtotime($details['date_published'])); 
	}else
	{
		$date = '0000-00-00 00:00:00';
	}
        //Inserting file  in folder and database
	if ( ( ($files["file_path"]["type"] == "application/msword") || ($files["file_path"]["type"] == "text/pdf") || ($files["file_path"]["type"] == "application/pdf"))&& in_array($extension, $allowedExts))
	{
		global $pdo;
		//$insert = "INSERT INTO reports (`title`,`file_path`,`description`,`date_published`) VALUES (?,?,?,?)";
		try{
			$update = $pdo->prepare("UPDATE reports SET `title` = ? ,`description` = ?,`file_path` = ?, `date_published` = ? WHERE id = ? ");
			$update_args = array($details['title'],  $details['description'],$file,$date,$details['id']);
			$update->execute($update_args);
		
		   }	
		catch(PDOException $e){
		        $this->setError($e->getMessage());
		        return false;
			//echo $e->getMessage();
		        //exit;
		}

		 move_uploaded_file($file_loc,$folder.$file);		 
                 return true;
	}else
	{
                $this->setError("Invalid File");
                return false;
	}	
}



function deleteDevice($device_id){
		$device_details = $this->getDeviceDetails($device_id);
		if($device_details['status']==1 && $this->isDeviceInstalled($device_id)){
			$this->setError("This device is active, you can not delete the device.");
		}else{
		
		global $pdo;
	try{
		$delete = $pdo->prepare("DELETE FROM devices WHERE id = ?");
		$delete->execute(array($device_id));
		$action_details=array();
		$action_details['table_name']='devices';
		$action_details['row_id']=$device_id;
		$action_details['operation']='Device Deleted';
		$this->createActionLog($action_details);
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
	}
	}
	
	function isDeviceInstalled($device_id){
		global $pdo;
		$select = $pdo->prepare("SELECT id from device_installations WHERE device_id = :device_id");
		$select->bindParam(':device_id',$device_id, PDO::PARAM_STR);
		$select->execute();
		if ($select->rowCount() > 0) {
 		return true;
		}else{
   		return false;
		}
	}
## DEVICE INSTALLATION FUNCTIONS

	function addDeviceInstallation($data){
		global $pdo;
	try{
		$installed = date('Y-m-d H:i:s', get_strtotime($data['installed']));
		$deployed = date('Y-m-d H:i:s', get_strtotime($data['deployed']));
		$insert = $pdo->prepare("INSERT INTO device_installations (`device_id`,`location_id`,`installed`,
			`status`,`sim_card_id`,`name`,`deployed`,`installed_by`,`remark`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$insert_args = array($data['device_id'], $data['location_id'], $installed, $data['status'], 
				$data['sim_card_id'], $data['name'], $deployed, $data['installed_by'], $data['remark']);
		$insert->execute($insert_args);

		if($this->isDeviceVoltagePresent($data['device_id'])){
			$voltage_str=array();
			$voltage_str=$this->getVoltagesForDevice($data['device_id']);
			if(count($voltage_str)>0){
				if($this->addVoltageReadings($voltage_str)){
					$this->markVoltUnPublishedByLocation($data['location_id']);
				}
			}
		}

		// call function to add remark
                 $lastId = $pdo->lastInsertId();
		$this->addRemarks($lastId,$data,'device_installations');
		$action_details=array();
		$action_details['table_name']='devices_installtion';
		$action_details['row_id']=$lastId;
		$action_details['operation']='New Device Installed';
		$this->createActionLog($action_details);

	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
	}

	function updateDeviceInstallation($data){
		global $pdo;
	try{
		$installed = date('Y-m-d h:i:s',get_strtotime($data['installed']));
		$deployed = date('Y-m-d h:i:s',get_strtotime($data['deployed']));
		$update = $pdo->prepare("UPDATE device_installations SET `device_id` = ?, `location_id` = ?, `installed` = ?,
			`status` = ?, `sim_card_id` = ?, `name` = ?, `deployed` = ?, `installed_by`= ?, `remark`=? WHERE id = ?");
		$update_args = array($data['device_id'], $data['location_id'], $installed, $data['status'], 
				$data['sim_card_id'], $data['name'], $deployed, $data['installed_by'], $data['remark'], $data['installation_id']);
		$update->execute($update_args);
		// call funcatoin to add remark
		if(($data['location_id'] != $data['old_location_id']) || ($data['old_device_id'] != $data['old_device_id'])){
			if($this->isDeviceVoltagePresent($data['device_id'])){
				$voltage_str=array();
				$voltage_str=$this->getVoltagesForDevice($data['device_id']);
				if(count($voltage_str)>0){
					if($this->addVoltageReadings($voltage_str)){
						$this->markVoltUnPublishedByLocation($data['location_id']);
					}
				}
			}
		}
		$this->addRemarks($data['installation_id'],$data,'device_installations');
		$action_details=array();
		$action_details['table_name']='device_installation';
		$action_details['row_id']=$data['installation_id'];
		$action_details['operation']='Device installation updated';
		$this->createActionLog($action_details);
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
	}

	function removeDeviceInstallation($device_installation_id){
		global $pdo;
	try{
		$delete = $pdo->prepare("DELETE FROM device_installations WHERE id = ?");
		$delete->execute(array($device_installation_id));
		$action_details=array();
		$action_details['table_name']='device_installtion';
		$action_details['row_id']=$device_installation_id;
		$action_details['operation']='Device Installtion Deleted';
		$this->createActionLog($action_details);
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
	}

	function getDeviceInstallationDetails($installation_id){
		global $pdo;
		try{
			$select = "select *, device_installations.id AS installation_id,installer_directory.id AS installer_id, 
				device_installations.name AS installation_name, devices.id AS dev_id,
				device_installations.status AS installation_status FROM device_installations
				LEFT JOIN installer_directory ON installer_directory.id = device_installations.installed_by
				LEFT JOIN locations ON locations.id = device_installations.location_id
				LEFT JOIN sim_cards ON sim_cards.id = device_installations.sim_card_id
				LEFT JOIN devices ON devices.id = device_installations.device_id
				WHERE device_installations.id = '$installation_id'";
			$res = $pdo->query($select);
			return $res->fetch(PDO::FETCH_ASSOC);
		}catch(PDOException $e){
			$this->setError($e->getMessage());
			return false;
		}
	}

	function getAllDeviceInstallations($details){
		global $pdo;
		$per_page= $this->app_config['per_page'];
		$p = ($details['p']) ? $details['p'] : 1; 
		$offset = ($p - 1) * $per_page;
		try{
		//$select = "SELECT * FROM device_installations LIMIT $offset, $per_page"; 
		/*$select = "SELECT *, device_installations.id AS installation_id,device_installations.location_id AS location_id, 
			device_installations.status AS installation_status 
			FROM device_installations LEFT JOIN devices ON devices.id = device_installations.device_id"; */
		$select = "SELECT locations.name AS location_name,sim_cards.sim_no AS sim_card_number, sim_cards.mobile_no AS moblie_number,
			sim_cards.company AS company,installer_directory.name AS installer,
			device_installations.id AS installation_id, device_installations.location_id AS location_id, 
			device_installations.status AS installation_status,
			device_installations.*,devices.* FROM device_installations 
			LEFT JOIN devices ON devices.id = device_installations.device_id 
			INNER JOIN sim_cards ON sim_cards.id = device_installations.sim_card_id 
			INNER JOIN locations ON locations.id=device_installations.location_id 
			INNER JOIN installer_directory ON installer_directory.id=device_installations.installed_by";
		$res = $pdo->query($select);
		return $res->fetchAll(PDO::FETCH_ASSOC);
		}
		catch(PDOException $e){
			$this->setError($e->getMessage());
			return false;
		}
	}

	function getAllDeviceInstallationsByCriteria($criteria){
                global $pdo;
                try{
                $select = "SELECT *, device_installations.id AS installation_id, device_installations.status AS installation_status 
                        FROM device_installations 
			LEFT JOIN devices ON devices.id = device_installations.device_id
			LEFT JOIN locations ON locations.id = device_installations.location_id
			WHERE locations.state LIKE '$criteria%' OR locations.district LIKE '$criteria%' OR locations.name LIKE '$criteria%'";
                $res = $pdo->query($select);
                return $res->fetchAll(PDO::FETCH_ASSOC);
                }
                catch(PDOException $e){
                        $this->setError($e->getMessage());
                        return false;
                }
        }


	function addSummaryParameter($data){
		global $pdo;
	try{
		$insert = $pdo->prepare("INSERT INTO summary_parameters (`param`,`desc`) VALUES(?, ?)");
		$insert_args = array($data['param'], $data['description']); 
		$insert->execute($insert_args);
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
	}
	
	function updateSummaryParameter($data){
		global $pdo;
	try{
		$update = $pdo->prepare("UPDATE summary_parameters SET `param` = ?, `desc` = ? WHERE id = ?");
		$update_args = array($data['param'], $data['description'], $data['summary_parameter_id']); 
		$update->execute($update_args);
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
	}

	function removeSummaryParameter($summary_parameter_id){
		global $pdo;
	try{
		$delete= $pdo->prepare("DELETE FROM summary_parameters WHERE id = ?");
		$delete->execute(array($summmary_parameter_id));
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
	}
// for setting of graph color

	function addParamColor($data){
                global $pdo;
        try{
                $insert = $pdo->prepare("UPDATE summary_parameters SET `graph_display_color` = ? WHERE id = ?");
                $insert_args = array($data['graph_display_color'], $data['param_id']);
                $insert->execute($insert_args);
        }
        catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
        return true;
        }

	function getAllSummaryParams(){
        $select = "SELECT * FROM summary_parameters ";
        global $pdo;
                if(!($res = $pdo->query($select))){
                        $this->setError($select);
                        return false;
                }
                return $res->fetchAll(PDO::FETCH_ASSOC);
        }

	function getSummaryParamDetails($param_id){

                $select = "SELECT * FROM summary_parameters WHERE id = ?";

        global $pdo;
                $stmt = $pdo->prepare($select);
                $stmt->execute(array($param_id));
                $result_array = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result_array;
        }



	//start validation parameters
	function addValidationParameter($data){
		global $pdo;
		try{
			$insert = $pdo->prepare("INSERT INTO validation_parameters (`param`,`desc`, `method`) VALUES(?, ?, ?)");
			$insert_args = array($data['param'], $data['description'], $data['method_name']); 
			$insert->execute($insert_args);
		}
		catch(PDOException $e){
			$this->setError($e->getMessage());
			return false;
		}
		return true;
	}

	function updateValidationParameter($data){
		global $pdo;
		try{
			$update = $pdo->prepare("UPDATE validation_parameters SET `param` =? ,`desc`= ?, `method`= ? WHERE id = ?");
			$update_args = array($data['param'], $data['description'], $data['method_name'],$data['val_param_id']); 
			$update->execute($update_args);
		}
		catch(PDOException $e){
			$this->setError($e->getMessage());
			return false;
		}
		return true;
	}
	
	function removeValidationParameter($validation_parameter_id){
		global $pdo;
		try{
			$delete = $pdo->prepare("DELETE FROM validation_parameters WHERE id = ?");
			$delete_args = array($validation_parameter_id); 
			$delete->execute($delete_args);
                      // echo $delete->queryString;exit;
		}
		catch(PDOException $e){
			$this->setError($e->getMessage());
			return false;
		}
		return true;
	}

           // function to get Validation Parameter list- Rupali

	function getAllValidationParameter($details){
			global $pdo;
		$per_page= $this->app_config['per_page'];
		$p = ($details['p']) ? $details['p'] : 1; 
		$offset = ($p - 1) * $per_page;
                        $select = "SELECT * FROM  validation_parameters";/* LIMIT $offset, $per_page";*/

                        $res = $pdo->query($select);
                        return $res->fetchAll(PDO::FETCH_ASSOC);
		}
// function to get Validation Parameter list- Rupali

	function getAllValidationParameterOptional($details){
			global $pdo;
		$per_page= $this->app_config['per_page'];
		$p = ($details['p']) ? $details['p'] : 1; 
		$offset = ($p - 1) * $per_page;
                        $select = "SELECT * FROM  validation_parameters Where compulsory=0";/* LIMIT $offset, $per_page";*/

                        $res = $pdo->query($select);
                        return $res->fetchAll(PDO::FETCH_ASSOC);
		}
          // function to get Particular Validation Parameter - Rupali
        function getValidationParameter($id){
			 global $pdo;
			        try{
                		$select = $pdo->prepare("select * FROM validation_parameters WHERE  id = ? ");
                		$select->execute(array($id));
        			}catch(PDOException $e){
                		$this->setError($e->getMessage());
                		return false;
        			}
				$result = $select->fetch(PDO::FETCH_ASSOC);
                        	return $result;
		}
//End validation parameters



	function getValidationParameters(){
		global $pdo; 
		try{
		$select = $pdo->prepare("SELECT * FROM validation_parameters order by priority asc ");
		$select->execute();
		return $select->fetchAll(PDO::FETCH_ASSOC);
		}catch(PDOException $e){
			$this->setError($this->getMessage());
			return false;
		}
	}

	function addDeviceStatusValues($data){
		global $pdo;
	try{
		$insert = $pdo->prepare("INSERT INTO device_status_values (`status`) VALUES(?)");
		$insert_args = array($data['status']); 
		$insert->execute($insert_args);
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
	}
	

	function updateDeviceStatusValues($data){
		global $pdo;
	try{
		$update = $pdo->prepare("UPDATE device_status_values SET `status` = ? WHERE id = ?");
		$update_args = array($data['status'], $data['device_status_value_id']); 
		$update->execute($update_args);
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
	}

	function removeDeviceStatusValues($device_status_value_id){
		global $pdo;
	try{
		$delete = $pdo->prepare("DELETE FROM device_status_values WHERE id = ?");
		$delete->execute($device_status_value_id);
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
	}

/***********Error Code*********************/
	function addErrorCode($data){
		global $pdo;
	try{
		$insert = $pdo->prepare("INSERT INTO error_codes (`error_code`,`err_str`) VALUES(? , ?)");
		$insert->execute(array($data['error_code'], $data['error_description']));
	}catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
	}

	function updateErrorCode($data){
		global $pdo;
	try{
		$update = $pdo->prepare("UPDATE error_codes SET `error_code` = ? ,`err_str`= ? WHERE id = ?");
		$update->execute(array($data['error_code'], $data['error_description'], $data['error_id']));
	}catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
	}

	function deleteErrorCode($error_id){
		global $pdo;
	try{
		$delete = $pdo->prepare("DELETE FROM error_codes WHERE id = ?");
		$delete->execute(array($error_id));
	}catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
	}

// function to get error codes list- Rupali

	function getAllErrors(){
			global $pdo;
		$per_page= $this->app_config['per_page'];
		$p = ($details['p']) ? $details['p'] : 1; 
		$offset = ($p - 1) * $per_page;
                        $select = "SELECT * FROM  error_codes";/* LIMIT $offset, $per_page";*/

                        $res = $pdo->query($select);
                        return $res->fetchAll(PDO::FETCH_ASSOC);
		}
// function to get particuler error code- Rupali

	function getError($id){
			 global $pdo;
			        try{
                		$select = $pdo->prepare("select * FROM error_codes WHERE  id = ? ");
                		$select->execute(array($id));
        			}catch(PDOException $e){
                		$this->setError($e->getMessage());
                		return false;
        			}
				$result = $select->fetch(PDO::FETCH_ASSOC);
                        	return $result;
		}
/***********End Error Code****************/



	function addReportRequest($data){
		global $pdo;
	try{
		$insert = $pdo->prepare("INSERT INTO report_requests (`user_id`,`requested`,`report_params`) 
					VALUES(?, ?, ?)");
		$insert->execute(array($data['user_id'], $data['requested'], $data['report_params']));
	}catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
	}	

	function updateReportRequest($data){
		global $pdo;
	try{
		$update = $pdo->prepare("UPDATE report_requests SET `user_id` = ?, `requested`=?, `report_params` = ? 
					WHERE id = ?");
		$update->execute(array($data['user_id'], $data['requested'], $data['report_params'], 
				$data['report_request_id']));
	}catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
	}

	function deleteReportRequest($report_request_id){
		global $pdo;
	try{
		$delete = $pdo->prepare("DELET FROM report_requests SET WHERE id = ?");
		$delete->execute(array($report_request_id));
	}catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
	}

	function addRevenueClassification($data){
		global $pdo;
	try{
		$insert = $pdo->prepare("INSERT INTO revenue_classification (`name`) VALUES( ? )");
		$insert->execute(array($data['name']));
	}catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
	}

	function updateRevenueClassification($data){
		global $pdo;
	try{
		$update = $pdo->prepare("UPDATE revenue_classification `name` = ? WHERE id = ?");
		$update->execute(array($data['name'], $data['id']));
	}catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
	}

	function deleteRevenueClassification($revenue_classification_id){
		global $pdo;
	try{
		$delete = $pdo->prepare("DELETE FROM revenue_classification WHERE id = ?");
		$delete->execute(array($revenue_classification_id));
	}catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
	}
 
// function to get vendor details - Jagdish

         function getVendors(){
                        $per_page=10;
			$p =1;
                        $offset = ($p - 1) * $per_page;
                        $select = "SELECT * ,vendors.id as vendor_id FROM vendors"; 
                        //LIMIT $offset, $per_page";
                        global $pdo;
                        $res = $pdo->query($select);
                        return $res->fetchAll(PDO::FETCH_ASSOC);
                }

// function to get device status - Jagdish

	function getDeviceStatus(){
                        $select = "SELECT * ,device_status_values.id AS status_id FROM device_status_values ";
                        global $pdo;
                        $res = $pdo->query($select);
                        return $res->fetchAll(PDO::FETCH_ASSOC);
                }

// function to get devices- Jagdish

	function getAllDevices(){
			$per_page=10;
                        $p =1;
                        $offset = ($p - 1) * $per_page;
                        $select = "SELECT * ,devices.id as device_id, vendors.code AS vendor_code, 
				   device_status_values.status AS device_status FROM devices 
				   LEFT JOIN vendors ON vendors.id=devices.vendor_id
				   LEFT JOIN device_status_values ON device_status_values.id=devices.status ORDER BY device_id_string ASC";
				 //  LIMIT $offset, $per_page";
                        global $pdo;
                        $res = $pdo->query($select);
                        return $res->fetchAll(PDO::FETCH_ASSOC);
		}	

	/*
		Get available (not installed) devices
	*/

	function getAvailableDevices(){
		global $pdo;
		$select = $pdo->prepare("SELECT id as device_id, device_id_string FROM devices 
			WHERE id NOT IN (SELECT device_id FROM device_installations)");
		$select->execute();
		return $select->fetchAll(PDO::FETCH_ASSOC);
	}
	
	function getAvailableSimCards(){
                global $pdo;
                $select = $pdo->prepare("SELECT * FROM sim_cards 
                        WHERE id NOT IN (SELECT sim_card_id FROM device_installations WHERE sim_card_id is not null) AND status='active'");
                $select->execute();
                return $select->fetchAll(PDO::FETCH_ASSOC);
        }
	
	function getAvailableLocations(){
                global $pdo;
                $select = $pdo->prepare("SELECT * FROM locations 
                        WHERE id NOT IN (SELECT location_id FROM device_installations)");
                $select->execute();
                return $select->fetchAll(PDO::FETCH_ASSOC);
        }



// function to get device details - Jagdish

	function getDeviceDetails($device_id){
			 global $pdo;
			        try{
                		$select = $pdo->prepare("select * FROM devices WHERE id = ?");
                		$select->execute(array($device_id));
        			}catch(PDOException $e){
                		$this->setError($e->getMessage());
                		return false;
        			}
				$result = $select->fetch(PDO::FETCH_ASSOC);
                        	return $result;
		}
// function to get device details - Rupali

	function getReportDetails($id){
			 global $pdo;
			        try{
                		$select = $pdo->prepare("select * FROM reports WHERE id = ?");
                		$select->execute(array($id));
        			}catch(PDOException $e){
                		$this->setError($e->getMessage());
                		return false;
        			}
				$result = $select->fetch(PDO::FETCH_ASSOC);
                        	return $result;
		}
// function for get revenue classification

	function getRevenueClassification(){
                        $select = "SELECT *, revenue_classification.id AS revenue_class_id FROM revenue_classification";
                        global $pdo;
                        $res = $pdo->query($select);
                        return $res->fetchAll(PDO::FETCH_ASSOC);
                }

/*
	
	}
*/

	

	function getEvents($details){
		global $pdo;
		$per_page= $this->app_config['per_page'];
		$p = ($details['p']) ? $details['p'] : 1; 
		$offset = ($p - 1) * $per_page;
		try{
		$select = "SELECT datafiles.id as datafile_id ,filename,DATE(event_date) as event_date,datafiles.content, 
			error_codes.error_code as event_code,error_codes.err_str,
			 LEFT(filename,7) as device_id FROM datafiles
			LEFT JOIN error_codes ON error_codes.id=datafiles.error_code WHERE 1";
		$select .=" AND datafiles.error_code is not null AND datafiles.error_code !=0 ";

		if($details['event_id']!=''){
			$select .=" AND datafiles.error_code= $details[event_id]";
		}
		if($details['device_id']!=''){
			$select .=" AND LEFT(filename,7) = '$details[device_id]'";
		}else if($details['location_id']!=''){
			$select .=" AND LEFT(filename,7) IN ($details[device_str])";
		}
		if($details['from_date']!=''){
			$from_date = date('Y-m-d H:i:s', get_strtotime($details['from_date'])); 
			$to_date = date('Y-m-d H:i:s', get_strtotime($details['to_date'])); 
			//$from_date =  date('Y-m-d', get_strtotime($details['from_date'])); 
			//$to_date =  date('Y-m-d', get_strtotime($details['to_date'])); 
			if($details['to_date']!=''){
				$select .=" AND (event_date >= '$from_date' AND event_date <= '$to_date')";
			}else{
				$select .=" AND (event_date >= '$from_date')";
			}

		}elseif($details['to_date']!=''){
			//$to_date =  date('Y-m-d', get_strtotime($details['to_date'])); 
			$to_date = date('Y-m-d H:i:s', get_strtotime($details['to_date'])); 
				$select .=" AND (event_date <= '$to_date'";
		}
//echo $select;
		//$select .=" LIMIT $offset, $per_page"; 
		$stmt = $pdo->query($select);
		}catch(PDOException $e){
                      $this->setError($e->getMessage());
                      return false;
                }

		$events = array();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$events[] = $row;
		}
		return $events;
	}
	function getEventCountForDeviceAndError($details){
		global $pdo;
		$per_page= $this->app_config['per_page'];
		$p = ($details['p']) ? $details['p'] : 1; 
		$offset = ($p - 1) * $per_page;
		try{
		$select = "SELECT  LEFT(filename,7) as device_id, error_codes.err_str as error,
			 count(datafiles.id) as cnt FROM datafiles
			LEFT JOIN error_codes ON error_codes.id=datafiles.error_code WHERE 1";
		$select .=" AND datafiles.error_code is not null AND datafiles.error_code !=0 ";

		if($details['event_id']!=''){
			$select .=" AND datafiles.error_code= $details[event_id]";
		}
		if($details['device_id']!=''){
			$select .=" AND LEFT(filename,7) = '$details[device_id]'";
		}else if($details['location_id']!=''){
			$select .=" AND LEFT(filename,7) IN ($details[device_str])";
		}
		if($details['from_date']!=''){
			$from_date =  date('Y-m-d', get_strtotime($details['from_date'])); 
			$to_date =  date('Y-m-d', get_strtotime($details['to_date'])); 
			if($details['to_date']!=''){
				$select .=" AND (event_date >= '$from_date' AND event_date <= '$to_date')";
			}else{
				$select .=" AND (event_date >= '$from_date')";
			}

		}elseif($details['to_date']!=''){
			$to_date =  date('Y-m-d', get_strtotime($details['to_date'])); 
				$select .=" AND (event_date <= '$to_date'";
		}elseif($details['event_id']=='' && $details['device_id']==''){
			$select .=" AND (event_date >=DATE_SUB(NOW(), INTERVAL 7 DAY)  AND event_date <= NOW())";

		}

		
		$select .=" group by device_id, error ORDER BY cnt DESC";
		//$select .=" LIMIT $offset, $per_page"; 
		$stmt = $pdo->query($select);
		}catch(PDOException $e){
                      $this->setError($e->getMessage());
                      return false;
                }

		$events = array();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$events[] = $row;
		}
		return $events;
	}

	public function getDevicesByLocation($location_id){
	    $select="SELECT * FROM devices LEFT JOIN device_installations 
                ON devices.id=device_installations.device_id WHERE location_id =$location_id";
	    global $pdo;
	    $stmt = $pdo->query($select);
		$devices = array();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$devices[] = $row;
		}
		return $devices;
	}
	public function getAllVoltageParams(){
	    $select="SELECT * FROM voltage_parameters";
	    global $pdo;
	    $stmt = $pdo->query($select);
		$params = array();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$params[$row['param']] = $row['id'];
		}
		return $params;

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
	function addVoltageParamValues($voltage_range_id,$data){
		global $pdo;
	$insert = "INSERT INTO voltage_parameters_values 
			(`parameter_id`,`voltage_range_id`,`low_value`,`high_value`)
        		VALUES ";
	foreach($data as $k => $v){
	    	    $insert .= "('$k', '$voltage_range_id','$v[low]','$v[high]'),";
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
	return true;
	}


	public function getVoltageRanges(){
	     $select="SELECT * FROM voltage_ranges ";
	    global $pdo;
	    $stmt = $pdo->query($select);
		$params = array();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$params[] = $row;
		}
		return $params;

	}

	function addVoltageRangeParam($title,$data){
		global $pdo;
	try{
		$insert = $pdo->prepare("INSERT INTO voltage_ranges (`title`) VALUES( ? )");
		$insert->execute(array($title));
		$voltage_range_id=$pdo->lastInsertId();
		$this->addVoltageParamValues($voltage_range_id,$data);
	}catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
	}
	
	function deleteVoltageParam($voltage_range_id){
		global $pdo;
	try{
		$delete = $pdo->prepare("DELETE FROM voltage_parameters_values
			WHERE voltage_range_id = ?");
		$delete->execute(array($voltage_range_id));
	}catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;

	}
	return true;
	}

	function deleteVoltageRangeParam($voltage_range_id){
		global $pdo;
	try{
		$this->deleteVoltageParam($voltage_range_id);
		$delete = $pdo->prepare("DELETE FROM voltage_ranges WHERE id = ?");
		$delete->execute(array($voltage_range_id));
	}catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;	
		
	}
	
	function updateVoltageRangeParam($form_data,$param_data){
		global $pdo;
	try{
		$this->deleteVoltageParam($form_data['id']);
		if($form_data['flag'] =='1'){
			$update = $pdo->prepare("UPDATE voltage_ranges SET `title` = ? WHERE id = ?");
			$update->execute(array($form_data['title'], $form_data['id']));
		}
		$this->addVoltageParamValues($form_data['id'],$param_data);
		
	}catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
	}
	
	

//function to add vendor -Rupali	
    function addVendor($data){
		global $pdo;
				try{
					$insert = $pdo->prepare("INSERT INTO vendors (`name`,`category`, 
						`address`, `city`, `pincode`,`state`,`remark`) VALUES (?, ?, ?, ?, ?, ?, ?)");
					$insert_args = array($data['name'], $data['cat'], 
							$data['address'], $data['city'], $data['pincode'], $data['state'],$data['remark']); 
					$insert->execute($insert_args);
                                        $venid = $pdo->lastInsertId();
                                       
                                         //Add vendor contacts
  
                                        $insert_contact = $pdo->prepare("INSERT INTO vendor_contacts (`vendor_id`,`display_order`,`name`, 
						`email`, `phone`, `mobile`) VALUES (?, ?, ?, ?, ?, ?)");
					$insert_contact_args = array($venid, '1', $data['name'], 
							$data['email'], $data['phone'], $data['mobile']); 
			
					$insert_contact->execute($insert_contact_args);
				}
				catch(PDOException $e){
					$this->setError($e->getMessage());
					return false;
				}
	       return true;
	}
	//function to add vendor -Rupali	add
function getVendorDetails($vendor_id){
			 global $pdo;
			        try{
                		$select = $pdo->prepare("select * FROM vendors WHERE id = ?");
                		$select->execute(array($vendor_id));
        			}catch(PDOException $e){
                		$this->setError($e->getMessage());
                		return false;
        			}
				$result = $select->fetch(PDO::FETCH_ASSOC);
                        	return $result;
		}
	//function to add vendor -Rupali	
function getVendorContactDetails($vendor_id){
			 global $pdo;
			        try{
                		$select = $pdo->prepare("select * FROM vendor_contacts WHERE  display_order='1' AND vendor_id = ?");
                		$select->execute(array($vendor_id));
        			}catch(PDOException $e){
                		$this->setError($e->getMessage());
                		return false;
        			}
				$result = $select->fetch(PDO::FETCH_ASSOC);
                        	return $result;
		}
//function to add vendor -Rupali	
function getVendorContactDetail($id){
			 global $pdo;
			        try{
                		$select = $pdo->prepare("select * FROM vendor_contacts WHERE  id = ? ORDER BY display_order ASC");
                		$select->execute(array($id));
        			}catch(PDOException $e){
                		$this->setError($e->getMessage());
                		return false;
        			}
				$result = $select->fetch(PDO::FETCH_ASSOC);
                        	return $result;
		}
	//function to add vendor -Rupali	
function getAllVendorContacts($vendor_id){
		global $pdo;
		$per_page= $this->app_config['per_page'];
		$p = ($details['p']) ? $details['p'] : 1; 
		$offset = ($p - 1) * $per_page;
                     try{
                        $select = $pdo->prepare("SELECT *  FROM vendor_contacts WHERE vendor_id = ?");                	
                        $select->execute(array($vendor_id));
                        }catch(PDOException $e){
                		$this->setError($e->getMessage());
                		return false;
        			}
				//$result = $select->fetch(PDO::FETCH_ASSOC);
                        return $result= $select->fetchAll(PDO::FETCH_ASSOC);
		}

function getAllRequestLog($details){
	global $pdo;
	$per_page= $this->app_config['per_page'];
	$p = ($details['p']) ? $details['p'] : 1; 
	$offset = ($p - 1) * $per_page;
	try{
		$select = "SELECT report_requests.*,users.name as name,email FROM report_requests 
			LEFT JOIN users ON users.id = report_requests.user_id";
			//LIMIT $offset, $per_page"; 
		$res = $pdo->query($select);
		return $res->fetchAll(PDO::FETCH_ASSOC);
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
}

function searchRequestLog($details){
	global $pdo;
	$per_page= $this->app_config['per_page'];
	$p = ($details['p']) ? $details['p'] : 1; 
	$offset = ($p - 1) * $per_page;
$year=$details['year'];
$month=$details['month']; 
	//if($month=="" and $year!=""){
	//	$where = "WHERE requested YEAR(requested) = $year";
          // }
        //else if($month!="" and $year!=""){
                $where = "WHERE  YEAR(requested) = $year AND MONTH(requested) = $month"; 
	//}
	try{
		$select = "SELECT report_requests.*,users.name as name FROM report_requests 
			LEFT JOIN users ON users.id = report_requests.user_id $where
			LIMIT $offset, $per_page"; 
		$res = $pdo->query($select);
		return $res->fetchAll(PDO::FETCH_ASSOC);
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
}

function getLocationValidationParameters($location_id){
	global $pdo; 
	try{
		$select = $pdo->prepare("SELECT * FROM location_validation_parameters WHERE location_id = ?");
		$select->execute(array($location_id));
		$location_parameters = array();
		
		while( $row = $select->fetch(PDO::FETCH_ASSOC)){
			$location_parameters[$row['parameter_id']] = $row['location_id']; 
		}
		return $location_parameters;
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
}

function getAllLocationValidationParameters(){
	global $pdo; 
	try{
		$select = $pdo->prepare("SELECT * FROM location_validation_parameters");
		$select->execute();
		$location_parameters = array();
		
		while( $row = $select->fetch(PDO::FETCH_ASSOC)){
			$location_parameters[$row['location_id']][$row['parameter_id']] = $row['location_id']; 
		}
		return $location_parameters;
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
}

function removeLocationValidationParameters($location_id){
	global $pdo;
	try{
		$delete = $pdo->prepare("DELETE FROM location_validation_parameters WHERE location_id = ?");
		$delete->execute(array($location_id)); 
	}catch(PDOException $e)
	{
		$this->setError($e->getMessage());
		return false;
	}
	return true;
}

function addLocationValidationParameters($details){
	global $pdo;
	$location_id = $details['location_id']; 
	if($location_id){
		if($this->removeLocationValidationParameters($location_id)){
		$insert = "INSERT INTO location_validation_parameters (`location_id`,`parameter_id`) VALUES";
		foreach($details['location_parameters'] as $p_id => $p_val){
			$p_val = sanitizeInput($p_val);
			$insert .= "('$location_id', '$p_val'),";
			}
		### remove the last comma
		$insert = chop($insert, ",");
		try{
		$stmt = $pdo->prepare($insert);
		$stmt->execute();
		}catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
		}
	return true;
		}
	}
}

public function validateInstalledDevice($file_name,$codes,$content,$file_extension,$installed_codes_array){
	//return $this->isDevicePresent(substr($file_name, 0, 7));
	$ret_array['error']=0;
	$ret_array['voltage_data']='';
	$device_code=substr($file_name, 0, 7);
	if(!in_array($device_code,$installed_codes_array)){
		$ret_array['error']=1;
	}
	return $ret_array;
}
public function validateDevice($file_name,$codes,$content,$file_extension,$installed_codes_array){
	//return $this->isDevicePresent(substr($file_name, 0, 7));
	$ret_array['error']=0;
	$ret_array['voltage_data']='';
	$device_code=substr($file_name, 0, 7);
	if(!in_array($device_code,$codes)){
		$ret_array['error']=1;
	}
	return $ret_array;
}
// name changed from validateDateStr to validateFileName
public function validateFileName($file_name,$codes,$content,$file_extension,$installed_codes_array){
	$ret_array['error']=0;
	$ret_array['voltage_data']='';
	$record_date=rtrim(chunk_split((substr($file_name,7,6)), 2, '-'),'-');
	$reg_ex='/^[a-zA-Z]\d{14}$/';
	if (!preg_match($reg_ex, $file_name)) {
		$ret_array['error']=1;
	} else {
		if(!$this->validateDate('yy-mm-dd',$record_date)){
			$ret_array['error']=1;
		}
	}
	return $ret_array;
}
public function validateIsInSevenDays($file_name,$codes,$content,$file_extension,$installed_codes_array){
	$ret_array['error']=0;
	$ret_array['voltage_data']='';
	$record_date=rtrim(chunk_split((substr($file_name,7,6)), 2, '-'),'-');
	if(!$this->isFileInSevenDays($record_date)){
		$ret_array['error']=1;

	}
	return $ret_array;
}
public function validateSixtyReadings($file_name,$codes,$content,$file_extension,$installed_codes_array){
	return $this->hasSixtyReadings($file_name,$content);
}


public function getVoltagesToPublish($details,$flag){
	$from_date = date('Y-m-d H:i:s', get_strtotime($details['from_date'])); 
	$to_date = date('Y-m-d H:i:s', get_strtotime($details['to_date'])); 
	$select = "SELECT id,location_id,readings,hour_of_day,day FROM voltage_readings 
		WHERE date_format(concat(day,' ',concat(hour_of_day,':00:00')), '%Y-%m-%d %H:%i:%s') 
		BETWEEN ? AND ? AND location_id = ? AND published= ?";
	global $pdo;
	try{
	$stmt = $pdo->prepare($select);
	$stmt->execute(array($from_date, $to_date, $details['location_id'],$flag));
	}catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		$voltage_strings[] = $row;
	}
	return $voltage_strings;
}
//Upadte default config values ---Rupali
function updateDefaultConfig($details){
	global $pdo;
		
	try{
		$update = $pdo->prepare("UPDATE config SET `value` = ? WHERE `id` =? ");
		$update->execute(array($details['param'], $details['id']));
		$action_details=array();
		$action_details['table_name']='config';
		$action_details['row_id']=$details['id'];
		$action_details['operation']='Default configuration updated ';
		$this->createActionLog($action_details);
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
	
}
//function to add vendor -Rupali	
function getConfigID($param){
		global $pdo;
		$per_page= $this->app_config['per_page'];
		$p = ($details['p']) ? $details['p'] : 1; 


		$offset = ($p - 1) * $per_page;
                     try{
                        $select = $pdo->prepare("SELECT *  FROM config WHERE value = ?");                	
                        $select->execute(array($param));
                        }catch(PDOException $e){
                		$this->setError($e->getMessage());
                		return false;
        			}
				//$result = $select->fetch(PDO::FETCH_ASSOC);
                        return $result= $select->fetch(PDO::FETCH_ASSOC);
		}

//public function validateContentByLocation($file_name,$content,$length,$location_id,$allow_error){
public function validateContentByLocation($file,$content,$devices,$device_codes,$installed_codes_array,$allow_error){
	$file_name_array=explode(".",$file);
	$file_name=$file_name_array[0];
	$file_extension=$file_name_array[1];
	$validation_parameters=$this->getValidationParameters();
	$simple_validation_params=array();
	$compulsory_validation_params=array();
	$k=0;
	$l=0;
	$error=array();
	$ret_val=array();
	$ret_val['error']=0;
	$ret_val['voltage_reading']='';
	foreach($validation_parameters as $val){
		if($val['compulsory'] !=1){
			$simple_validation_params[$k]=$val;
			$k++;
		}else{
			$compulsory_validation_params[$l]=$val;
			$l++;
		}
	}

		foreach($compulsory_validation_params as $validation_parameter) {
			$method_name=$validation_parameter['method'];
			if($validation_parameter['return_error'] != $allow_error){
				$error=$this->$method_name($file_name,$device_codes,$content,$file_extension,$installed_codes_array);
				if($error['voltage_data']!=''){
					$ret_val['voltage_reading']=$error['voltage_data'];
				}
				if($error['error'] =='1')	
				{
					$ret_val['error']= $validation_parameter['return_error'];
					return $ret_val;
				}
			}
		}
		
		$device_code= substr($file_name, 0, 7);
		$device_details=$devices[$device_code];
		$location_id=$device_details['location_id'];
		$location_validation_parameters=$this->getLocationValidationParameters($location_id);	
		$loc_validation_param=array();
		$i=0;
		foreach($location_validation_parameters as $k => $v){
			$loc_validation_param[$i]=$k;
			$i++;
		}
		foreach($simple_validation_params as $validation_parameter) {
                  if(in_array($validation_parameter['id'], $loc_validation_param)){ 
			if($validation_parameter['return_error'] != $allow_error){
				$method_name=$validation_parameter['method'];
				$error=$this->$method_name($file_name,$device_codes,$content,$file_extension,$installed_codes_array);
				if($error['voltage_data']!=''){
					$ret_val['voltage_reading']=$error['voltage_data'];
				}
				if($error['error'] =='1')	
				{
					$ret_val['error']= $validation_parameter['return_error'];
					return $ret_val;
				}
			}
		  }
		}
	$ret_val['error']=0;
	$ret_val['voltage_reading']='';

	return $ret_val;
	
}
/*
	Copy of validateContentByLocation for speeding up the cron 
	By passing some data as arguments
*/
public function validateContentByLocationInCron($file,$content,$devices,$device_codes,$installed_codes_array,$validation_parameters, $all_location_validation_params, $allow_error){
	$file_name_array=explode(".",$file);
	$file_name=$file_name_array[0];
	$file_extension=$file_name_array[1];
	//$validation_parameters=$this->getValidationParameters(); // this call is not required now
	$simple_validation_params=array();
	$compulsory_validation_params=array();
	$k=0;
	$l=0;
	$error=array();
	$ret_val=array();
	$ret_val['error']=0;
	$ret_val['voltage_reading']='';
	foreach($validation_parameters as $val){
		if($val['compulsory'] !=1){
			$simple_validation_params[$k]=$val;
			$k++;
		}else{
			$compulsory_validation_params[$l]=$val;
			$l++;
		}
	}

		foreach($compulsory_validation_params as $validation_parameter) {
			$method_name=$validation_parameter['method'];
			if($validation_parameter['return_error'] != $allow_error){
				$error=$this->$method_name($file_name,$device_codes,$content,$file_extension,$installed_codes_array);
				if($error['voltage_data']!=''){
					$ret_val['voltage_reading']=$error['voltage_data'];
				}
				if($error['error'] =='1')	
				{
					$ret_val['error']= $validation_parameter['return_error'];
					return $ret_val;
				}
			}
		}
		
		$device_code= substr($file_name, 0, 7);
		$device_details=$devices[$device_code];
		$location_id=$device_details['location_id'];
		//$location_validation_parameters=$this->getLocationValidationParameters($location_id);	
		$location_validation_parameters=$all_location_validation_params[$location_id];	
		$loc_validation_param=array();
		$i=0;
		if(!empty($location_validation_parameters)){
		foreach($location_validation_parameters as $k => $v){
			$loc_validation_param[$i]=$k;
			$i++;
		}
		}
		foreach($simple_validation_params as $validation_parameter) {
                  if(in_array($validation_parameter['id'], $loc_validation_param)){ 
			if($validation_parameter['return_error'] != $allow_error){
				$method_name=$validation_parameter['method'];
				$error=$this->$method_name($file_name,$device_codes,$content,$file_extension,$installed_codes_array);

				if($error['voltage_data']!=''){
					$ret_val['voltage_reading']=$error['voltage_data'];
				}
				if($error['error'] =='1')	
				{
					$ret_val['error']= $validation_parameter['return_error'];
					return $ret_val;
				}
			}
		  }
		}
	$ret_val['error']=0;
	$ret_val['voltage_reading']='';

	return $ret_val;
	
}
	public function getDeviceDetialsByDevice($device_id){
	    $select="SELECT * FROM devices LEFT JOIN device_installations 
                ON devices.id=device_installations.device_id WHERE devices.device_id_string ='$device_id'";
	    global $pdo;
	    $stmt = $pdo->query($select);
		$devices = array();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row;
	}
	public function getDatafilesDetails($file_id){
	    $select="SELECT * FROM datafiles WHERE id =$file_id";
	    global $pdo;
	    $stmt = $pdo->query($select);
		$file_content = array();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row;
	}


public function unPublishVoltage($details){	
	global $pdo;
	$from_date =  date('Y-m-d %H:%i:%s', get_strtotime($details['from_date'])); 
	$to_date =  date('Y-m-d %H:%i:%s', get_strtotime($details['to_date'])); 
	 $delete = "DELETE FROM location_summary WHERE hour_of_day BETWEEN ? AND ? AND location_id = ?";
	try{
	$stmt = $pdo->prepare($delete);
	if($this->unPublishInterrupts($details)){
		$stmt->execute(array($from_date, $to_date, $details['location_id']));
	}
	}catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
}

function getActiveDeviceCount(){
	global $pdo;
        try{
                $select = "SELECT devices.id FROM devices 
                        LEFT JOIN device_status_values ON devices.status = device_status_values.id
			WHERE device_status_values.status='Active'";
		$result = $pdo->prepare($select); 
		$result->execute(); 
		$active_devices = $result->rowCount();
		return $active_devices;
        }
        catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
}

function getInactiveDeviceCount(){
	global $pdo;
        try{
                $select = "SELECT devices.id FROM devices 
                        LEFT JOIN device_status_values ON devices.status = device_status_values.id
			WHERE device_status_values.status='Inactive'";
		$result = $pdo->prepare($select); 
		$result->execute(); 
		$inactive_devices = $result->rowCount();
		return $inactive_devices;
        }
        catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
}

function getTestingDeviceCount(){
	global $pdo;
        try{
                $select = "SELECT id FROM device_installations WHERE status=0";
		$result = $pdo->prepare($select); 
		$result->execute(); 
		$testing_devices = $result->rowCount();
		return $testing_devices;
        }
        catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
}

function getDeployedDeviceCount(){
        global $pdo;
        try{
                $select = "SELECT id FROM device_installations WHERE status=1";
                $result = $pdo->prepare($select);
                $result->execute();
                $testing_devices = $result->rowCount();
                return $testing_devices;
        }
        catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
}


function getActiveUsersCount(){
	global $pdo;
        try{
                $select = "SELECT id FROM users WHERE status=1";
		$result = $pdo->prepare($select); 
		$result->execute(); 
		$active_users = $result->rowCount();
		return $active_users;
        }
        catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
}

function getInactiveUsersCount(){
	global $pdo;
        try{
                $select = "SELECT id FROM users WHERE status=0";
		$result = $pdo->prepare($select); 
		$result->execute(); 
		$inactive_users = $result->rowCount();
		return $inactive_users;
        }
        catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
}
/* functions copied from user class to admin starts */
public function addVoltageReadings($params){
   	 global $pdo;
	$error_code=$this->getErrorCodeIdFromErrStr('Duplicate File');
	$insert = "INSERT INTO voltage_readings 
        (location_id,device_id,sim_card_id,readings,hour_of_day,day)
        VALUES (?,?,?,?,?,?)";
	try{
		$insert = $pdo->prepare($insert);	
		foreach($params as $p_val){
		if(!($this->isVoltageForFilePresent($p_val['location_id'],$p_val['day'],$p_val['hour_of_day']))){
        	$insert->execute(array($p_val['location_id'], $p_val['device_id'],$p_val['sim_card_id'],$p_val['readings'], $p_val['hour_of_day'],$p_val['day']));
		$this->markProcessedById($p_val['id']);
		}else{
			$this->updateDataError($p_val['id'],$error_code);
		}
		}

	
	### remove the last comma
	//$insert = chop($insert, ",");
	//	$pdo->query($insert);
	   }	
	catch(PDOException $e){
                //$this->setError($e->getMessage());
                //return false;
		echo $e->getMessage();
		exit;
	}
	return true;
}

public function validateFileContent($file_name,$content){
	$record_date=rtrim(chunk_split((substr($file_name,7,6)), 2, '-'),'-');	
	if(!$this->isDevicePresent(substr($file_name, 0, 7))){
		return $this->getErrorCodeIdFromErrStr('Device does not exist');
	}
	else if(!$this->validateDate('yy-mm-dd',$record_date)){
		return $this->getErrorCodeIdFromErrStr('Invalid file name');
	}
	/*else if(!$this->isFileInSevenDays($record_date)){
		return $this->getErrorCodeIdFromErrStr('File older than allowed');
	}*/
	else if(!($this->hasSixtyReadings($file_name,$content))){
		return $this->getErrorCodeIdFromErrStr('Low data count');
	}

	return '0';

	
}
public function getUnPublishedVoltages(){
    $select = "SELECT voltage_readings.id as id, voltage_readings.location_id as location_id,
		readings,hour_of_day,day FROM voltage_readings 
		Left JOIN device_installations ON voltage_readings.device_id= device_installations.device_id
		WHERE (published =0 OR published is NULL) AND is_unpublished=0 AND voltage_readings.day < date(NOW())";
		//WHERE (published =0 OR published is NULL) AND device_installations.status=1";
    global $pdo;
    $stmt = $pdo->query($select);
	$voltage_strings = array();
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		$voltage_strings[] = $row;
	}
	return $voltage_strings;
}

public function getSummaryParametes(){
    $select = "SELECT * FROM summary_parameters";
    global $pdo;
    $stmt = $pdo->query($select);
	$params = array();
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		$params[$row['param']] = $row['id'];
	}
	return $params;
}

public function generateVoltageSummary($voltage_string,$day,$hr,$location){
	$voltage_data=explode(",",$voltage_string);
	//$voltage_ranges=$this->getVoltageRangesData();
	$voltage_ranges=$this->getVoltageRangesDataBylocation($location);

	$summary_params=$this->getSummaryParametes();
	$voltage_minutes_cnt=array();
	$voltage_minutes_cnt[$summary_params['no_data']]=0;
	$voltage_minutes_cnt[$summary_params['no_supply']]=0;
//	$voltage_minutes_cnt[$summary_params['very_low']]=0;
	$voltage_minutes_cnt[$summary_params['low']]=0;
	$voltage_minutes_cnt[$summary_params['normal']]=0;
	$voltage_minutes_cnt[$summary_params['high']]=0;
//	$voltage_minutes_cnt['hour_of_day']=$hour_of_day;
//	$voltage_minutes_cnt['location_id']=$location_id;

/***

In order to mainten interupts data we have to add interrupts tables with columns
id,location,down_date_time,up_date_time

create an array 
interrupts[0]['down']='';
interrupts[0]['nn']='';
$j=0;
Then check is interrupt occurs using $prev variable determind whether it low going or high going
if down going
	interrupts[$j]['down']=calculate date time using other details like date(date can be calculated usgin filename
        and index of voltage reading;
else if up going
	interrupts[$j]['up']=calculate date time using other details like date(date can be calculated usgin filename
        and index of voltage reading;
	$j++;
end if
   




***/
	$prev='';
	$i=0;
	$j=0;
	$data_cnt=0;
	$return_data=array();
	$interrupts=array();
     foreach($voltage_data as $voltage){
	if($data_cnt > 0 && $data_cnt< 59){
		if($voltage_data[$data_cnt] ==0){
			if($voltage_data[$data_cnt + 1] !=0 && $voltage_data[$data_cnt -1] !=0){
				$voltage=$voltage_data[$data_cnt -1];
			}
		}
	}
	$event_date=$day." ".$hr.":".$i.":00";
         if($voltage ==''|| $voltage ==' '){
		if($prev =='no_supply'){
			$interrupts[$j]['location']=$location;
			$interrupts[$j]['up']=$event_date;
			$j++;
		}
		$voltage_minutes_cnt[$summary_params['no_data']]=$voltage_minutes_cnt[$summary_params['no_data']]+1;
		$prev='no_data';
	}
	else if($voltage >= $voltage_ranges['no_supply']['low_value'] && $voltage <= $voltage_ranges['no_supply']['high_value']){
		if($prev !='no_supply'){
			$interrupts[$j]['location']=$location;
			$interrupts[$j]['down']=$event_date;
			$interrupts[$j]['up']='';
		}
		$voltage_minutes_cnt[$summary_params['no_supply']]=$voltage_minutes_cnt[$summary_params['no_supply']]+1;
		$prev='no_supply';
	}	
	/*else if($voltage >= $voltage_ranges['very_low']['low_value'] && $voltage < $voltage_ranges['very_low']['high_value']){
		$voltage_minutes_cnt[$summary_params['very_low']]=$voltage_minutes_cnt[$summary_params['very_low']]+1;
		if($prev =='no_data' || $prev =='no_supply'){
			$interrupts[$j]['location']=$location;
			$interrupts[$j]['up']=$event_date;
			$j++;
		}
		$prev='very_low';
		
	}
	*/	
	else if($voltage >= $voltage_ranges['low']['low_value'] && $voltage <= $voltage_ranges['low']['high_value']){
		$voltage_minutes_cnt[$summary_params['low']]=$voltage_minutes_cnt[$summary_params['low']]+1;
		if($prev =='no_supply'){
			$interrupts[$j]['location']=$location;
			$interrupts[$j]['up']=$event_date;
			$j++;
		}
		$prev='low';
	}	
	else if($voltage >= $voltage_ranges['normal']['low_value'] && $voltage <= $voltage_ranges['normal']['high_value']){
		$voltage_minutes_cnt[$summary_params['normal']]=$voltage_minutes_cnt[$summary_params['normal']]+1;
		if( $prev =='no_supply'){
			$interrupts[$j]['location']=$location;
			$interrupts[$j]['up']=$event_date;
			$j++;
		}
		$prev='normal';
	}	
	else if($voltage >= $voltage_ranges['high']['low_value'] && $voltage <= $voltage_ranges['high']['high_value']){
		$voltage_minutes_cnt[$summary_params['high']]=$voltage_minutes_cnt[$summary_params['high']]+1;
		if( $prev =='no_supply'){
			$interrupts[$j]['location']=$location;
			$interrupts[$j]['up']=$event_date;
			$j++;
		}
		$prev='high';
	}	
	$i++;
	$data_cnt++;
     }
	$return_data['data']=$voltage_minutes_cnt;
	$return_data['interrupt']=$interrupts;
return $return_data;
	
}
public function addSummaryData($params){
    global $pdo;
	$insert = "INSERT INTO location_summary 
        (location_id,hour_of_day,param_id,val)
        VALUES (?,?,?,?)";
	$insert=$pdo->prepare($insert);
	foreach($params as $p_val){
		foreach($p_val[0] as $k => $v){
		 $location= $p_val[1]['location_id'];
		 $hr_of_day= $p_val[1]['hour_of_day'];
	    	    //$insert .= "('$location', '$hr_of_day','$k','$v'),";
			try{
			      $insert ->execute(array($location, $hr_of_day,$k,$v));
			}
			catch(PDOException $e){
			echo $e->getMessage();
			//exit;
		}
		}
	}
	### remove the last comma
//	$insert = chop($insert, ",");
	//	$pdo->query($insert);
	return true;
}
// addDataFiles 
public function addDataFiles($params){
    	global $pdo;
	$insert = "INSERT INTO datafiles 
        (filename,content, imported,is_processed,error_code,event_date)
        VALUES (?,?,NOW(),?,NULL,?)";
	try{
	$insert =$pdo->prepare($insert);
	foreach($params as $p_val){
        //$insert .= "('$p_val[filename]', '$p_val[content]',NOW(),$p_val[is_processed], $p_val[errorcode],'$p_val[event_date]'),";
        $insert ->execute(array($p_val['filename'], $p_val['content'],$p_val['is_processed'], $p_val['event_date']));
	}
	### remove the last comma
//	$insert = chop($insert, ",");
//		$pdo->query($insert);
	}
	catch(PDOException $e){
		echo $e->getMessage();
		exit;
	}
}
// get error code id from err_str 
public function getErrorCodeIdFromErrStr($err_str){
    global $pdo;
	$select = $pdo->prepare("SELECT id FROM error_codes WHERE err_str = ?");

	$select->execute(array($err_str));
	$row = $select->fetch(PDO::FETCH_ASSOC);
	return $row['id'];
}

// get valid Data files 
public function getDataFiles(){
    $select = "SELECT DISTINCT(filename) as filename,id,content,imported,is_processed,error_code,
			event_date,is_event_processed  FROM datafiles WHERE is_processed = 0";
    global $pdo;
    $stmt = $pdo->query($select);
	$data_files = array();
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		$data_files[] = $row;
	}
	return $data_files;
}

public function hasSixtyReadings($file, $content){
	$datalength=13; // length of voltage reading
	$ret_array['error']=0;
	$ret_array['voltage_data']='';
//	$file=explode(".",$file_name);
	if($content !='' && $content !=' '){
		$data=explode(",", $content);
		$valid_data_cnt=0;
		for ($i=4; $i<64; $i++){
			$valid_data_cnt ++;
		}
		if($valid_data_cnt < 60){
			$ret_array['error']=1;
		}
	}else{
			$ret_array['error']=1;

	}
	return $ret_array;
}

public function validateDate($format,$date){
	if($format =='yy-mm-dd'){
		$date_regex = '/^\d\d[\-\/.](0[1-9]|1[012])[\-\/.](0[1-9]|[12][0-9]|3[01])$/';
	}
	else if($format =='dd-mm-yy'){
		$date_regex = '/^(0[1-9]|[12][0-9]|3[01])[\-\/.](0[1-9]|1[012])[\-\/.]\d\d$/';
	}
	if (!preg_match($date_regex, $date)) {
		return false;
	} else {
		return true;
	}

}

public function isFileInSevenDays($date){
	$cur=date("y-m-d");
	$datetime1 = DateTime::createFromFormat('y-m-d',$cur);
	$datetime2 = DateTime::createFromFormat('y-m-d', $date);
	$interval = $datetime1->diff($datetime2);
	$days=$interval->format('%a');
	if($days >7){
		return false;
	}else{
		return true;
	}

}

public function updateDataError($id,$error){
	global $pdo;
	try{
	$update = $pdo->prepare("UPDATE datafiles
	SET `error_code` = ?, `is_processed` =1
	WHERE id = ?");
	$update->execute(array($error, $id));
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
}
public function markProcessed($ids){
	global $pdo;
	try{
		$update = $pdo->prepare("UPDATE datafiles SET is_processed = 1 , error_code =NULL WHERE id IN ($ids)");
		$update->execute();
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
}
	function markVoltagePublished($ids){
		global $pdo;
	try{
		$update = $pdo->prepare("UPDATE voltage_readings SET `published` = 1, is_unpublished=0 WHERE id IN ($ids)");
		$update->execute();
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
	}

function getAllDataFilesCount($date){
        global $pdo;
        try{
                $select = "SELECT id FROM datafiles WHERE DATE(imported) ='$date' AND (DATE(event_date) >= DATE_SUB( '$date', INTERVAL 7 DAY)  				AND  DATE(event_date) <= '$date' )";
                $result = $pdo->prepare($select);
                $result->execute();
                $all_files = $result->rowCount();
                return $all_files;
        }
        catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
}

function getTodaysDataFilesCount($date){
        global $pdo;
        try{
                $select = "SELECT id FROM datafiles WHERE DATE(imported) ='$date' AND DATE(event_date) = '$date'";
                $result = $pdo->prepare($select);
                $result->execute();
                $todays_files = $result->rowCount();
                return $todays_files;
        }
        catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
}

function getAllProcessedFilesCount($date){
        global $pdo;
        try{
                $select = "SELECT id FROM datafiles WHERE is_processed=1 AND DATE(imported) ='$date' 
			AND (DATE(event_date) >= DATE_SUB( '$date', INTERVAL 7 DAY)  AND  DATE(event_date) <= '$date' )";
                $result = $pdo->prepare($select);
                $result->execute();
                $all_processed_files = $result->rowCount();
                return $all_processed_files;
        }
        catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
}

function getTodaysProcessedFilesCount($date){
        global $pdo;
        try{
                $select = "SELECT id FROM datafiles WHERE is_processed=1 AND  DATE(imported) ='$date' AND DATE(event_date)= '$date'";
                $result = $pdo->prepare($select);
                $result->execute();
                $todays_processed_files = $result->rowCount();
                return $todays_processed_files;
        }
        catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
}

function getTodaysInvalidFilesCount($date){
        global $pdo;
        try{
                $select = "SELECT id FROM datafiles WHERE error_code IS NOT NULL AND DATE(imported)= '$date' AND DATE(event_date)= '$date'";
                $result = $pdo->prepare($select);
                $result->execute();
                $todays_invalid_files = $result->rowCount();
                return $todays_invalid_files;
        }
        catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
}


function getAllInvalidFilesCount($date){
        global $pdo;
        try{
                $select = "SELECT id FROM datafiles WHERE error_code IS NOT NULL AND DATE(imported)='$date'
			 AND (DATE(event_date) >= DATE_SUB('$date', INTERVAL 7 DAY) AND DATE(event_date) <= '$date' )";
                $result = $pdo->prepare($select);
                $result->execute();
                $all_invalid_files = $result->rowCount();
                return $all_invalid_files;
        }
        catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
}

function getAllUserAddedCount($date){
        global $pdo;
        try{
                $select = "SELECT id FROM users WHERE DATE(created) >= DATE_SUB( '$date', INTERVAL 7 DAY) AND DATE(created) <='$date'";
                $result = $pdo->prepare($select);
                $result->execute();
                $all_users_added = $result->rowCount();
                return $all_users_added;
        }
        catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
}

function getTodaysUserCount($date){
        global $pdo;
        try{
                $select = "SELECT id FROM users WHERE DATE(created) = '$date'";
                $result = $pdo->prepare($select);
                $result->execute();
                $todays_users = $result->rowCount();
                return $todays_users;
        }
        catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
}

function getAllUnprocessedRequestsCount($date){
        global $pdo;
        try{
                $select = "SELECT id FROM report_requests WHERE is_processed=0 
			AND DATE(requested) >= DATE_SUB( '$date', INTERVAL 7 DAY) AND DATE(requested) <='$date'" ;
                $result = $pdo->prepare($select);
                $result->execute();
                $all_unprocessed_requests = $result->rowCount();
                return $all_unprocessed_requests;
        }
        catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
}


function getTodaysUnprocessedRequestsCount($date){
        global $pdo;
        try{
                $select = "SELECT id FROM report_requests WHERE DATE(requested) = '$date' AND is_processed=0";
                $result = $pdo->prepare($select);
                $result->execute();
                $todays_unprocessed_requests = $result->rowCount();
                return $todays_unprocessed_requests;
        }
        catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
}

function getRecivedFileByToday($date,$devices){
        global $pdo;
	$device_codes="";
	foreach($devices as $d){
		$device_codes .="'".$d['device_id_string']."',";
	}
	$device_codes=rtrim($device_codes,',');
	//$codes = array_column($devices, 'device_id_string');
	//$device_codes = "'".join("','",$codes)."'";
	$device_files_array=array();
	$ret_device_codes=array();
        try{
		$select= "SELECT COUNT(id) AS cnt,LEFT(filename, 7) as file FROM datafiles WHERE LEFT(filename, 7) IN ($device_codes)
			AND DATE(imported)='$date' AND DATE(event_date)='$date'  group by file";
                $result = $pdo->prepare($select);
                $result->execute();
		$params = array();
                while($row = $result->fetch(PDO::FETCH_ASSOC)){
                        $params[$row['file']] = $row['cnt'];
			$ret_device_codes[]=$row['file'];
                }
		foreach($codes as $code){
			if(in_array($code,$ret_device_codes)){
				$device_files_array[$code]=$params[$code];
			}else{
				$device_files_array[$code]=0;
			}		
		}
        }
        catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
	return $device_files_array;
}

function getReceivedFilesByExtendedDate($date,$devices){
        global $pdo;
	$device_codes="";
	foreach($devices as $d){
		$device_codes .="'".$d['device_id_string']."',";
	}
	$device_codes=rtrim($device_codes,',');
	//$codes = array_column($devices, 'device_id_string');
	//$device_codes = "'".join("','",$codes)."'";
        try{
	$select= "SELECT COUNT(id) AS cnt,LEFT(filename, 7) as file,DATE(event_date) As date 
			FROM datafiles WHERE LEFT(filename, 7) IN ($device_codes) AND DATE(imported)='$date' 
			AND (DATE(event_date) >= DATE_SUB( '$date', INTERVAL 7 DAY)  AND  DATE(event_date) <= '$date' )  group by file, date";
                $result = $pdo->prepare($select);
                $result->execute();
		$files = array();
		return $result->fetchAll(PDO::FETCH_ASSOC);
                while($row = $result->fetch(PDO::FETCH_ASSOC)){
                        $files[] = $row;
                }
        }
        catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
        return $files;
}


public function getErrorCodesCountByToday($date,$errors){
$error_codes="";
foreach($errors as $err){
$error_codes .="'".$err['error_code']."',";
	
}
$codes=array();
$error_codes=rtrim($error_codes,',');
foreach($errors as $err){
	array_push($codes,$err['error_code']);
}
//	$codes = array_column($errors, 'error_code');
  //      $error_codes = "'".join("','",$codes)."'";

        $error_files_array=array();
        $ret_error_codes=array();
        try{
                $select= "SELECT COUNT(datafiles.id) AS cnt,LEFT(filename, 7) as file, error_codes.error_code AS error_code_string
			FROM datafiles LEFT JOIN error_codes ON datafiles.error_code= error_codes.id 
			WHERE error_codes.error_code IN ($error_codes) AND DATE(imported)='$date' 
			AND (DATE(event_date) >= DATE_SUB( '$date', INTERVAL 7 DAY)  
			AND  DATE(event_date) <= '$date')  group by file";
		global $pdo;
                $result = $pdo->prepare($select);
                $result->execute();
                $params = array();
                while($row = $result->fetch(PDO::FETCH_ASSOC)){
                        $params[$row['error_code_string']] = $row['cnt'];
                        $ret_error_codes[]=$row['error_code_string'];
                }
                foreach($codes as $code){
                        if(in_array($code,$ret_error_codes)){
                                $error_files_array[$code]=$params[$code];
                        }else{
                                $error_files_array[$code]=0;
                        }
                }
        }
        catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
        return $error_files_array;
}

// wrapper function for all progess log functions

function getDailyProgressLog($date){
	$devices=$this->getAllDevices();
	$all_files= $this->getAllDataFilesCount($date);
	$todays_files = $this->getTodaysDataFilesCount($date);
	$all_processed_files = $this->getAllProcessedFilesCount($date);
	$processed_taday = $this->getTodaysProcessedFilesCount($date);
	$todays_invalid_files = $this->getTodaysInvalidFilesCount($date);
	$all_invalid_files = $this->getAllInvalidFilesCount($date);
	$todays_users=0;
	$todays_u = $this->getTodaysUserCount($date);
	if($todays_u>0){
		$todays_users=$todays_u;
	}
	$all_users=0;
	$all_u = $this->getAllUserAddedCount($date);
	if($all_u >0){
		$all_users=$all_u;
	}
	$todays_unprocessed_requests=0;
	$todays_unprocessed_req = $this->getTodaysUnprocessedRequestsCount($date);
	if($todays_unprocessed_req > 0){
		$todays_unprocessed_requests=$todays_unprocessed_req;
	}
	$all_unprocessed_requests=0;
	$all_unprocessed_req = $this->getAllUnprocessedRequestsCount($date);
	if($all_unprocessed_req > 0){
		$all_unprocessed_requests=$all_unprocessed_req;
	}
	$all_files_received = $this->getReceivedFilesByExtendedDate($date, $devices);
	$todays_files_received = $this->getRecivedFileByToday($date,$devices);
	$file_not_recieved_today=0;
        $todays_device_not_responding=0;
	foreach($todays_files_received as $k => $v){
       	if($v ==0){
               $todays_device_not_responding ++;
               $file_not_recieved_today =$file_not_recieved_today+24;

       	}else{
               $file_not_recieved_today =$file_not_recieved_today+(24-$v);

       	}
      	}

	foreach($all_files_received as $files_not){
		$date= $files_not['date'];
		$file= $files_not['file'];	
		$new_file[$date][$file]	= $files_not['cnt']; 
	}
	/*$new_file['2014-11-19']['00E21'] = 15;
	$new_file['2014-11-20']['00E22'] = 10;
	$new_file['2014-11-21']['00E23'] = 12;*/
	 
	$dates = array_keys($new_file);
	$all_devices_not_reporting =0;	
	 $all_files_not_received=0;	
	$device_cnt_array=array();
	foreach($dates as $date){
		foreach($new_file[$date] as $key => $value){
	   	if($value ==0){
			
			$all_devices_not_reporting ++;
			if(!in_array($key,$device_cnt_array)){
				array_push($device_cnt_array,$key);
			}
      			$all_files_not_received = abs($all_files_not_received + 24); 
		}else{
         	 	$all_files_not_received = abs($all_files_not_received + (24 - $value));
        	}
		}
	}

	$all_devices_not_reporting=count($device_cnt_array);

 	
	$daily_progress = array('all_files'=>$all_files, 'today_files'=>$todays_files, 'all_processed_files'=>$all_processed_files,
		  'processed_today'=>$processed_taday, 'todays_invalid_files'=>$todays_invalid_files, 'all_invalid_files'=>$all_invalid_files,
		  'todays_users'=>$todays_users, 'all_users'=>$all_users, 'todays_unprocessed_requests'=>$todays_unprocessed_requests,
		  'all_unprocessed_requests'=>$all_unprocessed_requests, 'all_files_not_received'=>$all_files_not_received,
		  'todays_files_not_received'=>$file_not_recieved_today,  'all_devices_not_reporting'=>$all_devices_not_reporting,
		  'todays_devices_not_reporting'=>$todays_device_not_responding);
	
	return $daily_progress;

}
// wrapper function for all progress log functions end here

function getErrorCodesFromFiles(){
	global $pdo;
        try{
		$select =$pdo->prepare(" SELECT DISTINCT error_code AS error_code_id, error_codes.error_code AS error_code_string 
					FROM datafiles LEFT JOIN error_codes ON error_code.id= datafiles.error_code ");
        	$select->execute();
        }
        catch(PDOException $e){
                 $this->setError($e->getMessage());
                 return false;
        }
                 $result = $select->fetch(PDO::FETCH_ASSOC);
                 return $result;

}


/* functions copied from user class to admin -starts */

function addPublishUnpublish($data){
	global $pdo;
	try{
		$from_date =  date('Y-m-d %H:%i:%s', get_strtotime($data['from_date'])); 
		$to_date =  date('Y-m-d %H:%i:%s', get_strtotime($data['to_date'])); 
	//	$from_date =  date('Y-m-d', get_strtotime($data['from_date'])); 
	//	$to_date =  date('Y-m-d', get_strtotime($data['to_date'])); 
		$insert = $pdo->prepare("INSERT INTO publish_unpublish_log(`location_id`,`from_date`,`to_date`,`type`,`created`,`reason`,`created_by`)
				VALUE (?, ?, ?, ?, NOW(),?,?)");
		$insert_args = array($data['location_id'], $from_date, $to_date, $data['is_publish'],$data['reason'],$this->user_id); 
		$insert->execute($insert_args);
		$row_id = $pdo->lastInsertId('id');
		$action_details=array();
		$action_details['table_name']='publish_unpublish_log';
		$action_details['row_id']=$row_id;
		$action_details['operation']='Data published or unpublished';
		$this->createActionLog($action_details);
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
}

function updatePublishUnpublish($data){
	global $pdo;
	try{
		$update = $pdo->prepare("UPDATE publish_unpublish_log SET is_processed = ? WHERE id = ?");
		$update->execute(array($data['is_processed'], $data['log_id']));
	}catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
}

function getPublishUnpublishLog($details){
	global $pdo;
	$per_page = empty($details['limit']) ? $this->app_config['per_page'] : $details['limit'];
	$p = $details['p'] ? $details['p'] : 1; 
	$offset = ($p - 1) * $per_page;
	try{
	$select = $pdo->prepare("SELECT publish_unpublish_log.*,locations.id AS location_id, 
			locations.name FROM publish_unpublish_log 
			LEFT JOIN locations ON locations.id = publish_unpublish_log.location_id
			WHERE publish_unpublish_log.is_processed IS NULL OR publish_unpublish_log.is_processed = 0
			ORDER BY publish_unpublish_log.id DESC LIMIT $offset, $per_page");
	$select->execute();
	return $select->fetchAll(PDO::FETCH_ASSOC);
	}catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
}
//To get voltage range added by ====Rupali
	public function getVoltageAvgRanges(){
	    $select="SELECT * FROM average_voltage_settings";
	    global $pdo;
	    $stmt = $pdo->query($select);
		$params = array();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$params[] = $row;
		}
		return $params;


	}
//To get voltage range added by ====Rupali
	public function getVoltageAvgRange($id){
	     global $pdo;
	
		 try{
                 $select = $pdo->prepare("SELECT * FROM average_voltage_settings WHERE id = ?");
                 $select->execute(array($id));
        }
	catch(PDOException $e){
                 $this->setError($e->getMessage());
                 return false;
        }
                 $result = $select->fetch(PDO::FETCH_ASSOC);
                 return $result;

	}
//To add voltage range added by ====Rupali
	function addVoltageAvgRange($data){
		global $pdo;
	try{
		$insert = $pdo->prepare("INSERT INTO average_voltage_settings (`title`,`low_limit`,`high_limit`, `display_color`,`second_low_limit`,`second_high_limit`, `second_display_color`,`third_low_limit`,`third_high_limit`, `third_display_color`) VALUES( ?,?,?,?,?,?,?,?,?,?)");
		$insert->execute(array($data['title'],$data['low_limit'],$data['high_limit'],$data['display_color'],$data['second_low_limit'],$data['second_high_limit'],$data['second_display_color'],$data['third_low_limit'],$data['third_high_limit'],$data['third_display_color']));
		$voltage_range_id=$pdo->lastInsertId();
		$action_details=array();
		$action_details['table_name']='average_voltage_settings';
		$action_details['row_id']=$voltage_range_id;
		$action_details['operation']='New average voltage range added';
		$this->createActionLog($action_details);

	}catch(PDOException $e){
		if ($e->errorInfo[1] == 1062) {
                $this->setError("Duplicate entry for display color. You can not add same color for two parameters.");
                } else {
                $this->setError($e->getMessage());
                }
                return false;

	}
	return true;
	}
//To delete voltage range added by ====Rupali	
	function deleteAvgVoltageRange($voltage_avg_range_id){
		global $pdo;
	try{
		$delete = $pdo->prepare("DELETE FROM average_voltage_settings WHERE id = ?");
		$delete->execute(array($voltage_avg_range_id));
		$action_details=array();
		$action_details['table_name']='average_voltage_settings';
		$action_details['row_id']=$voltage_avg_range_id;
		$action_details['operation']='Avarage voltage range deleted';
		$this->createActionLog($action_details);
	}catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;

	}
	return true;
	}
	
//To Update voltage range added by ====Rupali		
	function updateVoltageAvgRange($data){
		global $pdo;
	try{
		
	       $update = $pdo->prepare("UPDATE average_voltage_settings SET `title`= ?, `low_limit` = ?, `high_limit`=?, `display_color` = ? , `second_low_limit` = ?, `second_high_limit`=?, `second_display_color` = ?,`third_low_limit` = ?, `third_high_limit`=?, `third_display_color` = ? 
					WHERE id = ?");
		$update->execute(array($data['title'],$data['low_limit'], $data['high_limit'], $data['display_color'],$data['second_low_limit'], $data['second_high_limit'], $data['second_display_color'],$data['third_low_limit'], $data['third_high_limit'], $data['third_display_color'], $data['avg_id']));
		
		$action_details=array();
		$action_details['table_name']='average_voltage_settings';
		$action_details['row_id']=$data['avg_id'];
		$action_details['operation']='Average voltage range updated';
		$this->createActionLog($action_details);
		
	}catch(PDOException $e){
		if ($e->errorInfo[1] == 1062) {
                $this->setError("Duplicate entry for display color. You can not add same color for two parameters.");
                } else {
                $this->setError($e->getMessage());
                }
                return false;
	}
	return true;
	}
//add location ranges=Rupali
function addlocation_voltage_parameter($data){
	global $pdo;
	try{
			
		if($this->removeLocationVoltageParameters($data['location_id'])){
			$insert = $pdo->prepare("INSERT INTO location_voltage_parameters(`location_id`,`voltage_range_id`,`voltage_average_id`)
				VALUE (?, ?, ?)");
			$insert_args = array($data['location_id'],$data['voltage_range_id'],$data['avg_voltage_id']); 
			$insert->execute($insert_args);
		}
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
}
//Get Unprocessed Events=Rupali
function unprocessed_events($data){
   global $pdo;
        try{
                $select = "SELECT id FROM datafiles WHERE error_code IS NOT NULL";
                $result = $pdo->prepare($select);
                $result->execute();
                $all_files = $result->rowCount();
                return $all_files;
        }
        catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
}

//get voltage parametes and values by location
public function getVoltageRangesDataBylocation($location_id){
    $select = "SELECT * FROM voltage_parameters 
		LEFT JOIN voltage_parameters_values ON 
		voltage_parameters.id=voltage_parameters_values.parameter_id
		WHERE voltage_range_id=(
		SELECT voltage_range_id FROM location_voltage_parameters WHERE location_id=$location_id)";
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

// Add Interrupts 
public function addIntrrupts($params){
    global $pdo;
	$insert = "INSERT INTO interrupts 
        (location_id,down_date,up_date)
        VALUES (?,?,?)";
	$insert=$pdo->prepare($insert);
	foreach($params as $value){
		foreach($value as $val){
		try{
		    $insert->execute(array($val['location'],$val['down'],$val['up']));
		}
		catch(PDOException $e){
			echo $e->getMessage();
		//exit;
		}
		}
	    	   // $insert .= "(".$val[0]['location'].",'".$val[0]['down']."','".$val[0]['up']."'),";
	}

	### remove the last comma
	//$insert = chop($insert, ",");
	//	$pdo->query($insert);
	return true;
}
function removeLocationVoltageParameters($location_id){
	global $pdo;
	try{
		$delete = $pdo->prepare("DELETE FROM location_voltage_parameters WHERE location_id = ?");
		$delete->execute(array($location_id)); 
	}catch(PDOException $e)
	{
		$this->setError($e->getMessage());
		return false;
	}
	return true;
}


public function unPublishInterrupts($details){	
	global $pdo;
	$from_date =  date('Y-m-d %H:%i:%s', get_strtotime($details['from_date'])); 
	$to_date =  date('Y-m-d %H:%i:%s', get_strtotime($details['to_date'])); 
	//$from_date =  date('Y-m-d', get_strtotime($details['from_date'])); 
	//$to_date =  date('Y-m-d', get_strtotime($details['to_date'])); 
	$delete = "DELETE FROM interrupts WHERE down_date BETWEEN ? AND ? AND location_id = ?";
	try{
	$stmt = $pdo->prepare($delete);
	$stmt->execute(array($from_date, $to_date, $details['location_id']));
	}catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
}
	//function to add vendor -Rupali	
function getlocation_voltage_parameters($location_id){
		global $pdo;
		$per_page= $this->app_config['per_page'];
		$p = ($details['p']) ? $details['p'] : 1; 
		$offset = ($p - 1) * $per_page;
                     try{
                        $select = $pdo->prepare("SELECT *  FROM location_voltage_parameters WHERE location_id = ?");                	
                        $select->execute(array($location_id));
                        }catch(PDOException $e){
                		$this->setError($e->getMessage());
                		return false;
        			}
			return	$result = $select->fetch(PDO::FETCH_ASSOC);
                        ///return $result= $select->fetchAll(PDO::FETCH_ASSOC);
		}
public function isFilePresent($file_name,$error_code){
        global $pdo;
        //$select=$pdo->prepare("SELECT 1 FROM datafiles WHERE filename =? AND error_code !=? AND is_processed=1");
        $select=$pdo->prepare("SELECT 1 FROM datafiles WHERE filename =? AND error_code !=? ");
        $select->execute(array($file_name,$error_code));
        if($select->fetchColumn() > 1){
                return true;
        }else{
                return false;
        }
}
public function validateIsDuplicateFile($file_name,$codes,$content,$file_extension,$installed_codes_array){
	$ret_array['error']=0;
	$ret_array['voltage_data']='';
	$file_name=$file_name.".".$file_extension;
	$error_code=$this->getErrorCodeIdFromErrStr('Timestamp exceeds limit');
	if($this->isFilePresent($file_name,$error_code)){
		$ret_array['error']=1;
	}
	return $ret_array;
}
public function validateFileSize($file_name,$codes,$content,$file_extension){
	return true;
}
public function validateReadingFormat($file_name,$codes,$content,$file_extension,$installed_codes_array){
	$validator=substr($file_name, 7);
	if($content !=''){
		$data=explode(",", $content);
		$datalength=13; // lenght of voltage readings 
		$valid_data_cnt=0;
		$ret_array['error']=0;
		$ret_array['voltage_data']='';
		for ($i=4; $i<count($data); $i++){
			$reading_validator = substr($data[$i], 0, 8);
			if($validator == $reading_validator && strlen($data[$i])== $datalength)
				$valid_data_cnt ++;
			else
				break;
		}
		if($valid_data_cnt < 60){
			$ret_array['error']=1;
		}
	}else{
		$ret_array['error']=1;

	}
	 	return $ret_array;
}
public function validateOutlierVoltage($file_name,$codes,$content,$file_extension,$installed_codes_array){
	$voltage_readings='';
	$ret_array=array();
	$ret_array['error']=0;
	$ret_array['voltage_data']='';
	if($content !=''){
	$data=explode(",", $content);
	for ($i=4; $i<64; $i++){
		$voltage=substr($data[$i],10,3);
		if($voltage > 400 && $voltage != 535){
			$ret_array['error']=1;
			break;
			//return false;
		}/*else{
			if($voltage < 110){
				$voltage=0;
			}
			if($voltage >350){
				$voltage=350;
			}
		}*/
		$voltage_readings.=$voltage.',';
	 }
		$ret_array['voltage_data']=rtrim($voltage_readings,',');
	}else{
		$ret_array['error']=1;
	}
	return $ret_array;
}
public function validateOutliertime($file_name,$codes,$content,$file_extension,$installed_codes_array){
	$record_date=rtrim(chunk_split((substr($file_name,7,6)), 2, '-'),'-');
	$hr_of_day=substr($file_name,13,2);
	$data_time=$record_date." ".$hr_of_day.":00:00";
	$file_date=strtotime($record_date);
	$ret_array['error']=0;
	$ret_array['voltage_data']='';
	$current_time=strtotime("now");
	if($file_date > $current_time){
		$ret_array['error']=1;
	}
	return $ret_array;
}
public function validateFileFormat($file_name,$codes,$content,$file_extension,$installed_codes_array){
	$allowed_files= array("txt","TXT","csv","CSV");
	$ret_array['error']=0;
	$ret_array['voltage_data']='';
	if(!(in_array($file_extension,$allowed_files))){
		$ret_array['error']=1;
	}
	
	return $ret_array;
}
/* Added By Rupali */
function getRemarks($table_name,$param_id){
	
  $select="SELECT * FROM remarks WHERE table_name = '$table_name' AND param_id = '$param_id'";
	    global $pdo;
	    $remark = $pdo->query($select);
		$remarks = array();
		while($row = $remark->fetch(PDO::FETCH_ASSOC)){
			$remarks[] = $row;
		}
		return $remarks;

	}
/* Added By Rupali */
function getVersionHistory($id){
	
 $select="SELECT * FROM version_logs WHERE device_id ='$id'";
	    global $pdo;
	    $remark = $pdo->query($select);
		$remarks = array();
		while($row = $remark->fetch(PDO::FETCH_ASSOC)){
			$remarks[] = $row;
		}
		return $remarks;

	}
/* Added By Rupali */
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
/* Added By Rupali */
 function getUserDetailsLocationOwner($user_id){
	
		$select = "SELECT * from location_owner_locations WHERE user_id = ?";
		
        global $pdo;
		$stmt = $pdo->prepare($select);
		$stmt->execute(array($user_id));
		$result_array = $stmt->fetch(PDO::FETCH_ASSOC);
		return $result_array;
	}
function addRemarks($id,$data,$tablename){
		global $pdo;
	try{
		 
                $insert_remark = $pdo->prepare("INSERT INTO remarks (`table_name`,`param_id`,`remark`, 
			`created_by`, `created_on`) VALUES (?, ?, ?, ?, NOW())");
		$insert_args_remark  = array($tablename, $id, $data['remark'], $data['user_id']); 
		$insert_remark->execute($insert_args_remark);
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
	}

public function isDeviceVoltagePresent($device_id){
        global $pdo;
        $select=$pdo->prepare("SELECT 1 FROM voltage_readings WHERE device_id =? ");
        $select->execute(array($device_id));
        if($select->fetchColumn() > 0){
                return true;
        }else{
                return false;
        }
}
public function getVoltagesForDevice($device_id){
	$select = "SELECT *  FROM voltage_readings WHERE  
		  device_id=$device_id";
	global $pdo;
	try{
	$stmt = $pdo->prepare($select);
	$stmt->execute(array($from_date, $to_date, $details['location_id']));
	}catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		$voltage_strings[] = $row;
	}
	return $voltage_strings;
}
function addLocationParameters($location_id, $params){
#params is an associative array of "prepared params" 
## get those using prepareParams()
# keys are param ids and values are user input
        $insert = "INSERT INTO location_other_values
        (param_id, location_id, value)
        VALUES ";
        foreach($params as $p_id => $p_val){
                $p_val[1] = sanitizeInput($p_val[1]);
        $insert .= "('$p_id', '$location_id', '$p_val[1]'),";
        }
        ### remove the last comma
        $insert = chop($insert, ",");
	global $pdo;
	try{
                $pdo->query($insert);
        }
        catch(PDOException $e){
        $this->setError($e->getMessage());
                return false;
	}
        return 1;

}
	function getDevicesByCriteria($details){
                        $select = "SELECT * ,devices.id as device_id, vendors.code AS vendor_code, 
				   device_status_values.status AS device_status FROM devices 
				   LEFT JOIN vendors ON vendors.id=devices.vendor_id
				   LEFT JOIN device_status_values ON device_status_values.id=devices.status WHERE 1";
			if($details['status']!=''){
				$select .=" AND device_status_values.status='$details[status]'";
			}
			if($details['device_code']!=''){
				$select .=" AND devices.device_id_string LIKE '$details[device_code]%'";
			}

			$select .=" ORDER BY device_id_string ASC";
				 //  LIMIT $offset, $per_page";
                        global $pdo;
                        $res = $pdo->query($select);
                        return $res->fetchAll(PDO::FETCH_ASSOC);
		}	

	function updateLocationParameters($location_id, $params){
		global $pdo;
        	foreach($params as $p_id=>$p_val){
                $select = "SELECT value FROM location_other_values 
                        WHERE param_id = '$p_id' AND location_id = '$location_id'";
		$res = $pdo->query($select);
		$row = $res->fetchAll(PDO::FETCH_ASSOC); 
                if(empty($row)){
                $insert = "INSERT INTO location_other_values 
                        (param_id, location_id, value)
                        VALUES ('$p_id','$location_id','$p_val[1]')";
		//echo $insert = chop($insert, ",");

		try{
		$pdo->query($insert);
                } catch(PDOException $e){
 	       	$this->setError($e->getMessage());
                return false;
        	}
		}
                else{
                     $param_val= sanitizeInput($p_val[1]);
                $update = "UPDATE location_other_values SET value = '$param_val'
                        WHERE location_id = '$location_id' AND param_id = '$p_id'";
		try{
		$pdo->query($update);
		}catch(PDOException $e){
		$this->setError($e->getMessage());
                return false;
		}
                }
	}
        return true;
}

function getLocationParams($location_id){

	$select = "SELECT param_id, location_other_param.param as param, value FROM location_other_values
		LEFT JOIN location_other_param ON location_other_param.id = location_other_values.param_id
        	WHERE location_other_values.location_id = '$location_id'";
	global $pdo;
        try{

	        $res = $pdo->query($select);
        	$details_tmp = array();
		while($row = $res->fetch(PDO::FETCH_ASSOC)){
                	$details_tmp[$row['param']] = $row['value'];
		}
        }catch(PDOException $e){
        	$this->setError($e->getMessage());
                return false;
        }
        return $details_tmp;
}


function getActionLog($date){

	$select = "SELECT *, users.name AS username from action_log LEFT JOIN users ON users.id = action_log.created_by 
		WHERE DATE(created_on) ='$date' ";
	global $pdo;
	try{
		
		$res = $pdo->query($select);	
		$data = $res->fetchAll(PDO::FETCH_ASSOC); 
	}catch(PDOException $e){
		$this->setError($e->getMessage());
                return false;
	}
	return $data;
}
function getFileNameFromRowId($row_id){
	$select = "SELECT filename from datafiles where id=$row_id";
	global $pdo;
	try{
		$res = $pdo->query($select);
                $filename = $res->fetchColumn();
	}catch(PDOException $e){
	$this->setError($e->getMessage());
                return false;
	}
	return $filename;
}

function getFileDataFromFileName($file_name){
        $select = "SELECT content from datafiles where filename='$file_name'";
        global $pdo;
        try{
                $res = $pdo->query($select);
                $content = $res->fetchColumn();
        }catch(PDOException $e){
        $this->setError($e->getMessage());
                return false;
        }
        return $content;
}


function getEventProcessingLog($date){
        $select = "SELECT users.name AS username, event_processing_log.* from event_processing_log 
		INNER JOIN users ON users.id = event_processing_log.created_by 
                WHERE DATE(event_processing_log.created) ='$date' ";
        global $pdo;
        try{

                $res = $pdo->query($select);
                $data = $res->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
        return $data;
}

	function markVoltUnPublishedByLocation($location_id){
		global $pdo;
	try{
		$update = $pdo->prepare("UPDATE voltage_readings SET `published` = 0 WHERE location_id= $location_id");
		$update->execute();
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
	}
/* Added By Rupali */
function getDeviceInstallationLog($device_id,$location_id){
global $pdo;
try{
			
               $select = "SELECT devices.device_id_string as device_name, locations.name as location_name, 
			 device_installation_log.operation as oper,created as date, sim_cards.sim_no as sim
			 from device_installation_log 
		LEFT JOIN devices ON devices.id=device_installation_log.device_id
		LEFT JOIN locations ON locations.id=device_installation_log.location_id
		LEFT JOIN sim_cards ON sim_cards.id=device_installation_log.sim_card_id
		WHERE 1";
        if($device_id!='')
	{

               $select .= " AND device_id='$device_id'";
	}
       if($location_id!='')
	{
               $select .= " AND location_id='$location_id'";
	}
//echo $select;
	
	
		
		$res = $pdo->query($select);	
		$data = $res->fetchAll(PDO::FETCH_ASSOC); 
	}catch(PDOException $e){
		$this->setError($e->getMessage());
                return false;
	}
	return $data;
}
/* Added By Rupali */
function getPublish_Unpublish_Log($location_id=''){
global $pdo;
try{
			
               $select = "SELECT  locations.name as location_name, publish_unpublish_log.from_date as from_date,publish_unpublish_log.to_date as to_date,
			publish_unpublish_log.type as type,publish_unpublish_log.created_by as created_by,publish_unpublish_log.created as date
			 from publish_unpublish_log
		LEFT JOIN locations ON locations.id=publish_unpublish_log.location_id
		WHERE 1";
       
       if($location_id!='')
	{
               $select .= " AND location_id='$location_id'";
	}
//echo $select;
	
	
		
		$res = $pdo->query($select);	
		$data = $res->fetchAll(PDO::FETCH_ASSOC); 
	}catch(PDOException $e){
		$this->setError($e->getMessage());
                return false;
	}
	return $data;
}

/* Added By Rupali */ 
function addevent_processing_log($details,$row_array){
		global $pdo; 
if(($details['from_date']!= ' ') && ($details['to_date']!=' '))
{
$from_date = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $details['from_date'])));
$to_date = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $details['to_date'])));
}
else
{
$from_date = '0000-00-00 00:00:00';
$to_date = '0000-00-00 00:00:00';

}
if($details['location_id']==''){
$details['location_id']= NULL;
}
	try{ 
		
                $insert_remark = $pdo->prepare("INSERT INTO event_processing_log(`location_id`,`row_id`,`event_id`,`from_date`,`to_date`,`created_by`, `created`) VALUES (?, ?, ?, ?, ?, ?, NOW())");
		if(!empty($row_array)){ 
		foreach($row_array as $row=>$value){
		$insert_args_remark  = array($details['location_id'],$value,$details['event_id'],$from_date,$to_date,$details['user_id']); 
		$insert_remark->execute($insert_args_remark); 
		}
		}else{
		$insert_args_remark  = array($details['location_id'],$details['file_id'],$details['eid'],$from_date,$to_date,$details['user_id']);
                $insert_remark->execute($insert_args_remark);
		}
		
	} 
	catch(PDOException $e){ 
		$this->setError($e->getMessage()); 
		return false; 
	} 
	return true; 
	}

public function markProcessedById($id){
	global $pdo;
	try{
		$update = $pdo->prepare("UPDATE datafiles SET is_processed = 1 , error_code =NULL WHERE id =$id");
		$update->execute();
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
}
function isLocationNameExists($location_name){
	global $pdo;
	$select = $pdo->prepare("SELECT name from locations WHERE name = '$location_name'");
	$select->execute();
	$count = $select->rowCount();
	if($count>0){
	return false;
	}	
	return true;
}

public function isVoltageForFilePresent($location,$day,$hour_of_day){
        global $pdo;
        $select=$pdo->prepare("SELECT 1 FROM voltage_readings WHERE location_id =? AND day =? AND hour_of_day=?");
        $select->execute(array($location,$day,$hour_of_day));
        if($select->fetchColumn() > 0){
                return true;
        }else{
                return false;
        }
}
	function markVoltageUnPublished($ids){
		global $pdo;
	try{
		$update = $pdo->prepare("UPDATE voltage_readings SET `published` = 0, is_unpublished=1 WHERE id IN ($ids)");
		$update->execute();
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
	}
public function addZeroVoltageReadings($params){
   	 global $pdo;
	$insert = "INSERT INTO voltage_readings 
        (location_id,device_id,sim_card_id,readings,hour_of_day,day)
        VALUES (?,?,?,?,?,?)";
	$readings = '';
	for($i=0;$i<60;$i++){
		$readings .= '0,';
	}
	$readings = rtrim($readings,',');
	
	try{
		$insert = $pdo->prepare($insert);	
		foreach($params as $p_val){
        	$insert->execute(array($p_val['location_id'], $p_val['device'],$p_val['sim_card'],$readings, $p_val['hr'],$p_val['day']));
		}

	
	### remove the last comma
	//$insert = chop($insert, ",");
	//	$pdo->query($insert);
	   }	
	catch(PDOException $e){
		echo $e->getMessage();
		exit;
	}
	return true;
}
	function updateDatafilesContent($id,$content){
		global $pdo;
        try{
                $update = $pdo->prepare("UPDATE datafiles SET `is_processed` = 0 ,`content` = '$content'  WHERE id = ?");
                $update->execute(array($id));
        }
        catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
        return true;
        }


	function addAltizonDownloads($data){
                global $pdo;
        $insert = "INSERT INTO altizon_downloads 
                        (`device_code`,`date`,`hr`,`sensor`,`lat`,`long`,`sim_id`,`fw_ver`)
                        VALUES (?,?,?,?,?,?,?,?)";
        try{
		$insert = $pdo->prepare($insert);	
		foreach($data as  $p_val){
        	$insert->execute(array($p_val['device_id'],$p_val['date'],$p_val['hr'],$p_val['sensor'],$p_val['lat'],$p_val['long'],$p_val['sim_id'],$p_val['fw_ver']));
		}
	   }	
	catch(PDOException $e){
                $this->setError($e->getMessage());
                //return false;
		//echo $e->getMessage();
                //exit;
        }
        return true;
        }
	
	function removeAltizonDownloads($ids){
                global $pdo;
        try{
                $update = $pdo->prepare("DELETE FROM altizon_downloads WHERE `id` IN ($ids)");
                $update->execute(array());
        }
        catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
        return true;

	}
/* Added By Rupali */
function adduploadedreports($details,$files){
$allowedExts = array("pdf", "doc", "docx"); 
$extension = end(explode(".", $files["file_path"]["name"]));

$file =$files['file_path']['name'];
$file_loc = $files['file_path']['tmp_name'];
$file_size = $files['file_path']['size'];
$file_type = $files['file_path']['type'];
$folder=$_SERVER['DOCUMENT_ROOT']."/reports/";
//($files["file_path"]["size"] >1000000)
	 if($details['date_published']!= ' ')
	{
		$date =  date('Y-m-d %H:%i:%s', get_strtotime($details['date_published'])); 
	}else
	{
		$date = '0000-00-00 00:00:00';
	}
        //Inserting file  in folder and database
	if ( ( ($files["file_path"]["type"] == "application/msword") || ($files["file_path"]["type"] == "text/pdf") || ($files["file_path"]["type"] == "application/pdf"))&& in_array($extension, $allowedExts))
	{
		global $pdo;
		$insert = "INSERT INTO reports (`title`,`file_path`,`description`,`date_published`) VALUES (?,?,?,?)";
		try{
			$insert = $pdo->prepare($insert);	
			$insert->execute(array($details['title'],$file,$details['description'],$date));
		
		   }	
		catch(PDOException $e){
		        $this->setError($e->getMessage());
		        return false;
			//echo $e->getMessage();
		        //exit;
		}

		 move_uploaded_file($file_loc,$folder.$file);		 
                 return true;
	}else
	{
                $this->setError("Invalid File");
                return false;
	}
}
function deleteAnalysisReport($id){
		
		
		global $pdo;
	try{
		$delete = $pdo->prepare("DELETE FROM reports WHERE id = ?");
		$delete->execute(array($id));
		
	}
	catch(PDOException $e){
		$this->setError($e->getMessage());
		return false;
	}
	return true;
	
	}


function getAllVendorDevices($vendor_id){
        global $pdo;
        $select = "SELECT * FROM devices WHERE vendor_id='$vendor_id'";
        $res = $pdo->query($select);
        return $res->fetchAll(PDO::FETCH_ASSOC);
}

function deleteDeviceVoltageReadings($device_id,$date){
        global $pdo;
        try{
        #$select = "SELECT * FROM voltage_readings WHERE device_id='$device_id' AND day='$date'";
        #$res = $pdo->query($select);
        #return $res->fetchAll(PDO::FETCH_ASSOC);
                $delete = $pdo->prepare("DELETE FROM voltage_readings WHERE device_id = ? AND day = ?");
                $delete->execute(array($device_id, $date));
                return $delete->rowCount();
        }
        catch(PDOException $e){
                $this->setError($e->getMessage());
                return false;
        }
        return true;
}
} // class ends here

