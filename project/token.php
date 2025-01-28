<?php 
    //GENERATE ACCESS TOKEN/AUTHENTICATION REQUEST
	$consumerKey = 'QRwtuwJs4NFukd0QJfy2lvHDzBEgmy7Jp6li9277rC0WuEZw'; //Fill with your app Consumer Key
	$consumerSecret = 'jO4nAjC693o5iAb0IHroNVA4EjvJ8xBZIQ86HLc2QGAWHDTC5CEixuWZx5Javwe5'; // Fill with your app Secret
    $headers = ['Content-Type:application/json; charset=utf8'];
	$token_url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
	$curl = curl_init($token_url);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($curl, CURLOPT_HEADER, FALSE);
	curl_setopt($curl, CURLOPT_USERPWD, $consumerKey.':'.$consumerSecret);
	$result = curl_exec($curl);
	$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	$result = json_decode($result);
	$access_token = $result->access_token;
	echo $access_token;
	curl_close($curl);
?>
