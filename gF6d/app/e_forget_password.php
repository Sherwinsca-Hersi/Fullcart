<?php
include_once '../api/config.php';
include_once '../api/response.php';
header('Content-type: application/json');

ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);

$data = json_decode(file_get_contents('php://input'), true);

$mobile = $data['mobile'];
$password = $data['password'];
$platform = $data['platform'];
$token  = $data['token'];

$user_id = '';
logOpen($mobile);
writeLog("Update Password Details Change");

if ($mobile == '' || $password == '') {
    $returnArr = array(
        "ResponseCode" => "401",
        "Result" => "false",
        "ResponseMsg" => "Something Went wrong. Please try again"
    );
    writeLog("Something Went wrong. Please try again");
} else {
    $mobile = strip_tags(mysqli_real_escape_string($mysqli, $mobile));
    $password = strip_tags(mysqli_real_escape_string($mysqli, $password));

    // Check if the user exists :
    $query = "SELECT * FROM e_user_details WHERE mobile = '$mobile' AND cos_id = '$cos_id'";
    $result = $mysqli->query($query);

    if ($result->num_rows != 0) {
        // Fetch user details
        $user = $result->fetch_assoc();
        
        $user_id = $user['id']; // Fetch the user ID from the user details

        // Update the password :
        $updateQuery = "UPDATE e_user_details 
                        SET password = '$password', up_platform = '$platform' 
                        WHERE mobile = '$mobile' AND cos_id = '$cos_id'";
        
          writeLog("$updateQuery");               
        //Insert Token:
                $insert_fields = "`u_id`, `platform`, `token`, `cos_id`";
                $insert_values = "'$user_id', '" . $platform . "', '" . $token . "', '" . $cos_id . "'";
                
                $final_query = "INSERT INTO  e_notification ($insert_fields) VALUES ($insert_values)";
                $insert_query = $mysqli->query($final_query);
        
        if ($mysqli->query($updateQuery) === TRUE) {
            $returnArr = array(
                "ResponseCode" => "200",
                "Result" => "true",
                "ResponseMsg" => "Password Changed Successfully",
                "UserLogin" => $user
            );
            writeLog("Password Changed Successfully");           
        } else {
            $returnArr = array(
                "ResponseCode" => "500",
                "Result" => "false",
                "ResponseMsg" => "Error updating password: " . $mysqli->error
            );
            writeLog( $mysqli->error);             
        }
    } else {
        $returnArr = array(
            "ResponseCode" => "401",
            "Result" => "false",
            "ResponseMsg" => "Mobile No Does Not Match"
        );
         writeLog( "Mobile No Does Not Match". $mobile);   
    }
}

echo json_encode($returnArr);
fclose($logFile);
?>
