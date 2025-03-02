<?php
require_once 'Database.php';

class Order {
    public static function createOrder($domains, $total_price, $tax) {
        $db = new Database();
        $conn = $db->getConnection();

        $query = $conn->prepare("INSERT INTO orders (domains, total_price, tax) VALUES (?, ?, ?)");
        $query->execute([json_encode($domains), $total_price, $tax]);

        return $conn->lastInsertId();
    }

    public static function getOrders() {
        $db = new Database();
        $conn = $db->getConnection();

        $query = $conn->prepare("SELECT * FROM orders ORDER BY created_at DESC");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getOrderById($id) {
        $db = new Database();
        $conn = $db->getConnection();

        $query = $conn->prepare("SELECT * FROM orders WHERE id = ?");
        $query->execute([$id]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}
?>
