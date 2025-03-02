<?php
session_start();
?>

<!DOCTYPE html>
<html lang="nl">
    <link rel="stylesheet" href="style.css">
<head>
    <title>Winkelmand</title>
    <script>
        function loadCart() {
            fetch('cart_handler.php', { method: 'GET' })
            .then(response => response.json())
            .then(data => {
                let cartList = document.getElementById("cartList");
                let total = 0;
                cartList.innerHTML = "";

                Object.entries(data).forEach(([domain, price]) => {
                    total += parseFloat(price);
                    cartList.innerHTML += `
                        <li>${domain} - €${price} 
                            <button onclick="removeFromCart('${domain}')">Verwijderen</button>
                        </li>`;
                });

                document.getElementById("total").innerText = "Totaal: €" + total.toFixed(2);
            });
        }

        function removeFromCart(domain) {
            fetch('cart_handler.php', {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ domain })
            }).then(() => loadCart());
        }

        document.addEventListener("DOMContentLoaded", loadCart);
    </script>
</head>
<body>
    <h1>Winkelmand</h1>
    <ul id="cartList"></ul>
    <p id="total"></p>
    <a href="checkout.php"><button>Afrekenen</button></a>
</body>
</html>
