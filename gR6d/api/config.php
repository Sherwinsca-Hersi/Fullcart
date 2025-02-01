<?php
// require 'header.php'
// mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
// try {
//   $mysqli = new mysqli("localhost", "martway_db", "martway_db", "martway_dev_db");
//   $mysqli->set_charset("utf8mb4");
// } catch(Exception $e) {
//   error_log($e->getMessage());
// }
// // $cos_id = "";
// $vendor="Vendor";
// $project_name='FullComm';
// $combo = "Combo"; 
// $imgname='hapi.png';






mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $mysqli = new mysqli("localhost", "root", "", "martway_dev_db");
    $mysqli->set_charset("utf8mb4");
} catch (Exception $e) {
    error_log($e->getMessage());
    exit('Database connection failed');
}

// Initialize cos_id
$cos_id = isset($_SESSION['cos_id']) ? $_SESSION['cos_id'] : null;

// If an AJAX request sends cos_id, store it in session
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cos_id'])) {
    $_SESSION['cos_id'] = $_POST['cos_id'];
    echo json_encode(["success" => true, "cos_id" => $_POST['cos_id']]);
    exit;
}

// Other variables
$vendor = "Vendor";
$project_name = "FullComm";
$combo = "Combo"; 


$profile_query=$mysqli->query("SELECT id,logo_img,business_name FROM `e_data_profile` WHERE active=1 AND cos_id = '$cos_id'");
$profile_details=[];
while ($profile_table = $profile_query->fetch_assoc()) {
    $profile_details[] = $profile_table;
}
if(($profile_query->num_rows) > 0){
  forEach($profile_details as $profile_detail){
    $project_name=$profile_detail['business_name'];
    $imgname=$profile_detail['logo_img'];
  }
}
else{
  $project_name='FullComm';
  $imgname='logo\hapi.png';
}
?>