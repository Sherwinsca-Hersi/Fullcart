<?php 
include_once '../api/config.php';
error_reporting(E_ALL); 
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 

$data = json_decode(file_get_contents('php://input'), true);
header('Content-type: text/json');
if($data['u_id'] == '' or $data['order_id'] == '')
{
 $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went Wrong!!");    
}
else
{
	 $order_id = $mysqli->real_escape_string($data['order_id']);
 $u_id =  $mysqli->real_escape_string($data['u_id']);
 
  $sel = $mysqli->query("select * from e_normal_order_details where u_id=".$u_id." and id=".$order_id." and cos_id = '$cos_id'");
  
  
  $result = array();
  $pp = array();
  while($row = $sel->fetch_assoc())
    {
		$pp['order_id'] = $row['id'];
		$pp['order_date'] = $row['o_date'];
		$pname = $mysqli->query("select * from e_dat_payment where id=".$row['p_method_id']." and cos_id = '$cos_id'")->fetch_assoc();
		
		$pp['p_method_name'] = $pname['title'] ?? 'Cash';
		$pp['customer_address'] = $row['address'];
		$pp['Wallet_Amount'] = $row['wall_amt'];
		$pp['customer_name'] = $row['name'];
		$pp['customer_mobile'] = $row['mobile'];
		$pp['Delivery_charge'] = $row['d_charge'];
		$pp['Delivery_Timeslot'] = $row['t_slot'];
		$pp['Coupon_Amount'] = $row['cou_amt'];
		$pp['Order_Total'] = $row['o_total'];
		$pp['Order_SubTotal'] = $row['subtotal'];
		$pp['cgst'] = $row['tot_cgst'];
	    $pp['sgst'] = $row['tot_sgst'];
	    $pp['igst'] = $row['tot_igst'];
	    $pp['status_date'] = date('d F Y', strtotime($row['updated_ts']));
	    $pp['status_name'] = $row['status'];
		if($row['p_method_id'] == 5)
		{
			$pp['Order_Transaction_id'] = $row['wall_amt'];
		}
		else 
		{
		$pp['Order_Transaction_id'] = $row['trans_id'];
		}
		$pp['Additional_Note'] = $row['a_note'];
		$pp['Order_Status'] = $row['active'];
		
		
		$fetchpro = $mysqli->query("select *  from e_normal_order_product_details where o_id=".$row['id']." and cos_id = '$cos_id'");
		$kop = array();
		$pdata = array();
		while($jpro = $fetchpro->fetch_assoc())
		{
			$kop['Product_quantity'] = $jpro['p_quantity'];
			$kop['Product_name'] = $jpro['p_title'];
			$kop['Product_discount'] = $jpro['p_discount'];
			$kop['Product_image'] = $jpro['p_img'];
			$kop['Product_price'] = $jpro['p_price'];
			$kop['Product_variation'] = $jpro['p_type'];
			$kop['Product_instruction'] = ($jpro['instruction'] == '')  || ($jpro['instruction'] == null) ? '' : $jpro['instruction'];
			$discount = $jpro['p_price'] * (($jpro['p_discount']=='' || $jpro['p_discount']==null) ? 0 : $jpro['p_discount']) *$jpro['p_quantity'] /100;
			
// 			$kop['Product_total'] = ($jpro['p_price'] * $jpro['p_quantity']) - $discount;
            $kop['Product_total'] = ($jpro['p_price'] * $jpro['p_quantity']);

			$pdata[] = $kop;
		}
		$pp['Order_Product_Data'] = $pdata;
		
	}
	
    $returnArr = array("OrderProductList"=>$pp,"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Order Retrieved successfully");
}
echo json_encode($returnArr);
?>