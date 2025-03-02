<?php
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Domein Zoeker</title>
</head>
<body>
    <h1>Domein Zoeker</h1>
    <form id="domainForm">
        <label for="domainName">Domeinnaam:</label>
        <input type="text" id="domainName" name="domainName" required>
        <button type="submit">Zoek Domeinen</button>
        <button type="button" id="addToCart">Voeg toe aan winkelwagen</button>
    </form>

    <h2>Beschikbare Domeinen</h2>
    <ul id="domainList"></ul> 

    <h2>Winkelmand</h2>
    <ul id="cartList"></ul>
    <p id="total"></p>
    <a href="cart.php"><button>Ga naar Winkelmand</button></a>

    <button id="clearCart">Leeg Winkelwagen</button>

    <script>
        document.getElementById('domainForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const domainName = document.getElementById('domainName').value;
            const defaultTLDs = ['.com', '.net', '.org', '.info', '.biz', '.nl', '.eu', '.co', '.io', '.tech'];

            const requestData = defaultTLDs.map(extension => ({
                name: domainName,
                extension: extension
            }));

            fetch('search_domains.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(requestData)
            })
            .then(response => response.json())
            .then(data => {
                const domainList = document.getElementById('domainList');
                domainList.innerHTML = '';

                data.forEach(domain => {
                    const listItem = document.createElement('li');
                    listItem.style.display = 'flex';
                    listItem.style.justifyContent = 'space-between';
                    listItem.style.alignItems = 'center';
                    listItem.style.marginBottom = '10px';
                    
                    const textSpan = document.createElement('span');
                    textSpan.textContent = `${domain.domain} - ${domain.status} - €${domain.price}`;
                    listItem.appendChild(textSpan);
                    
                    const addButton = document.createElement('button');
                    addButton.textContent = 'Toevoegen aan winkelwagen';
                    addButton.id = 'addToCart';
                    addButton.style.marginLeft = '10px';
                    addButton.style.padding = '5px 10px';
                    addButton.style.backgroundColor = '#007bff';
                    addButton.style.color = 'grey';
                    addButton.style.border = 'none';
                    addButton.style.borderRadius = '4px';
                    addButton.style.cursor = 'pointer';
                    
                    if (domain.status === 'beschikbaar') {
                        addButton.addEventListener('click', function() {
                            const domainInfo = {
                                domain: domain.domain,
                                price: domain.price,
                                status: domain.status
                            };
                            addToCart(domainInfo);
                        });
                    } else {
                        addButton.disabled = true;
                        addButton.style.backgroundColor = '#ccc';
                    }
                    
                    listItem.appendChild(addButton);
                    domainList.appendChild(listItem);
                });
            })
            .catch(error => {
                console.error('Fout bij het ophalen van domeingegevens:', error);
            });
        });

        const cart = [];

        document.getElementById('addToCart').addEventListener('click', function() {
            const domainName = document.getElementById('domainName').value;
            if (domainName) {
                const domain = {
                    domain: domainName,
                    price: 10.00, // Standaard prijs, pas aan indien nodig
                    status: 'beschikbaar'
                };
                addToCart(domain);
            }
        });

        function addToCart(domain) {
            if (!cart.some(item => item.domain === domain.domain)) {
                cart.push(domain);
                updateCart();
            } else {
                alert('Dit domein zit al in je winkelwagen!');
            }
        }

        function removeFromCart(index) {
            cart.splice(index, 1);
            updateCart();
        }

        function updateCart() {
            const cartList = document.getElementById('cartList');
            cartList.innerHTML = '';

            cart.forEach((domain, index) => {
                const listItem = document.createElement('li');
                listItem.textContent = `${domain.domain} - €${domain.price}`;
                
                const removeButton = document.createElement('button');
                removeButton.textContent = 'Verwijderen';
                removeButton.addEventListener('click', () => removeFromCart(index));
                listItem.appendChild(removeButton);

                cartList.appendChild(listItem);
            });
        }

        document.getElementById('clearCart').addEventListener('click', () => {
            cart.length = 0;
            updateCart();
        });
    </script>
</body>
</html>