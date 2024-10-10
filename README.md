
# KALSTEIN_TEST - Gestion des Produits

Cette application de gestion de produits est développée en PHP pour la plateforme Kalstein Plus. Elle permet d'afficher, ajouter, modifier et supprimer des produits via une interface utilisateur simple et une API RESTful.

## Table des Matières

1. [Prérequis](#prérequis)
2. [Installation](#installation)
3. [Configuration](#configuration)
4. [Exécution de l'application](#exécution-de-lapplication)
5. [Tests Unitaires](#tests-unitaires)
6. [Endpoints de l'API](#endpoints-de-lapi)
7. [Sécurité et Bonnes Pratiques](#sécurité-et-bonnes-pratiques)
8. [Structure du Projet](#structure-du-projet)
9. [Contribuer](#contribuer)

## Prérequis

Avant de commencer, assurez-vous que les éléments suivants sont installés sur votre machine :

- **PHP 7.x ou supérieur**
- **Composer** (gestionnaire de dépendances PHP)
- **MySQL** (pour la base de données)
- Un serveur local comme **WAMP, XAMPP, MAMP** ou autre pour exécuter MySQL et les fichiers PHP
- **Python 3.x** (pour exécuter le serveur Flask)
- **Git** (pour le contrôle de version)

## Installation

1. Clonez le dépôt :

    ```bash
    git clone https://github.com/Arwoun/KALSTEIN_TEST.git
    cd KALSTEIN_TEST
    ```

2. Installez les dépendances PHP avec Composer :

    ```bash
    composer install
    ```

3. Installez les dépendances Python :

    ```bash
    pip install flask mysql-connector-python flask-cors
    ```

## Configuration

### 1. Configuration de la base de données

Créez une base de données MySQL nommée `kalstein` :

```sql
CREATE DATABASE kalstein;
```

Importez le fichier SQL pour créer la table `wp_k_products` :

```sql
CREATE TABLE wp_k_products (
    product_aid INT AUTO_INCREMENT PRIMARY KEY,
    product_name_fr VARCHAR(255) NOT NULL,
    product_peso_bruto DECIMAL(10, 2) NOT NULL,
    product_stock_units INT NOT NULL
);
```

### 2. Configuration de l'API Flask

Modifiez la configuration de la base de données dans le fichier `app.py` si nécessaire :

```python
db_config = {
    'host': 'localhost',
    'user': 'root',
    'password': '',
    'database': 'kalstein'
}
```

## Exécution de l'application

1. **Lancer le serveur Flask**  
   Exécutez le fichier `app.py` pour démarrer l'API Flask :

    ```bash
    python app.py
    ```

   L'API sera disponible sur [http://localhost:5000](http://localhost:5000).

2. **Exécuter l'interface utilisateur**  
   Ouvrez le fichier `index.html` dans votre navigateur pour accéder à l'interface de gestion des produits. Assurez-vous que le serveur Flask est en cours d'exécution.

## Tests Unitaires

### Installation de PHPUnit

Si PHPUnit n'est pas encore installé, installez-le via Composer :

```bash
composer require --dev phpunit/phpunit ^9
```

### Exécution des tests

Lancez les tests unitaires avec la commande suivante :

```bash
vendor/bin/phpunit --configuration phpunit.xml
```

Les tests vérifient les endpoints de l'API pour les opérations CRUD.

## Endpoints de l'API

- **GET /api/produits** : Retourne la liste complète des produits.
- **GET /api/produits/{id}** : Affiche les détails d’un produit spécifique.
- **POST /api/produits** : Permet d'ajouter un nouveau produit.
- **PUT /api/produits/{id}** : Permet de mettre à jour un produit existant.
- **DELETE /api/produits/{id}** : Supprime un produit.

### Exemple de requêtes

- **Ajouter un produit**

    ```bash
    curl -X POST http://localhost:5000/api/produits     -H "Content-Type: application/json"     -d '{"product_name_fr": "Produit Test", "product_peso_bruto": 10.5, "product_stock_units": 100}'
    ```

- **Mettre à jour un produit**

    ```bash
    curl -X PUT http://localhost:5000/api/produits/1     -H "Content-Type: application/json"     -d '{"product_name_fr": "Produit Test Modifié", "product_peso_bruto": 20.0, "product_stock_units": 150}'
    ```

- **Supprimer un produit**

    ```bash
    curl -X DELETE http://localhost:5000/api/produits/1
    ```

## Sécurité et Bonnes Pratiques

- **Protection contre les Injections SQL** : Utilisation de requêtes préparées pour éviter les attaques par injection.
- **Validations Côté Client** : Les formulaires vérifient que les champs sont remplis correctement avant l'envoi (nom non vide, prix positif, stock non négatif).
- **CORS Activé** : Permet les requêtes cross-origin pour les interactions avec l'interface.

## Structure du Projet

```plaintext
KALSTEIN_TEST/
├── app.py                  # Code de l'API Flask
├── index.html              # Interface utilisateur
├── README.md               # Documentation du projet
├── phpunit.xml             # Configuration PHPUnit
├── tests/                  # Tests unitaires pour l'API
│   └── ProductApiTest.php
├── vendor/                 # Dépendances PHP installées
└── .gitignore
```

## Contribuer

1. Forkez le projet.
2. Créez une branche pour votre fonctionnalité (`git checkout -b nouvelle-fonctionnalité`).
3. Commitez vos modifications (`git commit -am 'Ajout d'une nouvelle fonctionnalité'`).
4. Poussez vers la branche (`git push origin nouvelle-fonctionnalité`).
5. Créez une Pull Request.

Pour toute question, n'hésitez pas à [ouvrir une issue](https://github.com/Arwoun/KALSTEIN_TEST/issues).
