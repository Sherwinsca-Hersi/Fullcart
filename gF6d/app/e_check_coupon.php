<?php 
include_once '../api/config.php';
include_once '../api/function.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
$u_id  = $data['u_id'];
$c_id  = $data['c_id'];
$o_total  = $data['o_total'];
$platform  = $data['platform'];
if($u_id == '' or $c_id == '' or $o_total == '')
{
	$returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went Wrong!!");
}
else 
{
	$getcinfo = $mysqli->query("select * from e_data_coupon where id=".$c_id." and cos_id = $cos_id");
	$cinfo = $getcinfo->num_rows;
	
	if($cinfo !=0)
	{
    	$c = $mysqli->query("select * from e_cupon_used where cupon_id=".$c_id." and user_id=".$u_id." and cos_id = $cos_id");
    	$used = $c->num_rows;
	    $row = $getcinfo->fetch_assoc();
	    if($used < (int)$row["times"]){
	        if($o_total>=$row["min_amt"]){
                $table="e_cupon_used";
                $field_values=array("cos_id","cupon_id","user_id","order_id","times", "platform");
                $data_values=array("$cos_id","$c_id","$u_id","0","1", "$platform");
                $h = new CommonFunction();
                $ins = $h->Ins_Api_id($field_values,$data_values,$table);
                $returnArr = array("ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Coupon Applied Successfully");   
		    }else{
		        $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Your order amount is lower than coupon's minimum amount");
		    }   
	    }else{
            $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Already coupon usage limit reached...");
		}   
	}
	else 
	{
		$returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Coupon does Not Exists");
	}
}
echo json_encode($returnArr);
?>