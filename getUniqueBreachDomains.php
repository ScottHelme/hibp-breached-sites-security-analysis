<?php

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, 'https://haveibeenpwned.com/api/v3/breaches');
$response = curl_exec($ch);
$breachList = json_decode($response, true);
$domainList = [];
foreach ($breachList as $breach) {
	if (isset($breach['Domain']) && trim(strtolower($breach['Domain'])) !== '') {
		$domainList[] = trim(strtolower($breach['Domain']));
	}
}
$domainList = array_unique($domainList);
foreach ($domainList as $domain) {
	file_put_contents('output.txt', $domain . "\r\n", FILE_APPEND | LOCK_EX);
}
