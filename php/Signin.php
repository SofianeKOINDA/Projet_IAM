<?php
session_start();
require_once '../action/connexion.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_BCRYPT);
    $date_naissance = $_POST['date_naissance'];
    $adresse = $_POST['adresse'];
    $permis_conduire = $_POST['numero_permis'];

    $sql = "INSERT INTO clients (nom, prenom, email, mot_de_passe, date_naissance, adresse, numero_permis) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $query = $pdo->prepare($sql);

    if ($query->execute([$nom, $prenom, $email, $mot_de_passe, $date_naissance, $adresse, $permis_conduire])) {
        $id_client = $pdo->lastInsertId(); // Récupérer l'ID du client ajouté
        $_SESSION['user'] = [
        'id_client' => $id_client,
        'email' => $email
    ];
        $_SESSION['user_type'] = 'client';
        header("Location: client_compte.php");
        exit();
    } else {
        $error = "Erreur lors de l'inscription.";
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Se Connecter</title>
    <link rel="stylesheet" href="../css/logsign.css">
</head>
<body>
    <header>
        <a href="#" class="logo"><img src="../img/jeep.png" alt="Logo"></a>
        <span class="logo-text">Hallo Car</span>
    </header>

    <section class="Accueil" id="Accueil">
        <div class="text">
            <h1><span>Avec Hallo Car</span>, louer autrement</h1>

        
        <div class="formulaire-connexion">
           
            <form method="POST">
                <input type="text" name="nom" placeholder="Nom" required>
                <input type="text" name="prenom" placeholder="Prénom" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="mot_de_passe" placeholder="Mot de passe" 
                pattern ="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                title="Le mot de passe doit comporter au moins 8 caractères, dont au moins une minuscule, une majuscule et un chiffre."
                required>
                <input type="date" name="date_naissance" required>
                <input type="text" name="adresse" placeholder="Adresse" required>
                <input type="text" name="numero_permis" placeholder="Numéro de permis" required>
                <p class="erreur"> <?php if (isset($e)) {
                                            echo $e;
                                            }?></p>
                <button type="submit">S'inscrire</button>
            </form>
        </div>
    </section>
</body>
</html>