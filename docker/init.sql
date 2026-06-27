-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : db
-- Généré le : mar. 12 mai 2026 à 09:09
-- Version du serveur : 5.7.44
-- Version de PHP : 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `db_magasin_electronique`
--
DROP DATABASE IF EXISTS db_magasin_electronique;
CREATE DATABASE db_magasin_electronique
DEFAULT CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE db_magasin_electronique;

-- --------------------------------------------------------

--
-- Structure de la table `t_attribut`
--

CREATE TABLE `t_attribut` (
  `attribut_id` int(11) NOT NULL,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `t_attribut`
--

INSERT INTO `t_attribut` (`attribut_id`, `nom`) VALUES
(1, 'Color'),
(2, 'Storage');

-- --------------------------------------------------------

--
-- Structure de la table `t_categorie`
--

CREATE TABLE `t_categorie` (
  `categorie_id` int(11) NOT NULL,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `t_categorie`
--

INSERT INTO `t_categorie` (`categorie_id`, `nom`) VALUES
(1, 'Iphone'),
(2, 'Samsung'),
(3, 'Xiaomi'),
(4, 'Google'),
(5, 'OnePlus'),
(6, 'Nothing');

-- --------------------------------------------------------

--
-- Structure de la table `t_client`
--

CREATE TABLE `t_client` (
  `client_id` int(11) NOT NULL,
  `login` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prenom` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rue` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `npa` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `localite` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  `privilege_fk` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `t_image`
--

CREATE TABLE `t_image` (
  `image_id` int(11) NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `produit_fk` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `t_image`
--

INSERT INTO `t_image` (`image_id`, `url`, `produit_fk`) VALUES
(1, 'http://localhost/media/images/iPhone.png', 1),
(2, 'http://localhost/media/images/samsung-galaxy-s25.jpg', 2),
(3, 'http://localhost/media/images/xiaomi14.png', 3),
(4, 'http://localhost/media/images/iphone15.png', 4),
(5, 'http://localhost/media/images/samsunga56.png', 5),
(6, 'http://localhost/media/images/iphone14.png', 6),
(7, 'http://localhost/media/images/xiaomi15t.png', 7), -- Xiaomi 15T (14T Base)
(8, 'http://localhost/media/images/iphone17pro.webp', 8), -- iPhone 17 Pro
(9, 'http://localhost/media/images/iphone17.webp', 9), -- iPhone 17
(10, 'http://localhost/media/images/iPhone16ProMax.webp', 10), -- iPhone 16 Pro Max
(11, 'http://localhost/media/images/iPhone16Plus.webp', 11), -- iPhone 16 Plus
(12, 'http://localhost/media/images/iphonese4.webp', 12), -- iPhone SE 4
(13, 'http://localhost/media/images/samsungs25ultra.webp', 13), -- Samsung S25 Ultra
(14, 'http://localhost/media/images/samsungs25plus.webp', 14), -- Samsung S25 Plus
(15, 'http://localhost/media/images/samsungzfold.webp', 15), -- Samsung Z Fold 7
(16, 'http://localhost/media/images/SamsungZFlip7.jpg', 16), -- Samsung Z Flip 7
(17, 'http://localhost/media/images/SamsungA36.webp', 17), -- Samsung A36
(18, 'http://localhost/media/images/Xiaomi15Ultra.webp', 18), -- Xiaomi 15 Ultra
(19, 'http://localhost/media/images/Xiaomi15Pro.jpg', 19), -- Xiaomi 15 Pro
(20, 'http://localhost/media/images/XiaomiRedmiNote14Pro.jpg', 20), -- Xiaomi Redmi Note 14 Pro
(21, 'http://localhost/media/images/XiaomiPocoF7Pro.webp', 21), -- Xiaomi Poco F7 Pro
(22, 'http://localhost/media/images/GooglePixel10Pro.webp', 22), -- Google Pixel 10 Pro
(23, 'http://localhost/media/images/GooglePixel10.webp', 23), -- Google Pixel 10
(24, 'http://localhost/media/images/GooglePixel9a.webp', 24), -- Google Pixel 9a
(25, 'http://localhost/media/images/OnePlus13.webp', 25), -- OnePlus 13
(26, 'http://localhost/media/images/OnePlusNord5.jpg', 26), -- OnePlus Nord 5
(27, 'http://localhost/media/images/NothingPhone(3).jpg', 27); -- Nothing Phone (3)

-- --------------------------------------------------------

--
-- Structure de la table `t_panier`
--

CREATE TABLE `t_panier` (
  `panier_id` int(11) NOT NULL,
  `statut` enum('actif','valide') COLLATE utf8mb4_unicode_ci DEFAULT 'actif',
  `date_ajout` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_modification` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `client_fk` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `t_privilege`
--

CREATE TABLE `t_privilege` (
  `privilege_id` int(11) NOT NULL,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `t_privilege`
--

INSERT INTO `t_privilege` (`privilege_id`, `nom`) VALUES
(1, 'guest'),
(2, 'user'),
(3, 'moderator'),
(4, 'editor'),
(5, 'admin');

-- --------------------------------------------------------

--
-- Structure de la table `t_produit`
--

CREATE TABLE `t_produit` (
  `produit_id` int(11) NOT NULL, 
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prix` DECIMAL(10, 2) NOT NULL,
  `quantite` INT DEFAULT NULL, 
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_modification` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `categorie_fk` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `t_produit`
--

INSERT INTO `t_produit` (`produit_id`, `nom`, `prix`, `quantite`, `date_creation`, `date_modification`, `categorie_fk`) VALUES
(1, 'Iphone 17 Pro Max', 999, 10, '2026-05-05 09:14:51', '2026-05-05 09:14:51', 1),
(2, 'Samsung S25', 469, 15, '2026-05-05 09:14:51', '2026-05-05 09:14:51', 2),
(3, 'Xiaomi 14', 379, 5, '2026-05-05 09:14:51', '2026-05-05 09:14:51', 3),
(4, 'Iphone 15', 399, 0, '2026-05-05 09:14:51', '2026-05-05 09:14:51', 1),
(5, 'Samsung A56', 299, 2, '2026-05-05 09:14:51', '2026-05-05 09:14:51', 2),
(6, 'Iphone 14', 299, 4, '2026-05-05 09:14:51', '2026-05-05 09:14:51', 1),
(7, 'Xiaomi 15T', 329, 1, '2026-05-05 09:14:51', '2026-05-05 09:14:51', 3),
(8, 'Iphone 17 Pro', 899, 7, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 1),
(9, 'Iphone 17', 749, 12, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 1),
(10, 'Iphone 16 Pro Max', 849, 4, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 1),
(11, 'Iphone 16 Plus', 699, 6, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 1),
(12, 'Iphone SE 4', 429, 20, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 1),
(13, 'Samsung S25 Ultra', 1199, 5, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 2),
(14, 'Samsung S25 Plus', 899, 8, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 2),
(15, 'Samsung Z Fold 7', 1599, 2, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 2),
(16, 'Samsung Z Flip 7', 949, 4, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 2),
(17, 'Samsung A36', 249, 18, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 2),
(18, 'Xiaomi 15 Ultra', 1049, 3, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 3),
(19, 'Xiaomi 15 Pro', 799, 5, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 3),
(20, 'Xiaomi Redmi Note 14 Pro', 279, 25, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 3),
(21, 'Xiaomi Poco F7 Pro', 449, 14, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 3),
(22, 'Google Pixel 10 Pro', 899, 9, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 4),
(23, 'Google Pixel 10', 699, 11, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 4),
(24, 'Google Pixel 9a', 399, 15, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 4),
(25, 'OnePlus 13', 749, 8, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 5),
(26, 'OnePlus Nord 5', 369, 13, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 5),
(27, 'Nothing Phone (3)', 599, 10, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 6);

-- --------------------------------------------------------

--
-- Structure de la table `t_produit_attribut`
--

CREATE TABLE `t_produit_attribut` (
  `produit_fk` int(11) NOT NULL,
  `valeur_fk` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `t_produit_attribut`
--

INSERT INTO `t_produit_attribut` (`produit_fk`, `valeur_fk`) VALUES
(3, 1),
(4, 1),
(1, 3),
(2, 2),
(7, 1),
(5, 3),
(6, 3),
(8, 1),   
(9, 3),   
(10, 2),  
(11, 3), 
(12, 2), 
(13, 2), 
(14, 1), 
(15, 1),  
(16, 2), 
(17, 2), 
(18, 1), 
(19, 3), 
(20, 1),  
(21, 3), 
(22, 3), 
(23, 1), 
(24, 1), 
(25, 1),  
(26, 3),  
(27, 1),
(1, 4),  
(2, 6),  
(3, 4),  
(4, 5),  
(5, 5),  
(6, 5),  
(7, 5),  
(8, 4),  
(9, 4),  
(10, 7), 
(11, 7), 
(12, 4), 
(13, 4), 
(14, 6), 
(15, 5), 
(16, 6), 
(17, 4), 
(18, 5), 
(19, 5), 
(20, 6), 
(21, 4), 
(22, 5), 
(23, 7), 
(24, 4), 
(25, 4),
(26, 6),
(27, 5);

-- --------------------------------------------------------

--
-- Structure de la table `t_produit_panier`
--

CREATE TABLE `t_produit_panier` (
  `produit_fk` int(11) NOT NULL,
  `panier_fk` int(11) NOT NULL,
  `quantite` smallint(6) DEFAULT NULL,
  `prix_unitaire` decimal(10,2) DEFAULT NULL,
  `date_ajout` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `t_valeur`
--

CREATE TABLE `t_valeur` (
  `valeur_id` int(11) NOT NULL,
  `valeur` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attribut_fk` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `t_valeur`
--

INSERT INTO `t_valeur` (`valeur_id`, `valeur`, `attribut_fk`) VALUES
(1, 'Sombre', 1),
(2, 'Clair', 1),
(3, 'Personnalisé', 1),
(4, '128 Go', 2),
(5, '256 Go', 2),
(6, '512 Go', 2),
(7, '1 To', 2);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `t_attribut`
--
ALTER TABLE `t_attribut`
  ADD PRIMARY KEY (`attribut_id`);

--
-- Index pour la table `t_categorie`
--
ALTER TABLE `t_categorie`
  ADD PRIMARY KEY (`categorie_id`);

--
-- Index pour la table `t_client`
--
ALTER TABLE `t_client`
  ADD PRIMARY KEY (`client_id`),
  ADD UNIQUE KEY `login` (`login`),
  ADD KEY `idx_client_privilege` (`privilege_fk`);

--
-- Index pour la table `t_image`
--
ALTER TABLE `t_image`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `produit_fk` (`produit_fk`);

--
-- Index pour la table `t_panier`
--
ALTER TABLE `t_panier`
  ADD PRIMARY KEY (`panier_id`),
  ADD KEY `idx_panier_client` (`client_fk`);

--
-- Index pour la table `t_privilege`
--
ALTER TABLE `t_privilege`
  ADD PRIMARY KEY (`privilege_id`);

--
-- Index pour la table `t_produit`
--
ALTER TABLE `t_produit`
  ADD PRIMARY KEY (`produit_id`),
  ADD KEY `idx_produit_categorie` (`categorie_fk`);

--
-- Index pour la table `t_produit_attribut`
--
ALTER TABLE `t_produit_attribut`
  ADD PRIMARY KEY (`produit_fk`,`valeur_fk`),
  ADD KEY `valeur_fk` (`valeur_fk`);

--
-- Index pour la table `t_produit_panier`
--
ALTER TABLE `t_produit_panier`
  ADD PRIMARY KEY (`produit_fk`,`panier_fk`),
  ADD KEY `panier_fk` (`panier_fk`);

--
-- Index pour la table `t_valeur`
--
ALTER TABLE `t_valeur`
  ADD PRIMARY KEY (`valeur_id`),
  ADD KEY `attribut_fk` (`attribut_fk`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `t_attribut`
--
ALTER TABLE `t_attribut`
  MODIFY `attribut_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `t_categorie`
--
ALTER TABLE `t_categorie`
  MODIFY `categorie_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `t_client`
--
ALTER TABLE `t_client`
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `t_image`
--
ALTER TABLE `t_image`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `t_panier`
--
ALTER TABLE `t_panier`
  MODIFY `panier_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `t_privilege`
--
ALTER TABLE `t_privilege`
  MODIFY `privilege_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `t_produit`
--
ALTER TABLE `t_produit`
  MODIFY `produit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `t_valeur`
--
ALTER TABLE `t_valeur`
  MODIFY `valeur_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `t_client`
--
ALTER TABLE `t_client`
  ADD CONSTRAINT `t_client_ibfk_1` FOREIGN KEY (`privilege_fk`) REFERENCES `t_privilege` (`privilege_id`);

--
-- Contraintes pour la table `t_image`
--
ALTER TABLE `t_image`
  ADD CONSTRAINT `t_image_ibfk_1` FOREIGN KEY (`produit_fk`) REFERENCES `t_produit` (`produit_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `t_panier`
--
ALTER TABLE `t_panier`
  ADD CONSTRAINT `t_panier_ibfk_1` FOREIGN KEY (`client_fk`) REFERENCES `t_client` (`client_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `t_produit`
--
ALTER TABLE `t_produit`
  ADD CONSTRAINT `t_produit_ibfk_1` FOREIGN KEY (`categorie_fk`) REFERENCES `t_categorie` (`categorie_id`);

--
-- Contraintes pour la table `t_produit_attribut`
--
ALTER TABLE `t_produit_attribut`
  ADD CONSTRAINT `t_produit_attribut_ibfk_1` FOREIGN KEY (`produit_fk`) REFERENCES `t_produit` (`produit_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `t_produit_attribut_ibfk_2` FOREIGN KEY (`valeur_fk`) REFERENCES `t_valeur` (`valeur_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `t_produit_panier`
--
ALTER TABLE `t_produit_panier`
  ADD CONSTRAINT `t_produit_panier_ibfk_1` FOREIGN KEY (`produit_fk`) REFERENCES `t_produit` (`produit_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `t_produit_panier_ibfk_2` FOREIGN KEY (`panier_fk`) REFERENCES `t_panier` (`panier_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `t_valeur`
--
ALTER TABLE `t_valeur`
  ADD CONSTRAINT `t_valeur_ibfk_1` FOREIGN KEY (`attribut_fk`) REFERENCES `t_attribut` (`attribut_id`);
  
CREATE USER IF NOT EXISTS 'site_user'@'%' IDENTIFIED BY 'user_password_123';
GRANT SELECT, INSERT, UPDATE, DELETE ON db_magasin_electronique.* TO 'site_user'@'%';
FLUSH PRIVILEGES;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
