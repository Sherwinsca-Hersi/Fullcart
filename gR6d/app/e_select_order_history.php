<?php 

include_once '../api/config.php';

$data = json_decode(file_get_contents('php://input'), true);
header('Content-type: text/json');
if($data['u_id'] == '')
{
 $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went Wrong!!");    
}
else
{
	$u_id =  $mysqli->real_escape_string($data['u_id']);
  $sel = $mysqli->query("select * from e_normal_order_details where u_id=".$u_id." and cos_id = '$cos_id' order by id desc "); 
  $g=array();
  $po= array();
  if($sel->num_rows != 0)
  {
  while($row = $sel->fetch_assoc())
  {
	  $grid = $mysqli->query("select * from e_salesman_details where id=".$row['salesman_id']." and cos_id = '$cos_id'")->fetch_assoc();
	  if($row['salesman_id'] == 0)
	  {
		  $ridername = 'Not Assigned';
	  }
  else 
  {
	  $ridername = 'Assigned';
  }
  
      $g['id'] = $row['id'];
      $g['Delivery_name'] = $ridername;
      $g['active'] = $row['status'];
	  $g['date'] = $row['o_date'];
	  $g['total'] = $row['o_total'];
      $po[] = $g;
	  
      
  }
  $returnArr = array("OrderHistory"=>$po,"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Order History Retrieved Successfully");
  }
  else 
  {
	  $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Order  Not Found");
  }
}
echo json_encode($returnArr);
?>