<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once '../api/config.php';
include_once '../api/response.php';

$data=json_decode(file_get_contents("php://input"),true);

switch ($data['search_type']) {
    case 'getAllProducts':
        $query = "SELECT * FROM e_product_details pd,e_product_stock ps,e_product_price pp 
                  WHERE pd.id = ps.s_product_id AND ps.s_product_id = pp.product_id AND pp.product_id=pd.id AND pp.batch_no=ps.s_batch_no AND pp.qty_left > 0 AND pp.cos_id=$cos_id AND pd.active=1 
                  AND pd.cos_id=$cos_id AND ps.active=1 AND ps.cos_id=$cos_id";
        break;
    
    case 'get_invoice_no':
        $query = "SELECT MAX(invoice_no) AS highest_number FROM `e_normal_order_details` WHERE cos_id=$cos_id";
        break; 
        
    case 'customers':
        $query = "SELECT `id`,`name`,`mobile`,`email_id` FROM `e_user_details` WHERE active=1 AND cos_id =$cos_id";
        break; 
        
    default:
        http_response_code(400);
        echo json_encode(array("error" => "Invalid query type"));
        exit;
}

$query_result = $mysqli->query($query);
$row_count = $query_result->num_rows;

if ($row_count > 0) {
    $result = mysqli_fetch_all($query_result, MYSQLI_ASSOC);
    http_response_code(200);
    echo json_encode($result);
} else {
    http_response_code(400);
    echo json_encode(array("error" => "No data found"));
}

?>
