<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
include_once '../api/config.php';

require '/home/hapidev/vendor/autoload.php';

use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\OAuth2;

function getAccessToken() {
    $serviceAccountFile = '/home/hapidev/subdoms/fullcomm.in/json/fullcomm-a500f-firebase-adminsdk-uechm-886f16d711.json';  
    $scope = 'https://www.googleapis.com/auth/firebase.messaging';

    $jsonKey = json_decode(file_get_contents($serviceAccountFile), true);

    $credentials = new ServiceAccountCredentials($scope, $serviceAccountFile);
    $accessToken = $credentials->fetchAuthToken();
    
    return $accessToken['access_token'];
}

$accessToken = getAccessToken();


$fcmUrl = 'https://fcm.googleapis.com/v1/projects/fullcomm-a500f/messages:send';
$data = json_decode(file_get_contents("php://input"), true);
if (empty($data)) {
    http_response_code(400);
    echo json_encode($no_data);
    return;
}

$u_id          = $data["u_id"];

$msgTitle      = $data["msgTitle"];
$msgBody       = $data["msgBody"];
$platform      = $data['platform'];

$sql = "SELECT n.token, u.id FROM e_notification n, e_user_details u WHERE u.id = '$u_id' AND n.u_id = u.id AND u.cos_id = '$cos_id' AND n.cos_id = '$cos_id' ORDER BY n.created_ts DESC LIMIT 1"; 
$get_result = $mysqli->query($sql);
$insert_query = false;
$final_query="";

$id="";

if ($get_result) {
    $row = $get_result->fetch_assoc();
    if ($row && isset($row['token'])){ 
        $id    = $row['id'];
        $token = $row['token']; 
    } else {
        $token = NULL; 
    }
    $get_result->free(); 
} else {
    echo "Error: " . $mysqli->error;
}

try{
    mysqli_autocommit($mysqli, false);
    mysqli_begin_transaction($mysqli);
 
 $message = [
        "message" => [
            "token" => $token,
            "notification" => [
                 "title" => $msgTitle,
                 "body"  => $msgBody,
            ],
            "data" => [
                "title" => $msgTitle,
                 "body"  => $msgBody,
            ]
        ]
    ];

$dataString = json_encode($message);

    $headers = [
        "Authorization: Bearer $accessToken",
        "Content-Type: application/json",
    ];

$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $fcmUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

    $result = curl_exec($ch);
    curl_close($ch);
    
    $insert_user_fields = "`u_id`, `platform`, `title`, `description`, `datetime`, `cos_id`";
    $current_datetime = date('Y-m-d H:i:s'); 
    $insert_user_values = "'" . $id . "', '" . $platform . "', '" . $msgTitle . "', '" . $msgBody . "', '" . $current_datetime . "', '" . $cos_id . "'";

    $final_query = "INSERT INTO  e_notification_details ($insert_user_fields) VALUES ($insert_user_values)";
    $insert_query = $mysqli->query($final_query);
    mysqli_autocommit($mysqli, true);   //implicit commit
    mysqli_commit($mysqli);
    echo $result;
}catch (mysqli_sql_exception $exception) {
    echo $exception;
    echo $result;
    mysqli_rollback($mysqli);
    $insert_query = false;
}

?>