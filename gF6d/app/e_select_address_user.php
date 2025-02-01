<?php
include_once '../api/config.php';

// Enable error reporting :
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate User ID :
    if (empty($data['u_id'])) {
        throw new Exception("User ID is missing!");
    }

    $u_id = $data['u_id'];
    $aid = $data['aid'] ?? 0;
    $address = $data['address'] ?? '';
    $pincode = $data['pincode'] ?? '';
    $city = $data['city'] ?? '';
    $state = $data['state'] ?? '';
    $houseno = $data['houseno'] ?? '';
    $landmark = $data['landmark'] ?? '';
    $type = $data['type'] ?? '';
    $lat_map = $data['lat_map'] ?? '';
    $long_map = $data['long_map'] ?? '';
    $name = $data['name'] ?? '';
    $mobile = $data['mobile'] ?? '';
    $platform = $data['platform'] ?? '';

    // Check if user exists
    $user_check_query = "SELECT id FROM e_user_details WHERE active = 1 AND id = $u_id AND cos_id = '$cos_id'";
    $user_check_result = $mysqli->query($user_check_query);

    if ($user_check_result->num_rows == 0) {
        throw new Exception("User does not exist or is deactivated!");
    }

    // Insert or Update Address
    if ($aid == 0) {
        // Insert new address
        $insert_query = "INSERT INTO e_address_details 
            (cos_id, user_id, area, pincode, address_line_1, landmark, type, lat, lng, name, mobile, city, state, platform) 
            VALUES ('$cos_id', '$u_id', '$address', '$pincode', '$houseno', '$landmark', '$type', '$lat_map', '$long_map', '$name', '$mobile', '$city', '$state', '$platform')";

        if (!$mysqli->query($insert_query)) {
            throw new Exception("Failed to save address: " . $mysqli->error);
        }

        echo json_encode([
            "ResponseCode" => "200",
            "Result" => "true",
            "ResponseMsg" => "Address saved successfully!",
        ]);
    } else {
        // Update existing address
        $update_query = "UPDATE e_address_details SET 
            area = '$address', pincode = '$pincode', city = '$city', address_line_1 = '$houseno', 
            landmark = '$landmark', type = '$type', lat = '$lat_map', lng = '$long_map', 
            name = '$name', mobile = '$mobile', state = '$state', up_platform = '$platform' 
            WHERE id = $aid AND cos_id = '$cos_id'";

        if (!$mysqli->query($update_query)) {
            throw new Exception("Failed to update address: " . $mysqli->error);
        }

        echo json_encode([
            "ResponseCode" => "200",
            "Result" => "true",
            "ResponseMsg" => "Address updated successfully!",
        ]);
    }
} catch (Exception $e) {
    // Handle errors
    echo json_encode([
        "ResponseCode" => "500",
        "Result" => "false",
        "ResponseMsg" => $e->getMessage(),
    ]);
}
?>
