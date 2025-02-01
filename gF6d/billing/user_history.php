<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once '../api/config.php';
include_once '../api/response.php';

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data)) {
    http_response_code(400);
    echo json_encode($no_data);
}

$login_id       = $data['login_id'];
$mobile_number  = $data['mobile_number'];
$device_id      = $data['device_id'];
$user_id        = ($data['user_id'] != "") ? $data['user_id'] : 0 ;             //Null Safety For Signup
$app_version    = $data['app_version'];
$ip_id          = $data['ip_id'];
$device_info    = $data['device_info'];
$device_os      = $data['device_os'];
$platform       = $data['platform'];
$role           = $data['role'];


$insert_query = false;
$final_query = "";

    $insert_user_fields = "cos_id,login_id,role,platform, mobile_number, device_id, user_id, app_version, ip_id, device_info, device_os"; 
    $insert_user_values = "$cos_id,'" . $login_id . "','" . $role . "', '" . $platform . "', '" . $mobile_number . "', '" . $device_id . "', '" . $user_id . "', 
                            '" . $app_version . "', '" . $ip_id . "', '" . $device_info . "', '" . $device_os . "'";
    $final_query        = "INSERT INTO  e_user_login_history ($insert_user_fields) VALUES ($insert_user_values)";
    $insert_query       = $mysqli->query($final_query);

if ($insert_query == true) {
    http_response_code(200);
    echo json_encode($success);
} else {
    http_response_code(400);
    $no_data['message'] = $no_data['message'] . " ::" . $final_query;
    echo json_encode($no_data);
}

?>