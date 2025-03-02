<?php
require '../config/database.php';

$db = new Database();
$conn = $db->getConnection();

$query = $conn->query("SELECT * FROM orders ORDER BY created_at DESC");
$orders = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">
<link rel="stylesheet" href="../assets/style.css">

<head>
    <title>Bestellingen</title>
</head>
<body>
    <h1>Bestellingen</h1>
    <table border="1">
        <tr>
            <th>Datum</th>
            <th>Domeinen</th>
            <th>Totaalprijs</th>
            <th>BTW</th>
        </tr>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= $order["created_at"] ?></td>
                <td><?= implode(", ", json_decode($order["domains"], true)) ?></td>
                <td>€<?= number_format($order["total_price"], 2) ?></td>
                <td>€<?= number_format($order["tax"], 2) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
