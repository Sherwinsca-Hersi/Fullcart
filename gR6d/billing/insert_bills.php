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

if (empty($data)) {
    http_response_code(400);
    echo json_encode($no_data);
}
    $o_id           = 0; 
    
    $invoice_query=$mysqli->query("SELECT MAX(invoice_no) FROM e_normal_order_details WHERE cos_id = $cos_id"); 
    $invoice_no = $invoice_query->fetch_array()[0];
    
    $id_query=$mysqli->query("SELECT MAX(id) FROM e_normal_order_details"); 
    $o_id = $id_query->fetch_array()[0];
    
    $subtotal       = "";
    $o_total        = "";
    $mobile         = "";
    $status         = "Completed";
    $bill_type      = 2;
    $currentDate    = date('Y-m-d');
    
    $insert_query=false;
    
    foreach($data as $item){

    $product_id     = $item['product_id'];
    $subtotal       = $item['subtotal'];
    $o_total        = $item['o_total'];
    $subtotal       = $item['subtotal'];
    $mobile         = $item['mobile'];
    
    $qty            = $item['qty'];
    $batch_no       = $item['batch_no'];
    $product_title  = $item['product_title'];
    $p_discount     = $item['p_discount'];
    $product_img    = $item['product_img'];
    $out_price      = $item['out_price'];
    $p_type         = $item['unit'];
    
    $cashier        = $item['cashier'];
    $payment_method = $item['payment_method'];
    $payment_id     = $item['payment_id'];


        $query_result = $mysqli->query("SELECT '" . $product_id . "' FROM `e_product_stock`s,`e_product_details`d,`e_product_price`p
                                        WHERE d.id=p.product_id AND p.product_id = s_product_id AND s_product_id = d.id AND s_batch_no = p.batch_no AND s.active=1 AND s.cos_id=$cos_id AND d.cos_id=$cos_id AND p.cos_id=$cos_id");
                                    
        $qty_query    = $mysqli->query("SELECT p.qty_left FROM `e_product_stock`s,`e_product_details`d,`e_product_price`p
                                        WHERE d.id=p.product_id AND p.product_id = s_product_id AND s_product_id = '" . $product_id . "' AND s_batch_no = p.batch_no AND p.qty_left >=0 AND s.active=1 AND s.cos_id=$cos_id AND d.cos_id=$cos_id AND p.cos_id=$cos_id");
        
        $row_count    = $query_result->num_rows;

    if($row_count>0){
        try {
        mysqli_autocommit($mysqli, false);
        mysqli_begin_transaction($mysqli);
        
        $qty_result = $qty_query->fetch_array()[0];
      
        $is_active   = $qty_result-$qty <= 0 ? 0 : 1;
        
        $final_query    = "UPDATE `e_product_price` SET qty_left = qty_left-$qty,up_platform=3 WHERE '" . $product_id . "'= product_id AND batch_no='" . $batch_no . "' AND cos_id=$cos_id";
        $insert_query   = $mysqli->query($final_query);
        
        if($is_active==0){
            $final_query    = "UPDATE `e_product_stock` SET active = $is_active,up_platform=3  WHERE '" . $product_id . "'= s_product_id AND s_batch_no='" . $batch_no . "' AND cos_id=$cos_id";
            $insert_query   = $mysqli->query($final_query);
        }

        if($insert_query == true){

            $insert_user_fields = "invoice_no,p_quantity,p_title,p_discount,p_img,p_price,p_type,batch_no,o_id,cos_id,platform";
            $insert_user_values = "$invoice_no+1, '" . $qty . "' , '" . $product_title . "', '" . $p_discount . "','" . $product_img . "','" . $out_price . "','" . $p_type . "','" . $batch_no . "',$o_id+1,$cos_id,3";
        
            $final_query = "INSERT INTO e_normal_order_product_details ($insert_user_fields) VALUES ($insert_user_values)";
            $insert_query = $mysqli->query($final_query);

            mysqli_autocommit($mysqli, true);             //implicit commit
        }
        
    } catch (mysqli_sql_exception $exception) {
        mysqli_rollback($mysqli);
        $insert_query = false;
        $err_msg = $exception->getMessage();
    }
    
        if ($insert_query == true) {
            http_response_code(200);
            echo json_encode($success);
        } else {
            mysqli_rollback($mysqli);
            http_response_code(400);
            $no_data['message'] = $no_data['message'] . " ::" . $err_msg  . " ::" . $final_query;
            echo json_encode($no_data);
    }
    }else{
            mysqli_rollback($mysqli);
            http_response_code(400);
            $no_data['message'] = $no_data['message'] . " ::" . $err_msg  . " ::" . $final_query;
            echo json_encode($no_data);
    }
}
    try{
        if($insert_query==true){
            
            $insert_user_fields = "invoice_no,o_total,subtotal,mobile,status,bill_type,o_date,p_method_id,address,d_charge,cou_id,cou_amt,wall_amt,name,t_slot,u_id,payment_active,created_by,trans_id,active,platform,cos_id";
            $insert_user_values = "$invoice_no+1, '" . $o_total . "','" . $subtotal . "','" . $mobile . "','" . $status . "','" . $bill_type . "','" . $currentDate . "','" . $payment_id . "','',0.0,0,0.0,0.0,'','',0,0,'" . $cashier . "','" . $payment_method . "',6,3,$cos_id";

            $final_query = "INSERT INTO e_normal_order_details ($insert_user_fields) VALUES ($insert_user_values)";
            $insert_query = $mysqli->query($final_query);
        }else{
            mysqli_rollback($mysqli);
                $insert_query = false;
                $err_msg = $exception->getMessage();
        }
            
        }catch (mysqli_sql_exception $exception) {
                mysqli_rollback($mysqli);
                $insert_query = false;
                $err_msg = $exception->getMessage();
        }
        
        if ($insert_query == true) {
            http_response_code(200);
            echo json_encode($success);
        } else {
            mysqli_rollback($mysqli);
            http_response_code(400);
            $no_data['message'] = $no_data['message'] . " ::" . $err_msg  . " ::" . $final_query;
            echo json_encode($no_data);
        }

                                    

?>
