<?php
require '../api/config.php';
header('Content-Type: application/json');

if (isset($_POST['product_id']) && isset($_POST['combo_id'])) {
    $product_id = $_POST['product_id'];
    $combo_id = $_POST['combo_id'];

    $query = $mysqli->query("SELECT id FROM e_product_collection_map WHERE prod_id = '".$product_id."' AND c_id = '".$combo_id."'");

    if ($query->num_rows > 0) {
        echo json_encode(['exists' => true]); 
    } else {
        echo json_encode(['exists' => false]);
    }
}
?>


