<?php
// Database connection using the specified format
$servername = "localhost";  // Typically localhost
$username = "root";         // Your MySQL username
$password = "";             // Your MySQL password (usually empty in XAMPP/WAMP)
$dbname = "payments";       // Name of the database

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) !== TRUE) {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db($dbname);

// Create table if it doesn't exist
$tableSql = "CREATE TABLE IF NOT EXISTS received_payments(
    TransID INT AUTO_INCREMENT NOT NULL,
    TransactionType VARCHAR(10) NOT NULL,
    TransTime VARCHAR(14) NOT NULL,
    TransAmount VARCHAR(6) NOT NULL,
    BillRefNumber VARCHAR(6) NOT NULL,
    InvoiceNumber VARCHAR(6) NOT NULL,
    OrgAccountBalance VARCHAR(10) NOT NULL,
    ThirdPartyTransID VARCHAR(10) NOT NULL,
    MSISDN VARCHAR(14) NOT NULL,
    FirstName VARCHAR(10),
    MiddleName VARCHAR(10),
    LastName VARCHAR(10),
    PRIMARY KEY (TransID),
    UNIQUE(TransID)
) ENGINE=InnoDB;";

if ($conn->query($tableSql) !== TRUE) {
    die("Error creating table: " . $conn->error);
}

header("Content-Type: application/json");
$response = '{
    "ResultCode": 0, 
    "ResultDesc": "Confirmation Received Successfully"
}';

// Get M-Pesa response
$mpesaResponse = file_get_contents('php://input');
$data = json_decode($mpesaResponse, true);

if (isset($data['Body']['stkCallback']['ResultCode']) && $data['Body']['stkCallback']['ResultCode'] == 0) {
    // Successful transaction
    $callbackMetadata = $data['Body']['stkCallback']['CallbackMetadata']['Item'];

    // Extract relevant details
    $TransID = "";
    $TransactionType = "";
    $TransTime = "";
    $TransAmount = "";
    $BillRefNumber = "";
    $InvoiceNumber = "";
    $OrgAccountBalance = "";
    $ThirdPartyTransID = "";
    $MSISDN = "";
    $FirstName = "";
    $MiddleName = "";
    $LastName = "";

    foreach ($callbackMetadata as $item) {
        switch ($item['Name']) {
            case 'TransactionType':
                $TransactionType = $item['Value'];
                break;
            case 'TransID':
                $TransID = $item['Value'];
                break;
            case 'TransTime':
                $TransTime = $item['Value'];
                break;
            case 'TransAmount':
                $TransAmount = $item['Value'];
                break;
            case 'BillRefNumber':
                $BillRefNumber = $item['Value'];
                break;
            case 'InvoiceNumber':
                $InvoiceNumber = $item['Value'];
                break;
            case 'OrgAccountBalance':
                $OrgAccountBalance = $item['Value'];
                break;
            case 'ThirdPartyTransID':
                $ThirdPartyTransID = $item['Value'];
                break;
            case 'MSISDN':
                $MSISDN = $item['Value'];
                break;
            case 'FirstName':
                $FirstName = $item['Value'];
                break;
            case 'MiddleName':
                $MiddleName = $item['Value'];
                break;
            case 'LastName':
                $LastName = $item['Value'];
                break;
        }
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO received_payments (TransactionType, TransID, TransTime, TransAmount, BillRefNumber, InvoiceNumber, OrgAccountBalance, ThirdPartyTransID, MSISDN, FirstName, MiddleName, LastName) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siissssssssss", $TransactionType, $TransID, $TransTime, $TransAmount, $BillRefNumber, $InvoiceNumber, $OrgAccountBalance, $ThirdPartyTransID, $MSISDN, $FirstName, $MiddleName, $LastName);
    $stmt->execute();
    $stmt->close();
}

// Log the response
$logFile = "M_PESAConfirmationResponse.json";
$log = fopen($logFile, "a");
fwrite($log, $mpesaResponse);
fclose($log);

// Send response back to M-Pesa
echo $response;

// Close the database connection
$conn->close();
?>
