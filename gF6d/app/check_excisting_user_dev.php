<?php 
include_once '../api/config.php'; 
include_once '../api/response.php';
header('Content-type: application/json');
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);

$data = json_decode(file_get_contents('php://input'), true);
$mobile = strip_tags(mysqli_real_escape_string($mysqli, $data['mobile']));

logOpen($mobile);
writeLog("Check Excisting user for forget password");
// Check if mobile field is empty:
if($data['mobile'] == '') {
    $returnArr = array("ResponseCode" => "401", "Result" => "false", "ResponseMsg" => "Mobile number is required.");
    writeLog("Mobile number is required.");
    echo json_encode($returnArr);
    exit();
}

// Clean the mobile input :


// Check if user already exists :
$checkUser = $mysqli->query("SELECT * FROM e_user_details WHERE mobile = '$mobile' AND active=1 AND cos_id = '$cos_id'");
  
if ($checkUser->num_rows != 0) {
    $returnArr = array("ResponseCode" => "409", "Result" => "false", "ResponseMsg" => "User already exists");
    writeLog("User already exists");
} else {
    
    $returnArr = array(
        "ResponseCode" => "200",
        "Result" => "true",
        "ResponseMsg" => "User not existed"
    );
    writeLog("User not existed". $mobile);
}

echo json_encode($returnArr);
fclose($logFile);
?>
