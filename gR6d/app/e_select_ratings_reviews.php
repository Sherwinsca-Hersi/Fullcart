<?php 
// Enable error reporting :
error_reporting(E_ALL);          
ini_set('display_errors', 1);    

// Connection :
include_once '../api/config.php';

header('Content-type: text/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['product_id'])) {
    echo json_encode(['error' => 'Product ID is required']);
    exit;
}

$product_id = $data['product_id'];  

// Average rating for the product :
$avg_rating_sql = "SELECT AVG(rating) AS average_rating FROM e_products_rating WHERE product_id = '$product_id' AND active = 1 AND cos_id='$cos_id'";
$result = $mysqli->query($avg_rating_sql);

// Check for query errors
if (!$result) {
    echo json_encode(['error' => 'Error executing query: ' . $mysqli->error]);
    exit;
}

// Fetch the result and calculate the average rating
$average_rating = 0;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $average_rating = round($row['average_rating'], 2); // Round to 2 decimal places
} else {
    $average_rating = 0;
}

// List of reviews for the products :
$reviews_sql = "SELECT r.rating, r.comment, r.added_on, u.name AS user_name 
                FROM e_products_rating r
                JOIN e_user_details u ON r.user_id = u.id
                WHERE r.product_id = '$product_id' AND r.active = 1 AND r.cos_id = '$cos_id' AND u.cos_id = r.cos_id
                ORDER BY r.added_on DESC"; 
$reviews_result = $mysqli->query($reviews_sql);

// Check for query errors
if (!$reviews_result) {
    echo json_encode(['error' => 'Error executing query: ' . $mysqli->error]);
    exit;
}

// Create an array to store reviews
$reviews = [];
if ($reviews_result->num_rows > 0) {
    while ($row = $reviews_result->fetch_assoc()) {
        $reviews[] = [
            'user_name' => $row['user_name'],
            'rating' => $row['rating'],
            'comment' => $row['comment'],
            'added_on' => $row['added_on'],
        ];
    }
}

// Average rating and reviews :
$response = [
    'average_rating' => $average_rating,
    'reviews' => $reviews
];

// Output the response as JSON :
header('Content-Type: application/json');
echo json_encode($response);

// Close the connection
$mysqli->close();
?>
