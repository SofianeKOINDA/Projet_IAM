-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql-kia.alwaysdata.net
-- Generation Time: Mar 21, 2025 at 12:15 PM
-- Server version: 10.11.9-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kia_gestion_comptes`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrateurs`
--

CREATE TABLE `administrateurs` (
  `id_admin` int(11) NOT NULL,
  `nom_utilisateur` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `administrateurs`
--

INSERT INTO `administrateurs` (`id_admin`, `nom_utilisateur`, `email`, `mot_de_passe`, `role`) VALUES
(4, 'Sofiane', 'admin@sofiane.com', '$2y$10$j9jEVB789kwUfJIgwFa5keLJ1xBErBn904Uwl2lA0pKy2P8BJubey', 'super_admin'),
(5, 'soulé', 'admin@soule.com', '$2y$10$QHgT.8FbuQkEjmnpdgd3X.VAHjhRJ4SCZxcpvT9kX0PJzkDYPnptW', 'admin'),
(6, 'aissata', 'admin@aissata.com', '$2y$10$EvsjtQLQglXgaLxUMJjGDeWRyhh09GNn0sQXIuIRcU8fmbdblBqOG', 'admin'),
(7, 'KIA', 'kia@gmail.com', '$2y$10$66Y2PA4rkbVdY8r0mn68ouA7n/VqtAKP8/CJfoABOMWQPJjKKghsK', 'admin'),
(8, 'boua', 'boua@gmail.com', '$2y$10$/o5ivSY/06j1oC12mHvoNufO6hityZPCq3xLotM2e06dRyte2Dh5m', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id_client` int(11) NOT NULL,
  `nom` varchar(25) NOT NULL,
  `prenom` varchar(25) NOT NULL,
  `email` varchar(50) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `date_naissance` date DEFAULT NULL,
  `adresse` varchar(100) NOT NULL,
  `numero_permis` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id_client`, `nom`, `prenom`, `email`, `mot_de_passe`, `date_naissance`, `adresse`, `numero_permis`) VALUES
(41, 'SANGARE', 'N\'Barka dite Bakia', 'sangarembarka26@gmail.com', '$2y$12$7zDgCpUY/FTFlctkzJmuF.mDbriGKO8wlEXaIdII2SmKJDVAL5Xze', '2005-02-12', 'Mermoz RUE MZ-45', 'AA2345'),
(62, 'MARIKO', 'Mariam', 'mariko@gmail.com', '$2y$10$PkmBXejYDvrRkjmZ9OuDcOoRXYT93bso60Kcsc14SRehkZwj8njxi', '2000-09-23', '23 ALLEE DE LA SEBYLLE CERGY', 'AA2345'),
(63, 'oumou', 'kone', 'koneoumo@gmail.com', '$2y$10$63j93iW.fD6vVmRSbWjMSeGvp0t0xgeHjzO5tiOPIxomKlWqTtGq2', '2007-12-02', '23 ALLEE DE LA SEBYLLE CERGY', '45566gè'),
(64, 'djire', 'chaka', 'djire@gmail.com', '$2y$10$pfNLZM2ZqVrNx9JigylsZuPs9VJigwYUM6eTzYKPERjeGv0OgJ6uW', '2005-12-12', '234', '45566gè'),
(65, 'Afsatou', 'sang', 'modibo@gmail.com', '$2y$10$bSAz6lzk4lx7PWSPkIU/PODlWRRf.VyJTcZiJbZKHZH5IhoOSrUqu', '2000-02-12', '234', '45566gè'),
(66, 'MARIKO', 'Mariam', 'modibo@gmail.com', '$2y$10$EzRgERySlyHnXLdSn0FDv.RVveeuwOTwMYk8L3PKcveycDoTh0Ocy', '2000-05-12', '23 ALLEE DE LA SEBYLLE CERGY', '23R8345'),
(67, 'Diallo', 'sekou', 'sekou@gmail.com', '$2y$10$JbM6/QPmHwS19pCGS9/UJO.1YJRYHCTot6gODRq/F6Gh.9oDRnEt.', '2025-10-09', 'Mermoz RUE MZ-45', '23R8345'),
(68, 'fatoumata', 'keita', 'keita@gmail.com', '$2y$10$Z1kuZdxu2r6n1qWdZ9kXd.qrwVWfClduaLkXpebVmnQcTJVbTbxZe', '2005-05-02', 'Rue mz34', '12AZ'),
(69, 'diallo', 'djeneba', 'diallo@gmail.com', '$2y$10$F0XSQpGbl0WNAziDFG/Sb.kdgHo3OJUXFSsY8cQO49Z/.wQEc25sy', '2004-10-09', 'LIBERTE', 'SN34'),
(70, 'Saré', 'Zeïnab', 'zeinabsare892@gmail.com', '$2y$10$jrrWyBioRDmJRAxP2qA5a.cbv1kbz//YWr/.5yPoOiJd4f3pdSZku', '2003-10-10', 'Medina', 'Evildoers ');

-- --------------------------------------------------------

--
-- Table structure for table `commentaires`
--

CREATE TABLE `commentaires` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `commentaire` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `commentaires`
--

INSERT INTO `commentaires` (`id`, `nom`, `prenom`, `email`, `commentaire`, `created_at`) VALUES
(1, 'sofiane', 'koinda', 'client@sofiane.com', 'très beau site, j\'aime bien', '2025-03-19 15:11:05'),
(2, 'SANGARE', 'N\'Barka dite Bakia', 'sangarembarka26@gmail.com', '', '2025-03-19 20:27:03');

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `voiture_id` int(11) DEFAULT NULL,
  `chemin_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `voiture_id`, `chemin_image`) VALUES
(7, 26, 'tlcharger_20250317_171612.jpg'),
(8, 27, '300300No_7_2025MOTA_20250317_171847.jpg'),
(9, 28, 'LandCruiserXtremeEdition_SaharaMotorsDubai_20250317_172409.jpg'),
(10, 29, '2025AudiQ8Review_LuxurySUVwithPowerTechnology_20250317_173231.jpg'),
(11, 30, 'FreePremiumPNGMercedesBenzGLECoupeWhiteCar_20250317_173418.jpg'),
(12, 31, 'MercedesBenzGlkClass-MercedesBenzXClassAmgHDPngDownloadTransparentPngImage-PNGitem_20250317_173623.jpg'),
(13, 32, 'YIKONGYK4102PRO4WDRCCar1_10Off-roadRockCrawlerwithHigh_lowDifferentialLockOriginalLEDLights-Green_YES_20250317_174049.jpg'),
(14, 33, 'JeepWrangler_20250317_174317.jpg'),
(15, 34, 'RangeRoverCarClipartPNGImagesCarClipartRangeRoverPNGTransparentBackground-Pngtree_20250317_174451.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `paiements`
--

CREATE TABLE `paiements` (
  `id_paiement` int(11) NOT NULL,
  `id_reservation` int(11) NOT NULL,
  `Montant` decimal(10,0) NOT NULL,
  `date_paiement` date NOT NULL,
  `mode_paiement` enum('carte','espèces','chèque') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `paiements`
--

INSERT INTO `paiements` (`id_paiement`, `id_reservation`, `Montant`, `date_paiement`, `mode_paiement`) VALUES
(11, 27, 120000, '2025-03-20', 'carte'),
(12, 27, 120000, '2025-03-20', 'carte'),
(13, 27, 120000, '2025-03-20', 'espèces'),
(14, 27, 120000, '2025-03-20', 'espèces'),
(15, 28, 180000, '2025-03-20', 'espèces'),
(16, 28, 180000, '2025-03-20', 'chèque'),
(17, 28, 180000, '2025-03-20', 'carte'),
(18, 28, 180000, '2025-03-20', 'espèces'),
(19, 29, 60000, '2025-03-20', 'espèces'),
(20, 30, 210000, '2025-03-20', 'carte'),
(21, 30, 210000, '2025-03-20', 'espèces'),
(22, 31, 100000, '2025-03-20', 'espèces'),
(23, 33, 240000, '2025-03-20', 'espèces'),
(24, 34, 140000, '2025-03-20', 'espèces'),
(25, 35, 120000, '2025-03-20', 'espèces'),
(26, 36, 120000, '2025-03-20', 'carte');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id_reservation` int(11) NOT NULL,
  `id_client` int(11) UNSIGNED NOT NULL,
  `id_voiture` int(11) UNSIGNED NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `montant` int(10) DEFAULT NULL,
  `statut_reservation` enum('en attente','confirmée','annulée') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id_reservation`, `id_client`, `id_voiture`, `date_debut`, `date_fin`, `montant`, `statut_reservation`) VALUES
(27, 65, 28, '2025-07-14', '2025-07-16', 120000, 'confirmée'),
(28, 41, 26, '2025-05-12', '2025-05-15', 180000, ''),
(29, 41, 29, '2025-11-01', '2025-11-02', 60000, ''),
(30, 41, 30, '2025-10-09', '2025-10-12', 210000, 'confirmée'),
(31, 41, 32, '2025-12-12', '2025-12-14', 100000, 'confirmée'),
(32, 67, 0, '2025-05-12', '2025-05-14', 0, 'en attente'),
(33, 67, 26, '2025-12-12', '2025-12-16', 240000, 'confirmée'),
(34, 68, 33, '2025-09-09', '2025-09-11', 140000, 'confirmée'),
(35, 69, 28, '2025-06-06', '2025-06-08', 120000, 'confirmée'),
(36, 70, 29, '2025-03-21', '2025-03-23', 120000, 'confirmée');

-- --------------------------------------------------------

--
-- Table structure for table `voitures`
--

CREATE TABLE `voitures` (
  `id_voiture` int(11) NOT NULL,
  `marque` varchar(50) NOT NULL,
  `modele` varchar(50) NOT NULL,
  `annee_fabrication` int(11) DEFAULT NULL,
  `plaque_immatriculation` varchar(25) NOT NULL,
  `statut` enum('disponible','louée','en maintenance','') NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `prix_jour` int(11) NOT NULL,
  `reserve` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `voitures`
--

INSERT INTO `voitures` (`id_voiture`, `marque`, `modele`, `annee_fabrication`, `plaque_immatriculation`, `statut`, `image`, `prix_jour`, `reserve`) VALUES
(26, 'Mercedes', 'AMG', 2019, 'DK-223-ML', 'disponible', NULL, 60000, 0),
(27, 'Toyota', 'LAND-CRUSER', 2015, 'DK-221-JK', 'disponible', NULL, 80000, 0),
(28, 'TOYOTA', 'MOBELLISTR', 2021, 'DK-333-TY', 'disponible', NULL, 60000, 0),
(29, 'AUDI', 'Q8', 2019, 'DK-223-FR', 'disponible', NULL, 60000, 0),
(30, 'MERCEDES', 'GLE', 2021, 'DK-221-RF', 'disponible', NULL, 70000, 0),
(31, 'MERCEDES', 'BENZ X', 2021, 'DK-345-RT', 'disponible', NULL, 80000, 0),
(32, 'YIKONG', 'YK4102', 2020, 'DK-544-JT', 'disponible', NULL, 50000, 0),
(33, 'JEEP', 'Wrangler', 2020, 'DK-445-SD', 'disponible', NULL, 70000, 0),
(34, 'RANGE ROVER', 'SV', 2019, 'DK-567-RT', 'disponible', NULL, 80000, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administrateurs`
--
ALTER TABLE `administrateurs`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id_client`);

--
-- Indexes for table `commentaires`
--
ALTER TABLE `commentaires`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `voiture_id` (`voiture_id`);

--
-- Indexes for table `paiements`
--
ALTER TABLE `paiements`
  ADD PRIMARY KEY (`id_paiement`),
  ADD KEY `id_reservation` (`id_reservation`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id_reservation`),
  ADD KEY `id_client` (`id_client`,`id_voiture`);

--
-- Indexes for table `voitures`
--
ALTER TABLE `voitures`
  ADD PRIMARY KEY (`id_voiture`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `administrateurs`
--
ALTER TABLE `administrateurs`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id_client` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `commentaires`
--
ALTER TABLE `commentaires`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `paiements`
--
ALTER TABLE `paiements`
  MODIFY `id_paiement` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id_reservation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `voitures`
--
ALTER TABLE `voitures`
  MODIFY `id_voiture` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `images_ibfk_1` FOREIGN KEY (`voiture_id`) REFERENCES `voitures` (`id_voiture`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
