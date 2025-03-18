<?php
session_start();
require_once '../action/connexion.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reservation</title>
    <link rel="stylesheet" type="text/css" href="../css/gestion.css">
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
      <!-- Liste réservations -->
      <div class="liste-section-2">
        <h2 class="liste-titre-2">Liste réservations</h2>
          <div class="liste-content-2">
            <div class="form-box">
              <!-- Contenu de la liste des réservations -->
              <table border="1" style="width: 100%;">
                <tr style="background: #fe5b3d; color: white;">
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
                $result = $pdo->query($sql);

                if ($result->rowCount() > 0) {
                    while ($reservation = $result->fetch(PDO::FETCH_ASSOC)) {
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