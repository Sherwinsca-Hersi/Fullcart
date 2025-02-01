<?php
include_once '../api/config.php';

header('Content-type: text/json');
$sel = $mysqli->query("SELECT meta_key,meta_value,description,MAX(version) version FROM e_dat_stack WHERE active = '1' and cos_id = '$cos_id' GROUP BY meta_key,version");
$myarray = array();
$pop = array();
while($row = $sel->fetch_assoc())
{
	$pop['meta_key'] = $row['meta_key'];
	$pop['meta_value'] = $row['meta_value'];
	$pop['description'] = $row['description'];
	$pop['version'] = $row['version'];
			$myarray[] = $pop;
}
$returnArr = array("dataStack"=>$myarray,"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Data Stack List Found");
echo json_encode($returnArr);
?>