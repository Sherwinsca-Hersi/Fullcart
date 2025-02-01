<?php

// sucess response code
$success=[];
$success['status_code']=200;
$success['message']="OK";

// not_found response code
$not_found=[];
$not_found['status_code']=404;
$not_found['message']="Page not found1";

// un_authorised response code
$un_authorised=[];
$un_authorised['status_code']=401;
$un_authorised['message']="unauthorised page";

// forbidden response code
$forbidden=[];
$forbidden['status_code']=403;
$forbidden['message']="forbidden";

// created response code
$created=[];
$created['status_code']=201;
$created['message']="created succesfully";

// not_created response code
$not_created=[];
$not_created['status_code']=400;
$not_created['message']="Bad Request";

// no_data response code
$no_data=[];
$no_data['status_code']=400;
$no_data['message']="no data found !";

$no_data=[];
$no_data['status_code']=400;
$no_data['message']="error. no data found";

$no_data_found=[];
$no_data_found['status_code']=200;
$no_data_found['message']="no data found";

$no_data_delete=[];
$no_data_delete['status_code']=400;
$no_data_delete['message']="no data deleted";

$delete_success=[];
$delete_success['status_code']=200;
$delete_success['message']="deleted data succesfully";


// Open or create log file
$dateTime = date('Y-m-d_H:i:s');

function logOpen($log_file) {
    global $logFolderPath, $logFilePath, $logFile;

    // Define the path relative to the /dev directory
    $logFolderPath = dirname(__FILE__) . '/../app/error_logs/';

    // Create the folder if it doesn't exist
    if (!file_exists($logFolderPath)) {
        if (!mkdir($logFolderPath, 0777, true)) {
            die("Failed to create log folder: $logFolderPath");
        }
    }

    // Set the log file path
    $logFilePath = $logFolderPath . 'log_' . $log_file . '.txt';

    // Open the log file for appending
    $logFile = fopen($logFilePath, 'a');
    if (!$logFile) {
        die("Failed to open log file: $logFilePath");
    }

    // echo "Log file opened successfully: $logFilePath\n";
}

// Write to the log file
function writeLog($message) {
    global $logFile;
    if (!$logFile) {
        die("Log file not open for writing");
    }

    // Get the current date and time
    $dateTime = date('Y-m-d H:i:s');

    // Check if message is an array or object
    if (is_array($message) || is_object($message)) {
        $message = json_encode($message); // Convert to JSON string
    }

    // Format the message with timestamp
    $logMessage = "[$dateTime] $message";

    // Write to the log file
    if (fwrite($logFile, $logMessage . PHP_EOL) === false) {
        die("Error writing to log file");
    }
}

?>