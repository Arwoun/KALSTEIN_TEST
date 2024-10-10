<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Produits</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            width: 80%;
            max-width: 1000px;
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            font-weight: normal;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 20px;
        }
        form input[type="text"], form input[type="number"] {
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: calc(100% - 18px);
            background-color: #f9f9f9;
        }
        form button {
            padding: 10px;
            font-size: 14px;
            background-color: #5a6268;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
        }
        form button:hover {
            background-color: #444b51;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 14px;
            background-color: #fff;
            table-layout: fixed;
        }
        table thead {
            background-color: #e9ecef;
        }
        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        table th:nth-child(2), table td:nth-child(2) {
            max-width: 300px;
        }
        table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .action-buttons button {
            padding: 5px 8px;
            font-size: 12px;
            background-color: #6c757d;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .action-buttons button:hover {
            background-color: #5a6268;
        }
        .edit {
            margin-right: 5px;
        }
        .delete {
            background-color: #dc3545;
        }
        .delete:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Gestion des Produits</h2>

    <!-- Formulaire pour ajouter ou mettre à jour un produit -->
    <form id="productForm">
        <input type="hidden" id="productId">
        <label for="productName">Nom du produit:</label>
        <input type="text" id="productName" placeholder="Entrez le nom du produit" required>
        <label for="productPrice">Prix (Peso Bruto):</label>
        <input type="number" id="productPrice" placeholder="Entrez le prix" step="0.01" required>
        <label for="productStock">Stock (Unités):</label>
        <input type="number" id="productStock" placeholder="Entrez le stock" required>
        <button type="submit">Enregistrer</button>
        <button type="reset" onclick="resetForm()">Annuler</button>
    </form>

    <!-- Tableau pour afficher les produits -->
    <table id="productTable">
        <thead>
        <tr>
            <th style="width: 50px;">ID</th>
            <th>Nom du produit</th>
            <th style="width: 120px;">Prix (Peso Bruto)</th>
            <th style="width: 100px;">Stock (Unités)</th>
            <th style="width: 150px;">Actions</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<script>
    const apiUrl = 'http://localhost:5000/api/produits';
    let currentEditIndex = -1;

    // Charger les produits
    function loadProducts() {
        fetch(apiUrl)
            .then(response => response.json())
            .then(data => updateProductTable(data))
            .catch(error => console.error('Erreur lors du chargement:', error));
    }

    // Mettre à jour le tableau des produits
    function updateProductTable(products) {
        const tableBody = document.getElementById('productTable').querySelector('tbody');
        tableBody.innerHTML = '';
        products.forEach(product => {
            const row = tableBody.insertRow();
            row.innerHTML = `
                <td>${product.product_aid}</td>
                <td>${product.product_name_fr}</td>
                <td>${product.product_peso_bruto}</td>
                <td>${product.product_stock_units}</td>
                <td class="action-buttons">
                    <button class="edit" onclick="editProduct(${product.product_aid})">Modifier</button>
                    <button class="delete" onclick="deleteProduct(${product.product_aid})">Supprimer</button>
                </td>
            `;
        });
    }

    // Ajouter ou mettre à jour un produit avec des validations côté client
    document.getElementById('productForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const name = document.getElementById('productName').value.trim();
        const price = parseFloat(document.getElementById('productPrice').value);
        const stock = parseInt(document.getElementById('productStock').value, 10);

        // Validations côté client
        if (name === '') {
            alert("Le nom du produit ne doit pas être vide.");
            return;
        }
        if (isNaN(price) || price <= 0) {
            alert("Le prix doit être un nombre positif.");
            return;
        }
        if (isNaN(stock) || stock < 0) {
            alert("Le stock doit être un entier non négatif.");
            return;
        }

        const product = { product_name_fr: name, product_peso_bruto: price, product_stock_units: stock };

        if (currentEditIndex === -1) {
            // Ajouter un nouveau produit
            fetch(apiUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(product)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        alert('Produit ajouté avec succès !');
                        loadProducts();
                        resetForm();
                    } else {
                        alert('Erreur lors de l\'ajout du produit.');
                        console.error('Erreur:', data);
                    }
                })
                .catch(error => console.error('Erreur lors de l\'ajout:', error));
        } else {
            // Mettre à jour le produit existant
            fetch(`${apiUrl}/${currentEditIndex}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(product)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        alert('Produit mis à jour avec succès !');
                        loadProducts();
                        resetForm();
                        currentEditIndex = -1; // Réinitialiser l'indice
                    } else {
                        alert('Erreur lors de la mise à jour du produit.');
                        console.error('Erreur:', data);
                    }
                })
                .catch(error => console.error('Erreur lors de la mise à jour:', error));
        }
    });

    // Modifier un produit
    function editProduct(id) {
        fetch(`${apiUrl}/${id}`)
            .then(response => response.json())
            .then(product => {
                document.getElementById('productName').value = product.product_name_fr;
                document.getElementById('productPrice').value = product.product_peso_bruto;
                document.getElementById('productStock').value = product.product_stock_units;
                currentEditIndex = id; // Mettre à jour l'indice du produit actuel
            })
            .catch(error => console.error('Erreur lors de la récupération du produit:', error));
    }

    // Supprimer un produit
    function deleteProduct(id) {
        fetch(`${apiUrl}/${id}`, { method: 'DELETE' })
            .then(() => loadProducts())
            .catch(error => console.error('Erreur lors de la suppression:', error));
    }

    // Réinitialiser le formulaire
    function resetForm() {
        document.getElementById('productForm').reset();
        currentEditIndex = -1;
    }

    // Charger les produits au démarrage
    window.onload = loadProducts;
</script>
</body>
</html>
