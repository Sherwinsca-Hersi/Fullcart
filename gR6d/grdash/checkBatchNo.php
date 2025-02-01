<?php 
include_once '../api/config.php';
if(isset($_GET['barcode'])) {
    $batch_no = mysqli_real_escape_string($mysqli, $_GET['batchNo']);
    $result = $mysqli->query("SELECT id FROM e_product_stock WHERE cos_id = '$cos_id' and `s_batch_no = '$batch_no'");
    
    if($result->num_rows > 0) {
        echo "exists";
    } else {
        echo "available";
    }
} else {
    echo "parameter_not_set";
}
?>