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
(1, 'Color');

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
(3, 'Xiaomi');

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
(7, 'http://localhost/media/images/xiaomi15t.png', 7),
(8, 'http://localhost/media/images/iphone17pro_white.png', 1);

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
  `prix` decimal(10,2) DEFAULT NULL,
  `quantite` smallint(6) DEFAULT NULL,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_modification` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `categorie_fk` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `t_produit`
--

INSERT INTO `t_produit` (`produit_id`, `nom`, `prix`, `quantite`, `date_creation`, `date_modification`, `categorie_fk`) VALUES
(1, 'Iphone 17', 1000.00, 10, '2026-05-05 09:14:51', '2026-05-05 09:14:51', 1),
(2, 'Samsung S25', 1000.00, 15, '2026-05-05 09:14:51', '2026-05-05 09:14:51', 2),
(3, 'Xiaomi 14', 400.00, 15, '2026-05-05 09:14:51', '2026-05-05 09:14:51', 3),
(4, 'Iphone 15', 650.00, 0, '2026-05-05 09:14:51', '2026-05-05 09:14:51', 1),
(5, 'Samsung A56', 300.00, 2, '2026-05-05 09:14:51', '2026-05-05 09:14:51', 2),
(6, 'Iphone 14', 500.00, 4, '2026-05-05 09:14:51', '2026-05-05 09:14:51', 1),
(7, 'Xiaomi 15T', 400.00, 1, '2026-05-05 09:14:51', '2026-05-05 09:14:51', 3);

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
(1, 2),
(1, 3),
(2, 3),
(7, 4),
(5, 5),
(6, 6);

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
(1, 'Noir', 1),
(2, 'Orange', 1),
(3, 'Blanc', 1),
(4, 'Gris', 1),
(5, 'Vert', 1),
(6, 'Bleu', 1);

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
