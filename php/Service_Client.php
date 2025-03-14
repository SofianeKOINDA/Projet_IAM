<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Se Connecter</title>
    <link rel="stylesheet" href="../css/Client.css">
</head>
<body>
    <header>
        <a href="#" class="logo"><img src="../img/jeep.png" alt="Logo"></a>
        <span class="logo-text">Hallo Car</span>
        <ul class="navbar">
            <li><a href="../php/client_compte.php">Accueil</a></li>
            <li><a href="../php/Concession.php">Concession</a></li>
            <li><a href="../php/Service_Client.php">Service Client</a></li>
        </ul>
        <div class="header-btn">
            <a href="../php/profilClient.php" class="Profil"><img src="../img/user-regular-240.png" alt="" width="25px"></a>
        </div>
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
    <section class="Accueil" id="Accueil">
        <div class="Accueil-image">
            <img src="../img/about.png" alt="VOITURE JEEP">
       </div>
        <div class="Accueil-container">
            <h1>Service <span>Client</span></h1>
            <div class="formulaire-container"> 
                <p>Vous avez une question ou une suggestion ?</p>
                <form>
                    <input type="text" id="nom" name="nom" placeholder="Nom">
                    <input type="text" id="prenom" name="prenom" placeholder="Prénom">
                    <input type="email" id="email" name="email" placeholder="Email">
                    <input type="text" id="commentaire" name="commentaire" placeholder="Commentaire">
                    <button type="submit">Soumettre</button>
                </form>
            </div>
        </div>
    </section>
</body>
</html>