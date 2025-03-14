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
     <a href="../php/deconnexion"><img src="../img/exit-regular-240.png" alt="Deconnexion" width="20px" >Déconnexion</a>
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
    
      <div class="liste-section-2">
        <h2 class="liste-titre-2">Liste des clients </h2>
          <div class="liste-content-2">
            <div class="form-box">
             
              <table border="1" style="width: 100%;">
                <tr>
                  <th>Id_Client</th>
                  <th>nom</th>
                  <th>prenom</th>
                  <th>E-mail</th>
                  <th>numero_permis</th>
                  <th>date_naissance</th>
                  <th>Gestion</th>
                </tr>
                
                <?php

try {
    
    if (!isset($pdo)) {
        throw new Exception("Erreur : La connexion PDO n'a pas été établie.");
    }

    // Exécuter la requête SQL
    $sql = "SELECT id_client, nom, prenom, email, numero_permis, date_naissance FROM clients";
    $marequete = $pdo->query($sql);

    if ($marequete->rowCount() > 0) {
        while ($resultat = $marequete->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>
                    <td>" . htmlspecialchars($resultat["id_client"]) . "</td>
                    <td>" . htmlspecialchars($resultat["nom"]) . "</td>
                    <td>" . htmlspecialchars($resultat["prenom"]) . "</td>
                    <td>" . htmlspecialchars($resultat["email"]) . "</td>
                    <td>" . htmlspecialchars($resultat["numero_permis"]) . "</td>
                    <td>" . htmlspecialchars($resultat["date_naissance"]) . "</td>
                    <td>
                      <div class='form-box'>
                        <form method='post' action='decline_reservation.php'>
                          <input type='hidden' name='id_client' value='" . htmlspecialchars($resultat["id_client"]) . "'>
                          <input type='image' src='../img/x-regular-240.png' alt='Refuser' width='25'>
                        </form>
                      </div>
                    </td>
                </tr>";
        }
    } else {
        echo "<tr><td colspan='7'>Aucun client trouvé.</td></tr>";
    }
} catch (Exception $e) {
    echo "<tr><td colspan='7'>Erreur : " . htmlspecialchars($e->getMessage()) . "</td></tr>";
}
?>
</body>
</html>