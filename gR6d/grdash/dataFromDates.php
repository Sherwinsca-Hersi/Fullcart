<?php 
require '../api/config.php';
header('Content-Type: application/json');

$today = new DateTime();
$firstDayOfWeek = clone $today;
$currentDayOfWeek = $today->format('w');
$daysToSubtract = $currentDayOfWeek;
$firstDayOfWeek->modify("-$daysToSubtract days");

//date Ranges
$startDate1 = $_GET['startDate1'] ?? $_POST['startDate1'] ?? $today->format('Y-m-d');
$endDate1 = $_GET['endDate1'] ?? $_POST['endDate1'] ?? date('Y-m-d');

$startDate1 = date('Y-m-d', strtotime(str_replace('/', '-', $startDate1)));
$endDate1 = date('Y-m-d', strtotime(str_replace('/', '-', $endDate1)));

//dashboard DateRange
$startDate1 = $_GET['startDate'] ?? $_POST['startDate'] ?? $today->format('Y-m-d');
$endDate1 = $_GET['endDate'] ?? $_POST['endDate'] ?? date('Y-m-d');

$startDate1 = date('Y-m-d', strtotime(str_replace('/', '-', $startDate1)));
$endDate1 = date('Y-m-d', strtotime(str_replace('/', '-', $endDate1)));


$recent_saled_product=$mysqli->query("SELECT id,invoice_no,created_ts,bill_type,name,mobile,o_total,DATE_FORMAT(created_ts, '%d %m %Y') AS formatted_date
FROM `e_normal_order_details` WHERE DATE(created_ts) BETWEEN '$startDate1' AND '$endDate1' AND cos_id = '$cos_id' ORDER BY id DESC LIMIT 5");

$recent_sales = [];
while ($recent_sales_product_details = $recent_saled_product->fetch_assoc()) {
    $recent_sales[] = $recent_sales_product_details;
}


echo json_encode($recent_sales);
?>