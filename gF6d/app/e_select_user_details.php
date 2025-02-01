<?php 
include_once '../api/config.php';
include_once '../api/response.php';
header('Content-type: text/json');
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);

$data = json_decode(file_get_contents('php://input'), true);
 $mobile = strip_tags(mysqli_real_escape_string($mysqli,$data['mobile']));
 logOpen($mobile);
writeLog("User Login Details");
if($data['mobile'] == ''  or $data['password'] == '')
{
    $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went Wrong!!");
    writeLog("Something Went Wrong!!");
}
else
{
   
    $password = strip_tags(mysqli_real_escape_string($mysqli,$data['password']));
    $token = $data['token'];
    $platform = strip_tags(mysqli_real_escape_string($mysqli,$data['platform']));

    
$chek = $mysqli->query("select * from e_user_details where active = 1 and password='".$password."' and mobile='".$mobile."' and type=1 and cos_id = '$cos_id'");
$active = $mysqli->query("select * from e_user_details where active = 1 and type=1 and cos_id = '$cos_id'");
if($active->num_rows !=0)
{
if($chek->num_rows != 0)
{
    $c = $mysqli->query("select `id`, `name`, `mobile`, `email_id`, `password`, `whatsapp`, `r_date`, `c_code`, `code`, `refer_code`, `wallet`, `type`
                            from e_user_details where active = 1 and password='".$password."' and mobile='".$mobile."' and type=1 and cos_id = '$cos_id'");
    $c = $c->fetch_assoc();
    writeLog($c);	
	$check = $mysqli->query("select * from e_address_details where user_id=".$c['id']." and cos_id = '$cos_id'")->num_rows;
	if($check != 0)
	{
		$status_check = TRUE;
	}
	else 
	{
		$status_check = FALSE;
		
		}
		
	$insert_fields = "`u_id`, `platform`, `token`, `cos_id`";
    $insert_values = "'" . $c['id'] . "', '" . $platform . "', '" . $token . "', '" . $cos_id . "'";

    $final_query = "INSERT INTO  e_notification ($insert_fields) VALUES ($insert_values)";
    $insert_query = $mysqli->query($final_query);
	writeLog($final_query);	
    $returnArr = array("UserLogin"=>$c,"AddressExist"=>$status_check,"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Login Successful");
    writeLog("Login Successful");
}
else
{
    $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Invalid Mobile no or Password");
     writeLog("Invalid Mobile no or Password");
    
}
}
else  
{
	 $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Your Status Deactivated");
	 writeLog("Your Status Deactivated");
}
}
echo json_encode($returnArr);
fclose($logFile);
?>