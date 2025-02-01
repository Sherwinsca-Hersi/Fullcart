<?php
require_once '../api/header.php';

// Ensure $cos_id is defined before use
if (!isset($cos_id)) {
    die("Error: cos_id is not set.");
}
echo $_GET['order_id'];
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
if ($order_id === 0) {
    die("Error: order_id is not set or invalid.");
}


// Query to fetch delivery person
$query = "SELECT id, s_name FROM e_salesman_details WHERE cos_id = '$cos_id' AND role = 2 AND active != 2 AND active != 0";
$result = $mysqli->query($query);

$orderQuery = "SELECT salesman_id FROM `e_normal_order_details` WHERE id = '$order_id' AND cos_id='$cos_id'";



$orderResult = $mysqli->query($orderQuery);
$salesman_id = 0; // Default value
if ($orderResult && $orderResult->num_rows > 0) {
    $orderRow = $orderResult->fetch_assoc();
    $salesman_id = isset($orderRow['salesman_id']) ? $orderRow['salesman_id'] : 0;
}
// echo $salesman_id;
if ($result && $result->num_rows > 0) {
    // Generate the default option
    $defaultSelected = ($salesman_id == 0) ? 'selected' : '';
    echo '<option value="0" ' . $defaultSelected . '>Select Delivery Person</option>';
    
    // Loop through the salesmen and set the selected one based on salesman_id
    while ($row = $result->fetch_assoc()) {
        $selected = ($salesman_id != 0 && $salesman_id == $row['id']) ? 'selected' : '';
        ?>
        <option value="<?php echo $row['id']; ?>" <?php echo $selected; ?>>
            <?php echo $row['s_name']; ?>
        </option>
        <?php
    }
} else {
    // No results found
    echo '<option value="0" disabled selected>No Delivery Person available</option>';
}
?>