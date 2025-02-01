<?php

include_once '../api/config.php';

header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Decode JSON input :
$data = json_decode(file_get_contents('php://input'), true);

// Check database connection:
if (!$mysqli) {
    echo json_encode([
        "responseCode" => "500",
        "result" => "Error",
        "responseMsg" => "Database connection failed"
    ]);
    exit();
}

// Input validation
$sender_id   = isset($data['sender_id']) ? $data['sender_id'] : null;
$message     = isset($data['message']) ? $data['message'] : null;
$platform     = isset($data['platform']) ? $data['platform'] : null;


if (!$sender_id || !$message) {
    echo json_encode([
        "responseCode" => "400",
        "result" => "Error",
        "responseMsg" => "Invalid input: sender_id and message are required"
    ]);
    exit();
}

// Fetch the receiver ID :
$receiver_query = "SELECT id FROM `e_dat_admin` WHERE active = 1 AND cos_id = '$cos_id' ORDER BY id ASC LIMIT 1"; 
$receiver_result = mysqli_query($mysqli, $receiver_query);

if ($receiver_result && mysqli_num_rows($receiver_result) > 0) {
    $receiver_row = mysqli_fetch_assoc($receiver_result);
    $receiver_id = $receiver_row['id'];
} else {
    echo json_encode([
        "responseCode" => "404",
        "result" => "Error",
        "responseMsg" => "Receiver not found for the given sender_id"
    ]);
    exit();
}

// Insert message into the database
$insert_sql = "INSERT INTO e_feedback (cos_id, sender_id, receiver_id, platform, message) 
               VALUES ('$cos_id', '$sender_id', '$receiver_id', '$platform', '$message')";

if (mysqli_query($mysqli, $insert_sql)) {
    $response = [
        "responseCode" => "200",
        "result" => "Success",
        "responseMsg" => "Message sent successfully"
    ];
} else {
    $response = [
        "responseCode" => "500",
        "result" => "Error",
        "responseMsg" => "Failed to send message: " . mysqli_error($mysqli)
    ];
}

mysqli_close($mysqli);

// Output response as JSON
echo json_encode($response);
?>
