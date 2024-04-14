-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : dim. 14 avr. 2024 à 20:22
-- Version du serveur : 5.7.36
-- Version de PHP : 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestionformations`
--

-- --------------------------------------------------------

--
-- Structure de la table `employe`
--

DROP TABLE IF EXISTS `employe`;
CREATE TABLE IF NOT EXISTS `employe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mdp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `statut` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `employe`
--

INSERT INTO `employe` (`id`, `login`, `mdp`, `nom`, `prenom`, `statut`) VALUES
(1, 'jdoe', '$2y$10$heThSwqdOPb4g2Fr/Qmc/eQVWmqp88N/W0c9WuFQmJn2MNb/X4QBO', 'Doe', 'John', 0),
(2, 'test', '$2y$10$ymPbhn2.gXc3R5Tg4Wm7neoqe0XKnuKRHn801VfTk3KZWrR.gq3kO', 'Smith', 'Jane', 1),
(3, 'mstark', '$2y$10$uFOMQ76SLvaV/srxES35JeWYGhv0YzaPh/C3EZFewYOCW95n605K2', 'Stark', 'Max', 0);

-- --------------------------------------------------------

--
-- Structure de la table `formation`
--

DROP TABLE IF EXISTS `formation`;
CREATE TABLE IF NOT EXISTS `formation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_debut` date NOT NULL,
  `departement` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `produit_id` int(11) DEFAULT NULL,
  `nbr_heure` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_404021BFF347EFB` (`produit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `formation`
--

INSERT INTO `formation` (`id`, `date_debut`, `departement`, `produit_id`, `nbr_heure`) VALUES
(1, '2023-12-10', 'Mises a jour système', 1, 2),
(3, '2023-12-20', 'Gestion de projet IT', 2, 25),
(5, '2023-01-01', 'Application', 2, 3244);

-- --------------------------------------------------------

--
-- Structure de la table `inscription`
--

DROP TABLE IF EXISTS `inscription`;
CREATE TABLE IF NOT EXISTS `inscription` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `statut` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employe_id` int(11) DEFAULT NULL,
  `formation_id` int(11) DEFAULT NULL,
  `commentaire` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5E90F6D61B65292` (`employe_id`),
  KEY `IDX_5E90F6D65200282E` (`formation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `inscription`
--

INSERT INTO `inscription` (`id`, `statut`, `employe_id`, `formation_id`, `commentaire`) VALUES
(51, 'Acceptée', 1, 1, ''),
(52, '', 1, 3, ''),
(53, 'Acceptée', 2, 5, ''),
(54, 'Acceptée', 2, 1, NULL),
(56, 'Acceptée', 2, 3, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

DROP TABLE IF EXISTS `produit`;
CREATE TABLE IF NOT EXISTS `produit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id`, `libelle`) VALUES
(1, 'Ordinateur Portable'),
(2, 'Samsung Galaxy'),
(3, 'Caméra Sony');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `formation`
--
ALTER TABLE `formation`
  ADD CONSTRAINT `FK_404021BFF347EFB` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`);

--
-- Contraintes pour la table `inscription`
--
ALTER TABLE `inscription`
  ADD CONSTRAINT `FK_5E90F6D61B65292` FOREIGN KEY (`employe_id`) REFERENCES `employe` (`id`),
  ADD CONSTRAINT `FK_5E90F6D65200282E` FOREIGN KEY (`formation_id`) REFERENCES `formation` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
