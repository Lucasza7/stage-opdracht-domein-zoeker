<?php
// search_domains.php

// API configuratie
$apiUrl = 'https://dev.api.mintycloud.nl/api/v2.1/domains/search?with_price=true';
$authHeader = 'Basic 072dee999ac1a7931c205814c97cb1f4d1261559c0f6cd15f2a7b27701954b8d';

// Haal de JSON input op
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Initialiseer cURL
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: ' . $authHeader,
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

// Voer de API-aanroep uit
$response = curl_exec($ch);
curl_close($ch);

// Stuur de response terug naar de client
header('Content-Type: application/json');
echo $response;
?>