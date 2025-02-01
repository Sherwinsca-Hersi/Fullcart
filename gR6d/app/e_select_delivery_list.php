<?php 
include_once '../api/config.php';

header('Content-type: text/json');
$sel = $mysqli->query("select * from e_dat_delivery where active=1 and cos_id = '$cos_id'");
$myarray = array();
while($row = $sel->fetch_assoc())
{
			$myarray[] = $row;
}
$returnArr = array("Deliverylist"=>$myarray,"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Delivery List Found");
echo json_encode($returnArr);
?>