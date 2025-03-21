<?php

$host = 'mysql-kia.alwaysdata.net'; // Adresse du serveur MySQL
$dbname = 'kia_gestion_comptes'; // Nom de la base de données
$username = 'kia'; 
$password = 'LALLA1234'; 
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>