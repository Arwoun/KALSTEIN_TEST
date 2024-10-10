<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kalstein";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}
echo "Connexion réussie à la base de données";
?>
