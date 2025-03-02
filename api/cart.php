<?php
session_start();

header("Content-Type: application/json");

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Haal de huidige winkelmand op
        echo json_encode($_SESSION['cart']);
        break;

    case 'POST':
        // Voeg een domein toe aan de winkelmand
        $input = json_decode(file_get_contents('php://input'), true);
        $domain = $input['domain'];
        $price = $input['price'];

        // Voeg het domein toe aan de sessie winkelmand
        $_SESSION['cart'][$domain] = $price;
        echo json_encode(['status' => 'success']);
        break;

    case 'DELETE':
        // Verwijder een domein uit de winkelmand
        $input = json_decode(file_get_contents('php://input'), true);
        $domain = $input['domain'];

        if (isset($_SESSION['cart'][$domain])) {
            unset($_SESSION['cart'][$domain]);
        }
        echo json_encode(['status' => 'success']);
        break;

    default:
        echo json_encode(['status' => 'unsupported method']);
        break;
}

