<?php

// JSON data
$jsonData = '{"Data":{ "userName": "alwebuser", "password": "acid_qa"},"Risk":{} }';

// Specify the file path where JSON will be saved
$filePath = 'data.json';

// Open the file in append mode to add data to the next line if file exists
$file = fopen($filePath, 'a');

if ($file) {
    // Add a newline character if the file is not empty
    if (filesize($filePath) > 0) {
        fwrite($file, PHP_EOL);
    }

    // Write JSON data to the file
    fwrite($file, $jsonData);
    fclose($file);
    echo "JSON data has been successfully appended to $filePath.";
} else {
    echo "Failed to open file $filePath for writing.";
}
?>