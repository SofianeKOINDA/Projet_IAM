<?php
session_start();
require "connexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si une image a été téléchargée
    if (empty($_FILES["image_file"]["tmp_name"])) {
        header("Location: ajouter_voiture.php?message=er");
        exit();
    }

    // Obtenir le nom de l'image sans l'extension
    $file_basename = pathinfo($_FILES["image_file"]["name"], PATHINFO_FILENAME);
    $file_extension = pathinfo($_FILES["image_file"]["name"], PATHINFO_EXTENSION);
    
    // Validate file type and size
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array(strtolower($file_extension), $allowed_extensions) || $_FILES["image_file"]["size"] > 2000000) {
        header("Location: ajouter_voiture.php?message=invalid_file");
        exit();
    }

    // Renommer l'image en y ajoutant le nom de base et la date et l'heure
    $new_image_name = $file_basename . '_' . date("Ymd_His") . '.' . $file_extension;

    // Échapper les données pour éviter les attaques d'injection SQL
    $new_image_name = $pdo->quote($new_image_name);

    // Requête d'insertion dans la table "voitures"
    $sql = "INSERT INTO voitures (marque, modele, annee_fabrication, plaque_immatriculation, statut, image) 
            VALUES (:marque, :modele, :annee_fabrication, :plaque_immatriculation, 'disponible', $new_image_name)";
    $result = $pdo->prepare($sql);
    $result->execute([
        "marque" => $_POST["marque"],
        "modele" => $_POST["modele"],
        "annee_fabrication" => $_POST["annee_fabrication"],
        "plaque_immatriculation" => $_POST["plaque_immatriculation"],
        "image" => $new_image_name
    ]);

    if ($result) {
        // Déplacer l'image vers le dossier "images"
        $target_directory = "../img/";
        $target_path = $target_directory . $new_image_name;
        if (!move_uploaded_file($_FILES["image_file"]["tmp_name"], $target_path)) {
            header("Location: ajouter_voiture.php?message=er");
            exit();
        }
        $success = "Voiture ajoutée avec succès !";
    } else {
        header("Location: ajouter_voiture.php?message=no");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une voiture</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <header>
        <a href="#" class="logo"><img src="../img/jeep.png" alt="Logo"></a>
        <span class="logo-text">Hallo Car</span>
        <div class="header-btn">
            <a href="#" class="Profil" id="profile-link"><img src="../img/user-regular-240.png" alt="" width="25px"></a>
        </div>
    </header>
    <section class="container">
        <h2>Ajouter une <span>voiture</span></h2>
        <?php if(isset($success)) echo "<p class='success'>$success</p>"; ?>
        <?php if (isset($_GET["message"])): ?>
            <?php if ($_GET["message"] == "ok"): ?>
                <div class="alert success">
                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                    L'insertion de l'image dans la bdd a réussi !
                </div>
            <?php else: ?>
                <div class="alert fail">
                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                    L'insertion de l'image dans la bdd a échoué !
                </div>
            <?php endif; ?>
        <?php endif; ?>
        
        <div class="form-box"> 
            <form method="POST" enctype="multipart/form-data">
                <input type="text" name="marque" placeholder="Marque" required>
                <input type="text" name="modele" placeholder="Modèle" required>
                <input type="number" name="annee_fabrication" placeholder="Année de fabrication" required>
                <input type="text" name="plaque_immatriculation" placeholder="Plaque d'immatriculation" required>
                <label for="images" class="drop-container" id="dropcontainer">
                    <span class="drop-title">Déposez les fichiers ici</span>
                    ou
                    <input type="file" name="image_file" id="images" accept="image/*" required>
                </label>
                <button type="submit">Ajouter</button>
            </form>
        </div> 
        <p><a href="admin_compte.php">Retour à la page d'accueil</a></p>
    </section>
    <script src="script.js"></script>
</body>
</html>
