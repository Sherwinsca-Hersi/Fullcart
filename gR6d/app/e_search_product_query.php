<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once '../api/config.php';

header('Content-type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

$city_id = '1001';  
$keyword = isset($data['keyword']) ? $data['keyword'] : '';

if($keyword == '') {
    $returnArr = array("ResponseCode" => "401", "Result" => "false", "ResponseMsg" => "Keyword missing. Please try again.");
} else {
    // Query for fetching product data with proper joins and grouping
    $counter = $mysqli->query("SELECT pd.id as productid,  pd.sku_id, pd.hsn_code, pd.barcode, pd.cat_id, pd.sub_cat_id, pd.p_img, pd.p_title, pd.brand, 
                                        pd.p_variation, pd.cgst, pd.sgst, pd.igst, pd.p_disc, pd.unit, pd.type, pd.p_desc ,pd.city_id,ps.s_disc,pp.mrp,pp.out_price,pp.batch_no,pp.qty_left,cd.title
                                FROM e_product_details pd 
                                LEFT JOIN (
                                    SELECT * FROM e_product_stock ps 
                                    WHERE ps.active=1 and ps.cos_id = '$cos_id'
                                    GROUP BY ps.s_product_id 
                                ) AS ps ON pd.id = ps.s_product_id
                                LEFT JOIN (
                                    SELECT * FROM e_product_price pp 
                                    WHERE pp.qty_left > 0 AND pp.cos_id = '$cos_id' 
                                    ) AS pp ON ps.s_product_id = pp.product_id
                                LEFT JOIN e_category_details cd ON pd.cat_id = cd.id 
                                LEFT JOIN e_subcategory_details scd ON pd.sub_cat_id = scd.id
                                WHERE pd.active = 1 AND pd.is_loose=0 AND pd.cos_id = '$cos_id'
                                    AND (
                                        pd.p_title LIKE '%$keyword%' OR 
                                        cd.title LIKE '%$keyword%' OR 
                                        scd.title LIKE '%$keyword%'
                                    )
                                GROUP BY pd.id");

    if($counter->num_rows > 0) {
        $products = array();
        $lp = array();

        while($rows = $counter->fetch_assoc()) {
            $product = array();
            $product['product_id'] = $rows['productid'];
            $product['city_id'] = $rows['city_id'];
            $product['cat_id'] = $rows['cat_id'];

            // Fetch category name
            // $cat_query = $mysqli->query("SELECT title FROM e_category_details WHERE id = ".$rows['cat_id']." AND cos_id = '$cos_id'");
            // $catname = $cat_query->fetch_assoc();
            $product['catname'] = $rows['title'];

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
            $product['stock_level'] = $rows['qty_left']=='0' ? null : $rows['qty_left'];
            $product['batch_no'] = $rows['batch_no'];

            $lp[] = $product;
        }

        $returnArr = array("SearchData" => $lp, "ResponseCode" => "200", "Result" => "true", "ResponseMsg" => "Product List Retrieved Successfully");
    } else {
        $returnArr = array("ResponseCode" => "404", "Result" => "false", "ResponseMsg" => "Product List Not Found");
    }
}

echo json_encode($returnArr);
mysqli_close($mysqli); 
?>
