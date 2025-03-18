<?php
require_once '../action/connexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_reservation'])) {
    $id_reservation = $_POST['id_reservation'];

    $update_sql = "UPDATE reservations SET statut_reservation = 'confirmee' WHERE id_reservation = :id_reservation";
    $update_stmt = $pdo->prepare($update_sql);
    $update_stmt->bindParam(':id_reservation', $id_reservation, PDO::PARAM_INT);
    $update_stmt->execute();

    header("Location: gestion_reservation.php");
    exit();
}
?>