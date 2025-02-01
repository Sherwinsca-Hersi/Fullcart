<?php 
include_once '../api/config.php';
header('Content-Type: application/json');

$result = $mysqli->query("SELECT * FROM e_data_country_code WHERE cos_id = '$cos_id'");

$countryCodes = $result->fetch_all(MYSQLI_ASSOC);

$returnArr = array(
    "CountryCode" => $countryCodes,
    "ResponseCode" => "200",
    "Result" => "true",
    "ResponseMsg" => "Country Code List Found"
);

echo json_encode($returnArr);
?>
