<?php
session_start();
require_once '../action/connexion.php';

$id_client = isset($_SESSION['user']['id_client']) ? $_SESSION['user']['id_client'] : null;

// Recuperer les informations du client
$sql_profile = "SELECT id_client, nom, prenom, email, date_naissance, numero_permis FROM clients WHERE id_client = :id_client";
$mon_profile = $pdo->prepare($sql_profile);
$mon_profile->execute(["id_client" => $id_client]);
$profile = $mon_profile->fetch(PDO::FETCH_ASSOC);

// Récupérer les réservations du client
$sql_reservations = "SELECT r.id_reservation, v.marque, v.modele, r.date_debut, r.date_fin, r.statut_reservation
                     FROM reservations r
                     JOIN voitures v ON r.id_voiture = v.id_voiture
                     WHERE r.id_client = :id_client";
$ma_reservations = $pdo->prepare($sql_reservations);
$ma_reservations->execute(["id_client" => $id_client]);
$reservations = $ma_reservations->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les paiements du client
$sql_paiements = "SELECT p.id_paiement, p.id_reservation, p.montant, p.date_paiement, p.mode_paiement 
                  FROM paiements p
                  JOIN reservations r ON p.id_reservation = r.id_reservation
                  WHERE r.id_client = :id_client";
$mon_paiements = $pdo->prepare($sql_paiements);
$mon_paiements->execute(["id_client" => $id_client]);
$paiements = $mon_paiements->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Compte</title>
    <link rel="stylesheet" href="../css/client1.css">
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
    if (!isset($pdo)) {
        throw new Exception("Erreur : La connexion PDO n'a pas été établie.");
    }

    $id_client = $_SESSION['user']['id_client']; 
    
    $sql = "SELECT id_client, nom, prenom, email, numero_permis 
            FROM clients 
            WHERE id_client = :id_client";
    $stmt = $pdo->prepare($sql); // Utilisation de prepare()
    
    $stmt->execute(["id_client" => $id_client]);

    if ($stmt->rowCount() > 0) {
        while ($client = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr><td class='modal-td'>Nom :</td><td>" . htmlspecialchars($client["nom"]) . "</td></tr>";
            echo "<tr><td class='modal-td'>Prénom :</td><td>" . htmlspecialchars($client["prenom"]) . "</td></tr>";
            echo "<tr><td class='modal-td'>Email :</td><td>" . htmlspecialchars($client["email"]) . "</td></tr>";
            echo "<tr><td class='modal-td'>Permis :</td><td>" . htmlspecialchars($client["numero_permis"]) . "</td></tr>";
            echo "<tr><td colspan='2' class='modal-td'><a href='../action/deconnexion.php'>Déconnexion</a></td></tr>";
            echo "<tr><td colspan='2'><hr></td></tr>"; // Ligne de séparation
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

    <main class="dashboard-admin">
    <h2>Mes Réservations</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Voiture</th>
            <th>Date Début</th>
            <th>Date Fin</th>
            <th>Statut</th>
            <th>Paiement</th>
        </tr>
        <?php if ($reservations) { ?>
    <?php foreach ($reservations as $res) { ?>
        <tr>
            <td><?= htmlspecialchars($res['id_reservation']) ?></td>
            <td><?= htmlspecialchars($res['marque'] . " " . $res['modele']) ?></td>
            <td><?= htmlspecialchars($res['date_debut']) ?></td>
            <td><?= htmlspecialchars($res['date_fin']) ?></td>
            <td><?= htmlspecialchars($res['statut_reservation']) ?></td>
            <td>
                <?php if ($res['statut_reservation'] == 'confirmée') { ?>
                    <form action="paiement.php" method="GET" style="display:inline;">
                        <input type="hidden" name="id_reservation" value="<?= htmlspecialchars($res['id_reservation']) ?>">
                        <button type="submit" class="btn">Payer</button>
                    </form>
                <?php } else { ?>
                    <span>Non disponible</span>
                <?php } ?>
            </td>
        </tr>
    <?php } ?>
<?php } else { ?>
    <tr><td colspan="6">Aucune réservation trouvée.</td></tr>
<?php } ?>

    </table>

    <h2>Mes Paiements</h2>
    <table border="1">
        <tr>
            <th>ID Paiement</th>
            <th>ID Réservation</th>
            <th>Montant</th>
            <th>Date de Paiement</th>
            <th>Mode de Paiement</th>
        </tr>
        <?php if ($paiements): ?>
            <?php foreach ($paiements as $pay): ?>
            <tr>
                <td><?= htmlspecialchars($pay['id_paiement']) ?></td>
                <td><?= htmlspecialchars($pay['id_reservation']) ?></td>
                <td><?= htmlspecialchars($pay['montant']) ?> €</td>
                <td><?= htmlspecialchars($pay['date_paiement']) ?></td>
                <td><?= htmlspecialchars($pay['mode_paiement']) ?></td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5">Aucun paiement trouvé.</td></tr>
        <?php endif; ?>
    </table>
</main>
</body>
</html>