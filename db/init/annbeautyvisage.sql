-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : jeu. 03 juil. 2025 à 12:38
-- Version du serveur : 8.4.3
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `annbeautyvisage`
--
CREATE DATABASE IF NOT EXISTS `annbeautyvisage` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `annbeautyvisage`;

-- --------------------------------------------------------

--
-- Structure de la table `76_admin`
--

CREATE TABLE IF NOT EXISTS `76_admin` (
  `admin_id` int NOT NULL AUTO_INCREMENT,
  `admin_mail` varchar(255) NOT NULL,
  `admin_mdp` varchar(100) NOT NULL,
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `admin_mail` (`admin_mail`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `76_prestation`
--

CREATE TABLE IF NOT EXISTS `76_prestation` (
  `prestation_id` int NOT NULL AUTO_INCREMENT,
  `prestation_image` varchar(250) NOT NULL,
  `prestation_nom` varchar(100) DEFAULT NULL,
  `prestation_prix` decimal(5,2) NOT NULL,
  `prestation_description` varchar(1000) DEFAULT NULL,
  `prestation_duree` time DEFAULT NULL,
  PRIMARY KEY (`prestation_id`),
  UNIQUE KEY `prestation_nom` (`prestation_nom`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `76_prestation`
--

INSERT INTO `76_prestation` (`prestation_id`, `prestation_image`, `prestation_nom`, `prestation_prix`, `prestation_description`, `prestation_duree`) VALUES
(1, 'Browlift.png', 'Browlift', 35.00, 'Lifting des sourcils pour un effet structuré et durable, sans maquillage.', '00:45:00'),
(2, 'Cil a cil.png', 'Extension Cil à Cil', 60.00, 'Pose d’extensions de cils une à une pour un effet naturel et allongé.', '01:30:00'),
(3, 'Depose.png', 'Dépose Extensions', 15.00, 'Retrait des extensions de cils en douceur, sans abîmer les cils naturels.', '00:30:00'),
(4, 'Mascara semi-premanent.png', 'Mascara Semi-Permanent', 40.00, 'Application de mascara longue tenue pour intensifier le regard sans retouche.', '00:45:00'),
(5, 'Mixte.png', 'Extension Mixte', 75.00, 'Combinaison de technique cil à cil et volume pour un effet plus fourni.', '01:45:00'),
(6, 'Rehaussement de cil.png', 'Rehaussement de Cils', 50.00, 'Courbure naturelle des cils pour ouvrir le regard sans extensions.', '01:00:00'),
(7, 'Restructuration.png', 'Restructuration Sourcils', 25.00, 'Redéfinition de la ligne de sourcils selon la morphologie du visage.', '00:30:00'),
(8, 'Teinture cils ou sourcils.png', 'Teinture Cils ou Sourcils', 20.00, 'Coloration douce et durable pour accentuer les cils ou les sourcils.', '00:30:00'),
(9, 'Volume russe.png', 'Extension Volume Russe', 85.00, 'Pose d’éventails de cils ultra-légers pour un regard intense et volumineux.', '02:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `76_rating`
--

CREATE TABLE IF NOT EXISTS `76_rating` (
  `rating_id` int NOT NULL AUTO_INCREMENT,
  `rating_score` decimal(3,2) NOT NULL,
  `rating_description` varchar(250) DEFAULT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`rating_id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `76_rating`
--

INSERT INTO `76_rating` (`rating_id`, `rating_score`, `rating_description`, `user_id`) VALUES
(1, 4.00, 'Pas mal !!!', 1),
(2, 5.00, 'C\'est incroyable !!!', 2);

-- --------------------------------------------------------

--
-- Structure de la table `76_reservation`
--

CREATE TABLE IF NOT EXISTS `76_reservation` (
  `reservation_id` int NOT NULL AUTO_INCREMENT,
  `reservation_date` date NOT NULL,
  `reservation_start` time NOT NULL,
  `reservation_end` time NOT NULL,
  `prestation_id` int NOT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`reservation_id`),
  KEY `prestation_id` (`prestation_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `76_reservation`
--

INSERT INTO `76_reservation` (`reservation_id`, `reservation_date`, `reservation_start`, `reservation_end`, `prestation_id`, `user_id`) VALUES
(1, '2025-07-01', '09:00:00', '11:00:00', 9, 1),
(2, '2025-07-03', '13:00:00', '15:00:00', 9, 2);

-- --------------------------------------------------------

--
-- Structure de la table `76_users`
--

CREATE TABLE IF NOT EXISTS `76_users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `user_nom` varchar(100) DEFAULT NULL,
  `user_prenom` varchar(100) DEFAULT NULL,
  `user_mail` varchar(255) DEFAULT NULL,
  `user_mdp` varchar(255) NOT NULL,
  `user_telephone` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_mail` (`user_mail`),
  UNIQUE KEY `user_telephone` (`user_telephone`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `76_users`
--

INSERT INTO `76_users` (`user_id`, `user_nom`, `user_prenom`, `user_mail`, `user_mdp`, `user_telephone`) VALUES
(1, 'Saïd', 'FADLI', 'saidfadli213@gmail.com', '$2y$10$AdnrmuaAs5785EObViuTJOlSstC2AMWL3QDB325C.B46ZAjhQnV..', '0658703698'),
(2, 'JOURDAIN', 'Ichem', 'test@test.com', '$2y$10$YKdqYtbN8Vz06UK9Ye4B/uvqk4hN3pSzcJJCWOHKrhB2fnGZiVdBW', '0658703697');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `76_rating`
--
ALTER TABLE `76_rating`
  ADD CONSTRAINT `76_rating_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `76_users` (`user_id`);

--
-- Contraintes pour la table `76_reservation`
--
ALTER TABLE `76_reservation`
  ADD CONSTRAINT `76_reservation_ibfk_1` FOREIGN KEY (`prestation_id`) REFERENCES `76_prestation` (`prestation_id`),
  ADD CONSTRAINT `76_reservation_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `76_users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
