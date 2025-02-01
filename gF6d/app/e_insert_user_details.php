<?php 
include_once '../api/config.php';
include_once '../api/function.php';
include_once '../api/response.php';


header('Content-type: text/json');

$data = json_decode(file_get_contents('php://input'), true);

$platform = $data['platform'];

ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);

function generate_random() {
    require '../api/config.php';
    $six_digit_random_number = mt_rand(100000, 999999);
    $c_refer = $mysqli->query("SELECT id FROM e_user_details WHERE code=$six_digit_random_number AND cos_id = $cos_id")->num_rows;
    if ($c_refer != 0) {
        return generate_random();
    } else {
        return $six_digit_random_number;
    }
}
 $mobile = strip_tags(mysqli_real_escape_string($mysqli, $data['mobile']));
logOpen($mobile);
writeLog("Sign up user insert");
if ($data['name'] == '' || $data['mobile'] == '' || $data['password'] == '' || $data['email_id'] == '' || $data['c_code'] == '') {
    writeLog("Fill required fields.");
    $returnArr = array(
        "ResponseCode" => "401",
        "Result" => "false",
        "ResponseMsg" => "Fill required fields."
    );
} else {
    
    // Start the transaction
    $mysqli->begin_transaction();

    try {
        
        $name = strip_tags(mysqli_real_escape_string($mysqli, $data['name']));
       
        $c_code = strip_tags(mysqli_real_escape_string($mysqli, $data['c_code']));
        $email_id = strip_tags(mysqli_real_escape_string($mysqli, $data['email_id']));
        $password = strip_tags(mysqli_real_escape_string($mysqli, $data['password']));
        $refer_code = strip_tags(mysqli_real_escape_string($mysqli, $data['refer_code']));
        $token = $data['token'];

        // Check for existing mobile number (Existing E-Comm User):
        $checkmob = $mysqli->query("SELECT id FROM e_user_details WHERE mobile='$mobile' AND type='1' AND cos_id = '$cos_id'");
          
        if ($checkmob->num_rows != 0) {
            writeLog("Mobile Number Already Used!");
            throw new Exception("Mobile Number Already Used!");
            
         
        } else {
            
            // Check for Billing Customer:
            $checkType2 = $mysqli->query("SELECT id FROM e_user_details WHERE mobile='$mobile' AND type='2' AND cos_id = '$cos_id'");

            if ($checkType2->num_rows != 0) {
                
                // Update Billing Customer -> Ecomm Customer:
                $updateQuery = "UPDATE e_user_details SET 
                    name='$name', 
                    email_id='$email_id', 
                    password='$password', 
                    c_code='$c_code', 
                    platform='$platform',
                    type='1'
                    WHERE mobile='$mobile' AND type='2' AND cos_id = '$cos_id'";
                $mysqli->query($updateQuery);

                // Fetch the updated user details:
                $updatedUser = $mysqli->query("SELECT `id`, `name`, `mobile`, `email_id`, `password`, `whatsapp`, `r_date`, `c_code`, `code`, `refer_code`, `wallet`
                                                FROM e_user_details WHERE mobile='$mobile' AND type='1' AND cos_id = '$cos_id'")->fetch_assoc();
                                                
                //Insert Token:
                    $insert_fields = "`u_id`, `platform`, `token`, `cos_id`";
                    $insert_values = "'" . $updatedUser['id'] . "', '" . $platform . "', '" . $token . "', '" . $cos_id . "'";
                    
                    $final_query = "INSERT INTO  e_notification ($insert_fields) VALUES ($insert_values)";
                    $insert_query = $mysqli->query($final_query);

                // Add Signup credit:
                $wallet = $mysqli->query("SELECT `id`, `logo`, `d_title`, `fcm_server_key`, `fcm_api_key`, `msg91_key`, `one_key`, `one_hash`, `r_key`, `r_hash`, `currency`, `timezone`, 
                                                 `policy`, `about`, `contact`, `terms`, `p_limit`, `p_banner`, `signup_credit`, `refer_credit`, `a_sid`, `token`, `megic_Num`
                                                 FROM e_dat_setting WHERE active=1 AND cos_id = '$cos_id'")->fetch_assoc();
                $fin = $wallet['signup_credit'];

                $insertWalletQuery = "INSERT INTO e_wallet_report_details (cos_id, u_id, message, active, amt, platform,status) 
                                        VALUES ('$cos_id', '".$updatedUser['id']."', 'Sign up Credit!', '1', '$fin', '$platform','Credit')";
                                        
                $mysqli->query($insertWalletQuery);

                $returnArr = array(
                    "UserLogin" => $updatedUser, 
                    "ResponseCode" => "200", "Result" => "true", 
                    "ResponseMsg" => "Billing Customer is Switched");
            } else {
                
                // New User:
                $timestamp = date("Y-m-d H:i:s");
                $prentcode = generate_random();

                if ($refer_code != '') {
                    $c_refer = $mysqli->query("SELECT id FROM e_user_details WHERE code=$refer_code AND cos_id = '$cos_id'")->num_rows;
                    if ($c_refer != 0) {
                        $wallet = $mysqli->query("SELECT `id`, `logo`, `d_title`, `fcm_server_key`, `fcm_api_key`, `msg91_key`, `one_key`, `one_hash`, `r_key`, `r_hash`, `currency`, `timezone`, 
                                                 `policy`, `about`, `contact`, `terms`, `p_limit`, `p_banner`, `signup_credit`, `refer_credit`, `a_sid`, `token`, `megic_Num` 
                                                  FROM e_dat_setting WHERE active=1 AND cos_id = '$cos_id'")->fetch_assoc();
                        $fin = $wallet['signup_credit'];

                        $insertUserQuery = "INSERT INTO e_user_details (cos_id, name, mobile, r_date, password, c_code, email_id, refer_code, wallet, code, platform) 
                                            VALUES ('$cos_id', '$name', '$mobile', '$timestamp', '$password', '$c_code', '$email_id', '$refer_code', '$fin', '$prentcode', '$platform')";
                        $mysqli->query($insertUserQuery);
                        $user_id = $mysqli->insert_id;
                        
                        //Insert Token:
                        	$insert_fields = "`u_id`, `platform`, `token`, `cos_id`";
                            $insert_values = "'$user_id', '" . $platform . "', '" . $token . "', '" . $cos_id . "'";

                            $final_query = "INSERT INTO  e_notification ($insert_fields) VALUES ($insert_values)";
                            $insert_query = $mysqli->query($final_query);

                        //Insert Wallet:
                        $insertWalletQuery = "INSERT INTO e_wallet_report_details (cos_id, u_id, message, active, amt, platform,status) 
                                              VALUES ('$cos_id', '$user_id', 'Sign up Credit!', '1', '$fin', '$platform','Credit')";
                        $mysqli->query($insertWalletQuery);

                        $user = $mysqli->query("SELECT `id`, `name`, `mobile`, `email_id`, `password`, `whatsapp`, `r_date`, `c_code`, `code`, `refer_code`, `wallet` 
                                                FROM e_user_details WHERE id='$user_id' AND cos_id = '$cos_id'")->fetch_assoc();
                                               
                        $returnArr = array("UserLogin" => $user, "ResponseCode" => "200", "Result" => "true", "ResponseMsg" => "You have Successfully Signed Up");
                        
                    } else {
                        throw new Exception("Referral Code Not Found");
                    }
                
                } else {
                    $insertUserQuery = "INSERT INTO e_user_details (cos_id, name, mobile, r_date, email_id, password, c_code, code, platform) 
                                        VALUES ('$cos_id', '$name', '$mobile', '$timestamp', '$email_id', '$password', '$c_code', '$prentcode', '$platform')";
                                    
                    $mysqli->query($insertUserQuery);
                    $user_id = $mysqli->insert_id;
                     writeLog( $insertUserQuery );
                    //Insert Token:
                    $insert_fields = "`u_id`, `platform`, `token`, `cos_id`";
                    $insert_values = "'$user_id', '" . $platform . "', '" . $token . "', '" . $cos_id . "'";
                    
                    $final_query = "INSERT INTO  e_notification ($insert_fields) VALUES ($insert_values)";
                    $insert_query = $mysqli->query($final_query);

                    $user = $mysqli->query("SELECT `id`, `name`, `mobile`, `email_id`, `password`, `whatsapp`, `r_date`, `c_code`, `code`, `refer_code`, `wallet` 
                                            FROM e_user_details WHERE id='$user_id' AND cos_id = '$cos_id'")->fetch_assoc();
                                            
              writeLog("You have Successfully Signed Up");
                    $returnArr = array(
                        "UserLogin" => $user, 
                        "ResponseCode" => "200",
                        "Result" => "true", 
                        "ResponseMsg" => "You have Successfully Signed Up");
                }
            }
        }
        
        // Commit the transaction:
        $mysqli->commit();
        
    } catch (Exception $e) {
          writeLog($e->getMessage());
        // Rollback if any error occurs:
        $mysqli->rollback();
        $returnArr = array(
            "ResponseCode" => "401", 
            "Result" => "false",
            "ResponseMsg" => $e->getMessage());
        
    }
    
}
echo json_encode($returnArr);
fclose($logFile);
?>
