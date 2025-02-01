<?php 
include_once '../api/config.php';
$sel = $mysqli->query("select * from e_dat_timeslot where cos_id = '$cos_id' and active=1");
$p = array();
while($row = $sel->fetch_assoc())
{
    $myarray['id'] = $row['id'];
    $myarray['slot_limit'] = $row['slot_limit'];
	$myarray['min_time'] = date("g:i A", strtotime($row['min_time']));
	$myarray['max_time'] = date("g:i A", strtotime($row['max_time']));
	$p[] = $myarray;
}
$returnArr = array("data"=>$p,"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Timeslot Found");
echo json_encode($returnArr);
?>