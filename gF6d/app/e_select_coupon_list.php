<?php 
include_once '../api/config.php';

header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);

$user_id    = $data['u_id'];
$platform   = $data['platform'];

$pol = array();
$c = array();
$timestamp = date("Y-m-d");

$sel = $mysqli->query("SELECT  cd.id,cd.c_code,cd.c_title, cd.c_img, cd.c_date, cd.c_desc, cd.c_value, cd.min_amt, cd.min_lvl
                        FROM  e_data_coupon cd
                        LEFT JOIN  e_cupon_used cu ON cd.id = cu.cupon_id AND cu.user_id = '$user_id' AND cu.active = 1
                        WHERE cd.active = 1 AND cu.cupon_id IS NULL AND cd.cos_id = '$cos_id'");

while($row = $sel->fetch_assoc())
{
    if($row['c_date'] < $timestamp)
	{
		$mysqli->query("update e_data_coupon set active=0, up_platform='$platform' where id=".$row['id']." and cos_id = '$cos_id'");
	}
	else 
	{
		$pol['id'] = $row['id'];
		$pol['c_img'] = $row['c_img'];
		
		$pol['c_date'] = $row['c_date'];
		
		$pol['c_desc'] = $row['c_desc'];
		
		$pol['c_value'] = $row['c_value'];
		$pol['coupon_code'] = $row['c_code'];
		$pol['coupon_title'] = $row['c_title'];
		$pol['min_amt'] = $row['min_amt'];
		$c[] = $pol;
	}	
	
}
if(empty($c))
{
	$returnArr = array("couponlist"=>$c,"ResponseCode"=>"200","Result"=>"false","ResponseMsg"=>"Coupon Not Found");
}
else 
{
$returnArr = array("couponlist"=>$c,"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Coupon List Found");
}
echo json_encode($returnArr);
?>