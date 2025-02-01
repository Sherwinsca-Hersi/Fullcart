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

if(!empty($data)){
    
    $username=$data['mobile'];
    $password=$data['password'];

    $check_query1 =  $mysqli->query("SELECT id FROM `e_dat_admin` WHERE mobile='".$username."' AND password='".$password."' AND cos_id=$cos_id");
    
    $check_query2 =  $mysqli->query("SELECT id FROM `e_salesman_details` WHERE s_mobile='".$username."' AND password='".$password."' AND active=1 AND cos_id=$cos_id");

    
    if($check_query1 ->num_rows>0){
        
        $query_result=$mysqli->query("SELECT id,mobile,username,password
                                      FROM `e_dat_admin`
                                      WHERE mobile = '".$username."' AND password='".$password."' AND active=1 AND cos_id=$cos_id");
                                    
        $result=$query_result->fetch_assoc();
        http_response_code(200);
        echo json_encode($result);
        
    }else if($check_query2 ->num_rows>0){
        
        $query_result=$mysqli->query("SELECT * FROM `e_salesman_details`
                                      WHERE s_mobile = '".$username."' AND password='".$password."' AND active=1 AND cos_id=$cos_id");
        $result=$query_result->fetch_assoc();
        http_response_code(200);
        echo json_encode($result);
        
    }
    else {
        http_response_code(400);
        echo json_encode($not_found);
    }
    
    }else{
        http_response_code(400);
        echo json_encode($not_found);
    }
?>
