<?php

require '../api/config.php';
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newPassword = $_POST['new_pass'];
    $confirmPassword = $_POST['confirm_pass'];
    $userId = $_POST['userId']; 
    $mobile = $_POST['mobile'];
    $cos_id = $cos_id;

    // Check if passwords match
    if ($newPassword !== $confirmPassword) {
        echo json_encode([
            'ResponseCode' => '400',
            'Message' => 'Passwords do not match.',
        ]);
        exit;
    }

    $plainPassword = $newPassword;

    // Check if the user exists in either admin or salesman table
    $userType = getUserType($mysqli, $userId, $mobile, $cos_id);

    if ($userType) {

        if (updatePassword($mysqli, $userId, $mobile, $cos_id, $plainPassword, $userType)) {
            echo json_encode([
                'ResponseCode' => '200',
                'Message' => "Password updated successfully in $userType.",
            ]);
        } else {
            echo json_encode([
                'ResponseCode' => '404',
                'Message' => 'Password update failed.',
            ]);
        }
    } else {
        echo json_encode([
            'ResponseCode' => '404',
            'Message' => 'User not found in admin or salesman tables.',
        ]);
    }
}

// Function to check user type
function getUserType($mysqli, $userId, $mobile, $cos_id) {
    // Check in admin table
    $admin_query = "SELECT 1 FROM `e_dat_admin` WHERE `id` = ? AND `mobile` = ? AND cos_id = ? AND active = 1";
    $stmt = $mysqli->prepare($admin_query);
    $stmt->bind_param('isi', $userId, $mobile, $cos_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        return 'admin';
    }

    // Check in salesman table
    $salesman_query = "SELECT 1 FROM `e_salesman_details` WHERE `id` = ? AND `s_mobile` = ? AND cos_id = ? AND active = 1";
    $stmt = $mysqli->prepare($salesman_query);
    $stmt->bind_param('isi', $userId, $mobile, $cos_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        return 'salesman'; 
    }

    return null;
}


function updatePassword($mysqli, $identifier1, $identifier2, $identifier3, $plainPassword, $userType) {
    try {
        if ($userType === 'admin') {
            $update_query = "UPDATE `e_dat_admin` SET `password` = ? WHERE `id` = ? AND `mobile` = ? AND cos_id = ? AND active = 1";
            $stmt = $mysqli->prepare($update_query);
            if (!$stmt) {
                echo json_encode([
                    'ResponseCode' => '500',
                    'Message' => 'Database error: Failed to prepare admin update statement.',
                ]);
                exit;
            }
            $stmt->bind_param('ssis', $plainPassword, $identifier1, $identifier2, $identifier3);
        } else {
            $update_query = "UPDATE `e_salesman_details` SET `password` = ? WHERE `id` = ? AND `s_mobile` = ? AND cos_id = ? AND active = 1";
            $stmt = $mysqli->prepare($update_query);
            if (!$stmt) {
                echo json_encode([
                    'ResponseCode' => '500',
                    'Message' => 'Database error: Failed to prepare salesman update statement.',
                ]);
                exit;
            }
            $stmt->bind_param('ssis', $plainPassword, $identifier1, $identifier2, $identifier3);
        }
        
        // Execute the statement
        $stmt->execute();

        // Return true if any rows were affected (password updated)
        return $stmt->affected_rows > 0;

    } catch (mysqli_sql_exception $exception) {
        echo json_encode([
            'ResponseCode' => '500',
            'Message' => $exception->getMessage(),
        ]);
        exit; // Exit after sending error response
    } finally {
        // Close the statement if it was prepared
        if (isset($stmt) && $stmt instanceof mysqli_stmt) {
            $stmt->close();
        }
    }
}


$mysqli->close();
?>
