<?php
require "connexion.php"; // Inclut le fichier de connexion à la base de données


$error = "";
$success = "";


if ($_SESSION['user_type'] == 'admin') { // Vérifie si l'utilisateur est un administrateur

    
    $sql = "SELECT id_reservation, 
                   (SELECT CONCAT(nom, ' ', prenom) FROM clients WHERE id_client = reservations.id_client) AS client, // Récupère le nom du client
                   (SELECT CONCAT(marque, ' ', modele) FROM voitures WHERE id_voiture = reservations.id_voiture) AS voiture // Récupère les détails de la voiture
            FROM reservations"; // De la table réservations

    $stmt = $pdo->query($sql);
} else {
    
    $sql = "SELECT id_reservation,  // Requête SQL pour récupérer les réservations d'un client

                   (SELECT CONCAT(nom, ' ', prenom) FROM clients WHERE id_client = reservations.id_client) AS client, // Récupère le nom du client
                   (SELECT CONCAT(marque, ' ', modele) FROM voitures WHERE id_voiture = reservations.id_voiture) AS voiture // Récupère les détails de la voiture
            FROM reservations
            WHERE id_client = :id_client"; // Filtre par ID du client

    $stmt = $pdo->prepare($sql);
    $stmt->execute(["id_client" => $_SESSION['user']['id_client']]);
}
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC); // Récupère toutes les réservations sous forme de tableau associatif


 // Traitement du formulaire de paiement // Vérifie si le formulaire a été soumis

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_reservation = $_POST["id_reservation"]; // Récupère l'ID de la réservation

    $montant = $_POST["montant"]; // Récupère le montant du paiement

    $mode = $_POST["mode"]; // Récupère le mode de paiement


    // Vérifier que la réservation existe et appartient à l'utilisateur // Valide la réservation

    $sql = "SELECT id_reservation FROM reservations WHERE id_reservation = :id_reservation"; // Requête pour vérifier l'existence de la réservation

    if ($_SESSION['user_type'] != 'admin') {
        $sql .= " AND id_client = :id_client"; // Filtre par ID du client

    }
    $stmt = $pdo->prepare($sql);
    $params = ["id_reservation" => $id_reservation]; // Paramètres pour la requête

    if ($_SESSION['user_type'] != 'admin') { // Si l'utilisateur n'est pas un administrateur

        $params["id_client"] = $_SESSION['user']['id_client']; // Ajoute l'ID du client aux paramètres

    }
    $stmt->execute($params); // Exécute la requête

    $reservation = $stmt->fetch(); // Récupère la réservation


    if ($reservation) { // Si la réservation est valide

        // Enregistrer le paiement // Insère les détails du paiement dans la base de données

        $sql = "INSERT INTO paiements (id_reservation, montant, date_paiement, mode_paiement) // Requête pour insérer le paiement

                VALUES (:id_reservation, :montant, NOW(), :mode)"; // Insère les valeurs dans la table paiements

        $stmt = $pdo->prepare($sql); // Prépare la requête d'insertion

        $stmt->execute([ // Exécute la requête d'insertion

            "id_reservation" => $id_reservation, // ID de la réservation

            "montant" => $montant, // Montant du paiement

            "mode" => $mode // Mode de paiement

        ]);
        $success = "Paiement enregistré avec succès !";
    } else {
        $error = "Réservation invalide ou non autorisée.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Effectuer un paiement</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Effectuer un paiement</h2>
    <?php if(isset($success)) echo "<p class='success'>$success</p>"; ?>
    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="POST">
        <label for="id_reservation">Réservation :</label>
        <select name="id_reservation" required>
            <?php foreach ($reservations as $res): ?>
                <option value="<?= $res['id_reservation'] ?>">
                    Réservation #<?= $res['id_reservation'] ?> - <?= $res['client'] ?> (<?= $res['voiture'] ?>)
                </option>
            <?php endforeach; ?>
        </select>
        <label for="montant">Montant :</label>
        <input type="number" name="montant" step="0.01" required>
        <label for="mode">Mode de paiement :</label>
        <select name="mode" required>
            <option value="carte">Carte</option>
            <option value="espèces">Espèces</option>
            <option value="chèque">Chèque</option>
        </select>
        <button type="submit">Payer</button>
    </form>
    <p><a href="<?php echo ($_SESSION['user_type'] == 'admin' ? 'admin_compte.php' : 'client_compte.php'); ?>">Retour au tableau de bord</a></p>
</body>
</html>
