<?php
require_once("connexion.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reservation</title>
    <link rel="stylesheet" type="text/css" href="../css/gestion.css">
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
    $sql = "SELECT id_admin, nom_utilisateur, email, super_admin FROM administrateurs";
    $resultat = $pdo->query($sql);

    if ($resultat->rowCount() > 0) {
        while ($reservations = $resultat->fetch(PDO::FETCH_ASSOC)) {
            echo "  <tr>
                    <th>" . htmlspecialchars($reservations["id_admin"]) . "</th>
                    </tr>

                    <tr>
                    <th>" . htmlspecialchars($reservations["nom_utilisateur"]) . "</th>
                    </tr>

                    <tr>
                    <th>" . htmlspecialchars($reservations["email"]) . "</th>
                    </tr>

                    <tr>
                    <th>" . htmlspecialchars($reservations["super_admin"]) . "</th>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='4'>Aucun admin trouvé.</td></tr>";
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
      <!-- Liste réservations -->
      <div class="liste-section-2">
        <h2 class="liste-titre-2">Liste réservations</h2>
          <div class="liste-content-2">
            <div class="form-box">
              <!-- Contenu de la liste des réservations -->
              <table border="1" style="width: 100%;">
                <tr>
                  <th>id_reservation</th>
                  <th>Id_Client</th>
                  <th>Id_Voiture</th>
                  <th>date_debut</th>
                  <th>date_fin</th>
                  <th>statut_reservation</th>
                  <th>Validation</th>
                </tr>
                
                <?php
            try {
                $sql = "SELECT * FROM reservations";
                $stmt = $pdo->query($sql);

                if ($stmt->rowCount() > 0) {
                    while ($reservation = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>
                                <td>" . htmlspecialchars($reservation["id_reservation"]) . "</td>
                                <td>" . htmlspecialchars($reservation["id_client"]) . "</td>
                                <td>" . htmlspecialchars($reservation["id_voiture"]) . "</td>
                                <td>" . htmlspecialchars($reservation["date_debut"]) . "</td>
                                <td>" . htmlspecialchars($reservation["date_fin"]) . "</td>
                                <td>" . htmlspecialchars($reservation["statut_reservation"]) . "</td>
                                <td>";

                        // Si la réservation n'est pas encore acceptée, afficher les boutons
                        if ($reservation["statut_reservation"] !== "confirmee") {
                            echo "<div class='form-box'>
                                    <form method='post' action='accept_reservation.php'>
                                        <input type='hidden' name='id_reservation' value='" . htmlspecialchars($reservation["id_reservation"]) . "'>
                                        <input type='image' src='../img/check-regular-240.png' alt='Accepter' width='30'>
                                    </form>
                                    <form method='post' action='decline_reservation.php'>
                                        <input type='hidden' name='id_reservation' value='" . htmlspecialchars($reservation["id_reservation"]) . "'>
                                        <input type='image' src='../img/x-regular-240.png' alt='Refuser' width='30'>
                                    </form>
                                  </div>";
                        } else {
                            echo "<p>Réservation acceptée</p>";
                        }

                        echo "</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>Aucune réservation trouvée.</td></tr>";
                }
            } catch (Exception $e) {
                echo "<tr><td colspan='7'>Erreur : " . htmlspecialchars($e->getMessage()) . "</td></tr>";
            }
            ?>
</body>
</html>