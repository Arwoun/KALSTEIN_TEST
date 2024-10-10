from flask import Flask, request, jsonify
import mysql.connector
from flask_cors import CORS  # Importer CORS

app = Flask(__name__)
CORS(app)

db_config = {
    'host': 'localhost',
    'user': 'root',
    'password': '',
    'database': 'kalstein'
}

def get_db_connection():
    connection = mysql.connector.connect(**db_config)
    return connection

# Endpoint pour obtenir la liste des produits avec un paramètre de limite
@app.route('/api/produits', methods=['GET'])
def get_produits():
    limit = request.args.get('limit', default=None, type=int)
    connection = get_db_connection()
    cursor = connection.cursor(dictionary=True)
    if limit:
        cursor.execute("SELECT * FROM wp_k_products ORDER BY product_aid LIMIT %s", (limit,))
    else:
        cursor.execute("SELECT * FROM wp_k_products ORDER BY product_aid")
    produits = cursor.fetchall()
    cursor.close()
    connection.close()
    return jsonify(produits)

# Endpoint pour obtenir les détails d'un produit spécifique
@app.route('/api/produits/<int:id>', methods=['GET'])
def get_produit(id):
    connection = get_db_connection()
    cursor = connection.cursor(dictionary=True)
    cursor.execute("SELECT * FROM wp_k_products WHERE product_aid = %s", (id,))
    produit = cursor.fetchone()
    cursor.close()
    connection.close()
    if produit:
        return jsonify(produit)
    else:
        return jsonify({"message": "Produit non trouvé"}), 404

# Endpoint pour ajouter un nouveau produit
@app.route('/api/produits', methods=['POST'])
def add_produit():
    data = request.json
    nom = data.get('product_name_fr')
    prix = data.get('product_peso_bruto')
    stock = data.get('product_stock_units')

    if not nom or prix is None or stock is None:
        return jsonify({"message": "Les champs nom, prix et stock sont obligatoires"}), 400

    connection = get_db_connection()
    cursor = connection.cursor()
    cursor.execute("INSERT INTO wp_k_products (product_name_fr, product_peso_bruto, product_stock_units) VALUES (%s, %s, %s)",
                   (nom, prix, stock))
    connection.commit()
    cursor.close()
    connection.close()
    return jsonify({"message": "Produit ajouté avec succès"}), 201


# Endpoint pour mettre à jour les informations d'un produit existant
@app.route('/api/produits/<int:id>', methods=['PUT'])
def update_produit(id):
    data = request.json
    nom = data.get('product_name_fr')
    prix = data.get('product_peso_bruto')
    stock = data.get('product_stock_units')

    if not nom or prix is None or stock is None:
        return jsonify({"message": "Les champs nom, prix et stock sont obligatoires"}), 400

    connection = get_db_connection()
    cursor = connection.cursor()
    cursor.execute("UPDATE wp_k_products SET product_name_fr = %s, product_peso_bruto = %s, product_stock_units = %s WHERE product_aid = %s",
                   (nom, prix, stock, id))
    connection.commit()
    cursor.close()
    connection.close()
    return jsonify({"message": "Produit mis à jour avec succès"})


# Endpoint pour supprimer un produit
@app.route('/api/produits/<int:id>', methods=['DELETE'])
def delete_produit(id):
    connection = get_db_connection()
    cursor = connection.cursor()
    cursor.execute("DELETE FROM wp_k_products WHERE product_aid = %s", (id,))
    connection.commit()
    cursor.close()
    connection.close()
    return jsonify({"message": "Produit supprimé avec succès"})

if __name__ == '__main__':
    app.run(debug=True)
