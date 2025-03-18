<?php
session_start();
require_once '../action/connexion.php';

// Initialiser les variables pour afficher les messages ou les résultats
$message = '';
$success = false;

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_reservation = $_POST['id_reservation'];
    $montant = $_POST['montant'];
    $mode_paiement = $_POST['mode_paiement'];

    try {
        // Étape 1 : Récupérer les informations de la réservation
        $sql = "SELECT id_voiture, id_client FROM reservations WHERE id_reservation = :id_reservation";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id_reservation' => $id_reservation]);
        $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$reservation) {
            $message = "Réservation introuvable.";
        } else {
            $id_voiture = $reservation['id_voiture'];
            $id_client = $reservation['id_client'];

            // Débuter une transaction pour garantir la cohérence des données
            $pdo->beginTransaction();

            // Étape 2 : Insérer dans la table paiement
            $sqlPaiement = "INSERT INTO paiement (id_client, id_reservation, montant, mode_paiement, date_paiement) 
                            VALUES (:id_client, :id_reservation, :montant, :mode_paiement, NOW())";
            $stmt = $pdo->prepare($sqlPaiement);
            $stmt->execute([
                'id_client' => $id_client,
                'id_reservation' => $id_reservation,
                'montant' => $montant,
                'mode_paiement' => $mode_paiement
            ]);

            // Étape 3 : Mettre à jour le statut de la voiture à "loué"
            $sqlVoiture = "UPDATE voitures SET statut = 'loué' WHERE id_voiture = :id_voiture";
            $stmt = $pdo->prepare($sqlVoiture);
            $stmt->execute(['id_voiture' => $id_voiture]);

            // Étape 4 : Supprimer la réservation
            $sqlReservation = "DELETE FROM reservations WHERE id_reservation = :id_reservation";
            $stmt = $pdo->prepare($sqlReservation);
            $stmt->execute(['id_reservation' => $id_reservation]);

            // Valider la transaction
            $pdo->commit();
            $success = true;

            // Rediriger vers la page "paiement.php" en cas de succès
            header("Location: paiement.php");
            exit;
        }
    } catch (Exception $e) {
        // Annuler la transaction en cas d'erreur
        $pdo->rollBack();
        $message = "Erreur lors du traitement du paiement : " . $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement</title>
    <link rel= "stylesheet" href="../css/paiement.css">
</head>
<style>
   /* Style pour le modal */
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
    // Vérifiez que la connexion PDO est bien initialisée
    if (!isset($pdo)) {
        throw new Exception("Erreur : La connexion PDO n'a pas été établie.");
    }

    // Obtenez l'ID client depuis une source (par exemple, la session)
    $id_client = $_SESSION['user']['id_client']; // Assurez-vous que cette valeur existe !

    // Préparez la requête SQL avec un paramètre nommé
    $sql = "SELECT id_client, nom, prenom, email, numero_permis 
            FROM clients 
            WHERE id_client = :id_client";
    $recherche = $pdo->prepare($sql); // Utilisation de prepare()
    
    $recherche->execute(["id_client" => $id_client]);

    // Vérifie si des résultats existent
    if ($recherche->rowCount() > 0) {
        while ($client = $recherche->fetch(PDO::FETCH_ASSOC)) {
            
            echo "<tr><td class='modal-td'>Nom :</td><td>" . htmlspecialchars($client["nom"]) . "</td></tr>";
            echo "<tr><td class='modal-td'>Prénom :</td><td>" . htmlspecialchars($client["prenom"]) . "</td></tr>";
            echo "<tr><td class='modal-td'>Email :</td><td>" . htmlspecialchars($client["email"]) . "</td></tr>";
            echo "<tr><td class='modal-td'>Permis :</td><td>" . htmlspecialchars($client["numero_permis"]) . "</td></tr>";
            echo "<tr><td colspan='2' class='modal-td'><a href='deconnexion.php'>Déconnexion</a></td></tr>";
            echo "<tr><td colspan='2'><hr></td></tr>"; // Ligne de séparation
        }
    } else {
        // Aucun client trouvé
        echo "<tr><td colspan='2'>Aucun client trouvé.</td></tr>";
    }
} catch (Exception $e) {
    // Gestion des erreurs
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
    <h2>Paiement > </h2>
    <div class="container">
        <h2>Effectuer un Paiement</h2>
        <form  method="POST">
            <!-- Champ caché pour l'ID de réservation -->
            <input type="hidden" name="id_reservation" value="<?= htmlspecialchars($reservation['id_reservation'] ?? '') ?>">
            
            <!-- Champ pour le montant -->
            <div class="form-group">
                <label for="montant">Montant :</label>
                <input type="number" name="montant" id="montant" placeholder="Entrez le montant" required>
            </div>
            
            <!-- Champ pour le mode de paiement -->
            <div class="form-group">
                <label for="mode_paiement">Mode de paiement :</label>
                <select name="mode_paiement" id="mode_paiement" required>
                    <option value="">-- Sélectionnez un mode de paiement --</option>
                    <option value="carte">Carte</option>
                    <option value="paypal">PayPal</option>
                </select>
            </div>
            
            <!-- Bouton pour soumettre le formulaire -->
            <div class="form-group">
                <button type="submit" class="btn">Confirmer le Paiement</button>
            </div>
        </form>
    </div>
</body>
</html>