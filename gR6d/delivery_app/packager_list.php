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
    
    $packager_id = $data['packager_id'];
        
        try{
            $query_result=$mysqli->query("SELECT od.*,pd.o_id,pd.batch_no,pd.p_quantity,pd.p_discount,pd.p_img,pd.p_price,pd.p_type,pd.created_by,sd.s_name FROM `e_normal_order_details`od,`e_normal_order_product_details`pd,`e_salesman_details`sd
                                          WHERE sd.id = '".$packager_id."' AND pd.created_by=od.created_by AND sd.active=1 AND od.created_by = sd.id AND sd.cos_id=$cos_id AND od.cos_id=$cos_id AND od.bill_type=1 AND pd.active=1");
            $row_count=$query_result->num_rows;

        }catch(mysqli_sql_exception $exception){
            $query_result = false;
            $err_msg = $exception->getMessage();
        }
        
        if( $query_result == true){
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
    
    }else{
        http_response_code(400);
        $no_data['message'] = $no_data['message'] . " ::" . $err_msg  . " ::" . $query_result;
        echo json_encode($no_data);
    }
?>
