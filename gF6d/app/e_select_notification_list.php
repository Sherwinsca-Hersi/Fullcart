<?php 
include_once '../api/config.php';

$data = json_decode(file_get_contents('php://input'), true);
if($data['u_id'] == '')
{
    $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went Wrong!!");
}
else
{
    $u_id = strip_tags(mysqli_real_escape_string($mysqli,$data['u_id']));
    
    
$check = $mysqli->query("select `u_id`, `created_ts` as datetime, `title`, `description` from e_notification_details where u_id=".$u_id." and cos_id = '$cos_id' ORDER BY `datetime` DESC");

$op = array();

while($row = $check->fetch_assoc())
{
		$op[] = $row;
}
$returnArr = array("NotificationData"=>$op,"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Notification List Retrieved");
}
echo json_encode($returnArr);
?>