<?php
header('Content-type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$data = json_decode(file_get_contents('php://input'), true);

// $mobileNo=9025056768;
$mobileNo=$_POST['mobile'];

if (empty($mobileNo)) {
    $returnArr = array("ResponseCode" => "400", "Result" => "false", "ResponseMsg" => "Mobile number is required.");
    echo json_encode($returnArr);
    exit();
}

// if (empty($mobileNo)) {
//     $returnArr = array("ResponseCode" => "400", "Result" => "false", "ResponseMsg" => "Mobile number is required.");
//     echo json_encode($returnArr);
//     exit();
// }

$mobile = '91'. $mobileNo;


$otp = random_int(100000, 999999);

$curl = curl_init();
$authKey = "134036A7Zoj0GB3SJ65d31569P1";
$templateId = "66b31aecd6fc0575304cd342";  
$var1 = "Martway";  
$var2 = $otp;  


$postFields = json_encode([
    "template_id" => $templateId,
    "short_url" => "0",
    "realTimeResponse" => "1",
    "recipients" => [
        [
            "mobiles" => $mobile,
            "var1" => $var1,
            "var2" => $var2
        ]
    ]
]);

// echo $postFields;

curl_setopt_array($curl, [
    CURLOPT_URL => "https://control.msg91.com/api/v5/flow",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $postFields,
    CURLOPT_HTTPHEADER => [
        "accept: application/json",
        "authkey: $authKey",
        "content-type: application/json"
    ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    $returnArr = array("ResponseCode" => "500", "Result" => "false","mobile" => $mobile ,"ResponseMsg" => "Error: " . $err);
    echo json_encode($returnArr);
} else {

    $returnArr = array("ResponseCode" => "200", "Result" => "true", "otp" => $otp, "ResponseMsg" => "OTP sent successfully");
    echo json_encode($returnArr);
}
?>

