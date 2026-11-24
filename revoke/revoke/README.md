# CyberShield — Société de cybersécurité

Application web complète (PHP + MySQL) avec une démo interactive autonome en HTML/CSS/JS, et un business plan détaillé. Projet basé à **Tunis, Centre Urbain**.

**Réalisé par : Siwar Hbibi & Marwa Mejri — Classe 2TSGI 2**

---

## 📁 Contenu du dépôt

```
revoke/
├── landing.html                 ← Landing page claire et autonome (à ouvrir directement)
├── README.md                    ← Ce guide
├── BUSINESS_PLAN.md             ← Business plan complet (idée, financement, local, équipe, budget, prévisions…)
├── cybershield/                 ← Application PHP/MySQL (à placer dans htdocs/)
│   ├── database.sql             ← Schéma + données de démonstration
│   ├── config/database.php      ← Connexion PDO + configuration
│   ├── includes/                ← Auth, helpers, layout, moteur CRUD générique
│   ├── assets/                  ← CSS dark navy/cyan, JS, logo et illustrations SVG
│   ├── index.php                ← Page d'accueil publique
│   ├── login.php / register.php / logout.php
│   ├── dashboard.php            ← Statistiques + graphiques (par rôle)
│   ├── clients.php, employees.php, devices.php, interventions.php,
│   │   alerts.php, reports.php, services.php, users.php   ← CRUD Admin
│   ├── my-interventions.php     ← Espace Technicien
│   ├── my-requests.php          ← Espace Client
│   └── profile.php
└── demo/                        ← Démo autonome (aucune installation)
    ├── index.html
    ├── css/style.css
    ├── js/app.js
    └── *.svg
```

---

## ⚡ Essayer tout de suite (démo sans installation)

1. Ouvrez **`demo/index.html`** dans un navigateur (double-clic).
2. Sur l'écran de connexion, cliquez sur l'un des **3 boutons de comptes de démonstration** puis sur « Se connecter ».
3. Explorez : tableau de bord, CRUD, recherche, ajout/modification/suppression, demandes client, notes technicien.

> La démo stocke ses données dans `localStorage`. Le bouton **« Réinitialiser les données de démo »** restaure le jeu de données initial.

Comptes :

| Rôle | E-mail | Mot de passe |
|---|---|---|
| **Admin** | `admin@cybershield.tn` | `Admin123!` |
| **Technicien** | `tech@cybershield.tn` | `Tech123!` |
| **Client** | `client@cybershield.tn` | `Client123!` |

---

## 🖥️ Installer l'application PHP/MySQL (XAMPP)

### 1. Prérequis

- **XAMPP** (Windows / macOS / Linux) avec **PHP 7.4+** et **MySQL/MariaDB**.
- Un navigateur récent.

### 2. Installation

1. Copiez le dossier **`cybershield/`** dans le dossier `htdocs` de XAMPP.
   - Windows : `C:\xampp\htdocs\cybershield`
   - macOS : `/Applications/XAMPP/xamppfiles/htdocs/cybershield`
   - Le nom du dossier peut être changé : les liens s'adaptent automatiquement.
2. Démarrez **Apache** et **MySQL** dans le panneau de contrôle XAMPP.
3. Ouvrez **phpMyAdmin** : `http://localhost/phpmyadmin`.
4. Cliquez sur **Importer** → choisissez `cybershield/database.sql` → **Envoyer**.
   - La base `cybershield_db` est créée automatiquement avec toutes les tables et les données de test.
5. Ouvrez l'application : **`http://localhost/cybershield/`**.

### 3. Paramètres de connexion

Par défaut dans `config/database.php` :

```php
DB_HOST = 'localhost'
DB_NAME = 'cybershield_db'
DB_USER = 'root'
DB_PASS = ''   // mot de passe vide par défaut sous XAMPP
```

Si vous avez mis un mot de passe à `root`, modifiez `DB_PASS`.

### 4. Comptes de démonstration (identiques à la démo)

| Rôle | E-mail | Mot de passe |
|---|---|---|
| Admin (Siwar Hbibi) | `admin@cybershield.tn` | `Admin123!` |
| Technicien (Marwa Mejri) | `tech@cybershield.tn` | `Tech123!` |
| Technicien (Karim Slimani) | `tech2@cybershield.tn` | `Tech123!` |
| Client (Nadia Ferjani) | `client@cybershield.tn` | `Client123!` |
| Client (Mehdi Trabelsi) | `client2@cybershield.tn` | `Client123!` |

> Les mots de passe sont stockés en **bcrypt** (`password_hash()` / `password_verify()`). L'inscription publique crée uniquement des comptes **Client** ; l'admin crée les comptes **Admin / Technicien** depuis « Utilisateurs ».

---

## ✅ Fonctionnalités

### Public
- Page d'accueil avec présentation, statistiques, services et appel à l'action.
- Inscription (client) avec validation et création automatique de la fiche client.
- Connexion sécurisée par session, avec gestion des erreurs et roles.

### Admin
- Tableau de bord global : 4 indicateurs, graphique des statuts, évolution 6 mois, gravité des alertes.
- CRUD complet avec **recherche** sur : clients, employés, appareils, interventions, alertes, rapports, services et comptes utilisateurs.
- Création d'un utilisateur technicien → crée automatiquement la fiche employée ; création d'un client → crée automatiquement la fiche client.
- Attribution des interventions, planification, priorisation.

### Technicien
- Tableau de bord personnel (ses interventions, à faire, en cours, terminées).
- Liste filtrable et recherchable de ses interventions.
- Mise à jour du **statut**, de la **date de fin** et des **notes d'intervention**.
- Consultation et mise à jour des alertes ; lecture des rapports.

### Client
- Tableau de bord personnel (demandes, en cours, terminées, rapports).
- Dépôt d'une **demande d'intervention** (titre, description, appareil, priorité).
- Suivi d'avancement avec **timeline** et note du technicien.
- Consultation de ses rapports et de services (lecture seule).
- Modification de son profil.

### Technique
- Interface **Modern Dark** : navy, bleu foncé, cyan — responsive mobile.
- Logo SVG original et illustration hero SVG (aucune image externe).
- Graphiques **SVG générés côté serveur/côté JS** : aucune dépendance CDN, fonctionne hors ligne.
- Requêtes préparées (PDO) et échappement HTML sur toutes les sorties.
- Moteur CRUD générique documenté dans `includes/crud.php`.

---

## 🚀 Déploiement sur Render

Le repo contient un `Dockerfile` à la racine. Render peut donc le déployer directement :

| Réglage Render | Valeur |
|---|---|
| Runtime | **Docker** |
| Dockerfile Path | `./Dockerfile` |
| Docker Build Context | `.` |
| Root Directory | *(vide)* |
| Branch | `main` |

La base **SQLite est fournie** dans `cybershield/database.sqlite` : aucune base externe n'est nécessaire. Le conteneur écoute automatiquement sur le port `$PORT` fourni par Render.

Pour utiliser un vrai MySQL externe à la place, définir les variables d'environnement :

```
DB_DRIVER=mysql
DB_HOST=...
DB_NAME=cybershield_db
DB_USER=...
DB_PASS=...
```

> Pour régénérer le fichier SQLite après une modification de `database.sql` : `python3 scripts/build_sqlite.py`.

⚠️ Vérifiez que les fichiers du projet sont bien **à la racine du repo GitHub** (pas dans un sous-dossier), sinon le build Docker échoue.

---

## 🧩 Dépannage

| Symptôme | Solution |
|---|---|
| « Connexion à la base de données impossible » | Vérifiez que MySQL est démarré et que `database.sql` a bien été importé. |
| Page blanche PHP | Activez `display_errors` dans php.ini ou regardez le log d'erreurs Apache. |
| Mot de passe refusé | Vérifiez la casse : `Admin123!`, `Tech123!`, `Client123!`. |
| Liens cassés | Le dossier doit être servi par Apache (pas ouvert en `file://`). |
| Démo qui ne se remet pas à zéro | Cliquez sur « Réinitialiser les données de démo » sur l'écran de connexion. |

---

## 📄 Business Plan

Le fichier **`BUSINESS_PLAN.md`** contient : genèse de l'idée, étude de marché, équipe, localisation et décoration, équipements, services et tarifs, organisation, marketing, plan de financement, prévisions financières sur 3 ans, seuil de rentabilité, risques, calendrier et KPI.

---

*CyberShield — Siwar Hbibi & Marwa Mejri — 2TSGI 2 — Novembre 2026*
