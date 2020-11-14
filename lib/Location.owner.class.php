<?php
chdir(dirname(__FILE__));
include_once('User.class.php');
class LocationOwner extends User{

    public function __construct($user_id){
        parent::__construct($user_id);
	
        if(!$this->isLocationowner()){ 
            $this->_loginRedirect();
	//log failure
            $log = debug_backtrace();
            $this->createActionLog($log,0);
	    throw new Exception('No privileges');
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
	if($details['category_id']){
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
	if($details['district']){
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



    

} // class ends here
