<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once '../api/config.php';

header('Content-type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$city_id = '1001';  

// Optimized query
$counter = $mysqli->query("
    SELECT pd.*, ps.*, pp.*, cd.title as catname
    FROM e_product_details pd 
    LEFT JOIN e_product_stock ps ON pd.id = ps.s_product_id AND ps.active = 1 AND ps.cos_id = '$cos_id'
    LEFT JOIN e_product_price pp ON ps.s_product_id = pp.product_id AND pp.qty_left > 0 AND pp.cos_id = '$cos_id'
    LEFT JOIN e_category_details cd ON pd.cat_id = cd.id AND cd.cos_id = '$cos_id'
    WHERE pd.active = 1 AND pd.is_loose=0 AND pd.cos_id = '$cos_id' 
    GROUP BY pd.id
    ORDER BY ps.created_ts DESC 
    LIMIT 10
");

if($counter->num_rows > 0) {
    $products = array();
    $lp = array();

    while($rows = $counter->fetch_assoc()) {
        $product = array();
        $product['product_id'] = $rows['id'];
        $product['city_id'] = $rows['city_id'];
        $product['cat_id'] = $rows['cat_id'];
        $product['catname'] = $rows['catname'] ?? '';
        $product['sub_cat_id'] = $rows['sub_cat_id'];
        $product['product_discount'] = $rows['s_disc'];
        $product['product_variation'] = $rows['p_variation'] . " " . $rows['unit'];
        $product['product_regularprice'] = $rows['mrp'];
        $product['product_subscribeprice'] = $rows['out_price'];
        $product['product_title'] = $rows['p_title'];
        $product['product_img'] = $rows['p_img'];
        $product['cgst'] = $rows['cgst'];
        $product['sgst'] = $rows['sgst'];
        $product['igst'] = $rows['igst'];
        $product['stock_level'] = $rows['qty_left'];
        $product['batch_no'] = $rows['batch_no'];

        $lp[] = $product;
    }

    $returnArr = array("SearchData" => $lp, "ResponseCode" => "200", "Result" => "true", "ResponseMsg" => "Product List Retrieved Successfully");
} else {
    $returnArr = array("ResponseCode" => "404", "Result" => "false", "ResponseMsg" => "Product List Not Found");
}

echo json_encode($returnArr);
mysqli_close($mysqli); 
?>
