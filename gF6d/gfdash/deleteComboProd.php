<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require '../api/config.php';
session_start();
$platform = "3";
$created_by = $_SESSION['username'];
$updated_by = $_SESSION['username'];

header('Content-Type: application/json');

if (isset($_GET['product_cid']) && isset($_GET['combo_id'])) {
    $prod_combo_id = $_GET['product_cid'];
    $combo_id = $_GET['combo_id'];

    // Debugging: Log the IDs being used
    error_log("Product Combo ID: " . $prod_combo_id);
    error_log("Combo ID: " . $combo_id);
    
    // Ensure $cos_id is defined and logged
    if (!isset($cos_id)) {
        error_log("cos_id is not set.");
        echo json_encode(['success' => false, 'message' => 'cos_id is not set.']);
        exit;
    }
    
    $combo_prod_delete = "UPDATE `e_product_collection_map` SET `active`=0, `updated_by`='$updated_by', `up_platform`='$platform' WHERE `prod_id`='$prod_combo_id' AND `c_id`='$combo_id' AND `cos_id`='$cos_id'";

    // Debugging: Log the query
    error_log("SQL Query: " . $combo_prod_delete);
    
    if ($delete_query = $mysqli->query($combo_prod_delete)) {
        if ($mysqli->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Product deleted successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No rows affected.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $mysqli->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Product or combo ID not set.']);
}

?>