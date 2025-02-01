<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '/home/hapidev/vendor/autoload.php';

// Path to the JSON file you downloaded in step 1
$client = new Google_Client();
$client->setAuthConfig('C:\\Users\\dell\\Documents\\Mahesh_2024\\google_sheet.json');
$client->setScopes([Google_Service_Sheets::SPREADSHEETS]);

$service = new Google_Service_Sheets($client);

// The ID of the spreadsheet to update
$spreadsheetId = '1uRjFdMQC70BoxsEhbj-aQROj-mhs4uK5GOFJDqruoYE';

// The A1 notation of the range to update (leave it as the sheet name for append)
$range = 'MSG91'; // or 'Sheet1!A1:Z'

// The new row of values you want to insert
$values = [
    ['Value 1', '9', '50'] // Add as many values as needed
];

$body = new Google_Service_Sheets_ValueRange([
    'values' => $values
]);

// Parameters for the update
$params = [
    'valueInputOption' => 'RAW',
    'insertDataOption' => 'INSERT_ROWS'
];

// Insert the new row
$response = $service->spreadsheets_values->append(
    $spreadsheetId,
    $range,
    $body,
    $params
);

echo 'New row inserted successfully!';
?>
