<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once '../api/config.php';

$data = json_decode(file_get_contents("php://input"), true);

$response = [];
$err_msg = '';

if (!isset($data['u_id'])) {
    http_response_code(400); 
    echo json_encode(['responseCode' => 400, 'message' => 'Missing required parameters']);
    exit;
}

$user_id = $mysqli->real_escape_string($data['u_id']);

try {
    $result = $mysqli->query("SELECT od.*, od.active as active_status,sd.s_name, sd.s_mobile, opd.*,sd.id AS packager_id
                              FROM e_normal_order_details od
                              JOIN e_salesman_details sd ON od.salesman_id = sd.id
                              JOIN e_normal_order_product_details opd ON od.id = opd.o_id
                              WHERE od.cos_id = $cos_id AND sd.cos_id = $cos_id AND od.salesman_id = $user_id
                              ORDER BY od.updated_ts DESC");
    
    if ($result->num_rows > 0) {
        $orders = [];
        $packager_id = null;
        $packager_name = '';
        $packager_mobile = '';

        while ($row = $result->fetch_assoc()) {
            $order_id = $row['o_id'];  

            if (!$packager_id) {
                $packager_id = $row['packager_id'];
                $packager_name = $row['s_name'];
                $packager_mobile = $row['s_mobile'];
            }

            if (!isset($orders[$order_id])) {
                $orders[$order_id] = [
                    'order_id' => $order_id,
                    'customer_id' => $row['u_id'],
                    'customer_name' => $row['name'],
                    'order_date' => $row['o_date'],
                    'active' => $row['active_status'],
                    'order_total' => $row['o_total'],
                    'products' => []
                ];
            }

            $orders[$order_id]['products'][] = [
                'product_name' => $row['p_title'],
                'product_img' => $row['p_img'],
                'quantity' => $row['p_quantity'],
                'variation' => $row['p_type'],
                'price' => $row['p_price'],
            ];
        }

        $response = array_values($orders);

        http_response_code(200); 
        echo json_encode([
            'responseCode' => 200, 
            'packager_id' => $packager_id,
            'packager_name' => $packager_name,
            'packager_mobile' => $packager_mobile,
            'orders' => $response
        ]);

    } else {
        http_response_code(404); 
        echo json_encode(['responseCode' => 404, 'message' => "No packager orders found"]);
    }

} catch (mysqli_sql_exception $exception) {
    http_response_code(500); 
    echo json_encode(['responseCode' => 500, 'message' => $exception->getMessage()]);
}

?>
