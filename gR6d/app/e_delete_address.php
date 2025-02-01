<?php 
include_once '../api/config.php';
include_once '../api/function.php';

$data = json_decode(file_get_contents('php://input'), true);

$platform  = $data['platform'];

ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);

if ($data['u_id'] == '') {
    $returnArr = array("ResponseCode" => "401", "Result" => "false", "ResponseMsg" => "Empty User Id");
} else {
    $u_id = $data['u_id'];
    $aid = $data['a_id']; 

    $count = $mysqli->query("SELECT * FROM e_user_details WHERE active = 1 AND id = $u_id AND cos_id = '$cos_id'")->num_rows;

    if ($count != 0) {
        
        if ($aid != 0) {
            
            $updateStatus = $mysqli->query("UPDATE e_address_details SET active = 0 WHERE id = $aid AND cos_id = '$cos_id'");

            if ($updateStatus) {
                $returnArr = array("ResponseCode" => "200", "Result" => "true", "ResponseMsg" => "Address Deactivated Successfully!!!");
            } else {
                $returnArr = array("ResponseCode" => "500", "Result" => "false", "ResponseMsg" => "Failed to Deactivate Address!");
            }
        } else {
            $returnArr = array("ResponseCode" => "401", "Result" => "false", "ResponseMsg" => "Address ID (aid) is missing or invalid for deactivation.");
        }
    } else {
        $returnArr = array("ResponseCode" => "401", "Result" => "false", "ResponseMsg" => "User Either Not Exists OR Deactivated From Admin!");
    }
}
echo json_encode($returnArr);
?>
