<?php 
include_once '../api/config.php';

$data = json_decode(file_get_contents('php://input'), true);
header('Content-type: text/json');
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
if($data['u_id'] == '' or $data['order_id'] == '')
{
 $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went Wrong!!");    
}
else
{
	 $order_id = $mysqli->real_escape_string($data['order_id']);
 $u_id =  $mysqli->real_escape_string($data['u_id']);
 
  $sel = $mysqli->query("select * from e_subscribe_order_details where u_id=".$u_id." and id=".$order_id." and cos_id = '$cos_id'");
  
  
  $result = array();
  $pp = array();
  while($row = $sel->fetch_assoc())
    {
		$pp['order_id'] = $row['id'];
		
		$pname = $mysqli->query("select * from e_dat_payment where id=".$row['p_method_id']." and cos_id = '$cos_id'")->fetch_assoc();
		
		$pp['p_method_name'] = $pname['title'];
		$pp['customer_address'] = $row['address'];
		$pp['customer_name'] = $row['name'];
		$pp['customer_mobile'] = $row['mobile'];
		$pp['Delivery_charge'] = $row['d_charge'];
		$pp['Coupon_Amount'] = $row['cou_amt'];
		$pp['Wallet_Amount'] = $row['wall_amt'];
		$pp['Order_Total'] = $row['o_total'];
		$pp['Order_SubTotal'] = $row['subtotal'];
		$pp['Order_Transaction_id'] = $row['trans_id'];
		$pp['Additional_Note'] = $row['a_note'];
		$pp['Order_Status'] = $row['active'];
		$pp['cgst'] = $row['tot_cgst'];
	    $pp['sgst'] = $row['tot_sgst'];
	    $pp['igst'] = $row['tot_igst'];
	
		$fetchpro = $mysqli->query("select *  from e_subscribe_order_product_details where o_id=".$row['id']." and cos_id = '$cos_id'");
		$kop = array();
		$pdata = array();
		while($jpro = $fetchpro->fetch_assoc())
		{
			$kop['Subscribe_Id'] = $jpro['id'];
			$kop['Product_quantity'] = $jpro['p_quantity'];
			$kop['Product_name'] = $jpro['p_title'];
			$kop['Product_discount'] = $jpro['p_discount'];
			$kop['Product_image'] = $jpro['p_img'];
			$kop['Product_price'] = $jpro['p_price'];
			$kop['Product_variation'] = $jpro['p_type'];
			$kop['Delivery_Timeslot'] = $jpro['t_slot'];
			$discount = $jpro['p_price'] * $jpro['p_discount']*$jpro['p_quantity'] /100;
			
			$kop['Product_total'] = ($jpro['p_price'] * $jpro['p_quantity']) - $discount;
			$kop['totaldelivery'] = $jpro['totaldelivery'];
			$kop['startdate'] = $jpro['startdate'];
			
			$checks = explode(',',$jpro['totaldates']);
			$in = explode(',',$jpro['completedates']);
			$prem = array();
			for($i=0;$i<count($checks);$i++)
			{
				 if (in_array($checks[$i],$in))
				 {
					 $date=date_create($checks[$i]);
 $fdate = date_format($date,"D\nM d\nY");

					 $prem[] = array("date"=>$checks[$i],"is_complete"=>1,"format_date"=>$fdate);
				 }
else 
{
	$date=date_create($checks[$i]);
 $fdate = date_format($date,"D\nM d\nY");
	$prem[] = array("date"=>$checks[$i],"is_complete"=>0,"format_date"=>$fdate);
}	
			}
			$kop['totaldates'] = $prem;
			$pdata[] = $kop;
		}
		$pp['Order_Product_Data'] = $pdata;
		$result[] = $pp;
	}
	
    $returnArr = array("OrderProductList"=>$pp,"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Subscribe Order Retrieved successfully");
}
echo json_encode($returnArr);

?>