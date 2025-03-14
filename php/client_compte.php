<?php
session_start();
require "connexion.php";

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
    <link rel="stylesheet" href="../css/client.css">
</head>
<body>
<header>
        <a href="#" class="logo"><img src="../img/jeep.png" alt="Logo"></a>
        <span class="logo-text">Hallo Car</span>
        <ul class="navbar">
            <li><a href="../php/gestion_reservation.php">Réservation</li>
            <li><a href="../php/gestion_client.php">Client</a></li>
            <li><a href="../php/ajouter_voiture.php">Voiture</a></li>
        </ul>
        <div class="header-btn">
        <a href="#" class="Profil" id="profile-link"><img src="../img/user-regular-240.png" alt="" width="25px" ></a>

        <div id="profile-modal" class="modal">
            <div class="modal-content">
                <span id="close-modal" class="close">&times;</span>
                <h2>Profil</h2>
                <table>
    <tr>
        <td>ID_Admin</td>
    </tr>
    <tr>
        <td>Nom d'utilisateur</td>
    </tr>
    <tr>
        <td>Email</td>
    </tr>
    <tr>
        <td>Super_Admin</td>
    </tr>
<?php
try {
    // Vérifier que la connexion PDO est bien initialisée
    if (!isset($pdo)) {
        throw new Exception("Erreur : La connexion PDO n'a pas été établie.");
    }

    // Exécuter la requête SQL
    $sql = "SELECT id_client, nom, prenom, email, adresse, date_naissance, numero_permis FROM clients";
    $resultat = $pdo->query($sql);

    if ($resultat->rowCount() > 0) {
        while ($mon_profile = $resultat->fetch(PDO::FETCH_ASSOC)) {
            echo "  <tr>
                    <th>" . htmlspecialchars($mon_profile["id_client"]) . "</th>
                    </tr>

                    <tr>
                    <th>" . htmlspecialchars($mon_profile["nom"]) . "</th>
                    </tr>

                    <tr>
                    <th>" . htmlspecialchars($mon_profile["prenom"]) . "</th>
                    </tr>

                    <tr>
                    <th>" . htmlspecialchars($mon_profile["email"]) . "</th>
                    </tr>
                    <tr>
                    <th>" . htmlspecialchars($mon_profile["adresse"]) . "</th>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='4'>Aucun client trouvé.</td></tr>";
    }
} catch (Exception $e) {
    echo "<tr><td colspan='4'>Erreur : " . htmlspecialchars($e->getMessage()) . "</td></tr>";
}
?>
</table>
     <a href="../php/deconnexion"><img src="../img/exit-regular-240.png" alt="Deconnexion" width="20px" class="deco">Déconnexion</a>
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
    <h2>Mes Réservations</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Voiture</th>
            <th>Date Début</th>
            <th>Date Fin</th>
            <th>Statut</th>
        </tr>
        <?php if ($reservations): ?>
            <?php foreach ($reservations as $res): ?>
            <tr>
                <td><?= htmlspecialchars($res['id_reservation']) ?></td>
                <td><?= htmlspecialchars($res['marque'] . " " . $res['modele']) ?></td>
                <td><?= htmlspecialchars($res['date_debut']) ?></td>
                <td><?= htmlspecialchars($res['date_fin']) ?></td>
                <td><?= htmlspecialchars($res['statut_reservation'])?></td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5">Aucune réservation trouvée.</td></tr>
        <?php endif; ?>
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

    <a href="deconnexion.php">Déconnexion</a> | <a href="Concession.php">Concession</a>
</body>
</html>