<?php
require_once '../php/connexion.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Admin_compte</title>
  <link rel="stylesheet" href="../css/admin.css">
</head>

<body>
    <header>
    <a href="#" class="logo"><img src="../img/jeep.png" alt="Logo"></a>
        <span class="logo-text">Hallo Car</span>
        <div class="header-btn">
        <a href="#" class="Profil" id="profile-link"><img src="../img/user-regular-240.png" alt="" width="25px"></a>

        <div id="profile-modal" class="modal">
            <div class="modal-content">
                <span class="close" id="close-modal">&times;</span>
                <h1>Profil Administrateur</h1>
                <p>ID Admin : <?php echo htmlspecialchars($user['idadmin']); ?></p>
                <p>Nom d'utilisateur : <?php echo htmlspecialchars($user['nom_user']); ?></p>
                <a href="deconnexion.php">Déconnexion</a>
                <?php if ($is_superadmin): ?>
                    <a href="ajoutee_admin.php">Ajouter un Admin</a>
                <?php endif; ?>
            </div>
        </div>
  </header>
  <main class="dashboard-admin">
    <!-- Section des voitures -->
    <div class="voitures-container box">
      <div class="voiture-card-1">
        <img src="../img/car-solid-240.png"/>     
      </div>
      <div class="voiture-card-2">
        <img src="../img/car-solid-240.png"/>     
      </div>
      <div class="voiture-card-3">
        <img src="../img/car-solid-240.png"/>     
      </div>
      <div class="voiture-card-4">
        <img src="../img/car-solid-240.png"/>     
      </div>
      <div class="voiture-card-5">
        <img src="../img/car-solid-240.png"/>     
        <span class="voiture-text">VOITURE 5</span>
      </div>
    </div>
    <div class="ajouter-voiture">
     <a href="../php/ajouter_voiture.php"><img src="../img/plus-medical-regular-240.png"/></a>
    </div>
  </main>
    <!-- Sections listes -->
    <div class="listes-container">
      <!-- Liste clients -->
      <div class="liste-section-1">
        <h2 class="liste-titre-1">Liste Clients</h2>
        <div class="liste-content-1">
          <!-- Contenu de la liste des clients -->
           <table border="1">
            <tr>
              <th>ID</th>
              <th>Nom</th>
              <th>Prénom</th>
              <th>Email</th>
              <th>Adresse</th>
              <th>Date de naissance</th>
              <th>Numéro de permis</th>
            </tr>
            <?php
require_once '../php/connexion.php';

try {
    // Vérifier que la connexion PDO est bien initialisée
    if (!isset($pdo)) {
        throw new Exception("Erreur : La connexion PDO n'a pas été établie.");
    }

    // Exécuter la requête SQL
    $sql = "SELECT * FROM clients";
    $stmt = $pdo->query($sql);

    if ($stmt->rowCount() > 0) {
        while ($client = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>
                    <td>" . htmlspecialchars($client["id_client"]) . "</td>
                    <td>" . htmlspecialchars($client["nom"]) . "</td>
                    <td>" . htmlspecialchars($client["prenom"]) . "</td>
                    <td>" . htmlspecialchars($client["email"]) . "</td>
                    <td>" . htmlspecialchars($client["adresse"]) . "</td>
                    <td>" . htmlspecialchars($client["date_naissance"]) . "</td>
                    <td>" . htmlspecialchars($client["numero_permis"]) . "</td>
                </tr>";
        }
    } else {
        echo "<tr><td colspan='7'>Aucun client trouvé.</td></tr>";
    }
} catch (Exception $e) {
    echo "<tr><td colspan='7'>Erreur : " . htmlspecialchars($e->getMessage()) . "</td></tr>";
}
?>
 </table>
        <a href="../php/gestion_client.php" class="voir-liste-btn-1">Voir Liste</a>
      </div>
      </div>
      <!-- Liste réservations -->
      <div class="liste-section-2">
        <h2 class="liste-titre-2">Liste réservations</h2>
        <div class="liste-content-2">
          <!-- Contenu de la liste des réservations -->
          <table border="1">
            <tr>
              <th>id_reser</th>
              <th>Id_C</th>
              <th>Id_V</th>
              <th>date_debut</th>
              <th>date_fin</th>
              <th>statut</th>
            </tr>
            <?php
require_once '../php/connexion.php';

try {
    // Vérifier que la connexion PDO est bien initialisée
    if (!isset($pdo)) {
        throw new Exception("Erreur : La connexion PDO n'a pas été établie.");
    }

    // Exécuter la requête SQL
    $sql = "SELECT * FROM reservations LIMIT 5";
    $stmt = $pdo->query($sql);

    if ($stmt->rowCount() > 0) {
        while ($reservations = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>
                    <td>" . htmlspecialchars($reservations["id_reservation"]) . "</td>
                    <td>" . htmlspecialchars($reservations["id_client"]) . "</td>
                    <td>" . htmlspecialchars($reservations["id_voiture"]) . "</td>
                    <td>" . htmlspecialchars($reservations["date_debut"]) . "</td>
                    <td>" . htmlspecialchars($reservations["date_fin"]) . "</td>
                    <td>" . htmlspecialchars($reservations["statut_reservation"]) . "</td>
                </tr>";
        }
    } else {
        echo "<tr><td colspan='7'>Aucun client trouvé.</td></tr>";
    }
} catch (Exception $e) {
    echo "<tr><td colspan='7'>Erreur : " . htmlspecialchars($e->getMessage()) . "</td></tr>";
}
?>
         <a href="../php/gestion_reservation.php" class="voir-liste-btn-2">Voir Liste</a>
      </div>
    </div>

  
</body>
</html>