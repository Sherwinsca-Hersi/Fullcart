<?php
include_once '../api/config.php';
include_once '../api/function.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$platform  = isset($data['platform']) ? $data['platform'] : '';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$login_id = isset($data['login_id']) ? $data['login_id'] : '';
$user_id = isset($data['user_id']) && $data['user_id'] !== "" ? $data['user_id'] : 0;
$mobile = isset($data['mobile']) && $data['mobile'] !== "" ? $data['mobile'] : '0';
$app_version = isset($data['app_version']) ? $data['app_version'] : '';
$device_info = isset($data['device_info']) ? $data['device_info'] : '';
$device_os = isset($data['device_os']) ? $data['device_os'] : '';
$device_id = isset($data['device_id']) ? $data['device_id'] : '';
$table = "e_user_login_history";

$h = new CommonFunction();

try {
    
    $checkUser = $mysqli->query("select user_id from e_user_login_history where user_id='".$user_id."' and cos_id = '$cos_id'")->num_rows;

    if($checkUser != 0)
    {
        $field = ['user_id' => $user_id, 'mobile_number' => $mobile,'login_id' => $login_id, 'up_platform' => $platform, 
                    'device_id' => $device_id,'device_info'=>$device_info,'app_version'=>$app_version,'device_info'=>$device_info,'device_os'=>$device_os];
        $where = "WHERE user_id='".$user_id."' AND cos_id='$cos_id'";
        
        $result = $h->Ins_update_Api($field, $table, $where); 
    }else {
        $field_values = ["cos_id", "login_id", "user_id", "mobile_number","app_version", "device_info", "device_os", "device_id", "platform"];
        $data_values = [$cos_id, $login_id, $user_id, $mobile, $app_version, $device_info, $device_os, $device_id, $platform];
        
        $result = $h->Ins_latest_Api($field_values, $data_values, $table);
    }

    if ($result) {
        $returnArr = ["ResponseCode" => "200", "Result" => "true", "ResponseMsg" => "Inserted Successfully"];
        
    } else {
        $returnArr = ["ResponseCode" => "400", "Result" => "false", "ResponseMsg" => "Failed to insert/update"];
        
    }
} catch (Exception $e) {
    $returnArr = ["ResponseCode" => "500", "Result" => "false", "ResponseMsg" => "Server Error: " . $e->getMessage()];
}

echo json_encode($returnArr);
?>
