<?php
session_start();
require '../config/database.php';

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Method Not Allowed"]);
    exit;
}

// Check if cart is empty
if (empty($_SESSION['cart'])) {
    http_response_code(400);
    echo json_encode(["error" => "Je winkelmand is leeg."]);
    exit;
}

try {
    $db = new Database();
    $conn = $db->getConnection();

    // Calculate total price & tax
    $total_price = array_sum($_SESSION['cart']);
    $tax = $total_price * 0.21; // 21% BTW

    // Convert cart to JSON for storage
    $domains_json = json_encode(array_keys($_SESSION['cart']));

    // Insert order into database
    $query = $conn->prepare("INSERT INTO orders (domains, total_price, tax) VALUES (?, ?, ?)");
    $query->execute([$domains_json, $total_price, $tax]);

    // Clear cart after successful order
    $_SESSION['cart'] = [];

    echo json_encode(["message" => "Bestelling voltooid!", "order_id" => $conn->lastInsertId()]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Er is iets misgegaan: " . $e->getMessage()]);
}
?>
