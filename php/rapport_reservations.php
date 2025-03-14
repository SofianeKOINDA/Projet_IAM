<?php
session_start(); // Démarre la session
if (!isset($_SESSION['user'])) { // Vérifie si l'utilisateur est connecté
    header("Location: connexion_page.php "); // Redirige vers la page de connexion si non connecté
    exit();
}

require "connexion.php"; // Inclut le fichier de connexion à la base de données


if ($_SESSION['user_type'] == 'admin') { // Vérifie si l'utilisateur est un administrateur

    
    $sql = "SELECT id_reservation, 
                   (SELECT CONCAT(nom, ' ', prenom) FROM clients WHERE id_client = r.id_client) AS client, // Récupère le nom du client
                   (SELECT CONCAT(marque, ' ', modele) FROM voitures WHERE id_voiture = r.id_voiture) AS voiture, // Récupère les détails de la voiture
                   date_debut, date_fin, statut
            FROM reservations r"; // De la table réservations

    $stmt = $pdo->query($sql);
} else { // Si l'utilisateur n'est pas un administrateur

    
    $sql = "SELECT id_reservation,  // Requête SQL pour récupérer les réservations d'un client

                   (SELECT CONCAT(nom, ' ', prenom) FROM clients WHERE id_client = r.id_client) AS client, // Récupère le nom du client
                   (SELECT CONCAT(marque, ' ', modele) FROM voitures WHERE id_voiture = r.id_voiture) AS voiture, // Récupère les détails de la voiture
                   date_debut, date_fin, statut
            FROM reservations r
            WHERE id_client = :id_client"; // Filtre par ID du client

    $stmt = $pdo->prepare($sql);
    $stmt->execute(["id_client" => $_SESSION['user']['id_client']]);
}
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC); // Récupère toutes les réservations sous forme de tableau associatif

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des réservations</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <section class="reservations-container">
    <h2>Liste des réservations</h2> <!-- Titre de la section des réservations -->

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Client</th>
            <th>Voiture</th>
            <th>Début</th>
            <th>Fin</th>
            <th>Statut</th>
        </tr>
        <?php foreach ($reservations as $res): ?> <!-- Boucle pour afficher chaque réservation -->

            <tr>
                <td><?= $res['id_reservation'] ?></td> <!-- ID de la réservation -->

                <td><?= $res['client'] ?></td> <!-- Nom du client -->

                <td><?= $res['voiture'] ?></td> <!-- Détails de la voiture -->

                <td><?= $res['date_debut'] ?></td> <!-- Date de début de la réservation -->

                <td><?= $res['date_fin'] ?></td> <!-- Date de fin de la réservation -->

                <td><?= $res['statut'] ?></td> <!-- Statut de la réservation -->

            </tr>
        <?php endforeach; ?>
    </table>
    </section>
    <p><a href="<?php echo ($_SESSION['user_type'] == 'admin' ? 'admin_compte.php' : 'client_compte.php'); ?>">Retour au tableau de bord</a></p> <!-- Lien pour retourner au tableau de bord -->

</body>
</html>
