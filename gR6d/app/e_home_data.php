<?php 
include_once '../api/config.php';

header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
$u_id = $data['u_id'];
$platform  = $data['platform'];

$city_id = "1001";//$data['city_id'];

if($u_id == '' or $city_id == '')
{

	$returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went wrong. Pls try again");
}
else 
{
	$v = array();
	$cp = array(); 
	$d = array();
	$pop = array();
	$sec = array();
	$mon = array();
	
	$banner = $mysqli->query("select * from e_dat_banner where active=1 and cos_id = '$cos_id'");
    $vbanner = array();
    while($row = $banner->fetch_assoc())
    {
    	$vbanner['id'] = $row['id'];
    	$vbanner['img'] = $row['bg_img'];
    	
    	if($row['c_id'] == 0)
    	{
        	$vbanner['cat_id'] = 0;
        	$vbanner['title'] = '';
        	$vbanner['cat_img'] = '';	
    	}
    	else 
    	{
    		$cato = $mysqli->query("select * from e_category_details where id=".$row['c_id']." and cos_id = '$cos_id'")->fetch_assoc();
    		$vbanner['cat_id'] = $cato['id'];
        	$vbanner['title'] = $cato['title'];
        	$vbanner['cat_img'] = $cato['c_img'];
    	}
        $v[] = $vbanner;
    }
    
    $cato = $mysqli->query("SELECT cd.id,cd.c_img,cd.title FROM e_category_details cd JOIN e_product_details pd ON cd.id = pd.cat_id WHERE pd.active = 1
                            AND cd.active = 1
                            AND pd.cos_id = '$cos_id' GROUP BY pd.cat_id");
    $cat = array();
    while($row = $cato->fetch_assoc())
    {
    	$cat['id'] = $row['id'];
    	$cat['title'] = $row['title'];
    	$cat['cat_img'] = $row['c_img'];
        $cp[] = $cat;
    }
    
    
    $cato = $mysqli->query("select * from e_data_coupon where active=1 and cos_id = '$cos_id'");
    $coupon = array();
    $timestamp = date("Y-m-d");
    while($row = $cato->fetch_assoc())
    {
    	if($row['c_date'] < $timestamp)
    	{
    		$mysqli->query("update e_data_coupon set active=0, up_platform = '$platform' where id=".$row['id']." and cos_id = '$cos_id'");
    	}
    	else 
    	{
    	    
    	    $coupon['id'] = $row['id'];
    		$coupon['c_img'] = $row['c_img'];
    		
    		$coupon['c_date'] = $row['c_date'];
    		
    		$coupon['c_desc'] = $row['c_desc'];
    		
    		$coupon['c_value'] = $row['c_value'];
    		$coupon['coupon_code'] = $row['c_code'];
    		$coupon['coupon_title'] = $row['c_title'];
    		$coupon['min_amt'] = $row['min_amt'];
    		
    // 	$coupon['id'] = $row['id'];
    // 	$coupon['coupn_code'] = $row['c_code'];
    // 	$coupon['coupon_img'] = $row['c_img'];
    // 	$coupon['coupon_desc'] = $row['c_desc'];
    // 	$date=date_create($row['c_date']);
    // 	$coupon['coupon_expire_date'] = date_format($date,"d M");
        $sec[] = $coupon;
    	}
    }
    
    $rule = $mysqli->query("select * from e_dat_wallet_rule where active=1 and cos_id = '$cos_id'");
    $walletrule = array();
    $timestamp = date("Y-m-d");
    while($row = $rule->fetch_assoc())
    {
    
    	$walletrule['below'] = $row['below'];
    	$walletrule['above'] = $row['above'];
    	$walletrule['type'] = $row['type'];
    	$walletrule['description'] = $row['description'];
    	$walletrule['bonus'] = $row['bonus'];
        $wrule[] = $walletrule;
    }
    
    
    $month = $mysqli->query("select * from e_dat_month where active=1 and cos_id = '$cos_id'");
    $m = array();
    while($row = $month->fetch_assoc())
    {
    
    	$m['m_digit'] = $row['m_digit'];
    	$m['title'] = $row['title'];
    	$m['offer_amt'] = $row['offer_amt'];
        $mon[] = $m;
    }
    
    $delivery = $mysqli->query("select * from e_dat_delivery where active=1 and cos_id = '$cos_id'");
    $dell = array();
    while($row = $delivery->fetch_assoc())
    {
    
    	$dell['d_digit'] = $row['d_digit'];
    	$dell['offer_amt'] = $row['offer_amt'];
    	$dell['title'] = $row['title'];
        $del[] = $dell;
    }
    
    $timeslot = $mysqli->query("select * from e_dat_timeslot where active=1 and cos_id = '$cos_id'");
    $ts = array();
    while($row = $timeslot->fetch_assoc())
    {
    
        $ts['id'] = $row['id'];
    	$ts['min_time'] = date("g:i A", strtotime($row['min_time']));
    	$ts['max_time'] = date("g:i A", strtotime($row['max_time']));
        $times[] = $ts;
    }
    
    
    
    $main_data = $mysqli->query("select * from e_dat_setting where active=1 and cos_id = '$cos_id'")->fetch_assoc();
    $tbwallet = $mysqli->query("select * from e_user_details where id=".$u_id." and cos_id = '$cos_id'")->fetch_assoc();
    
    $kp = array('Banner'=>$v,'Catlist'=>$cp,"Couponlist"=>$sec,"Main_Data"=>$main_data,"wallet"=>$tbwallet['wallet'],"wallet_rule"=>$wrule,"month_list"=>$mon,"delivery_time"=>$del,"time_slot"=>$times);
    // $kp = array('Banner'=>$v,'Catlist'=>$cp,'f_stock'=>$d,"Collection"=>$pop,"Couponlist"=>$sec,"Main_Data"=>$main_data,"wallet"=>$tbwallet['wallet'],"wallet_rule"=>$wrule);
    	
    	$returnArr = array("ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Home Data Retrived Successfully","ResultData"=>$kp);
}

echo json_encode($returnArr);
?>