<?php
require '../api/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mobile = $_POST['mobile'];

    // Check the mobile number in both tables
    $query = "SELECT id, mobile FROM `e_dat_admin` WHERE cos_id = '$cos_id' AND mobile = '$mobile' AND active = 1";
    $result = $mysqli->query($query);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $response = [
            'ResponseCode' => '200',
            'Result' => 'true',
            'Message' => 'Mobile number is valid',
            'id' => $user['id'],
            'mobile' => $user['mobile']
        ];
    } else {
        // Check the salesman's table if not found in admin table
        $query = "SELECT id, s_mobile AS mobile FROM `e_salesman_details` WHERE cos_id = '$cos_id' AND s_mobile = '$mobile' AND active = 1";
        $result = $mysqli->query($query);
        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $response = [
                'ResponseCode' => '200',
                'Result' => 'true',
                'Message' => 'Mobile number is valid',
                'id' => $user['id'],
                'mobile' => $user['mobile']
            ];
        } else {
            $response = [
                'ResponseCode' => '400',
                'Result' => 'false',
                'Message' => 'User with this mobile number does not exist!!..'
            ];
        }
    }
    echo json_encode($response);
}
