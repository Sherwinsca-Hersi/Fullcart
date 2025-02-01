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

    $product_id   = $data['product_id'];
    
    $batch        = $data['batch'];
    
    $update_qty   = $data['update_qty'];
    

        $qty_query                      = $mysqli->query("SELECT qty_left FROM `e_product_price` WHERE cos_id=$cos_id AND batch_no='" . $batch . "' AND product_id='" . $product_id . "'");
        
        $existing_missing_qty_query     = $mysqli->query("SELECT missing_qty FROM `e_product_price` WHERE cos_id=$cos_id AND batch_no='" . $batch . "' AND product_id='" . $product_id . "'");
        
        $qty_exists                     = $qty_query->fetch_array()[0] ?? 0;
        
        $existing_missing_qty           = $existing_missing_qty_query->fetch_array()[0] ?? 0;
        
        if($update_qty < $qty_exists){
            $missing_qty                = ($qty_exists - $update_qty) + $existing_missing_qty;
        }else if($update_qty > $qty_exists){
            $missing_qty                = ($qty_exists+$existing_missing_qty) - $update_qty;
        }
        
        $qty_left                       = $update_qty;
            
        try{
            
            $query_result=$mysqli->query("UPDATE `e_product_price` SET missing_qty ='" . $missing_qty . "' WHERE cos_id=$cos_id AND batch_no='" . $batch . "' AND product_id='" . $product_id . "'");
             
            $query_result2=$mysqli->query("UPDATE `e_product_price` SET qty_left ='" . $qty_left . "' WHERE cos_id=$cos_id AND batch_no='" . $batch . "' AND product_id='" . $product_id . "'");
             
             
            }catch(mysqli_sql_exception $exception){
                mysqli_rollback($conn);
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
