<?php 
include_once '../api/config.php';
include_once '../api/function.php';
header('Content-type: text/json');
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$data = json_decode(file_get_contents('php://input'), true);
$platform  = $data['platform'];
if($data['u_id'] == '' or $data['wallet'] == '')
{
    
    $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went Wrong!!");
}
else
{
    $wallet = $data['wallet'];      //(float) strip_tags(mysqli_real_escape_string($mysqli,$data['wallet']));
    $u_id =  strip_tags(mysqli_real_escape_string($mysqli,$data['u_id']));
    $checkimei = mysqli_num_rows(mysqli_query($mysqli,"select * from e_user_details where `id`=".$u_id." and cos_id = '$cos_id'"));

if($checkimei != 0)
    {
		$bonus = 0;
		$bonus_amt = 0;
		$type = "Percentage";
		$msg = "";
		$sel = $mysqli->query("select * from e_dat_wallet_rule where active = 1 and cos_id = '$cos_id'");
	while($rule = $sel->fetch_assoc())
	{
	    if($wallet >= $rule["above"] && $wallet <= $rule["below"]){
	        $bonus = $rule["bonus"];
	       if($rule["type"] == 1 ){
	            $bonus_amt = $wallet*$bonus/100;
	            $type = "Percentage";
	        }else{
	            $bonus_amt = $bonus;
	            $type = "CashBack";
	        }
	        if($bonus > 0){
	            $msg = "Rs $wallet  and Rs $bonus_amt bonus added to your Wallet. Enjoy Shopping with Us";
	        }else{
	            $msg = "Rs $wallet added to your Wallet. Enjoy Shopping with Us";
	        }
	        break;
	    }
	}

      $vp = $mysqli->query("select * from e_user_details where id=".$u_id." and cos_id = '$cos_id'")->fetch_assoc();
	  
    $table="e_user_details";
    $field = array('wallet'=>$vp['wallet']+$wallet+$bonus_amt,'up_platform'=>"$platform");
    $where = "where id=".$u_id." and cos_id = '$cos_id'";
    $h = new CommonFunction();
	$check = $h->Ins_update_Api($field,$table,$where);

	$table="e_wallet_report_details";
    $field_values=array("cos_id","u_id","message","status","amt","amt_type","platform");
    $data_values=array("$cos_id","$u_id",'Wallet Balance Added!','Credit',"$wallet","1","$platform");
    $checks = $h->Ins_latest_Api($field_values,$data_values,$table);
    
    $field_values=array("cos_id","u_id","message","status","amt","amt_type","platform");
    $data_values=array("$cos_id","$u_id",'Wallet Bonus Added!','Credit',"$bonus_amt","2","$platform");
    $checks = $h->Ins_latest_Api($field_values,$data_values,$table);
	  
	$title = 'Wallet Balance Added!';
	  	$sql = "select token from e_notification where u_id = '$u_id' and cos_id = '$cos_id' ORDER BY created_ts DESC LIMIT 1";
	$sel = $mysqli->query($sql)->fetch_assoc();
    $token[] = $sel["token"];
  
    $h->send_notification($token,$msg,$title);
    $h->send_noti_to_admin($title,$msg);
    
	   $wallet = $mysqli->query("select * from e_user_details where id=".$u_id." and cos_id = '$cos_id'")->fetch_assoc();
        $returnArr = array("wallet"=>$wallet['wallet'],"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Wallet Update successfully");
        
    
	}
    else
    {
      $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"User Deactivated By Admin");  
    }
    
}
echo json_encode($returnArr);
?>