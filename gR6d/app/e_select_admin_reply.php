<?php
include_once '../api/config.php';

header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Decode JSON input
$data = json_decode(file_get_contents('php://input'), true);

// Check database connection
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



// Fetch messages from the database
$select_sql = "
    SELECT id,sender_id, receiver_id, message, created_ts as timestamp 
    FROM e_feedback 
    WHERE (sender_id = '$sender_id' OR receiver_id = '$sender_id') AND cos_id='$cos_id' AND active=1
    ORDER BY timestamp ASC";
$result = mysqli_query($mysqli, $select_sql);

$chat_history = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $chat_history[] = $row;
    }
    $response = [
        "responseCode" => "200",
        "result" => "Success",
        "responseMsg" => "Messages fetched successfully",
        "chatHistory" => $chat_history
    ];
} else {
    $response = [
        "responseCode" => "500",
        "result" => "Error",
        "responseMsg" => "Failed to fetch messages: " . mysqli_error($mysqli)
    ];
}

mysqli_close($mysqli);

// Output response as JSON
echo json_encode($response);
?>
