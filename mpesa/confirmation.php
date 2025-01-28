<?php
header("Content-Type: application/json");

// Default response to M-Pesa
$response = '{
    "ResultCode": 0, 
    "ResultDesc": "Confirmation Received Successfully"
}';

// Get the incoming data
$mpesaResponse = file_get_contents('php://input');
if ($mpesaResponse === false) {
    error_log("Failed to get input data");
    http_response_code(400); // Bad Request
    exit;
}

// Decode and validate JSON
$data = json_decode($mpesaResponse, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    error_log("Invalid JSON received");
    http_response_code(400); // Bad Request
    exit;
}

// Log the response to a file
$logFile = "response.json"; // Absolute path
$log = fopen($logFile, "a");
if ($log === false) {
    error_log("Failed to open log file");
    http_response_code(500); // Internal Server Error
    exit;
}
fwrite($log, $mpesaResponse . PHP_EOL);
fclose($log);

// Send response back to M-Pesa
echo $response;
?>
