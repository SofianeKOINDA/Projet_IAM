<?php
session_start();
require_once '../action/connexion.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit;
}

$message = '';
$success = false;
$paiementDejaEffectue = false;

// Vérifier si id_reservation est présent et valide
if (!isset($_GET['id_reservation']) || !filter_var($_GET['id_reservation'], FILTER_VALIDATE_INT)) {
    $message = "ID de réservation invalide.";
} else {
    $id_reservation = $_GET['id_reservation'];

    // Vérifier si la réservation a déjà été payée
    $sqlCheck = "SELECT montant FROM paiements WHERE id_reservation = :id_reservation";
    $stmt = $pdo->prepare($sqlCheck);
    $stmt->execute(['id_reservation' => $id_reservation]);
    $paiement = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($paiement) {
        $paiementDejaEffectue = true;
        $message = "Le paiement a déjà été effectué pour cette réservation.";
    } else {
        // Récupérer le montant de la réservation
        $sql = "SELECT montant FROM reservations WHERE id_reservation = :id_reservation";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id_reservation' => $id_reservation]);
        $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($reservation) {
            $montant = $reservation['montant'];
        } else {
            $message = "Réservation introuvable.";
        }
    }
}

// Traitement du paiement
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['mode_paiement']) && !$paiementDejaEffectue) {
    $mode_paiement = $_POST['mode_paiement'];
    $id_client = $_SESSION['user']['id_client'];

    try {
        $pdo->beginTransaction();

        // Insérer le paiement
        $sqlPaiement = "INSERT INTO paiements (id_reservation, montant, mode_paiement, date_paiement) 
                        VALUES (:id_reservation, :montant, :mode_paiement, NOW())";
        $stmt = $pdo->prepare($sqlPaiement);
        $stmt->execute([
            'id_reservation' => $id_reservation,
            'montant' => $montant,
            'mode_paiement' => $mode_paiement
        ]);

        // Mettre à jour le statut de la réservation en "confirmée"
        $sqlUpdate = "UPDATE reservations SET statut_reservation = 'confirmée' WHERE id_reservation = :id_reservation";
        $stmt = $pdo->prepare($sqlUpdate);
        $stmt->execute(['id_reservation' => $id_reservation]);

        $pdo->commit();
        $success = true;
        $message = "Paiement effectué avec succès !";
        $paiementDejaEffectue = true;
    } catch (Exception $e) {
        $pdo->rollBack();
        $message = "Erreur lors du paiement : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement</title>
    <link rel="stylesheet" href="../css/paiement.css">
</head>
<body>

<section class="paiement-container">
    <h1>Effectuer le Paiement</h1>
    
    <?php if ($message): ?>
        <p class="<?= $success ? 'success' : 'error' ?>"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <?php if (isset($montant) && !$paiementDejaEffectue): ?>
        <form method="POST">
            <h2> Montant à payer : <strong> <?= number_format($montant) ?> Fcfa</strong></h2>

            <label for="mode_paiement">Mode de paiement :</label>
            <select name="mode_paiement" id="mode_paiement" required>
                <option value="carte">Carte bancaire</option>
                <option value="espèces">Espèces</option>
                <option value="chèque">Chèque</option>
            </select>

            <button type="submit">Payer maintenant</button>
        </form>
    <?php endif; ?>
    
    <p><a href="client_compte.php">Retour au tableau de bord</a></p>
</section>

</body>
</html>