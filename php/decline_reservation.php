<?php
require_once 'connexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_reservation = $_POST['id_reservation'];

    try {
        
        $sql = "UPDATE reservations SET statut_reservation = 'annulé' WHERE id_reservation = :id_reservation";


        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_reservation', $id_reservation);
        $stmt->execute();

        
        header("Location: gestion_reservation.php");
        exit();
    } catch (Exception $e) {
        echo "Erreur : " . htmlspecialchars($e->getMessage());
    }
}
?>
