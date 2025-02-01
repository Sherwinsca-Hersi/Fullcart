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
    
        try{
            $query_result=$mysqli->query("SELECT od.*,sd.s_name,sd.s_mobile,sd.email,sd.dob,sd.s_address,sd.salary,sd.joining_date
                                          FROM `e_normal_order_details`od,`e_salesman_details`sd
                                          WHERE od.salesman_id=sd.id AND od.cos_id=$cos_id AND sd.cos_id=$cos_id AND od.bill_type=1");
            $row_count=$query_result->num_rows;

        }catch(mysqli_sql_exception $exception){
            $query_result = false;
            $err_msg = $exception->getMessage();
        }
        
        if($query_result ->num_rows>0){
            $result = mysqli_fetch_all($query_result, MYSQLI_ASSOC);
            $row_value=array();
            $row_value=$result;
            http_response_code(200);
            echo json_encode($row_value);  
        }else{
            http_response_code(400);
            $no_data['message'] = $no_data['message'] . " ::" . $err_msg  . " ::" . $query_result;
            echo json_encode($no_data);
        }
    
  
?>
