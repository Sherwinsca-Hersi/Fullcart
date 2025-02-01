<?php 
include_once '../api/config.php';

header('Content-type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['u_id'])) {
    $returnArr = array("ResponseCode"=>"401", "Result"=>"false", "ResponseMsg"=>"Something Went Wrong!!");
} else {
    $u_id = strip_tags(mysqli_real_escape_string($mysqli, $data['u_id']));

    $checkimei = $mysqli->query("SELECT * FROM e_user_details WHERE id = $u_id AND cos_id = '$cos_id'");

    if ($checkimei->num_rows != 0) {
        $wallet = $checkimei->fetch_assoc(); // Fetch wallet details

        $sel = $mysqli->query("SELECT message, status, amt, created_ts FROM e_wallet_report_details WHERE u_id = $u_id AND cos_id = '$cos_id' ORDER BY created_ts DESC");

        $myarray = array();
        $l = 0; // Credit total
        $k = 0; // Debit total

        while ($row = $sel->fetch_assoc()) {
            // Skip if status is 'Credit' and amount is 0
            if ($row['status'] == 'Credit' && $row['amt'] == 0) {
                continue;
            }

            if ($row['status'] == 'Credit') {
                $l += $row['amt']; // Add credit amount
            } else {
                $k += $row['amt']; // Add debit amount
            }
            
            $myarray[] = $row; // Add entry to the array
        }

        $returnArr = array(
            "Walletitem" => $myarray,
            "credit_total" => $l,
            "debit_total" => $k,
            "wallet" => $wallet['wallet'],
            "ResponseCode" => "200",
            "Result" => "true",
            "ResponseMsg" => "Wallet Report Get Successfully!"
        );
    } else {
        $returnArr = array("ResponseCode"=>"401", "Result"=>"false", "ResponseMsg"=>"Please Update Your Device");
    }
}

echo json_encode($returnArr);
?>
