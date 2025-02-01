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
$search_type = isset($data['search_type']) ? $data['search_type'] : '';

$response = [];
$query_result = false;
$err_msg = '';

try{
    switch ($search_type) {
        
        case 'fetch_all_orders':
        
            $query_result = $mysqli->query("SELECT od.*, sd.s_name, sd.s_mobile, sd.email, sd.dob, sd.s_address, sd.salary, sd.joining_date
                                            FROM `e_normal_order_details` od, `e_salesman_details` sd
                                            WHERE od.salesman_id = sd.id AND od.cos_id = $cos_id AND sd.cos_id = $cos_id AND od.bill_type = 1");
        break;
        
        case 'fetch_delivery_person_orders':
        
            $delivery_person_id = $data['delivery_person_id'];
            
            $query_result=$mysqli->query("SELECT * FROM `e_salesman_details`sd,`e_normal_order_details`od WHERE sd.id = '".$delivery_person_id."' AND sd.active=1 
                                            AND od.salesman_id = sd.id AND sd.cos_id=$cos_id AND od.cos_id=$cos_id");
        break;
        
        case 'fetch_category_stocks':
        
            $query_result=$mysqli->query("SELECT 
                                            pd.p_title,pd.p_variation,pd.unit, pp.missing_qty, pp.qty_left,ps.s_product_id, ps.qty, ps.stock_date, ps.supplier_id, ps.s_batch_no, ps.s_expiry_date, 
                                            ps.in_price, ps.s_mrp, ps.s_out_price, pd.p_img,
                                            cd.id, cd.title, cd.c_img
                                        FROM 
                                            e_product_stock ps
                                        JOIN 
                                            e_product_price pp ON pp.cos_id = ps.cos_id AND pp.product_id = ps.s_product_id AND pp.batch_no = ps.s_batch_no
                                        JOIN 
                                            e_product_details pd ON pd.cos_id = ps.cos_id AND pd.id = ps.s_product_id
                                        JOIN 
                                            e_category_details cd ON cd.id = pd.cat_id
                                        WHERE 
                                            ps.cos_id = $cos_id AND ps.active = 1 AND pp.active = 1 AND cd.active = 1 AND cd.cos_id = $cos_id");
        break;
        
        case 'fetch_stocks_category_wise':
            
            $category_id = $data['cat_id'];
        
            $query_result = $mysqli->query("SELECT 
                                            pd.p_title,pd.p_variation,pd.unit, pp.missing_qty, pp.qty_left,ps.s_product_id, ps.qty, ps.stock_date, ps.supplier_id, ps.s_batch_no, ps.s_expiry_date, 
                                            ps.in_price, ps.s_mrp, ps.s_out_price, pd.p_img,
                                            cd.id, cd.title, cd.c_img
                                        FROM 
                                            e_product_stock ps
                                        JOIN 
                                            e_product_price pp ON pp.cos_id = ps.cos_id AND pp.product_id = ps.s_product_id AND pp.batch_no = ps.s_batch_no
                                        JOIN 
                                            e_product_details pd ON pd.cos_id = ps.cos_id AND pd.id = ps.s_product_id AND pd.cat_id='".$category_id."'
                                        JOIN 
                                            e_category_details cd ON cd.id = pd.cat_id
                                        WHERE 
                                            ps.cos_id = $cos_id AND ps.active = 1 AND pp.active = 1 AND cd.active = 1 AND cd.cos_id = $cos_id");
        break;

        default:
            http_response_code(400);
            $response['message'] = "Invalid action";
            echo json_encode($response);
        break;
    }
    
        } catch (mysqli_sql_exception $exception) {
            http_response_code(404);
            $query_result = false;
            $err_msg = $exception->getMessage();
            echo $err_msg;
    }

        $row_count = $query_result->num_rows;

            if ($row_count > 0) {
                $result = mysqli_fetch_all($query_result, MYSQLI_ASSOC);
                $response = $result;
                http_response_code(200);
                echo json_encode($response);
            } else {
                http_response_code(404);
                $response['message'] = "No data found";
                echo json_encode($response);
        }

?>
