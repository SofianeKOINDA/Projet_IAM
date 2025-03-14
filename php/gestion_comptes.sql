-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 15 fév. 2025 à 00:45
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestion_comptes`
--

-- --------------------------------------------------------

--
-- Structure de la table `administrateurs`
--

CREATE TABLE `administrateurs` (
  `id_admin` int(11) NOT NULL,
  `nom_utilisateur` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `mot_de_passe` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `administrateurs`
--

INSERT INTO `administrateurs` (`id_admin`, `nom_utilisateur`, `email`, `mot_de_passe`) VALUES
(1, 'admin', 'teninbadje@gmail.com', 'LALLA123');

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE `clients` (
  `id_client` int(11) NOT NULL,
  `nom` varchar(25) NOT NULL,
  `prenom` varchar(25) NOT NULL,
  `email` varchar(50) NOT NULL,
  `mot_de_passe` varchar(50) NOT NULL,
  `date_naissance` date DEFAULT NULL,
  `adresse` varchar(100) NOT NULL,
  `numero_permis` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `paiements`
--

CREATE TABLE `paiements` (
  `id_paiement` int(11) NOT NULL,
  `id_reservation` int(11) NOT NULL,
  `Montant` decimal(10,0) NOT NULL,
  `date_paiement` date NOT NULL,
  `mode_paiement` enum('carte','espèces','chèque') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `paiements`
--

INSERT INTO `paiements` (`id_paiement`, `id_reservation`, `Montant`, `date_paiement`, `mode_paiement`) VALUES
(1, 1, 2000, '2025-02-14', 'carte'),
(2, 7, 5000, '2025-02-14', 'espèces');

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

CREATE TABLE `reservations` (
  `id_reservation` int(11) NOT NULL,
  `id_client` int(11) UNSIGNED NOT NULL,
  `id_voiture` int(11) UNSIGNED NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `statut_reservation` enum('en attente','confirmée','annulée') NOT NULL,
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `reservations`
--

INSERT INTO `reservations` (`id_reservation`, `id_client`, `id_voiture`, `date_debut`, `date_fin`, `statut_reservation`, `statut`) VALUES
(1, 2, 1, '2025-05-02', '2027-06-02', '', NULL),
(2, 3, 3, '2025-05-04', '2025-05-10', 'en attente', NULL),
(3, 4, 6, '2025-05-02', '2025-06-04', '', NULL),
(4, 12, 1, '2025-02-21', '2026-03-21', 'en attente', 'en attente'),
(5, 1, 1, '2025-10-02', '2025-11-02', 'en attente', 'en attente'),
(6, 1, 2, '2025-11-30', '2025-12-31', 'en attente', 'en attente'),
(7, 18, 2, '2025-12-04', '2024-12-10', 'en attente', 'en attente');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `type` enum('administrateurs','clients') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `prenom`, `email`, `mot_de_passe`, `type`) VALUES
(1, 'Admin', 'Test', 'admin@example.com', '$2y$10$V7z8b9Z1u2O3e4r5t6y7u8i9o0p1a2b3c4d5e6f7g8h9i0j1k2l3m4n5o6p', '');

-- --------------------------------------------------------

--
-- Structure de la table `voitures`
--

CREATE TABLE `voitures` (
  `id_voiture` int(11) NOT NULL,
  `marque` varchar(50) NOT NULL,
  `modele` varchar(50) NOT NULL,
  `annee_fabrication` int(11) DEFAULT NULL,
  `plaque_immatriculation` varchar(25) NOT NULL,
  `statut` enum('disponible','louée','en maintenance','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `voitures`
--

INSERT INTO `voitures` (`id_voiture`, `marque`, `modele`, `annee_fabrication`, `plaque_immatriculation`, `statut`) VALUES
(1, 'Mercedes', 'GLE', 0, '', 'disponible'),
(2, 'Mercedes', 'LE', 0, '', ''),
(3, 'Mercedes', 'KIA', 0, '', ''),
(4, 'Mercedes', 'LE', 0, '', ''),
(5, 'Mercedes', 'KIA', 0, '', ''),
(6, 'Mercedes', 'LE', 0, '', ''),
(7, 'Mercedes', 'GLE', 0, '', 'louée'),
(8, 'Mercedes', 'kia', 0, '', 'en maintenance'),
(9, 'kia', 'aven6', 2004, '2AE45', 'disponible'),
(10, 'kia', 'aven6', 2004, '2AE45', 'disponible'),
(11, 'kia', 'aven6', 2004, '2AE45', 'disponible'),
(12, 'kia', 'aven6', 2004, '2AE45', 'disponible'),
(13, 'kia', 'aven6', 2005, '2AE45', 'disponible');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `administrateurs`
--
ALTER TABLE `administrateurs`
  ADD PRIMARY KEY (`id_admin`);

--
-- Index pour la table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id_client`);

--
-- Index pour la table `paiements`
--
ALTER TABLE `paiements`
  ADD PRIMARY KEY (`id_paiement`),
  ADD KEY `id_reservation` (`id_reservation`);

--
-- Index pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id_reservation`),
  ADD KEY `id_client` (`id_client`,`id_voiture`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `voitures`
--
ALTER TABLE `voitures`
  ADD PRIMARY KEY (`id_voiture`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `administrateurs`
--
ALTER TABLE `administrateurs`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `clients`
--
ALTER TABLE `clients`
  MODIFY `id_client` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT pour la table `paiements`
--
ALTER TABLE `paiements`
  MODIFY `id_paiement` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id_reservation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `voitures`
--
ALTER TABLE `voitures`
  MODIFY `id_voiture` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
