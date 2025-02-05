<?php
session_start(); 
 require '../api/config.php';

 $type = $_POST['type'] ?? '';
 $mobile = $_POST['mobile'] ?? '';
 $password = $_POST['password'] ?? '';


if ($type) {
    $query = "SELECT id,cos_id FROM `e_dat_admin` WHERE store_type = '$type' AND mobile='$mobile' AND password='$password'";
    $result = mysqli_query($mysqli, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['cos_id'] = $row["cos_id"]; 

        $role = $mysqli->query("SELECT id FROM `e_salesman_role` WHERE role_title = 'Admin' AND cos_id=".$_SESSION['cos_id']."")->fetch_assoc();
        $_SESSION['role'] = $role['id']; 

        $_SESSION['mobile']=$mobile;
        $_SESSION['password']=$password;

        echo json_encode(["success" => true, "cos_id" => $row["cos_id"],"auth_id" => $row["id"]]);
    } else {
        echo json_encode(["success" => false, "message" => "Store not found"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
}
?>

