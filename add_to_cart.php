<?php
// add_to_cart.php

// Database configuratie
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "domein_zoeker";

// Maak verbinding met de database
$conn = new mysqli($servername, $username, $password, $dbname);

// Controleer de verbinding
if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}

// Haal de JSON input op
$input = file_get_contents('php://input');
$data = json_decode($input, true);

$domain_name = $data['domain_name'];
$status = $data['status'];
$price = $data['price'];

// Voeg het domein toe aan de database
$sql = "INSERT INTO domains (domain_name, status, price) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssd", $domain_name, $status, $price);

if ($stmt->execute()) {
    echo json_encode(["message" => "Domein toegevoegd aan winkelmand."]);
} else {
    echo json_encode(["error" => "Fout bij het toevoegen aan de database."]);
}

$stmt->close();
$conn->close();
?>