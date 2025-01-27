<?php
    include 'token.php';
	$registerurl = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl';
	$shortCode = '174379'; // provide the short code obtained from your test credentials
	$confirmationUrl = 'localhost\MPESA-API-Tutorial\Tutorial 1\confirmation_url.php'; // path to your confirmation url. can be IP address that is publicly accessible or a url
	$validationUrl = ' https://051b-105-160-95-120.ngrok-free.app/'; // path to your validation url. can be IP address that is publicly accessible or a url
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $registerurl);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$access_token)); //setting custom header

	$curl_post_data = array(
	  //Fill in the request parameters with valid values
	  'ShortCode' => $shortCode,
	  'ResponseType' => 'Completed',
	  'ConfirmationURL' => $confirmationUrl,
	  'ValidationURL' => $validationUrl
	);
	$data_string = json_encode($curl_post_data);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
	$curl_response = curl_exec($curl);
	print_r($curl_response);
	echo $curl_response;
?>
