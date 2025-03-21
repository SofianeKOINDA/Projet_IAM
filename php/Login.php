<?php
session_start();
require_once '../action/connexion.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe']; 

    // Vérifie si c'est un admin
    $sql = "SELECT * FROM administrateurs WHERE email = ?";
    $query = $pdo->prepare($sql); 
    $query->execute([$email]);
    $administrateur = $query->fetch(PDO::FETCH_ASSOC);
    
    if ($administrateur && password_verify($mot_de_passe, $administrateur['mot_de_passe'])) {
        $_SESSION['user'] = $administrateur;
        $_SESSION['user_type'] = 'admin';
        header("Location: admin_compte.php");
        exit();
    } else {
        // Vérifie si c'est un client 
        $sql = "SELECT * FROM clients WHERE email = ?";
        $query = $pdo->prepare($sql); 
        $query->execute([$email]);
        $client = $query->fetch(PDO::FETCH_ASSOC);
        
        if ($client && password_verify($mot_de_passe, $client['mot_de_passe'])) {
            $_SESSION['user'] = $client;
            $_SESSION['user_type'] = 'client';
            header("Location: ../php/Concession.php");
            exit();
        } else {
            $error = "Email ou mot de passe incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Se Connecter</title>
    <link rel="stylesheet" href="../css/logsign.css">
    <style>
        .erreur {
            color: red;
            font-size: 14px;
            margin-bottom: 18px;
        }
    </style>
</head>

<body>
    <header>
        <a href="#" class="logo"><img src="../img/jeep.png" alt="Logo"></a>
        <span class="logo-text">Hallo Car</span>
    </header>

    <section class="Accueil" id="Accueil">
        <div class="text">
            <h1><span>Avec Hallo Car</span>, louer autrement</h1>

            <div class="formulaire-connexion">
                <form method="POST">
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" placeholder="exemple@gmail.com">
                
                    <label for="mot-de-passe">Mot de passe:</label>
                    <input type="password" id="mot-de-passe" name="mot_de_passe" placeholder="MoHjbjer@133">
                    
                    <p class="erreur"> 
                        <?php if (isset($error)) { echo $error; } ?>
                    </p>
                    <button type="submit">Se connecter</button>
                </form>
                <div class="lien-inscription">
                    Vous n'avez pas de compte ? <a href="../php/Signin.php">S'inscrire</a>
                </div>
            </div>
        </div>
    </section>
</body>
</html>