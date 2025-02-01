 <?php 
include_once '../api/config.php';

$data = json_decode(file_get_contents('php://input'), true);
 
$u_id = $data['u_id'];
if($u_id == '')
{
	$returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went wrong. Pls try again");
}
else 
{ 
$count = $mysqli->query("select * from e_user_details where id=".$u_id." and cos_id = '$cos_id'")->num_rows;
if($count != 0)
{
$wallet = $mysqli->query("select * from e_user_details where id=".$u_id." and cos_id = '$cos_id'")->fetch_assoc();
$curr = $mysqli->query("select signup_credit,refer_credit from e_dat_setting where cos_id = '$cos_id'")->fetch_assoc();
$returnArr = array("ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Wallet Balance Get Successfully!","code"=>$wallet['code'],"signup_credit"=>$curr['signup_credit'],"refer_credit"=>$curr['refer_credit']);
}
else 
{
	$returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"User does Not Exist");
}
}
echo json_encode($returnArr);
?>