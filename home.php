<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Produits</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
<div class="container">
    <h2>TEST KALSTEIN NIRMALADAS</h2>
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
    <div class="pagination">
        <button id="prevPage" onclick="prevPage()" disabled>Précédent</button>
        <button id="nextPage" onclick="nextPage()">Suivant</button>
    </div>
</div>
<script src="JS/script.js"></script>

</body>
</html>
