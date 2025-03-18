<?php
session_start();
require "../action/connexion.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Se Connecter</title>
    <link rel="stylesheet" href="../css/client.css">
</head>
<body>
<header>
    <a href="#" class="logo"><img src="../img/jeep.png" alt="Logo"></a>
    <span class="logo-text">Hallo Car</span>
    <ul class="navbar">
        <li><a href="../php/client_compte.php">Accueil</a></li>
        <li><a href="../php/Concession.php">Concession</a></li>
        <li><a href="../php/service_client.php">Service client</a></li>
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

    $id_client = $_SESSION['user']['id_client'];

    $sql = "SELECT id_client, nom, prenom, email, numero_permis FROM clients WHERE id_client = :id_client";
    $recherche = $pdo->prepare($sql);
    
    $recherche->execute(["id_client" => $id_client]);

    if ($recherche->rowCount() > 0) {
        while ($client = $recherche->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr><td class='modal-td'>Nom :</td><td>" . htmlspecialchars($client["nom"]) . "</td></tr>";
            echo "<tr><td class='modal-td'>Prénom :</td><td>" . htmlspecialchars($client["prenom"]) . "</td></tr>";
            echo "<tr><td class='modal-td'>Email :</td><td>" . htmlspecialchars($client["email"]) . "</td></tr>";
            echo "<tr><td class='modal-td'>Permis :</td><td>" . htmlspecialchars($client["numero_permis"]) . "</td></tr>";
            echo "<tr><td colspan='2' class='modal-td<a href='deconnexion.php'>Déconnexion</a></td></tr>";
            echo "<tr><td colspan='2'><hr></td></tr>";
        }
    } else {
        echo "<tr><td colspan='2'>Aucun client trouvé.</td></tr>";
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
    <section class="Accueil" id="Accueil">
        <div class="Accueil-image">
            <img src="../img/about.png" alt="VOITURE JEEP">
        </div>
        <div class="Accueil-container">
            <h1>Service <span>Client</span></h1>
            <div class="formulaire-container">
                <p>Vous avez une question ou une suggestion ?</p>
                <form method="post" action="">
                    <input type="text" id="nom" name="nom" placeholder="Nom">
                    <input type="text" id="prenom" name="prenom" placeholder="Prénom">
                    <input type="email" id="email" name="email" placeholder="Email">
                    <textarea id="commentaire" name="commentaire" placeholder="Commentaire"></textarea>
                    <button type="submit" name="submit">Soumettre</button>
                </form>
            </div>
        </div>
    </section>

    <?php
    if (isset($_POST['submit'])) {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $commentaire = $_POST['commentaire'];

        try {
            if (!isset($pdo)) {
                throw new Exception("Erreur : La connexion PDO n'a pas été établie.");
            }

            $sql = "INSERT INTO commentaires (nom, prenom, email, commentaire) VALUES (:nom, :prenom, :email, :commentaire)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['nom' => $nom, 'prenom' => $prenom, 'email' => $email, 'commentaire' => $commentaire]);

            echo "<script>alert('Votre commentaire a été soumis avec succès.');</script>";
        } catch (Exception $e) {
            echo "<script>alert('Erreur : " . htmlspecialchars($e->getMessage()) . "');</script>";
        }
    }
    ?>
</body>
</html>