<?php
// Your authentication key (Authorization key from your MSG91 account)
$authKey = "134036A7Zoj0GB3SJ65d31569P1";

// Device token (FCM token) of the recipient
$deviceToken = "eUi67SKhSwGjlIM8Rhv-vq:APA91bEgd0vaKVOQJwbolducpLx-iAzxKoJw21D0xCQ7y0kMTrbiBuV25_zETMxJ9urcOwdHp-jdR78FNp6I3gPVbxPX9jAQQnFbrcDYSuKu9ocuWiED__7YJMVdVIViBLcOavPAbBgf";

// API URL for sending push notifications via MSG91
$url = "https://api.msg91.com/api/v5/notification/send";

// Payload for the notification
$notificationData = array(
    'to' => $deviceToken,
    'notification' => array(
        'title' => "Test Notification",   // Notification title
        'body' => "This is a test notification message",  // Notification body/message
        'sound' => "default"              // Optional sound setting
    ),
    'data' => array(
        'additionalData' => 'value'       // Custom additional data, if required
    ),
    'android' => array(
        'priority' => 'high'              // For Android-specific options (optional)
    ),
    'ios' => array(
        'badge' => 1                      // For iOS-specific options (optional)
    )
);

// Set up headers
$headers = array(
    'Content-Type: application/json',
    'Authorization: Bearer ' . $authKey  // Correct way to pass the auth key
);

// Initialize cURL request
$ch = curl_init();

// Set CURL options
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($notificationData));

// Execute CURL request and get the response
$response = curl_exec($ch);

// Check for any CURL errors
if ($response === FALSE) {
    die('CURL Error: ' . curl_error($ch));
}

// Close the CURL session
curl_close($ch);

// Display response
echo $response;

?>
