# 📱 Boutique E-Commerce — Magasin d'Électronique

Un site web interactif de vente de téléphones portables doté d'un système de filtrage avancé, d'un panier dynamique synchronisé et d'une architecture de base de données flexible de type EAV.

---

## 🛠️ Stack Technique

* **Backend :** PHP (PDO, Sessions)
* **Base de données :** MySQL / MariaDB
* **Frontend :** HTML5, Tailwind CSS, JavaScript (Fetch API)

---

## 📐 Architecture de la Base de Données (Modèle EAV)

Ce projet utilise le modèle **EAV (Entity-Attribute-Value)** pour gérer les caractéristiques des produits. Cette approche permet d'ajouter de nouveaux attributs (comme de nouvelles capacités de stockage ou de nouvelles catégories) de manière totalement dynamique, sans avoir à modifier la structure des tables SQL existantes.

### Structure des tables principales :
* `t_produit` — Données principales du produit (nom, prix, quantité en stock).
* `t_attribut` — Types de caractéristiques (ex: `Color`, `Storage`).
* `t_valeur` — Valeurs spécifiques des caractéristiques (ex: `Noir`, `128 Go`).
* `t_produit_attribut` — Table de liaison pour la relation plusieurs-à-plusieurs (Many-to-Many).
* `v_storage` — Vue MySQL optimisée pour l'extraction rapide des attributs de stockage.

---

## 🚀 Fonctionnalités Clés

### 1. Filtrage Intelligent et Groupement (Anti-Doublons)
Mise en place d'une requête SQL optimisée combinant `LEFT JOIN`, `GROUP BY` et `GROUP_CONCAT`. Sur la page d'accueil, chaque modèle de téléphone s'affiche **strictement une seule fois**, même s'il possède plusieurs variantes en base de données. Les couleurs et options disponibles sont regroupées puis transformées en sélecteurs interactifs directement dans l'interface.

Le système prend en compte le cumul de filtres dynamiques complexes :
* Par marques (`iPhone`, `Samsung`, `Xiaomi`).
* Par attributs multiples (Couleurs, Stockage) via l'opérateur SQL `IN`.
* Par état de disponibilité des stocks.

### 2. Gestion Dynamique de la Disponibilité
L'affichage de l'état des stocks s'ajuste automatiquement selon la valeur réelle du champ `quantite` en base de données :
* 🟩 `> 5` unités — **En stock**
* 🟥 `Entre 1 et 4` unités — **Bientôt épuisé** (Indicateur de rareté pour inciter à l'achat)
* 🟨 `0` unité — **Bientôt disponible**

### 3. Système de Panier Sécurisé et Synchrone
Gestion fluide du panier avec une double synchronisation entre la session PHP (`$_SESSION['cart']`) et la table de la base de données (`t_produit_panier`). La suppression d'un article a été fiabilisée en récupérant l'ID exact du panier actif (`panier_id`) de l'utilisateur connecté, évitant ainsi les requêtes imbriquées conflictuelles lors des opérations de `DELETE`.

---

## 📦 Installation et Lancement Local

1. Clonez le dépôt :
   ```bash
   git clone [https://github.com/b1t3zzZ/Projet-P_Appro.git](https://github.com/b1t3zzZ/Projet-P_Appro.git)
2. Déplacez les fichiers du projet dans le répertoire de votre serveur local (par exemple, dans le dossier htdocs de XAMPP ou votre environnement Docker).
3. Importez le fichier SQL db_magasin_electronique.sql dans votre gestionnaire de base de données (ex: phpMyAdmin).
4. Configurez vos identifiants de connexion à la base de données dans le fichier db.php :
   ```bash
   $host = 'localhost'; // Utilisez 'db' si vous lancez le projet via Docker Compose
   $dbname = 'db_magasin_electronique';
   $user = 'votre_utilisateur';
   $pass = 'votre_mot_de_passe';
Lancez votre serveur et accédez au projet via votre navigateur : http://localhost/Projet-P_Appro

## 🌐 Environnement de Production (DevOps)
Le projet est également déployé sur un serveur de production cloud (VPS) configuré manuellement :

Web Server : Nginx configuré avec isolation stricte du dossier /public pour empêcher l'accès aux scripts backend sensibles.

Sécurité SSH : Connexion au serveur sécurisée via une architecture à clé privée ED25519 transitant par un serveur de rebond (Bastion).

Administration : Déploiement des fichiers via tunnels SFTP locaux et administration de la base de données à distance via redirection de ports sécurisée sous VS Code.

## 📄 Licence
Ce projet est sous licence MIT. Voir le fichier [LICENSE](https://github.com/b1t3zzZ/Projet-P_Appro/blob/main/LICENSE) pour plus de détails.
