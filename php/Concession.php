<?php
session_start();

require "connexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_client = $_SESSION['user']['id_client'];
    $id_voiture = $_POST["id_voiture"];
    $date_debut = $_POST["date_debut"];
    $date_fin = $_POST["date_fin"];

    $sql = "SELECT COUNT(*) FROM reservations 
            WHERE id_voiture = :id_voiture 
            AND statut_reservation = 'confirmée' 
            AND (date_debut <= :date_fin AND date_fin >= :date_debut)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        "id_voiture" => $id_voiture,
        "date_debut" => $date_debut,
        "date_fin" => $date_fin
    ]);
    $disponible = $stmt->fetchColumn() == 0;

    if ($disponible) {
        $sql = "INSERT INTO reservations (id_client, id_voiture, date_debut, date_fin, statut_reservation) 
                VALUES (:id_client, :id_voiture, :date_debut, :date_fin, 'en attente')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            "id_client" => $id_client,
            "id_voiture" => $id_voiture,
            "date_debut" => $date_debut,
            "date_fin" => $date_fin
        ]);
        $success = "Réservation enregistrée avec succès !";
    } else {
        $error = "Cette voiture n'est pas disponible pour cette période.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location de voitures</title>
    <link rel="stylesheet" href="../css/stylesheet.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <style>
        :root {
            --primary: #fe5b3d;
            --secondary: #FFAC38;
            --third-color: #474fa0;
            --text: #333;
            --background: #f5f5f5;
            --border: #ddd;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .modal-content {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .modal-title {
            font-size: 1.5rem;
            color: var(--text);
        }

        .close-modal {
            cursor: pointer;
            font-size: 1.5rem;
            color: #666;
            transition: color 0.2s;
        }

        .close-modal:hover {
            color: var(--primary);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text);
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border);
            border-radius: 5px;
            font-size: 1rem;
        }

        .btn {
            background: var(--third-color);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            width: 100%;
            transition: background 0.2s;
        }

        .btn:hover {
            background: var(--primary);
        }

        .success {
            color: #28a745;
            margin-bottom: 1rem;
        }

        .error {
            color: #dc3545;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <header>
        <a href="#" class="logo"><img src="../img/jeep.png" alt=""></a>
        <div class="bx bx-menu" id="menu-icon"></div>
        <ul class="navbar">
            <li><a href="../php/client_compte.php">Accueil</a></li>
            <li><a href="../php/Concession.php">Concession</a></li>
            <li><a href="../php/service_client.php">Service client</a></li>
        </ul>
        <div class="header-btn">
            <a href="../php/profilClient" class="profil"><img src="../img/user-regular-240.png" alt="" width="25px"></a>
        </div>
        
    </header>

    <section class="service" id="services">
        <div class="heading">
            <span>Les meilleurs services</span>
            <h1>Explorez nos meilleures offres avec des voitures de luxe hors du commun</h1>
        </div>

        <div class="services-container">
            <?php
            $sql = "SELECT id_voiture, marque, modele, image, prix_jour FROM voitures";
            $stmt = $pdo->query($sql);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '
                <div class="box">
                    <div class="box-img">
                        <img src="../img/'.$row['image'].'" alt="'.$row['marque'].' '.$row['modele'].'">
                    </div>
                    <h3>'.$row['marque'].' '.$row['modele'].'</h3>
                    <p>Disponible</p>
                    <h2>' .(isset($row['prix_jour']) ? $row['prix_jour'] : 'N/A').' <span>/jour</span></h2>
                    <button class="btn" onclick="openModal('.$row['id_voiture'].')">louer maintenant</button>
                </div>';
            }
            ?>
        </div>

        <!-- Modale de réservation -->
        <div id="reservationModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Réserver une voiture</h2>
                    <span class="close-modal" onclick="closeModal()">&times;</span>
                </div>
                <?php if(isset($success)) echo "<p class='success'>$success</p>"; ?>
                <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
                <form method="POST">
                    <input type="hidden" name="id_voiture" id="selectedCar">
                    <div class="form-group">
                        <label for="date_debut">Date de début :</label>
                        <input type="date" name="date_debut" id="date_debut" required>
                    </div>
                    <div class="form-group">
                        <label for="date_fin">Date de fin :</label>
                        <input type="date" name="date_fin" id="date_fin" required>
                    </div>
                    <button type="submit" class="btn">Réserver</button>
                </form>
            </div>
        </div>
    </section>

    <script>
        // Fonctions pour gérer la modale
        function openModal(carId) {
            document.getElementById('selectedCar').value = carId;
            document.getElementById('reservationModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('reservationModal').style.display = 'none';
        }

        // Fermer la modale si on clique en dehors
        window.onclick = function(event) {
            var modal = document.getElementById('reservationModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>
