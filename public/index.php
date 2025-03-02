<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $domain = $_POST["domain"];
    $tlds = ["com", "net", "org", "nl", "de", "co.uk", "info", "biz", "eu", "fr"];

    $searchData = array_map(function ($tld) use ($domain) {
        return ["name" => $domain, "extension" => $tld];
    }, $tlds);

    $ch = curl_init("http://localhost/domein-zoeker/api/search.php");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($searchData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

    $result = curl_exec($ch);
    curl_close($ch);

    $domains = json_decode($result, true);
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <title>Domein Zoeker</title>
    <link rel="stylesheet" href="../assets/style.css">
    <script>
        function searchDomain() {
            let domain = document.getElementById("domain").value;
            if (domain.length < 2) return;

            let tlds = ["com", "net", "org", "nl", "de", "co.uk", "info", "biz", "eu", "fr"];
            let requestData = tlds.map(ext => ({ name: domain, extension: ext }));

            fetch('../api/search.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(requestData)
            })
            .then(response => response.json())
            .then(data => {
                let results = document.getElementById("results");
                results.innerHTML = "";
                data.forEach(domain => {
                    let button = domain.status === "free" 
                        ? `<button class="add-to-cart" onclick="addToCart('${domain.domain}', ${domain.price})">Toevoegen aan Winkelmand</button>` 
                        : "<span class='not-available'>Niet beschikbaar</span>";
                    results.innerHTML += `<li>${domain.domain} - €${domain.price} ${button}</li>`;
                });
            });
        }

        function addToCart(domain, price) {
            fetch('../api/cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ domain, price })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Er ging iets mis bij het toevoegen aan de winkelmand');
                }
                return response.json();
            })
            .then(data => {
                alert(`${domain} is succesvol toegevoegd aan je winkelmand!`);
                loadCart();
            })
            .catch(error => {
                alert(error.message);
            });
        }

        function loadCart() {
            fetch("../api/cart.php", { method: 'GET' })
            .then(response => response.json())
            .then(data => {
                let cartList = document.getElementById("cart-items");
                let total = 0;
                cartList.innerHTML = "";

                Object.entries(data).forEach(([domain, price]) => {
                    total += parseFloat(price);
                    cartList.innerHTML += `<li>${domain} - €${price}</li>`;
                });

                document.getElementById("total").innerText = "Totaal: €" + total.toFixed(2);
            });
        }
    </script>
</head>
<body>
    <h1>Zoek een Domein</h1>
    <input type="text" id="domain" placeholder="Voer domeinnaam in" onkeyup="searchDomain()">
    <ul id="results"></ul>

    <h2>Winkelmand</h2>
    <ul id="cart-items"></ul>
    <p id="total"></p>
    <a href="cart.php"><button>Ga naar Winkelmand</button></a>

    <script>loadCart();</script>
</body>
</html>
