<?php 
include_once '../api/config.php';
if(isset($_GET['barcode'])) {
    $barcode = mysqli_real_escape_string($mysqli, $_GET['barcode']);
    $result = $mysqli->query("SELECT id FROM e_product_details WHERE cos_id = '$cos_id' and barcode = '$barcode'");
    
    if($result->num_rows > 0) {
        echo "exists";
    } else {
        echo "not exists";
    }
} else {
    echo "parameter_not_set";
}
?>