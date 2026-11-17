# PhoneShop — Architecture conteneurisée (Docker / Podman)

Projet réalisé dans le cadre scolaire à l'ETML (**P_Bulle : Docker Network Toolkit**).  
L'objectif est de conteneuriser et d'orchestrer une application web e-commerce PHP/MariaDB (**PhoneShop**) à l'aide d'un `Dockerfile` personnalisé et d'un fichier `docker-compose.yml`, en appliquant les principes d'isolation réseau, de persistance des données et d'Infrastructure as Code.

---

## Architecture des conteneurs

L'infrastructure repose sur deux services isolés orchestrés par Compose (compatible **Docker** et **Podman**) :

| Service | Conteneur | Image / Build | Port exposé | Rôle |
| :--- | :--- | :--- | :--- | :--- |
| `app` | `site_web` | Build depuis `Dockerfile` (`php:7.4-apache`) | `8080:80` | Serveur Web Apache + PHP 7.4 avec extensions `mysqli`, `pdo`, `pdo_mysql` |
| `db` | `db_magasin` | `docker.io/library/mariadb:10.11` | `3306:3306` | Base de données MariaDB (`utf8mb4_unicode_ci`) |

### Réseau et persistance

- **Communication inter-conteneurs** : les deux services communiquent sur un réseau bridge. L'application PHP (`www/db.php`) se connecte à la base de données via le nom de service `db` résolu par le DNS interne.
- **Communication avec l'hôte** : l'application est accessible depuis la machine hôte sur `http://localhost:8080` et la base de données sur le port `3306`.
- **Volumes** :
  - `./www:/var/www/html` : montage du code source PHP dans le conteneur Web.
  - `./init.sql:/docker-entrypoint-initdb.d/init.sql` : création automatique de la base `db_magasin_electronique`, des tables, des données de démonstration et de l'utilisateur `site_user`.
  - `./db_data:/var/lib/mysql` : persistance des données MariaDB.

---

## Prérequis

- **Docker** + **Docker Compose**, ou **Podman** + **Podman Compose** (`podman`, `podman-docker`, `podman-compose` sous Ubuntu 24.04)
- **Git**

---

## Installation et lancement

1. **Cloner le dépôt :**

   ```bash
   git clone https://github.com/PetroProg/PhoneShop.git
   cd PhoneShop
   ```

2. **Créer le fichier `.env` à la racine du projet :**

   ```env
   DATABASE=db_magasin_electronique
   PASSWORD=votre_mot_de_passe_root
   ```

3. **Construire et démarrer les conteneurs :**

   Avec Podman :
   ```bash
   podman-compose up -d --build
   ```

   Ou avec Docker :
   ```bash
   docker compose up -d --build
   ```

4. **Accéder à l'application :**

   Ouvrir un navigateur à l'adresse :
   ```text
   http://localhost:8080
   ```

---

## Commandes utiles

```bash
# Vérifier l'état des conteneurs
podman ps

# Consulter les logs d'un conteneur
podman logs site_web
podman logs db_magasin

# Inspecter le réseau et les adresses IP attribuées
podman network inspect <nom_du_reseau>

# Arrêter et supprimer les conteneurs
podman-compose down
```

---

## Structure du projet

```text
PhoneShop/
├── Dockerfile                  # Image PHP 7.4 / Apache + pilotes MySQL/PDO
├── docker-compose.yml          # Orchestration des services app et db
├── init.sql                    # Script d'initialisation de la base MariaDB
├── www/                        # Code source de l'application web PhoneShop
│   ├── db.php                  # Connexion PDO vers l'hôte 'db'
│   └── ...
└── docs/
    ├── rapport.md              # Rapport technique d'installation et de déploiement
    ├── Presentation_MaltsevPetro.md
    └── Jornal_de_Travail_MaltsevPetro.md
```

---

## Application déployée (PhoneShop)

PhoneShop sert d'application support pour valider le fonctionnement de la stack conteneurisée :

- **Catalogue EAV** : 27 modèles de smartphones répartis en 6 marques, avec filtrage multi-attributs (couleurs, stockage) sans doublons (`GROUP_CONCAT` / `GROUP BY`).
- **Gestion des stocks et paniers** : synchronisation entre la session PHP et MariaDB (`t_panier`, `t_produit_panier`), décrémentation du stock à la commande.
- **Authentification et rôles** : mots de passe hachés (`password_hash` / `password_verify`) et niveaux de privilèges (`guest` à `admin`).

---

## Documentation et licence

- Rapport technique : [docs/rapport.md](docs/rapport.md)
- Présentation : [docs/Presentation_MaltsevPetro.md](docs/Presentation_MaltsevPetro.md)
- Licence : [LICENSE](LICENSE) (MIT)
