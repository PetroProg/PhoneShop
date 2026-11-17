---
marp: true
theme: default
size: 16:9
paginate: true
header: "P_BULLE · DOCKER NETWORK TOOLKIT"
footer: "Conteneurisation de PhoneShop · Maltsev Petro"
---
<style>
	section {
		background: #f5f7fb;
		color: #172033;
		font-family: "Aptos", "Segoe UI", sans-serif;
		font-size: 23px;
		padding: 52px 58px 46px;
		justify-content: flex-start;
	}
	section.hero {
		justify-content: center;
	}
	section::after {
		color: #64748b;
		font-size: 14px;
	}
	header {
		color: #64748b;
		font-size: 13px;
		font-weight: 700;
		letter-spacing: 0.16em;
	}
	footer {
		color: #64748b;
		font-size: 13px;
	}
	h1, h2, h3 { font-family: "Aptos Display", "Segoe UI", sans-serif; }
	h1 { color: #0f172a; font-size: 2.35em; line-height: 1.05; margin: 0 0 0.3em; }
	h2 { color: #0f172a; font-size: 1.45em; line-height: 1.15; margin: 0 0 0.55em; }
	h3 { color: #2563eb; font-size: 0.85em; letter-spacing: 0.08em; text-transform: uppercase; margin: 0 0 6px; }
	p, li { color: #475569; line-height: 1.32; font-size: 0.88em; margin: 0.22em 0; }
	ul { margin: 0.25em 0 0; padding-left: 1.15em; }
	strong { color: #0f172a; }
	code { background: #e8eef8; color: #1d4ed8; padding: 0.08em 0.3em; border-radius: 0.25em; font-size: 0.88em; }
	.lead { color: #475569; font-size: 1.05em; max-width: 820px; margin: 0.2em 0 0.5em; }
	.eyebrow { color: #2563eb; font-size: 0.72em; font-weight: 800; letter-spacing: 0.2em; text-transform: uppercase; margin: 0 0 8px; }
	.hero { background: linear-gradient(135deg, #eff6ff 0%, #f8fafc 58%, #dbeafe 100%); }
	.hero h1 { font-size: 2.8em; max-width: 780px; }
	.hero .lead { font-size: 1.15em; }
	.two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; align-items: start; }
	.three-col { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }
	.panel { background: #ffffff; border: 1px solid #dbe3ef; border-radius: 12px; padding: 14px 16px; box-shadow: 0 6px 18px #1720330a; }
	.metric { color: #0f172a; font-size: 1.75em; font-weight: 800; margin: 0; line-height: 1.1; }
	.metric-label { color: #64748b; font-size: 0.78em; margin: 4px 0 0; }
	.flow { display: flex; align-items: center; justify-content: space-between; gap: 8px; margin: 10px 0; }
	.step { background: #ffffff; border: 1px solid #cbd5e1; border-radius: 10px; flex: 1; padding: 10px 10px; text-align: center; }
	.step b { color: #0f172a; display: block; margin-bottom: 4px; font-size: 0.88em; }
	.arrow { color: #2563eb; font-size: 1.3em; font-weight: 800; }
	.codebox { background: #0f172a; border-radius: 12px; color: #dbeafe; font-family: "Cascadia Code", Consolas, monospace; font-size: 0.62em; line-height: 1.38; padding: 14px 16px; white-space: pre; }
	.phone { background: #ffffff; border: 7px solid #0f172a; border-radius: 24px; box-shadow: 12px 14px 0 #bfdbfe; margin: 0 auto; padding: 10px; width: 200px; }
	.phone img { display: block; height: 205px; margin: auto; object-fit: contain; width: 100%; }
	.quote { border-left: 4px solid #2563eb; color: #334155; font-size: 1.02em; font-weight: 600; padding-left: 14px; margin: 10px 0 14px; }
	.small { color: #64748b; font-size: 0.78em; line-height: 1.28; }
	.net-box { border: 2px dashed #2563eb; border-radius: 14px; background: #eff6ff; padding: 12px 16px; margin: 8px 0; }
	.net-title { color: #1d4ed8; font-size: 0.72em; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 6px; }
	.badge { display: inline-block; background: #dbeafe; color: #1d4ed8; font-weight: 700; font-size: 0.72em; padding: 2px 8px; border-radius: 999px; margin-bottom: 4px; }
</style>

<!-- _class: hero -->

<p class="eyebrow">ETML · P_Bulle Docker Network Toolkit</p>

# Conteneurisation de PhoneShop

## Orchestration d'une architecture PHP / MariaDB avec Podman & Compose

<p class="lead">Création d'une image personnalisée, isolation des services, communication réseau inter-conteneurs et déploiement reproductible sous Ubuntu 24.04.</p>

<br>

**Maltsev Petro** · 2026

---

## Contexte et objectifs du projet

<div class="two-col">
<div>

<p class="quote">Passer d'un hébergement local classique à une infrastructure conteneurisée, isolée et reproductible.</p>

### Livrables du cahier des charges

- **Image personnalisée** via un <code>Dockerfile</code> dédié ;
- **Orchestration multi-services** avec <code>docker-compose.yml</code> ;
- **Documentation technique** et journal de travail ;
- **Schéma réseau exhaustif** (hôte ↔ conteneurs).

</div>
<div style="display: grid; gap: 12px;">
<div class="panel"><p class="metric">2 conteneurs</p><p class="metric-label">Serveur Web (<code>site_web</code>) + Base de données (<code>db_magasin</code>)</p></div>
<div class="panel"><p class="metric">Ubuntu 24.04</p><p class="metric-label">VM hôte exécutant Podman &amp; Podman Compose</p></div>
<div class="panel"><p class="metric">PhoneShop</p><p class="metric-label">Application PHP 7.4 / MariaDB 10.11 (27 produits)</p></div>
</div>
</div>

---

## Choix technologique : Podman vs Docker

<p class="lead">Le projet utilise <strong>Podman</strong> (avec <code>podman-docker</code> et <code>podman-compose</code>) tout en restant 100 % compatible avec les fichiers Docker.</p>

<div class="three-col" style="margin-top: 14px;">
<div class="panel">
<h3>Sans démon central</h3>
<p>Podman fonctionne sans démon permanent (<em>daemonless</em>). Aucun point de défaillance unique.</p>
</div>
<div class="panel">
<h3>Sécurité renforcée</h3>
<p>Exécution possible en mode <strong>rootless</strong> : les conteneurs tournent sans privilèges root sur l'hôte.</p>
</div>
<div class="panel">
<h3>Compatibilité Compose</h3>
<p>Les mêmes fichiers <code>Dockerfile</code> et <code>docker-compose.yml</code> fonctionnent avec Podman et Docker.</p>
</div>
</div>

<div class="panel" style="margin-top: 14px;">
<h3>Installation sur la VM Ubuntu 24.04</h3>
<p><code>sudo apt install podman podman-docker podman-compose</code> — permet d'utiliser la syntaxe Docker standard.</p>
</div>

---

## Architecture et schéma réseau

<div class="panel" style="padding: 10px 14px; margin-bottom: 8px;">
<span class="badge">Hôte · VM Ubuntu 24.04 (127.0.0.1)</span>
<p class="small" style="margin:0;">Mappage de ports (NAT) : <code>8080 → 80</code> (HTTP vers <code>site_web</code>) et <code>3306 → 3306</code> (SQL vers <code>db_magasin</code>)</p>
</div>

<div class="net-box">
<div class="net-title">Réseau Bridge Conteneur (DNS interne · ex. sous-réseau 10.89.0.0/24)</div>
<div class="flow" style="margin: 6px 0;">
<div class="step">
<b>Client / Navigateur</b>
<span class="small"><code>localhost:8080</code><br>Requête HTTP</span>
</div>
<span class="arrow">→</span>
<div class="step" style="border: 2px solid #2563eb;">
<b><code>site_web</code> (service <code>app</code>)</b>
<span class="small">PHP 7.4 + Apache · Port <code>80</code><br>IP bridge (ex. <code>10.89.0.2</code>)</span>
</div>
<span class="arrow">↔</span>
<div class="step" style="border: 2px solid #0f172a;">
<b><code>db_magasin</code> (service <code>db</code>)</b>
<span class="small">MariaDB 10.11 · Port <code>3306</code><br>IP bridge (ex. <code>10.89.0.3</code>)</span>
</div>
</div>
</div>

<div class="two-col" style="margin-top: 8px;">
<div class="panel" style="padding: 10px 14px;">
<h3>Inter-conteneurs (DNS)</h3>
<p class="small" style="margin:0;">PHP se connecte via <code>$host = 'db'</code> dans <code>www/db.php</code>. Le DNS interne résout <code>db</code> vers l'IP de MariaDB.</p>
</div>
<div class="panel" style="padding: 10px 14px;">
<h3>Accès depuis l'hôte</h3>
<p class="small" style="margin:0;">L'hôte accède au site sur le port <code>8080</code> et à la base sur <code>3306</code> via les ports publiés.</p>
</div>
</div>

---

## Personnalisation de l'image (`Dockerfile`)

<div class="two-col">
<div>

<h3>Contenu du Dockerfile</h3>

<div class="codebox">FROM docker.io/library/php:7.4-apache

WORKDIR /app

COPY . .

RUN docker-php-ext-install mysqli pdo pdo_mysql</div>

</div>
<div>

<h3>Pourquoi personnaliser l'image ?</h3>

<div class="panel">
<p>L'image officielle <code>php:7.4-apache</code> ne contient pas par défaut les pilotes MariaDB / MySQL.</p>
<ul>
<li><strong><code>FROM</code></strong> : base Apache + PHP 7.4 ;</li>
<li><strong><code>docker-php-ext-install</code></strong> : compile <code>mysqli</code>, <code>pdo</code> et <code>pdo_mysql</code> ;</li>
<li><strong>Résultat</strong> : exécution de requêtes préparées PDO sécurisées.</li>
</ul>
</div>

</div>
</div>

---

## Orchestration (`docker-compose.yml`)

<div class="two-col">
<div>

<div class="codebox">services:
  db:
    image: docker.io/library/mariadb:10.11
    container_name: db_magasin
    environment:
      MYSQL_DATABASE: ${DATABASE}
      MYSQL_ROOT_PASSWORD: ${PASSWORD}
    ports: ["3306:3306"]
    volumes:
      - ./init.sql:/docker-entrypoint-initdb.d/init.sql
      - ./db_data:/var/lib/mysql
  app:
    container_name: site_web
    build: { context: ., dockerfile: Dockerfile }
    ports: ["8080:80"]
    volumes: ["./www:/var/www/html"]
    depends_on: [db]</div>

</div>
<div>

<h3>Points clés Infrastructure as Code</h3>

<ul>
<li><strong>Fichier <code>.env</code></strong> : sépare <code>DATABASE</code> et <code>PASSWORD</code> du code ;</li>
<li><strong><code>init.sql</code></strong> : crée les tables, les données et <code>site_user</code> au 1<sup>er</sup> lancement ;</li>
<li><strong><code>./db_data</code></strong> : persistance des données entre les redémarrages ;</li>
<li><strong><code>./www</code></strong> : code PHP monté dans <code>/var/www/html</code> ;</li>
<li><strong><code>depends_on</code></strong> : démarre <code>db</code> avant <code>app</code>.</li>
</ul>

</div>
</div>

---

## Application déployée : PhoneShop

<div class="two-col" style="align-items: center;">
<div>

<h3>Validation fonctionnelle de la stack</h3>

<p>L'application web valide concrètement la communication entre PHP et MariaDB :</p>

<ul>
<li><strong>Catalogue EAV</strong> : 27 smartphones, 6 marques, filtres multi-attributs sans doublons ;</li>
<li><strong>Stock dynamique</strong> : lecture temps réel et décrémentation à la commande ;</li>
<li><strong>Panier &amp; comptes</strong> : synchro session PHP ↔ <code>t_panier</code>, mots de passe hachés ;</li>
<li><strong>Administration</strong> : gestion des rôles (<code>guest</code> à <code>admin</code>).</li>
</ul>

</div>
<div class="phone">
<img src="../www/media/images/iPhone.png" alt="Smartphone du catalogue PhoneShop">
<p style="text-align:center; color:#64748b; font-size:0.65em; margin:6px 0 0; white-space:nowrap;"><code>http://localhost:8080</code></p>
</div>
</div>

---

## Déploiement et commandes utilisées

<div class="flow">
<div class="step"><b>1. Préparer</b><span class="small">Cloner le dépôt et créer <code>.env</code></span></div>
<span class="arrow">→</span>
<div class="step"><b>2. Lancer</b><span class="small"><code>podman-compose up -d</code></span></div>
<span class="arrow">→</span>
<div class="step"><b>3. Vérifier</b><span class="small"><code>podman ps</code> &amp; <code>logs</code></span></div>
<span class="arrow">→</span>
<div class="step"><b>4. Tester</b><span class="small"><code>localhost:8080</code></span></div>
</div>

<div class="two-col" style="margin-top: 12px;">
<div class="panel">
<h3>Commandes courantes</h3>
<div class="codebox" style="margin-top:6px;">podman-compose up -d --build  # build + start
podman ps                     # état des conteneurs
podman network inspect &lt;net&gt;  # inspecter les IP
podman-compose down           # arrêter la stack</div>
</div>
<div class="panel">
<h3>Reproductibilité</h3>
<p>Toute l'infrastructure tient dans <code>Dockerfile</code>, <code>docker-compose.yml</code> et <code>init.sql</code>.</p>
<p class="small">Déployable sans installer Apache, PHP ou MariaDB sur la machine hôte.</p>
</div>
</div>

---

## Défis rencontrés et solutions

<div class="two-col" style="gap: 16px;">
<div class="panel">
<h3>1. Verrouillage d'APT sur Ubuntu</h3>
<p><strong>Problème :</strong> <code>unattended-upgrades</code> bloquait l'installation de Podman.</p>
<p><strong>Solution :</strong> <code>sudo systemctl stop unattended-upgrades</code> avant <code>apt install</code>.</p>
</div>
<div class="panel">
<h3>2. Connexion PHP ↔ MariaDB</h3>
<p><strong>Problème :</strong> <code>localhost</code> dans un conteneur pointe sur lui-même.</p>
<p><strong>Solution :</strong> utiliser <code>$host = 'db'</code> via le DNS interne de Compose.</p>
</div>
<div class="panel">
<h3>3. Droits SQL automatisés</h3>
<p><strong>Problème :</strong> l'app utilise <code>site_user</code>, pas <code>root</code>.</p>
<p><strong>Solution :</strong> ajout de <code>CREATE USER</code> et <code>GRANT</code> à la fin de <code>init.sql</code>.</p>
</div>
<div class="panel">
<h3>4. Pilotes PHP manquants</h3>
<p><strong>Problème :</strong> erreur PDO avec l'image PHP de base.</p>
<p><strong>Solution :</strong> installation de <code>pdo_mysql</code> et <code>mysqli</code> dans le <code>Dockerfile</code>.</p>
</div>
</div>

---

## Apprentissages et améliorations futures

<div class="two-col">
<div class="panel">
<h3>Apprentissages clés</h3>
<ul>
<li><strong>Infrastructure as Code</strong> : décrire la stack en YAML ;</li>
<li><strong>Réseaux &amp; ports</strong> : distinguer ports publiés et réseau bridge interne ;</li>
<li><strong>Persistance</strong> : séparer cycle de vie des conteneurs et volumes ;</li>
<li><strong>Diagnostic</strong> : inspecter logs et réseaux.</li>
</ul>
</div>
<div class="panel">
<h3>Pistes d'amélioration</h3>
<ul>
<li>Déclarer <code>networks:</code> avec un sous-réseau fixe dans <code>docker-compose.yml</code> ;</li>
<li>Ajouter un <code>healthcheck</code> sur <code>db</code> ;</li>
<li>Externaliser les identifiants de <code>db.php</code> via variables d'environnement ;</li>
<li>Retirer le port <code>3306</code> exposé sur l'hôte en production et migrer vers PHP 8.2+.</li>
</ul>
</div>
</div>

<p class="quote" style="margin-top: 14px;">La conteneurisation transforme un projet multi-couches en une stack portable, isolée et déployable en quelques secondes.</p>

---

<!-- _class: hero -->

<p class="eyebrow">Merci pour votre attention</p>

# Questions ?

<p class="lead">P_Bulle Docker Network Toolkit · PhoneShop (Podman &amp; MariaDB)</p>

<br>

**Maltsev Petro** · ETML · 2026
