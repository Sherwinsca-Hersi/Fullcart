<?php
// Include Config File:
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
$user_id    = isset($data['user_id']) ? $data['user_id'] : null;
$product_id = isset($data['product_id']) ? $data['product_id'] : null;
$rating     = isset($data['rating']) ? $data['rating'] : null;
$comment    = isset($data['comment']) ? $data['comment'] : null;
$platform   = isset($data['platform']) ? $data['platform'] : null;

// Input validation
if (!$user_id || !$product_id || !$rating) {
    echo json_encode([
        "responseCode" => "400",
        "result" => "Error",
        "responseMsg" => "Invalid input: user_id, product_id, and rating are required"
    ]);
    exit();
}

// Check if the record already exists
$check_sql = "SELECT id FROM e_products_rating WHERE user_id = '$user_id' AND product_id = '$product_id'";
$result = mysqli_query($mysqli, $check_sql);

if ($result && mysqli_num_rows($result) > 0) {
    // Update the existing record
    $update_sql = "UPDATE e_products_rating 
                   SET rating = '$rating', comment = '$comment', platform = '$platform'
                   WHERE user_id = '$user_id' AND product_id = '$product_id' AND cos_id = '$cos_id'";

    if (mysqli_query($mysqli, $update_sql)) {
        $response = [
            "responseCode" => "200",
            "result" => "Success",
            "responseMsg" => "Review updated successfully"
        ];
    } else {
        $response = [
            "responseCode" => "500",
            "result" => "Error",
            "responseMsg" => "Failed to update review: " . mysqli_error($mysqli)
        ];
    }
} else {
    // Insert a new record
    $insert_sql = "INSERT INTO e_products_rating (`cos_id`,`product_id`, `user_id`, `rating`, `comment`, `platform`) 
                   VALUES ('$cos_id','$product_id', '$user_id', '$rating', '$comment', '$platform')";

    if (mysqli_query($mysqli, $insert_sql)) {
        $response = [
            "responseCode" => "200",
            "result" => "Success",
            "responseMsg" => "Review added successfully"
        ];
    } else {
        $response = [
            "responseCode" => "500",
            "result" => "Error",
            "responseMsg" => "Failed to add review: " . mysqli_error($mysqli)
        ];
    }
}

// Close database connection
mysqli_close($mysqli);

// Output response as JSON
echo json_encode($response);
?>
