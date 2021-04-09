<?php

function random($length,$a) 
	{
		$str = "";
		if ($a == 0) {
			$characters = array_merge(range('0','9'));
		}elseif ($a == 1) {
			$characters = array_merge(range('0','9'),range('a','z'));
		}
		$max = count($characters) - 1;
		for ($i = 0; $i < $length; $i++) {
			$rand = mt_rand(0, $max);
			$str .= $characters[$rand];
		}
		return $str;
	}
	
while(1){

$imeix = ''.random(10,0).'';

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://mydhl.express.dhl/shipmentTracking?AWB=$imeix&countryCode=id&languageCode=en");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

$headers = array();
$headers[] = 'Authority: mydhl.express.dhl';
$headers[] = 'Accept: application/json, text/plain, */*';
$headers[] = 'User-Agent: Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Mobile Safari/537.36 Edg/89.0.774.68';
$headers[] = 'Sec-Fetch-Site: same-origin';
$headers[] = 'Sec-Fetch-Mode: cors';
$headers[] = 'Sec-Fetch-Dest: empty';
$headers[] = 'Referer: https://mydhl.express.dhl/id/en/mobile.html';
$headers[] = 'Accept-Language: en-US,en;q=0.9';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);

if (strpos($result, '"status" : "delivered"')) {
    
$datax = json_decode($result);
$waybill = $datax->results[0]->id;
$status = $datax->results[0]->delivery->status;
$origin = $datax->results[0]->origin->value;
$destination = $datax->results[0]->destination->value;
$desc = $datax->results[0]->signature->description;

$resultnya = "$waybill - Origin: $origin - Destination: $destination - Date: $desc - Status: $status";
            $log='logresi.txt';
            if(!file_exists( $log )) {
	        fopen($log,'a');
            }
	$alllog = "logresi.txt";
	$log_data2 = file($alllog, FILE_IGNORE_NEW_LINES);
	if(in_array($waybill, $log_data2)) {
	
	echo 'SKIPPED [has been saved]'.PHP_EOL;
	
	} else {
	
	file_put_contents($alllog, $waybill . "\n", FILE_APPEND);
    file_put_contents("hasilnya.txt", $resultnya.PHP_EOL, FILE_APPEND);
    echo $resultnya.PHP_EOL;
    
	}
} else {
	//echo ' invalid'.PHP_EOL;
}
}
?>
