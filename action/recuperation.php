<?php
 
 include ("connexion.php");

 if (!$connexion){
    die("Erreur de connexion à la base de données : " );
 }
 else{
    $count = $connexion -> prepare("SELECT COUNT (id) AS nbEnregistrement FROM users");
    $count -> setFetchMode(PDO::FETCH_ASSOC);
    $count -> execute();
    $result = $count -> fetchAll();
 }