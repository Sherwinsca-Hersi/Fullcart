<?php 
include_once '../api/config.php';
header('Content-type: application/json');
$data = json_decode(file_get_contents('php://input'), true);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$city_id = '1001';                      // $data['city_id'];

$search_type = $data['search_type'];

switch (true) {
    case ($search_type == 'products'):
            $query = "SELECT pd.p_title FROM e_product_details pd,e_product_stock ps,e_product_price pp 
                  WHERE ps.active=1 AND pd.active=1 AND pd.id = ps.s_product_id AND ps.s_product_id = pp.product_id AND pp.product_id=pd.id AND pp.qty_left > 0
                AND pp.batch_no=ps.s_batch_no AND ps.cos_id=$cos_id AND pp.cos_id=$cos_id AND pd.cos_id=$cos_id GROUP BY pd.p_title";
        
            $counter = $mysqli->query($query);
        
        break;
        
    case ($search_type == 'favorite_products'):
        
        $user_id     = $data['user_id'];

        $query = "SELECT `id`,`user_id`,`product_id`,`added_on`,`updated_on`,`active` FROM `e_favorite_list` WHERE active=1 AND user_id='" . $user_id . "' AND cos_id = $cos_id ";
        
        $counter = $mysqli->query($query);
        
        break;
        
    case ($search_type == 'favorite_product_details'):
        
        $user_id     = $data['user_id'];

        $query = "SELECT *
        FROM e_product_details pd 
        LEFT JOIN (
            SELECT ps.`s_product_id`, ps.`qty`, ps.`stock_date`, ps.`supplier_id`, ps.`s_batch_no`, ps.`s_expiry_date`, ps.`in_price`, ps.`s_mrp`, `s_out_price`, `invoice_no`, `stock_bill`, `s_disc`, `in_order`,ps.cos_id
            FROM e_product_stock ps WHERE ps.active=1 and ps.cos_id = $cos_id 
            GROUP BY ps.s_product_id
        ) AS ps ON pd.id = ps.s_product_id
        LEFT JOIN (
            SELECT pp.`product_id`,`batch_no`, `mrp`, `out_price`, `qty_left`, `missing_qty`, `expiry_date`
            FROM e_product_price pp WHERE pp.qty_left > 0 and pp.cos_id = $cos_id
            GROUP BY pp.product_id 
        ) AS pp ON ps.s_product_id = pp.product_id
        LEFT JOIN (
        	SELECT id, user_id, product_id, added_on, updated_on FROM e_favorite_list fl WHERE fl.active=1 AND fl.user_id='" . $user_id . "' AND fl.cos_id = $cos_id 
        ) AS fl ON pp.product_id = fl.product_id
        WHERE pd.active=1 AND pd.is_loose=0 AND pp.product_id = fl.product_id AND pd.cos_id = ps.cos_id GROUP BY pd.id";
        
        $counter = $mysqli->query($query);
        
        break;
}

$row_count = $counter->num_rows;

if ($row_count > 0) {
    $result = mysqli_fetch_all($counter, MYSQLI_ASSOC);
    http_response_code(200);
    echo json_encode($result);
} else {
    http_response_code(400);
    echo json_encode(array("error" => "No data found"));
}

mysqli_close($mysqli);

?>
