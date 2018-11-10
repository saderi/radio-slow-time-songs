<?php 

include 'config.php';

function getAccessToken($clientID,$clientSecret)
{

    $authorization = base64_encode($clientID . ':' . $clientSecret);

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://accounts.spotify.com/api/token",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "grant_type=client_credentials",
        CURLOPT_HTTPHEADER => array(
            "authorization: Basic $authorization",
            "cache-control: no-cache",
            "content-type: application/x-www-form-urlencoded"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        return false;
    } else {
        $obj = json_decode($response);
        return $obj->access_token;
    }

}

$accessToken = getAccessToken($clientID,$clientSecret);
$curl = curl_init();
$NAME ='STEVE%20WINWOOD%20ANGEL%20OF%20MERCY';
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.spotify.com/v1/search?q=$NAME&type=track%2Cartist",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "accept: application/json",
    "authorization: Bearer $accessToken",
    "cache-control: no-cache",
    "content-type: application/json",
    "postman-token: 3a13337c-28c0-4c0f-539d-58f110e73873"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
	echo "<pre>";
	$obj = json_decode($response);

	// print_r($obj->tracks->items);

	foreach ($obj->tracks->items as $key => $value) {
		
		print_r($value->uri);
		echo "<br>";
		print_r($value->name);
		echo "<hr>";
		
	}
		echo "</pre>";
  // echo $response;
}
