 <?php 
include_once '../api/config.php';
include_once '../api/function.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);

function siteURL() {
  $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || 
    $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
  $domainName = $_SERVER['HTTP_HOST'];
  return $protocol.$domainName;
}

$product = array();
 $ProductData = $data['ProductData'];
for($i=0;$i<count($ProductData);$i++)
{

$product_id = mysqli_real_escape_string($mysqli,$ProductData[$i]['product_id']);

 $stock = $mysqli->query("SELECT * FROM e_product_details pd
                           LEFT JOIN ( SELECT ps1.* FROM e_product_stock ps1 WHERE ps1.cos_id = $cos_id AND ps1.s_product_id = $product_id ORDER BY ps1.active DESC LIMIT 1) ps 
                            ON pd.id = ps.s_product_id
                                INNER JOIN e_product_price pp 
                                    ON pd.id = pp.product_id
                                AND pp.batch_no = ps.s_batch_no
                                AND pp.qty_left >= 0
                                AND pp.cos_id = $cos_id
                                WHERE pd.cos_id = $cos_id
                                AND pd.active = 1
                                GROUP BY pd.id");
        
        
    while($row = $stock->fetch_assoc())
    {
        $product['product_id'] = $row['id'];
    	$product['product_regularprice'] = $row['mrp'];
    	$product['product_subscribeprice'] = $row['out_price'];
        $product['stock_level'] = ($row['qty_left'] < 0) ? 0 : $row['qty_left'];
    	$product['batch_no'] = $row['batch_no'];
        $d[] = $product;
    }
}

$response = array(
    'available_stock'=>$d
);
echo json_encode($response);
?>