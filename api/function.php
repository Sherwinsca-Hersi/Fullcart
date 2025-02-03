<?php 
require_once "config.php";

$GLOBALS['mysqli'] = $mysqli;
class CommonFunction {

	function sel_login($username,$password,$tblname) {
		global $mysqli, $cos_id; 
		
        $q = "SELECT id FROM $tblname WHERE cos_id = '$cos_id' AND username='$username' AND password='$password' AND active=1";
        $result = $GLOBALS['mysqli']->query($q);
        
        // Check if any rows were returned
        if ($result && $result->num_rows > 0) {
            // Fetch the first row (assuming username and password combination is unique)
            $row = $result->fetch_assoc();
            
            // Return the 'id' from the fetched row
            return $row['id'];
        } else {
            // Return false or handle the case when no rows match the criteria
            return 0;
        }
		
		
	}
	
	function Ins_latest($field,$data,$table){

    $field_values= implode(',',$field);
    $data_values=implode("','",$data);

    $sql = "INSERT INTO $table($field_values)VALUES('$data_values')";
    $result=$GLOBALS['mysqli']->query($sql);
  return $result;
  }
  
  function Ins_id($field,$data,$table){

    $field_values= implode(',',$field);
    $data_values=implode("','",$data);

    $sql = "INSERT INTO $table($field_values)VALUES('$data_values')";
    $result=$GLOBALS['mysqli']->query($sql);
  return $GLOBALS['mysqli']->insert_id;
  }
  
  function Ins_latest_Api($field,$data,$table){

    $field_values= implode(',',$field);
    $data_values=implode("','",$data);

    $sql = "INSERT INTO $table($field_values)VALUES('$data_values')";
    $result=$GLOBALS['mysqli']->query($sql);
    return $GLOBALS['mysqli']->insert_id;//// I dont know why i change this but i have to on date 11/Dec 2021 by Gowtham
  }
  
  function Ins_Api_id($field,$data,$table){

    $field_values= implode(',',$field);
    $data_values=implode("','",$data);

    $sql = "INSERT INTO $table($field_values)VALUES('$data_values')";
    $result=$GLOBALS['mysqli']->query($sql);
  return $GLOBALS['mysqli']->insert_id;
  }
  
  function Ins_update($field,$table,$where){
$cols = array();

    foreach($field as $key=>$val) {
        if($val != NULL) // check if value is not null then only add that colunm to array
        {
           $cols[] = "$key = '$val'"; 
        }
    }
    $sql = "UPDATE $table SET " . implode(', ', $cols) . " $where";
$result=$GLOBALS['mysqli']->query($sql);
    return $result;
  }
  
   function Ins_update_Api($field,$table,$where){
$cols = array();

    foreach($field as $key=>$val) {
        if($val != NULL) // check if value is not null then only add that colunm to array
        {
           $cols[] = "$key = '$val'"; 
        }
    }
    $sql = "UPDATE $table SET " . implode(', ', $cols) . " $where";
$result=$GLOBALS['mysqli']->query($sql);
    return $result;
  }
  
  function query($sql){
      $result=$GLOBALS['mysqli']->query($sql);
  return $result;
  }
  
  
  
  
  function Ins_update_single($field,$table,$where){
$query = "UPDATE $table SET $field";

$sql =  $query.' '.$where;
$result=$GLOBALS['mysqli']->query($sql);
  return $result;
  }
  
 function Ins_deldata($field,$where,$table){
    $cols = array();

    foreach($field as $key=>$val) {
        if($val != NULL) // check if value is not null then only add that colunm to array
        {
           $cols[] = "$key = '$val'"; 
        }
    }
    $sql = "UPDATE $table SET " . implode(', ', $cols) . " $where";
    $result=$GLOBALS['mysqli']->query($sql);
    echo $result;
    return $result;
    
//     $sql = "Delete From $table $where";
//     $result=$GLOBALS['mysqli']->query($sql);
//   return $result;
  }
 
    
    	function send_notification($token__1, $message__1, $title__1)
	{
	    $token = array($token__1);
	   // echo json_encode($token);
	    if(is_array($token__1) == 1){
	       // echo "is multiple";
	        $token = $token__1;
	       // echo json_encode($token);
	    }
	    
		$url = "https://fcm.googleapis.com/fcm/send";
		$fields = array(
			'registration_ids' => $token,
			'priority' => 'high',
			'data' => array(
								"title" => $title__1,
								"body" => $message__1,
								"icon" => "mini_logo",
								"sound" => "notif_chime.wav")
		);
		    $sql = "SELECT * FROM e_dat_setting ORDER BY id DESC LIMIT 1";
            $result=$GLOBALS['mysqli']->query($sql)->fetch_assoc();
		$headers = array(
			'Authorization:key=' . $result['fcm_server_key'],
			'Content-Type: application/json'
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		$result = curl_exec($ch);
		if ($result === FALSE) {
			die('Curl failed: ' . curl_error($ch));
		}
		curl_close($ch);
		return $result;
	}
	
	
	function send_noti_to_admin($title,$message){
	    $sel = $GLOBALS['mysqli']->query("SELECT a.username,a.mobile,u.id,n.token FROM `e_dat_admin` a,e_user_details u, e_notification n WHERE a.mobile = u.mobile and u.id = n.u_id");
	    $tokens = array();
        while($row = $sel->fetch_assoc())
        {
            $tokens[] = $row["token"];
        }
        $this->send_notification($tokens,$title,$message);
	    
	}

}

function getWeeks($date, $rollover)
{
    $cut = substr($date, 0, 8);
    $daylen = 86400;

    $timestamp = strtotime($date);
    $first = strtotime($cut . "00");
    $elapsed = ($timestamp - $first) / $daylen;

    $weeks = 1;

    for ($i = 1; $i <= $elapsed; $i++)
    {
        $dayfind = $cut . (strlen($i) < 2 ? '0' . $i : $i);
        $daytimestamp = strtotime($dayfind);

        $day = strtolower(date("l", $daytimestamp));

        if($day == strtolower($rollover))  $weeks ++;
    }

    return $weeks;
}

function getStartOfWeekDate($date = null)
{
    if ($date instanceof \DateTime) {
        $date = clone $date;
    } else if (!$date) {
        $date = new \DateTime();
    } else {
        $date = new \DateTime($date);
    }
    
    $date->setTime(0, 0, 0);
    
    if ($date->format('N') == 1) {
        // If the date is already a Monday, return it as-is
        return $date;
    } else {
        // Otherwise, return the date of the nearest Monday in the past
        // This includes Sunday in the previous week instead of it being the start of a new week
        return $date->modify('last monday');
    }
}

function getStartOfWeekDate2($date = null)
{
    if ($date instanceof \DateTime) {
        $date = clone $date;
    } else {
        // If date is falsy, it won't harm
        $date = new \DateTime($date);
    }
    
    return $date->modify('monday this week');
}

?>
