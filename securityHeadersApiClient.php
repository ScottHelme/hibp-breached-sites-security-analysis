<?php

$fileName = $argv[1];
$file = fopen($fileName, 'r');

while (($line = fgets($file)) !== false) {
	$domain = strtolower(trim($line));
	echo 'Scanning: ' . $domain . "\r\n";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, 'https://api.securityheaders.com/?q=https%3A%2F%2F' . $domain . '%2F&hide=on&followRedirects=on');
	curl_setopt($ch, CURLOPT_HTTPHEADER, ['x-api-key: ' . getenv('apikey')]);
	$response = curl_exec($ch);
	sleep(1);
	if ($response) {
		$json = json_decode($response, true);
		if ($json) {
			echo 'Grade: ' . $json['summary']['grade'] . "\r\n";
			file_put_contents('output.txt', $domain . ',' . $json['summary']['grade'] . "\r\n", FILE_APPEND | LOCK_EX);
			continue;
		}
	}
	file_put_contents('output.txt', $domain . ',' . 'fail' . "\r\n", FILE_APPEND | LOCK_EX);
}