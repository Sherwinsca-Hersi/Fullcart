<?php
include_once '../api/config.php';
include_once '../api/function.php';
    header('Content-type: text/json');
    $data = json_decode(file_get_contents('php://input'), true);
    ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
    $versionArray = array();
    $dataArray = array();
	$iw = $data['iw'];//ai
	$sql = "select version,package_name,os, validity_date, update_available, update_details from e_dat_version_life where active='1' and os='$iw' and cos_id = '$cos_id'";
	$sel = $mysqli->query($sql);
    while($row = $sel->fetch_assoc()) {
        $versionArray[] = $row;
    }
    $sel2 = $mysqli->query("SELECT meta_key,meta_value,description,MAX(version) version FROM e_dat_stack WHERE active = '1' and cos_id = '$cos_id' GROUP BY meta_key,version");
    while($row = $sel2->fetch_assoc()){
    	$pop['meta_key'] = $row['meta_key'];
    	$pop['meta_value'] = $row['meta_value'];
    	$pop['description'] = $row['description'];
    	$pop['version'] = $row['version'];
	    $dataArray[] = $pop;
    }
    
$returnArr = array("version"=>$versionArray,'data_stack' => $dataArray,"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Version Founded!");
echo json_encode($returnArr);
?>