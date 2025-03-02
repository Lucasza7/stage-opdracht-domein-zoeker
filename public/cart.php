<?php
session_start();
?>

<!DOCTYPE html>
<html lang="nl">
<link rel="stylesheet" href="../assets/style.css">

<head>
    <title>Winkelmand</title>
    <script>
        function loadCart() {
            fetch('../api/cart.php', { method: 'GET' })
            .then(response => response.json())
            .then(data => {
                let cartList = document.getElementById("cart-items");
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
            fetch('../api/cart.php', {
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
    <ul id="cart-items"></ul>
    <p id="total">Totaal: €0.00</p>
    <a href="checkout.php"><button>Afrekenen</button></a>
</body>
</html>
