<?php 
include_once '../api/config.php';
include_once '../api/function.php';
$data = json_decode(file_get_contents('php://input'), true);
if($data['mobile'] == '')
{
    $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went Wrong!!");
}
else
{
    $mobile = strip_tags(mysqli_real_escape_string($mysqli,$data['mobile']));
    
    
$chek = $mysqli->query("select * from e_user_details where mobile='".$mobile."' and cos_id = '$cos_id'")->num_rows;

if($chek != 0)
{
	$returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Mobile Number Already Exists");
}
else 
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"New Number");
}
}
echo json_encode($returnArr);
?>