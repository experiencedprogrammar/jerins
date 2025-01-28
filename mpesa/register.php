<?php
    include 'token.php'; // Ensure this file correctly generates and returns a valid token.

    $registerUrl = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl';
    $shortCode = '174379'; // Provide the short code obtained from your test credentials
    $confirmationUrl = 'https://jerins.vercel.app/mpesa/confirmation.php'; // Replace with a public URL
    $validationUrl = 'https://jerins.vercel.app/mpesa/validation.php'; // Replace with a public URL

    // Initialize cURL
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $registerUrl);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Content-Type:application/json',
        'Authorization:Bearer ' . $access_token
    )); // Setting custom headers

    // Prepare POST data
    $curl_post_data = array(
        'ShortCode' => $shortCode,
        'ResponseType' => 'Completed', // Ensure this matches your use case
        'ConfirmationURL' => $confirmationUrl,
        'ValidationURL' => $validationUrl
    );
    $data_string = json_encode($curl_post_data);

    // Configure cURL options
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

    // Execute cURL and handle errors
    $curl_response = curl_exec($curl);
    if (curl_errno($curl)) {
        echo 'Curl error: ' . curl_error($curl);
    } else {
        echo $curl_response;
    }

    // Close cURL session
    curl_close($curl);
?>
