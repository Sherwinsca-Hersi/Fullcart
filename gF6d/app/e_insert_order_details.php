<?php
include_once '../api/config.php';
include_once '../api/function.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
$platform = $data['platform'];

ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);

// Error Log:
$dateTime = date('Y-m-d_H:i:s');

$logFolderPath = dirname(__FILE__) . '/error_logs/';
if (!file_exists($logFolderPath) && !is_dir($logFolderPath)) {
    if (!mkdir($logFolderPath, 0777, true)) {
        die('Failed to create folders...');
    }
}

$dateTime = date('Ymd_His');
$logFilePath = $logFolderPath . 'log_' . $dateTime . '_' . $data['name'] . '_' . $data['mobile'] . '.txt';
$logFile = fopen($logFilePath, 'a');
if (!$logFile) {
    die('Failed to open log file for writing');
}


function siteURL() {
    $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $domainName = $_SERVER['HTTP_HOST'];
    return $protocol . $domainName;
}

try{
    if (empty($data['u_id']) || empty($data['ProductData'])) {
    
    $returnArr = array("ResponseCode" => "401", "Result" => "false", "ResponseMsg" => "Products are empty!!");
    fwrite($logFile, json_encode($returnArr) . "\n");

    echo json_encode($returnArr);
    exit();
    
} else {
    $u_id = $data['u_id'];
    $p_method_id = $data['p_method_id'];
    $ordate = explode('-', $data['order_date']);
    $order_date = $ordate[2] . '-' . $ordate[1] . '-' . $ordate[0];
    $full_address = $data['full_address'];
    $d_charge = number_format((float)$data['d_charge'], 2, '.', '');
    $cou_id = $data['cou_id'] ?? '';
    $landmark = $data['landmark'];
    $wall_amt = number_format((float)$data['wall_amt'], 2, '.', '');
    $cou_amt = number_format((float)$data['cou_amt'], 2, '.', '');
    $sdate = explode('-', $data['ndate']);
    $sdates = $sdate[2] . '-' . $sdate[1] . '-' . $sdate[0];
    $t_slot = $data['t_slot'];
    $transaction_id = $data['transaction_id'];
    $product_total = number_format((float)$data['product_total'], 2, '.', '');
    $product_subtotal = number_format((float)$data['product_subtotal'], 2, '.', '');
    $a_note = mysqli_real_escape_string($mysqli, $data['a_note']);
    $name = mysqli_real_escape_string($mysqli, $data['name']);
    $mobile = mysqli_real_escape_string($mysqli, $data['mobile']);
    $table = "e_normal_order_details";
    $cgst = $data['tot_cgst'];
    $sgst = $data['tot_sgst'];
    $igst = $data['tot_igst'];

    // Inserting Order Details :
    $field_values = array("cos_id", "u_id", "o_date", "p_method_id", "address", "d_charge", "cou_id", "cou_amt", "o_total", "subtotal", "trans_id", "a_note", "wall_amt", "tot_cgst", "tot_sgst", "tot_igst", "name", "mobile", "status", "landmark", "t_slot", "platform");
    $data_values = array("$cos_id", "$u_id", "$order_date", "$p_method_id", "$full_address", "$d_charge", "$cou_id", "$cou_amt", "$product_total", "$product_subtotal", "$transaction_id", "$a_note", "$wall_amt", "$cgst", "$sgst", "$igst", "$name", "$mobile", 'Order Placed', "$landmark", "$t_slot", "$platform");

    $h = new CommonFunction();
    $o_id = $h->Ins_Api_id($field_values, $data_values, $table);

    // Handling Product Details :
    $ProductData = $data['ProductData'];
    for ($i = 0; $i < count($ProductData); $i++) {
        $product_id = mysqli_real_escape_string($mysqli, $ProductData[$i]['product_id']);
        $title = mysqli_real_escape_string($mysqli, $ProductData[$i]['title']);
        $type = mysqli_real_escape_string($mysqli, $ProductData[$i]['variation']);
        $cost = $ProductData[$i]['price'] ?? '0';
        $qty = $ProductData[$i]['qty'] ?? '0';
        $discount = $ProductData[$i]['discount'] ?? '0';
        $image = $ProductData[$i]['image'] ?? '';
        $batch_no = $ProductData[$i]['batch_no'] ?? '';
        $instruction = $ProductData[$i]['instruction'] ?? '';

        // Inserting product details
        $table = "e_normal_order_product_details";
        $field_values = array("cos_id", "o_id", "batch_no", "p_quantity", "p_title", "p_discount", "p_img", "p_price", "p_type", "platform", "instruction");
        $data_values = array("$cos_id", "$o_id", "$batch_no", "$qty", "$title", "$discount", "$image", "$cost", "$type", "$platform", "$instruction");
        $h = new CommonFunction();
        $h->Ins_latest_Api($field_values, $data_values, $table);
        
        fwrite($logFile, "Order $o_id Products Error : $i " . json_encode([
                         "cos_id" => $cos_id,
                         "o_id" => $o_id,
                         "batch_no" => $batch_no,
                         "p_quantity" => $qty,
                         "p_title" => $title,
                         "p_discount" => $discount,
                         "p_img" => $image,
                         "p_price" => $cost,
                         "p_type" => $type,
                         "platform" => $platform
                        ]) . "\n");


        // Updating stock quantity
        $vp = $mysqli->query("SELECT qty_left FROM e_product_price WHERE batch_no='$batch_no' AND product_id=$product_id AND cos_id='$cos_id'")->fetch_assoc();
        $mt = intval($vp['qty_left']) - intval($qty);
        $table = "e_product_price";
        $field = array('qty_left' => "$mt", 'up_platform' => "$platform");
        $where = "WHERE batch_no='$batch_no' AND product_id=$product_id AND cos_id='$cos_id'";
        $h->Ins_update_Api($field, $table, $where);

        // Check for low stock levels
        $vp = $mysqli->query("SELECT reorder_level, emergency_level FROM e_product_details WHERE active=1 AND id=$product_id AND cos_id='$cos_id'")->fetch_assoc();
        $reorder_level = intval($vp['reorder_level']);
        $emergency_level = intval($vp['emergency_level']);
        if ($mt <= $emergency_level || $mt <= $reorder_level) {
            // Send notification (SMS or otherwise)
            // $mobile = "918754118624";
            // $message = "Stock for product #$product_id is low.";
            // MSG91 API call for notifications ----- to be added
        }

        // Deactivate stock if below 1
        if ($mt < 1) {
            $table = "e_product_stock";
            $field = array('active' => "0", 'up_platform' => "$platform");
            $where = "WHERE s_batch_no='$batch_no' AND s_product_id=$product_id AND cos_id='$cos_id'";
            $h->Ins_update_Api($field, $table, $where);
        }
    }

    // Deduct wallet amount (if used) :
    if ($wall_amt != 0) {
        $vp = $mysqli->query("SELECT * FROM e_user_details WHERE id=$u_id AND cos_id='$cos_id'")->fetch_assoc();
        $mt = intval($vp['wallet']) - intval($wall_amt);
        $table = "e_user_details";
        $field = array('wallet' => "$mt", 'up_platform' => "$platform");
        $where = "WHERE id=$u_id AND cos_id='$cos_id'";
        $h->Ins_update_Api($field, $table, $where);

        $table = "e_wallet_report_details";
        $field_values = array("cos_id", "u_id", "message", "status", "amt", "platform");
        $data_values = array("$cos_id", "$u_id", "Wallet used in Order #$o_id", 'Debit', "$wall_amt", "$platform");
        $h->Ins_latest_Api($field_values, $data_values, $table);
    }
    
    //Apply Coupon (if used) :
    if (empty($cou_id) || $cou_id!='0') {
    $table = "e_cupon_used";
    $coupon_fields = "cos_id, cupon_id, user_id, order_id, times, platform";
    $coupon_values = "'$cos_id', '$cou_id', '$u_id', '$o_id', '1', '$platform'";

    $query = "INSERT INTO $table ($coupon_fields) VALUES ($coupon_values)";
    $insert = $mysqli -> query($query);
    fwrite($logFile, "INSERT INTO $table ($coupon_fields) VALUES ($coupon_values)\n");
    }


    // Sending notification :
    $sql = "SELECT n.token, u.name FROM e_notification n, e_user_details u WHERE u.id = '$u_id' AND n.u_id = u.id AND u.cos_id = '$cos_id' AND n.cos_id = '$cos_id' ORDER BY n.created_ts DESC LIMIT 1";
    $sel = $mysqli->query($sql)->fetch_assoc();
    $token[] = $sel["token"] ?? '';
    $timestamp = date("Y-m-d H:i:s");
    $title_main = "Order Placed";
    $description = ucfirst(explode(" ", $name)[0]) . ', Your Order #' . $o_id . ' has been received.';
    $table = "e_notification_details";
    $field_values = array("cos_id", "u_id", "datetime", "title", "description", "platform");
    $data_values = array("$cos_id", "$u_id", "$timestamp", "$title_main", "$description", "$platform");
    $h->send_notification($token, $description, $title_main);
    $h->Ins_latest_Api($field_values, $data_values, $table);

    // Return success response :
    $tbwallet = $mysqli->query("SELECT * FROM e_user_details WHERE id=$u_id AND cos_id='$cos_id'")->fetch_assoc();
    
    $returnArr = array("ResponseCode" => "200", "Result" => "true", "ResponseMsg" => "Order Placed Successfully", "wallet" => $tbwallet['wallet'], "orderId" =>"' $o_id'");
    echo json_encode($returnArr);
}
    
} catch (Exception $e) {
    $returnArr = array("ResponseCode" => "500", "Result" => "false", "ResponseMsg" => $e->getMessage());
    fwrite($logFile, "Order details not inserted : " . json_encode($returnArr) . "\n");
    echo json_encode($returnArr);
}
fclose($logFile);
?>