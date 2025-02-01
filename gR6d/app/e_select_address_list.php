<?php 
include_once '../api/config.php';
$data = json_decode(file_get_contents('php://input'), true);
$u_id = $data['u_id'];
// $o_total = $data['o_total'];
if($u_id == '')
{
	$returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"User Id is empty");
}
else 
{
	$count = $mysqli->query("select * from e_address_details where user_id=".$u_id." and cos_id = '$cos_id' and active=1")->num_rows;
	if($count != 0)
	{
	$cy = $mysqli->query("select * from e_address_details where user_id=".$u_id." and cos_id = '$cos_id' and active=1");
	$p = array();
	$q = array();
	while($adata = $cy->fetch_assoc())
	{
		$p['id'] = $adata['id'];
		$p['u_id'] = $adata['user_id'];
		$p['hno'] = $adata['address_line_1'];
		$p['address'] = $adata['area'];
		$p['lat_map'] = $adata['lat'];
		$p['long_map'] = $adata['lng'];
		$p['name'] = $adata['name'];
		$p['mobile'] = $adata['mobile'];
		$p['pincode'] = $adata['pincode'];
		$p['city'] = $adata['city'];
		$p['state'] = $adata['state'];
		$p['landmark'] = $adata['landmark'];
		$p['type'] = $adata['type'];
		$reCity = str_replace(' ', '', strtolower($adata['city']));
		$reState = str_replace(' ', '', strtolower($adata['state']));
		if($adata['type'] == 'Office')
		{
			$p['address_image'] = 'a_icon/ic_office_current_location.png';
		}
		else if($adata['type'] == 'Home')
		{
			$p['address_image'] = 'a_icon/ic_home_current_location.png';
		}
		else
		{
			$p['address_image'] = 'a_icon/ic_other_current_location.png';
		}
		 $d_charge = $mysqli->query("select * from e_data_city where active = 1 and Replace(title, ' ','')='".$reCity."' OR (Replace(title, ' ','') = '".$reState."') OR title = 'Chennai' and cos_id = '$cos_id' LIMIT 1")->fetch_assoc();

$p['delivery_charge'] = $d_charge['d_charge'];
$p['disc_alert'] = $d_charge['disc_alert'];
$p['disc_alert_msg'] = $d_charge['disc_alert_msg'];
$p['min_amount'] = $d_charge['min_amt'];
		$q[] = $p;
	}
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Address List Retrived Successfully","AddressList"=>$q);
	}
	else 
	{
		$returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Address List Not Found");
	}
}
echo json_encode($returnArr);
?>