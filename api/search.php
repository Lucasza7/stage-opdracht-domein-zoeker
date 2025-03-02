<?php
header("Content-Type: application/json");

$api_url = "https://dev.api.mintycloud.nl/api/v2.1/domains/search?with_price=true";
$authorization = "Basic 072dee999ac1a7931c205814c97cb1f4d1261559c0f6cd15f2a7b27701954b8d";

// Get the input data
$inputData = json_decode(file_get_contents("php://input"), true);

// Validate input
if (!$inputData || empty($inputData)) {
    http_response_code(400);
    echo json_encode(["error" => "Geen geldige zoekopdracht"]);
    exit;
}

// Initialize cURL request
$ch = curl_init($api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($inputData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: $authorization",
    "Content-Type: application/json"
]);

// Execute API request
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

// Handle API errors
if ($response === false || $http_code !== 200) {
    http_response_code(500);
    echo json_encode(["error" => "Probleem met API", "details" => $error]);
    exit;
}

// Return the API response
echo $response;
?>
