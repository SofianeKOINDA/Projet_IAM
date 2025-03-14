<?php
require "connexion.php";
$nom_utilisateur = "Sofiane"; // Replace with the new admin's name
$email = "admin@sofiane.com"; // Replace with the new admin's email
$mot_de_passe = "waouh2025"; // Replace with the new admin's password
$super_admin = 1 ; // Replace with the new admin's type
$hashed_password = password_hash($mot_de_passe, PASSWORD_DEFAULT);

$sql = "INSERT INTO administrateurs (nom_utilisateur, email, mot_de_passe, super_admin) VALUES (:nom_utilisateur, :email, :mot_de_passe, :super_admin)";
$stmt = $pdo->prepare($sql);

try {
    $stmt->execute([
        "nom_utilisateur" => $nom_utilisateur,
        "email" => $email,
        "mot_de_passe" => $hashed_password,
        "super_admin" => $super_admin
    ]);
    echo "New user account created successfully!";
} catch (Exception $e) {
    echo "Error creating admin account: " . $e->getMessage();
}
?>
