<?php 
include_once '../api/config.php';
header('Content-Type: application/json');

$result = $mysqli->query("SELECT `id`, `title`, `c_img`, `d_charge`, `min_amt`, `disc_alert`, `disc_alert_msg`, `sort_order`, `created_ts`, `updated_ts` FROM `e_data_city` WHERE active=1 AND cos_id='$cos_id'");

$checkoutDetails = $result->fetch_all(MYSQLI_ASSOC);

$returnArr = array(
    "ResponseCode" => "200",
    "Result" => "true",
    "ResponseMsg" => "Country Code List Found",
    "CheckoutDetails" => $checkoutDetails
);

echo json_encode($returnArr);
?>
