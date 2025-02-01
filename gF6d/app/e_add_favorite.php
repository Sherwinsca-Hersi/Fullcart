<?php
include_once '../api/config.php';
include_once '../api/function.php';
include_once '../api/response.php';
header('Content-type: application/json');
$data = json_decode(file_get_contents('php://input'), true);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$product_id  = $data['product_id'];
$user_id     = $data['user_id'];
$platform    = $data['platform'];

$exists_query = $mysqli->query("SELECT active FROM e_favorite_list WHERE user_id='" . $user_id . "' AND product_id='" . $product_id . "' AND cos_id = $cos_id");

if ($exists_query->num_rows > 0) {
    $row = $exists_query->fetch_assoc();
    $active_value = $row['active'];
} else {
    $active_value = null;
}

try {
    if ($active_value === null) {
        $field_values = "cos_id,user_id,product_id,platform";
        $data_values  = "$cos_id,'" . $user_id . "','" . $product_id . "','" . $platform . "'";
        
        $final_query = "INSERT INTO e_favorite_list ($field_values) VALUES ($data_values)";
        $insert_query = $mysqli->query($final_query);
        
        $active_value = 1;
    } elseif ($active_value == '1') {
        $final_query = "UPDATE e_favorite_list SET active=0 WHERE user_id='" . $user_id . "' AND product_id='" . $product_id . "' AND cos_id = $cos_id";
        $insert_query = $mysqli->query($final_query);
        
        $active_value = 0;
    } elseif ($active_value == '0') {
        $final_query = "UPDATE e_favorite_list SET active=1 WHERE user_id='" . $user_id . "' AND product_id='" . $product_id . "' AND cos_id = $cos_id";
        $insert_query = $mysqli->query($final_query);
        
        $active_value = 1;
    }
} catch (mysqli_sql_exception $exception) {
    mysqli_rollback($mysqli);
    $insert_query = false;
    $err_msg = $exception->getMessage();
}

if ($insert_query == true) {
    http_response_code(200);
    echo json_encode(['success' => true, 'active' => $active_value]);
} else {
    mysqli_rollback($mysqli);
    http_response_code(400);
    $no_data['message'] = $no_data['message'] . " ::" . $err_msg  . " ::" . $final_query;
    echo json_encode(['success' => false, 'message' => $no_data['message']]);
}
?>
