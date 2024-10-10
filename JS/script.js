const apiUrl = 'http://localhost:5000/api/produits';
let currentEditIndex = -1;
let currentPage = 1;
const itemsPerPage = 20;

function loadProducts(page = 1) {
    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            updateProductTable(data, page);
            togglePaginationButtons(data.length);
        })
        .catch(error => console.error('Erreur lors du chargement:', error));
}

function updateProductTable(products, page) {
    const tableBody = document.getElementById('productTable').querySelector('tbody');
    tableBody.innerHTML = '';

    const start = (page - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const paginatedProducts = products.slice(start, end);

    paginatedProducts.forEach(product => {
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

function togglePaginationButtons(totalItems) {
    document.getElementById('prevPage').disabled = currentPage === 1;
    document.getElementById('nextPage').disabled = currentPage * itemsPerPage >= totalItems;
}

function prevPage() {
    if (currentPage > 1) {
        currentPage--;
        loadProducts(currentPage);
    }
}

function nextPage() {
    currentPage++;
    loadProducts(currentPage);
}

document.getElementById('productForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const name = document.getElementById('productName').value.trim();
    const price = parseFloat(document.getElementById('productPrice').value);
    const stock = parseInt(document.getElementById('productStock').value, 10);

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
        fetch(apiUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(product)
        })
            .then(response => response.json())
            .then(data => {
                alert('Produit ajouté avec succès !');
                loadProducts(currentPage);
                resetForm();
            })
            .catch(error => console.error('Erreur lors de l\'ajout:', error));
    } else {
        fetch(`${apiUrl}/${currentEditIndex}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(product)
        })
            .then(response => response.json())
            .then(data => {
                alert('Produit mis à jour avec succès !');
                loadProducts(currentPage);
                resetForm();
                currentEditIndex = -1;
            })
            .catch(error => console.error('Erreur lors de la mise à jour:', error));
    }
});

function editProduct(id) {
    fetch(`${apiUrl}/${id}`)
        .then(response => response.json())
        .then(product => {
            document.getElementById('productName').value = product.product_name_fr;
            document.getElementById('productPrice').value = product.product_peso_bruto;
            document.getElementById('productStock').value = product.product_stock_units;
            currentEditIndex = id;
        })
        .catch(error => console.error('Erreur lors de la récupération du produit:', error));
}

function deleteProduct(id) {
    fetch(`${apiUrl}/${id}`, { method: 'DELETE' })
        .then(() => loadProducts(currentPage))
        .catch(error => console.error('Erreur lors de la suppression:', error));
}

function resetForm() {
    document.getElementById('productForm').reset();
    currentEditIndex = -1;
}

window.onload = () => loadProducts();