<?php 
include_once '../api/config.php';

header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
$cat_id = $data['cat_id'];
$city_id = $data['city_id'];
$u_id = $data['u_id'];
if($u_id == '' or $city_id == '' or $cat_id == '')
{
	$returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went wrong. Pls try again");
}
else 
{
	$coll = $mysqli->query("select * from e_subcategory_details where active=1 and c_id=".$cat_id." and cos_id = '$cos_id'");
$collection = array();
$pop = array();
while($row = $coll->fetch_assoc())
{
	$collection['id'] = $row['id'];
	$collection['title'] = $row['title'];
	$collection['image'] = $row['c_img'];
	
	$plist = $mysqli->query("SELECT *
        FROM e_product_details pd 
        LEFT JOIN (
            SELECT *
            FROM e_product_stock ps WHERE ps.active=1 and ps.cos_id = '$cos_id'
            GROUP BY ps.s_product_id
        ) AS ps ON pd.id = ps.s_product_id
        LEFT JOIN (
            SELECT *
            FROM e_product_price pp WHERE pp.qty_left > 0 and pp.cos_id = '$cos_id'
            GROUP BY pp.product_id 
        ) AS pp ON ps.s_product_id = pp.product_id
        WHERE pd.active=1 and sub_cat_id=".$row['id']." and pd.cos_id = '$cos_id' GROUP BY pd.id");
	$products = array();
	$lp = array();
	while($rows = $plist->fetch_assoc())
{
    $products['product_id'] = $rows['id'];
    $products['city_id'] = $rows['city_id'];
    $products['cat_id'] = $rows['cat_id'];
    $products['catname'] = $catname['title'];
    $products['sub_cat_id'] = $rows['sub_cat_id'];
    $products['product_discount'] = $rows['s_disc'];
    $products['product_variation'] = $rows['p_variation']. " " .$rows['unit'];
    $products['product_regularprice'] = $rows['mrp'];
    $products['product_subscribeprice'] = $rows['out_price'];
    $products['cgst'] = $rows['cgst'];
    $products['sgst'] = $rows['sgst'];
    $products['igst'] = $rows['igst'];
    $products['product_title'] = $rows['p_title'];
    $products['product_img'] = $rows['p_img'];
    $products['stock_level'] = $rows['qty_left'];
    $products['batch_no'] = $rows['batch_no'];
    $products['description'] = $rows['p_desc'];

    $products['imgs'] = !empty($rows['imgs']) && strpos($rows['imgs'], '|') !== false
        ? explode('|', $rows['imgs'])
        : [];
    
    $products['is_editable'] = $rows['is_loose'] == '1' ? true : false;

    $lp[] = $products;
}

	$collection['productdata'] = $lp;
	$pop[] = $collection;
}
if(empty($pop))
	{
	    
		$returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Subcategory Not Found","subcatproductlist"=>$pop);
	}
	else 
	{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Subcategory List Retrieved Successfully","subcatproductlist"=>$pop);
	}
}
echo json_encode($returnArr);
mysqli_close($mysqli);	
?>