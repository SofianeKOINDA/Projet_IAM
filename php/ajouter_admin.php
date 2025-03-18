<?php
session_start();
require_once '../action/connexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = isset($_POST['role']) ? 'super_admin' : 'admin';

    try {
        $query = "INSERT INTO administrateurs (nom_utilisateur, email, mot_de_passe, role) 
                  VALUES (:nom, :email, :mot_de_passe, :role)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':nom' => $nom,
            ':email' => $email,
            ':mot_de_passe' => $password,
            ':role' => $role
        ]);

        $success = "Administrateur ajouté avec succès!";
    } catch (PDOException $e) {
        $error = "Erreur: " . $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un administrateur</title>
    <link rel="stylesheet" href="../css/ajouter.css">
    <style>
        .modal {
    display: none; /* Masqué par défaut */
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%; /* Prend toute la hauteur de la page */
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.5); /* Fond semi-transparent */
}

/* Contenu du modal (aligné à droite et pleine hauteur) */
.modal-content {
    background-color: #fefefe;
    position: absolute; /* Position fixe pour ancrer à droite */
    right: 0; /* Aligner à droite */
    top: 0;
    height: 100%; /* Prend toute la hauteur */
    padding: 20px;
    border-radius: 8px 0 0 8px; /* Bord arrondi à gauche pour un effet esthétique */
    width: 30%; /* Ajustez la largeur si nécessaire */
    max-width: 400px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    text-align: left; /* Contenu aligné à gauche dans le modal */
}

/* Bouton de fermeture */
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

/* Table dans le modal */
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
            <a href="#" class="Profil" id="profile-link"><img src="../img/user-regular-240.png" alt="" width="25px"></a>
            
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
        </div>
    </header>

    <section class="container">
        <h2>Ajouter un <span>administrateur</span></h2>
        <?php if(isset($success)) echo "<p class='success'>$success</p>"; ?>
        <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
        
        <div class="form-box">
            <form method="POST">
                <input type="text" name="nom" placeholder="Nom_utilisateur" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Mot de passe" required>
                <div style="margin: 10px 0;">
                    <label>
                        <input type="checkbox" name="role">
                        Super Admin
                    </label>
                </div>
                <button type="submit">Ajouter</button>
            </form>
        </div>
    </section>

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
</body>
</html>
