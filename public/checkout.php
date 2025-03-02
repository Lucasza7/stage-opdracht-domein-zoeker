<?php
session_start();
?>

<!DOCTYPE html>
<html lang="nl">
<link rel="stylesheet" href="../assets/style.css">

<head>
    <title>Afrekenen</title>
    <script>
        function processCheckout() {
            fetch('../api/checkout.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    alert("Bestelling geplaatst! Order ID: " + data.order_id);
                    window.location.href = "orders.php"; // Redirect to orders page
                }
            });
        }
    </script>
</head>
<body>
    <h1>Afrekenen</h1>
    <button onclick="processCheckout()">Bevestig Bestelling</button>
</body>
</html>
