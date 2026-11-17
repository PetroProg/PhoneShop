# Déploiement d’une application PHP avec Podman

## 1. Système d’exploitation

La machine virtuelle utilise **Ubuntu 24.04**.

Avant toute installation, il est recommandé de mettre à jour la liste des paquets :

```bash
sudo apt update
```

Cette commande actualise les informations provenant des dépôts logiciels configurés sur le système.

## 2. Installation de Podman

Podman est installé avec les commandes suivantes :

```bash
sudo apt install podman
sudo apt install podman-docker
sudo apt install podman-compose
```

Le paquet `podman-docker` permet d’utiliser certaines commandes Docker avec Podman.  
Le paquet `podman-compose` permet également de gérer plusieurs conteneurs à partir d’un fichier `docker-compose.yml`.

J’ai choisi Podman plutôt que Docker car Podman fonctionne sans démon central permanent. Ainsi, l’exécution des conteneurs ne dépend pas d’un service Docker unique. Cela améliore notamment la sécurité et réduit les risques liés à l’arrêt d’un démon central.

Si le processus `unattended-upgrades` bloque l’installation, il peut être arrêté temporairement :

```bash
sudo systemctl stop unattended-upgrades
```

## 3. Création du projet

Les dossiers et fichiers nécessaires sont créés avec les commandes suivantes :

```bash
mkdir ProjPodman
cd ProjPodman

touch Dockerfile docker-compose.yml
```

## 4. Initialisation du dépôt Git

Le dépôt Git est initialisé avec la branche principale `main` :

```bash
git init
git config --global init.defaultBranch main
git remote add origin https://github.com/PetroProg/PhoneShop.git
git fetch origin
```

Le fichier d’initialisation de la base de données est ensuite récupéré depuis le dépôt distant :

```bash
git checkout origin/main -- docker/init.sql
```

## 5. Création du Dockerfile

Le fichier `Dockerfile` définit l’image du serveur Web PHP :

```dockerfile
FROM php:7.4-apache

WORKDIR /app

COPY . .

RUN docker-php-ext-install mysqli pdo pdo_mysql
```

Cette image utilise Apache avec PHP 7.4 et installe les extensions nécessaires pour communiquer avec une base de données MySQL ou MariaDB.

## 6. Configuration des conteneurs

Le fichier `docker-compose.yml` décrit deux services :

- `db` : conteneur MariaDB contenant la base de données ;
- `site` : conteneur Apache exécutant l’application PHP.

```yaml
services:
  db:
    image: mariadb:10.11
    container_name: db_magasin
    environment:
      MYSQL_DATABASE: ${DATABASE}
      MYSQL_ROOT_PASSWORD: ${PASSWORD}
    ports:
      - "3306:3306"
    volumes:
      - ./init.sql:/docker-entrypoint-initdb.d/init.sql
      - ./db_data:/var/lib/mysql
    command: --character-set-server=utf8mb4 --collation-server=utf8mb4_unicode_ci

  site:
    container_name: site_web
    build:
      context: .
      dockerfile: Dockerfile
    ports:
      - "80:80"
    volumes:
      - ./www:/var/www/html
    depends_on:
      - db
```

Les variables `${DATABASE}` et `${PASSWORD}` doivent être définies dans un fichier `.env`.

## 7. Lancement de l’application

Les conteneurs peuvent être démarrés avec la commande suivante :

```bash
podman-compose up -d
```

L’option `-d` permet d’exécuter les conteneurs en arrière-plan.

L’application Web est ensuite accessible depuis un navigateur à l’adresse :

```text
http://localhost:8080
```

## Conclusion

Podman permet de déployer l’application PHP dans des conteneurs indépendants. L’architecture utilisée sépare le serveur Web et la base de données, ce qui facilite la maintenance, l’isolation et la gestion du projet.