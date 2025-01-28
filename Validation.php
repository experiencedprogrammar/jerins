<?php
header("Content-Type: application/json");

// Save the M-PESA input stream.
$mpesaResponse = file_get_contents('php://input');

// Check if the response is empty or malformed
if (empty($mpesaResponse)) {
    $response = '{ "ResultCode": 1, "ResultDesc": "Invalid data received." }';
    echo $response;
    exit; // Stop further execution
}

// Decode the M-PESA response to an associative array
$jsonMpesaResponse = json_decode($mpesaResponse, true);

// Log the raw M-PESA response (ensure that log file is writable)
$logFile = "Validation_response.txt";

// Check if we successfully decoded the JSON response
if ($jsonMpesaResponse === null) {
    $response = '{ "ResultCode": 1, "ResultDesc": "Failed to decode response." }';
    echo $response;
    exit; // Stop further execution
}

// Write the M-PESA Response to a log file (ensure log file is writable)
$log = fopen($logFile, "a");
if ($log) {
    fwrite($log, date('Y-m-d H:i:s') . " - " . $mpesaResponse . PHP_EOL);
    fclose($log);
} else {
    // Handle error in opening the log file
    $response = '{ "ResultCode": 1, "ResultDesc": "Failed to write to log file." }';
    echo $response;
    exit; // Stop further execution
}

// Placeholder validation - You can add your own logic here
// If validation fails, you can modify the response as needed
// Example: You could check for certain fields in $jsonMpesaResponse
// if ($jsonMpesaResponse['Amount'] < 100) { // example check for Amount
//     $response = '{ "ResultCode": 1, "ResultDesc": "Transaction Rejected." }';
// } else {
$response = '{ "ResultCode": 0, "ResultDesc": "Confirmation Received Successfully" }';
// }

// Send the response to M-Pesa
echo $response;
?>
