<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once '../api/config.php';
include_once '../api/response.php';

$data = json_decode(file_get_contents("php://input"), true);

$query_result = false;
$err_msg = '';
$response = [
    'status_code' => 400, 
    'message' => 'Invalid Request',
    'data' => null
];

if (!empty($data)) {
    
    $order_id           = $data['order_id'];
    $delivery_person_id = $data['packager_id'];
    $platform           = $data['platform'];


    try {
        $query_result = $mysqli->query("UPDATE `e_normal_order_details` 
                                        SET status='Packed', up_platform='$platform', active=3
                                        WHERE id='" . $order_id . "' AND cos_id=$cos_id 
                                        AND salesman_id='" . $delivery_person_id . "'");

        if ($query_result === true) {
            $response['status_code'] = 200;
            $response['message'] = "Order updated successfully.";
        } else {
            $response['status_code'] = 400;
            $response['message'] = "Failed to update order.";
            $response['data'] = ['error' => 'Query execution failed.'];
        }

    } catch (mysqli_sql_exception $exception) {
        $response['status_code'] = 500;
        $response['message'] = "Database error occurred.";
        $response['data'] = ['error' => $exception->getMessage()];
    }
    
} else {
    $response['message'] = "No input data received.";
}

http_response_code($response['status_code']);
echo json_encode($response);

?>
