<?php
session_start();
require_once '../action/connexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si une image a été téléchargée
    if (empty($_FILES["image_file"]["tmp_name"])) {
        header("Location: ajouter_voiture.php?message=er");
        exit();
    }

    // Obtenir et nettoyer le nom de l'image
    $file_basename = preg_replace('/[^A-Za-z0-9_\-]/', '', pathinfo($_FILES["image_file"]["name"], PATHINFO_FILENAME));
    $file_extension = strtolower(pathinfo($_FILES["image_file"]["name"], PATHINFO_EXTENSION));

    // Validation du type et de la taille
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($file_extension, $allowed_extensions) || $_FILES["image_file"]["size"] > 2000000) {
        header("Location: ajouter_voiture.php?message=invalid_file");
        exit();
    }

    // Renommer l'image
    $new_image_name = $file_basename . '_' . date("Ymd_His") . '.' . $file_extension;

    // Requête d'insertion dans la table "voitures"
    $sql_voiture = "INSERT INTO voitures (marque, modele, annee_fabrication, plaque_immatriculation, statut, prix_jour) 
                VALUES (:marque, :modele, :annee_fabrication, :plaque_immatriculation, 'disponible', :prix_jour)";
$result_voiture = $pdo->prepare($sql_voiture);
$result_voiture->execute([
    "marque" => $_POST["marque"],
    "modele" => $_POST["modele"],
    "annee_fabrication" => $_POST["annee_fabrication"],
    "plaque_immatriculation" => $_POST["plaque_immatriculation"],
    "prix_jour" => $_POST["prix_jour"]
]);


    // Récupération de l'ID de la voiture
    $voiture_id = $pdo->lastInsertId();

    // Requête d'insertion dans la table "images"
    $sql_image = "INSERT INTO images (voiture_id, chemin_image) VALUES (:voiture_id, :chemin_image)";
    $result_image = $pdo->prepare($sql_image);
    $result_image->execute([
        "voiture_id" => $voiture_id,
        "chemin_image" => $new_image_name
    ]);

    // Déplacement de l'image
    $target_directory = "../image/";
    $target_path = $target_directory . $new_image_name;
    if (is_uploaded_file($_FILES["image_file"]["tmp_name"])) {
        if (!move_uploaded_file($_FILES["image_file"]["tmp_name"], $target_path)) {
            header("Location: ajouter_voiture.php?message=er");
            exit();
        }
    } else {
        header("Location: ajouter_voiture.php?message=invalid_upload");
        exit();
    }

    // Message de succès
    $success = "Voiture et image ajoutées avec succès !";
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une voiture</title>
    <link rel="stylesheet" href="../css/ajouter.css">
    <style>
  
.modal {
    display: none; 
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%; 
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.5); 
}

.modal-content {
    background-color: #fefefe;
    position: absolute;
    right: 0; 
    top: 0;
    height: 100%; 
    padding: 20px;
    border-radius: 8px 0 0 8px; 
    width: 30%; 
    max-width: 400px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    text-align: left; 
}


.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
}

.modal-table {
    width: 100%;
    margin-top: 20px;
    border-collapse: collapse;
}

.modal-table td {
    padding: 10px;
    text-align: left;
}

.modal-td {
    font-weight: bold;
    border-bottom: 1px solid #ddd;
}

.modal-table a {
    color: #007bff;
    text-decoration: none;
}

.modal-table a:hover {
    text-decoration: underline;
}

/* Ligne de séparation */
.modal-table hr {
    border: 0;
    border-top: 1px solid #ccc;
}

</style>
</head>
<body>
<header>
        <a href="#" class="logo"><img src="../img/jeep.png" alt="Logo"></a>
        <span class="logo-text">Hallo Car</span>
        <ul class="navbar">
            <li><a href="../php/admin_compte.php">Accueil</li>
            <li><a href="../php/gestion_client.php">Client</a></li>
            <li><a href="../php/ajouter_voiture.php">Voiture</a></li>
        </ul>
        <div class="header-btn">
        <a href="#" class="Profil" id="profile-link"><img src="../img/user-regular-240.png" alt="" width="25px" ></a>

        <div id="profile-modal" class="modal">
            <div class="modal-content">
                <span id="close-modal" class="close">&times;</span>
                <h2>Profil</h2>
                <table class="modal-table">
    <?php
    try {
     if (!isset($pdo)) {
         throw new Exception("Erreur : La connexion PDO n'a pas été établie.");
     }
 
     $id_admin = $_SESSION['user']['id_admin']; 
     
     $sql = "SELECT id_admin, nom_utilisateur, email, role
             FROM administrateurs 
             WHERE id_admin = :id_admin";
     $resultat = $pdo->prepare($sql); // Utilisation de prepare()
     
     $resultat->execute(["id_admin" => $id_admin]);
 
         if ($resultat->rowCount() > 0) {
             while ($admin = $resultat->fetch(PDO::FETCH_ASSOC)) {
                 echo "<tr><td class='modal-td'>Nom :</td><td>" . htmlspecialchars($admin["nom_utilisateur"]) . "</td></tr>";
                 echo "<tr><td class='modal-td'>Email :</td><td>" . htmlspecialchars($admin["email"]) . "</td></tr>";
                 echo "<tr><td class='modal-td'>Role :</td><td>" . htmlspecialchars($admin["role"]) . "</td></tr>";
                 echo "<tr><td colspan='2' class='modal-td'><a href='ajouter_admin.php'>Ajouter admin</a></td></tr>";
                 echo "<tr><td colspan='2'class='modal-td'><a href='../action/deconnexion.php'>Déconnexion</a></td></tr>";
                 echo "<tr><td colspan='2'><hr></td></tr>"; // Ligne de séparation
             }
         } else {
             echo "<tr><td colspan='2'>Aucun admin trouvé.</td></tr>";
         }
     } catch (Exception $e) {
         echo "<tr><td colspan='2'>Erreur : " . htmlspecialchars($e->getMessage()) . "</td></tr>";
     }
     ?>
</table>
           </div>
        </div>

        <script>
            const profileLink = document.getElementById('profile-link');
            const modal = document.getElementById('profile-modal');
            const closeModal = document.getElementById('close-modal');

            profileLink.onclick = function() {
                modal.style.display = "block";
            }

            closeModal.onclick = function() {
                modal.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        </script>

        </div>
</header>
    <section class="container">
        <h2>Ajouter une <span>voiture</span></h2>
        <?php if(isset($success)) echo "<p class='success'>$success</p>"; ?>
        <?php if (isset($_GET["message"])): ?>
            <?php if ($_GET["message"] == "ok"): ?>
                <div class="alert success">
                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                    L'insertion de l'image dans la base de données a réussi !
                </div>
            <?php else: ?>
                <div class="alert fail">
                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                    L'insertion de l'image dans la bdd a échoué !
                </div>
            <?php endif; ?>
        <?php endif; ?>
        
        <div class="form-box-1"> 
            <form method="POST" enctype="multipart/form-data">
                <input type="text" name="marque" placeholder="Marque" required>
                <input type="text" name="modele" placeholder="Modèle" required>
                <input type="number" name="annee_fabrication" placeholder="Année de fabrication" required>
                <input type="text" name="plaque_immatriculation" placeholder="Plaque d'immatriculation" required>
                <input type="number" name="prix_jour" placeholder="Prix par jour" required>
                <label for="images" class="drop-container" id="dropcontainer">
                    <span class="drop-title">Déposez les fichiers ici</span>
                    ou 
                    <input type="file" name="image_file" id="images" accept="image/*" required>
                </label>
                <button type="submit">Ajouter</button>
            </form> 
        </div> 
    </section>
    <script src="script.js"></script>
</body>
</html>
