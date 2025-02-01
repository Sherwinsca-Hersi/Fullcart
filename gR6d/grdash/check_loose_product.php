<?php
include_once '../api/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    
    $input = json_decode(file_get_contents('php://input'), true);
    $product_id = $input['product_id'];
    
    $stmt = $mysqli->query("SELECT is_loose FROM e_product_details WHERE id = '$product_id' AND active = 1 AND cos_id ='$cos_id'");
    $row = $stmt->fetch_assoc();
    
    if ($row) {
        echo json_encode(['is_loose' => (bool)$row['is_loose']]);
    } else {
        echo json_encode(['is_loose' => false]);
    }
}
?>