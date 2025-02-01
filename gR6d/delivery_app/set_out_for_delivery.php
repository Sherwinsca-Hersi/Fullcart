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

$data=json_decode(file_get_contents("php://input"),true);

$query_result = false;
$err_msg = '';

if(!empty($data)){

    $order_id     = $data['order_id'];
    $delivery_person_id = $data['deliverer_id'];

        try{
            
            $query_result=$mysqli->query("UPDATE `e_normal_order_details` SET status='Out For Delivery',up_platform=3,active=5 WHERE 
                                            id='" . $order_id . "' and cos_id=$cos_id AND salesman_id= '" . $delivery_person_id . "'");

        }catch(mysqli_sql_exception $exception){
            $query_result = false;
            $err_msg = $exception->getMessage();
        }
        
        if($query_result == true){
            http_response_code(200);
            $success['message'] = $success['message'];
            echo json_encode($success);  
        }else{
            http_response_code(400);
            mysqli_rollback($conn);
            $no_data['message'] = $no_data['message'] . " ::" . $err_msg  . " ::" . $query_result;
            echo json_encode($no_data);
        }
    
    }else{
        http_response_code(400);
        $no_data['message'] = $no_data['message'] . " ::" . $err_msg  . " ::" . $query_result;
        echo json_encode($no_data);
    }
?>
