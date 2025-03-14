

-- Exemple d'insertion de données avec images
INSERT INTO voitures (marque, modele, prix_jour, image) VALUES
('BMV', 'RS8', 50000, 'C:\xampp2\htdocs\Php2\img\Audi RS8.png'),
('Audi', 'A4', 30000, 'C:\xampp2\htdocs\Php2\img\Audi RS8.png'),
('Mercedes', 'C200', 40000, 'C:\xampp2\htdocs\Php2\img\Audi RS8.png');

-- Mettre à jour les enregistrements existants
UPDATE voitures SET image = 'C:\xampp2\htdocs\Php2\img\Audi RS8.png' WHERE id_voiture = 1;
UPDATE voitures SET image = 'C:\xampp2\htdocs\Php2\img\Audi RS8.png' WHERE id_voiture = 2;
UPDATE voitures SET image = 'C:\xampp2\htdocs\Php2\img\Audi RS8.png' WHERE id_voiture = 3;
