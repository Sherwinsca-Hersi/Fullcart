<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once '../api/config.php';

header('Content-type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

$city_id = '1001';  
$user_id = $data['user_id'];

if($user_id == '') {
    $returnArr = array("ResponseCode" => "401", "Result" => "false", "ResponseMsg" => "Keyword missing. Please try again.");
} else {
  
    $counter = $mysqli->query("SELECT f.user_id, f.product_id, f.added_on, f.updated_on, f.active,pd.id,pd.city_id,pd.unit, pd.is_loose,
       pd.p_title, pd.sku_id, pd.cat_id, pd.sub_cat_id,pd.p_img,pd.brand,pd.p_variation,pd.cgst,pd.sgst,
       pd.igst,pd.p_disc,pd.p_disc,pd.type,pd.p_desc, ps.s_disc, pp.mrp, pp.out_price, pp.qty_left, pp.batch_no
        FROM e_favorite_list f
        LEFT JOIN e_product_details pd ON pd.id = f.product_id AND pd.active = 1 AND pd.cos_id = '$cos_id'
        LEFT JOIN e_product_stock ps ON ps.s_product_id = pd.id AND ps.cos_id = '$cos_id'
        LEFT JOIN e_product_price pp ON pp.product_id = pd.id AND pp.qty_left > 0 AND pp.cos_id = '$cos_id'
        WHERE f.user_id = '$user_id' AND f.active = 1 and pd.active=1
        GROUP BY pd.id
        ORDER BY f.added_on DESC");

    if($counter->num_rows > 0) {
        $products = array();
        $lp = array();

        while($rows = $counter->fetch_assoc()) {
            $product = array();
            
            // print_r($product);
            
            $product['product_id'] = $rows['id'];
            $product['city_id'] = $rows['city_id'];
            $product['cat_id'] = $rows['cat_id'];

            // Fetch category name
            $cat_query = $mysqli->query("SELECT title FROM e_category_details WHERE id = '".$rows['cat_id']."' AND cos_id = '$cos_id'");
            $catname = $cat_query->fetch_assoc();
            $product['catname'] = isset($catname['title']) ? $catname['title'] : '';

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
            
            $product['model_variation'] = $rows['p_variation'];
	        $product['model_unit'] = $rows['unit'];
        
            $product['description'] = $rows['p_desc'];
            $imgList = !empty($rows['imgs']) && strpos($rows['imgs'], '|') !== false 
                ? explode('|', $rows['imgs']) 
                : [];
            $product['imgs'] = $imgList;
            
            $product['is_editable'] = $rows['is_loose'] == '1' ? true : false;


            $lp[] = $product;
        }

        $returnArr = array("ResponseCode" => "200", "Result" => "true", "ResponseMsg" => "Product List Retrieved Successfully","FavoritesData" => $lp);
    } else {
        $returnArr = array("ResponseCode" => "404", "Result" => "false", "ResponseMsg" => "Product List Not Found");
    }
}

echo json_encode($returnArr);
mysqli_close($mysqli); 
?>
