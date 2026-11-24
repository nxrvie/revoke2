-- ============================================================
-- CyberShield - Base de données MySQL
-- Projet : Siwar Hbibi & Marwa Mejri - Classe 2TSGI 2
-- Importable directement dans phpMyAdmin (XAMPP)
-- ============================================================

CREATE DATABASE IF NOT EXISTS cybershield_db
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cybershield_db;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS reports;
DROP TABLE IF EXISTS alerts;
DROP TABLE IF EXISTS interventions;
DROP TABLE IF EXISTS devices;
DROP TABLE IF EXISTS employees;
DROP TABLE IF EXISTS clients;
DROP TABLE IF EXISTS services;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

-- ---------- Utilisateurs / comptes ----------
CREATE TABLE users (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  nom        VARCHAR(100) NOT NULL,
  prenom     VARCHAR(100) NOT NULL,
  email      VARCHAR(191) NOT NULL UNIQUE,
  password   VARCHAR(255) NOT NULL,
  role       ENUM('admin','technicien','client') NOT NULL DEFAULT 'client',
  telephone  VARCHAR(30),
  statut     ENUM('actif','inactif') NOT NULL DEFAULT 'actif',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------- Clients ----------
CREATE TABLE clients (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  user_id    INT NULL,
  nom        VARCHAR(150) NOT NULL,
  entreprise VARCHAR(150),
  email      VARCHAR(191),
  telephone  VARCHAR(30),
  adresse    VARCHAR(255),
  secteur    VARCHAR(100),
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------- Employés / techniciens ----------
CREATE TABLE employees (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  user_id       INT NULL,
  nom           VARCHAR(100) NOT NULL,
  prenom        VARCHAR(100) NOT NULL,
  poste         VARCHAR(100),
  specialite    VARCHAR(150),
  email         VARCHAR(191),
  telephone     VARCHAR(30),
  statut        ENUM('actif','inactif') NOT NULL DEFAULT 'actif',
  date_embauche DATE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------- Appareils / actifs ----------
CREATE TABLE devices (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  client_id    INT NOT NULL,
  nom          VARCHAR(120) NOT NULL,
  type         VARCHAR(80),
  ip_adresse   VARCHAR(60),
  os           VARCHAR(80),
  localisation VARCHAR(120),
  statut       ENUM('actif','en_maintenance','inactif','compromis') NOT NULL DEFAULT 'actif',
  created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------- Interventions ----------
CREATE TABLE interventions (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  titre         VARCHAR(180) NOT NULL,
  description   TEXT,
  client_id     INT NOT NULL,
  device_id     INT NULL,
  technicien_id INT NULL,
  priorite      ENUM('faible','moyenne','haute','critique') NOT NULL DEFAULT 'moyenne',
  statut        ENUM('en_attente','planifie','en_cours','termine','annule') NOT NULL DEFAULT 'en_attente',
  notes         TEXT,
  date_demande  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  date_planifiee DATETIME NULL,
  date_fin      DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------- Alertes de sécurité ----------
CREATE TABLE alerts (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  titre       VARCHAR(180) NOT NULL,
  description TEXT,
  gravite     ENUM('faible','moyenne','haute','critique') NOT NULL DEFAULT 'moyenne',
  device_id   INT NULL,
  client_id   INT NULL,
  statut      ENUM('ouverte','en_cours','resolue') NOT NULL DEFAULT 'ouverte',
  created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------- Rapports ----------
CREATE TABLE reports (
  id              INT AUTO_INCREMENT PRIMARY KEY,
  titre           VARCHAR(180) NOT NULL,
  intervention_id INT NULL,
  client_id       INT NULL,
  technicien_id   INT NULL,
  contenu         TEXT,
  created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------- Services ----------
CREATE TABLE services (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  nom         VARCHAR(120) NOT NULL,
  description TEXT,
  prix        DECIMAL(10,2) DEFAULT 0,
  icone       VARCHAR(50) DEFAULT 'shield',
  active      TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- DONNÉES DE DÉMONSTRATION
-- Mots de passe (bcrypt) : Admin123! / Tech123! / Client123!
-- ============================================================

INSERT INTO users (id, nom, prenom, email, password, role, telephone, statut, created_at) VALUES
(1, 'Hbibi', 'Siwar', 'admin@cybershield.tn',   '$2y$10$e.JY5NVLda5FcBFZYtWDpOBYco0M8NGr07WPCPEvmI3bfiFKjZ2aS', 'admin',      '+216 98 112 233', 'actif', '2026-01-10 09:00:00'),
(2, 'Mejri', 'Marwa',  'tech@cybershield.tn',   '$2y$10$V3qH9o7SCthOZ0nWomAKRu9xDiDuGCQjzGu56N.32weBi2FYDwh8W', 'technicien', '+216 96 445 120', 'actif', '2026-01-12 10:30:00'),
(3, 'Slimani', 'Karim','tech2@cybershield.tn',  '$2y$10$V3qH9o7SCthOZ0nWomAKRu9xDiDuGCQjzGu56N.32weBi2FYDwh8W', 'technicien', '+216 94 220 118', 'actif', '2026-02-03 08:15:00'),
(4, 'Ferjani', 'Nadia','client@cybershield.tn', '$2y$10$uRBik.Gft8iN0IH0f1.bmegfQN.YIowoJsWSe6A4x5mgusHjcPPRy', 'client',     '+216 71 330 909', 'actif', '2026-03-18 14:20:00'),
(5, 'Trabelsi', 'Mehdi','client2@cybershield.tn','$2y$10$uRBik.Gft8iN0IH0f1.bmegfQN.YIowoJsWSe6A4x5mgusHjcPPRy','client',      '+216 93 550 671', 'actif', '2026-05-06 11:05:00');

INSERT INTO clients (id, user_id, nom, entreprise, email, telephone, adresse, secteur, created_at) VALUES
(1, 4, 'Nadia Ferjani',  'Atlas Telecom',   'nadia@atlastelecom.tn',  '+216 71 330 909', '12 Av. Habib Bourguiba, Tunis',   'Télécommunications', '2026-03-18 14:20:00'),
(2, 5, 'Mehdi Trabelsi', 'Sfax Logistics',  'mehdi@sfaxlog.tn',       '+216 93 550 671', 'Zone Industrielle, Sfax',        'Logistique',         '2026-05-06 11:05:00'),
(3, NULL, 'Leïla Bouzid','Banque Sahara',   'leila@banquesahara.tn',  '+216 71 120 440', '45 Rue de la République, Tunis', 'Banque',             '2026-04-02 09:40:00'),
(4, NULL, 'Amine Gharbi', 'MedTech Solutions','amine@medtech.tn',     '+216 52 660 310', 'Technopole El Ghazela, Ariana',  'Santé / Tech',       '2026-06-14 16:10:00');

INSERT INTO employees (id, user_id, nom, prenom, poste, specialite, email, telephone, statut, date_embauche) VALUES
(1, 2, 'Mejri',   'Marwa',  'Technicienne senior', 'Pentest & audit',        'tech@cybershield.tn',  '+216 96 445 120', 'actif',  '2026-01-12'),
(2, 3, 'Slimani', 'Karim',  'Technicien réseau',    'Firewall & architecture','tech2@cybershield.tn', '+216 94 220 118', 'actif',  '2026-02-03'),
(3, NULL, 'Kallel','Inès',  'Analyste SOC',         'SIEM & détection',       'ines@cybershield.tn',  '+216 90 778 201', 'actif',  '2026-03-01'),
(4, NULL, 'Ben Ali','Youssef','Consultant',         'Conformité & RGPD',      'youssef@cybershield.tn','+216 97 334 552', 'inactif','2025-11-20');

INSERT INTO devices (id, client_id, nom, type, ip_adresse, os, localisation, statut, created_at) VALUES
(1, 1, 'SRV-ATLAS-01',   'Serveur',      '192.168.10.5',  'Ubuntu Server 24.04', 'Salle serveur Tunis', 'actif',          '2026-03-20 10:00:00'),
(2, 1, 'PC-FINANCE-07',  'Poste de travail','192.168.10.47','Windows 11 Pro',    'Bureau Finance',      'en_maintenance', '2026-04-11 08:30:00'),
(3, 1, 'FW-ATLAS-EDGE',  'Pare-feu',     '192.168.10.1',  'FortiOS 7.4',         'DMZ',                 'actif',          '2026-03-22 12:00:00'),
(4, 2, 'SRV-SFAX-DB',    'Serveur',      '10.0.0.12',     'Debian 12',           'Datacenter Sfax',     'compromis',      '2026-07-02 07:45:00'),
(5, 2, 'PC-LOG-12',      'Poste de travail','10.0.0.88',  'Windows 10',          'Entrepôt',            'actif',          '2026-05-10 13:15:00'),
(6, 3, 'SW-CORE-BS',     'Switch',       '172.16.0.2',    'Cisco IOS',           'Armoire réseau',      'actif',          '2026-04-05 09:20:00'),
(7, 4, 'PC-MED-03',      'Poste de travail','172.20.1.33', 'Windows 11 Pro',     'Laboratoire',         'actif',          '2026-06-16 15:00:00');

INSERT INTO interventions (id, titre, description, client_id, device_id, technicien_id, priorite, statut, notes, date_demande, date_planifiee, date_fin) VALUES
(1, 'Audit de sécurité réseau',        'Audit complet du réseau interne et des accès distants.',        1, 1, 1, 'haute',    'termine',    'Audit terminé, 3 failles critiques corrigées.', '2026-07-04 09:00:00', '2026-07-08 09:00:00', '2026-07-10 17:00:00'),
(2, 'Analyse d''une alerte intrusion', 'Investigation sur tentative de connexion suspecte sur le serveur.',2, 4, 2, 'critique','en_cours', 'Analyse des logs en cours, IP bloquée provisoirement.', '2026-11-10 08:30:00', '2026-11-10 10:00:00', NULL),
(3, 'Mise à jour pare-feu',            'Mise à jour des règles du pare-feu périmétrique.',             1, 3, 2, 'moyenne',  'planifie',   '', '2026-11-12 14:00:00', '2026-11-20 09:00:00', NULL),
(4, 'Test de pénétration applicatif',  'Test d''intrusion sur l''application métier.',               4, 7, 1, 'haute',    'en_attente', '', '2026-11-15 11:20:00', NULL, NULL),
(5, 'Réparation poste infecté',        'Nettoyage et réinstallation du poste finance.',              1, 2, 1, 'haute',    'en_cours',   'Antivirus mis à jour, analyse en cours.', '2026-11-16 07:50:00', '2026-11-17 08:00:00', NULL),
(6, 'Configuration sauvegardes',       'Mise en place d''un plan de sauvegarde automatique.',        2, 5, 2, 'moyenne',  'termine',    'Sauvegardes quotidiennes actives.', '2026-08-01 10:00:00', '2026-08-03 10:00:00', '2026-08-04 12:30:00'),
(7, 'Audit conformité RGPD',           'Vérification de la conformité des traitements de données.',   3, 6, 4, 'faible',   'en_attente', '', '2026-11-18 15:45:00', NULL, NULL),
(8, 'Renforcement mot de passe',       'Politique de mots de passe forts sur tous les comptes.',      1, 1, 1, 'moyenne',  'termine',    'Politique appliquée à 42 comptes.', '2026-09-05 09:10:00', '2026-09-07 09:00:00', '2026-09-07 16:00:00'),
(9, 'Installation EDR',                'Déploiement d''un antivirus nouvelle génération.',          4, 7, 2, 'haute',    'planifie',   '', '2026-11-19 08:00:00', '2026-11-25 09:00:00', NULL),
(10,'Examen des journaux SIEM',        'Revue hebdomadaire des événements de sécurité.',             3, 6, 3, 'faible',   'termine',    'Aucune anomalie détectée.', '2026-10-20 10:00:00', '2026-10-20 11:00:00', '2026-10-20 13:00:00');

INSERT INTO alerts (id, titre, description, gravite, device_id, client_id, statut, created_at) VALUES
(1, 'Tentative de brute force SSH',   'Plus de 200 connexions échouées en 10 minutes.',        'critique', 1, 1, 'en_cours', '2026-11-18 22:14:00'),
(2, 'Trafic réseau anormal',          'Pic de trafic sortant détecté vers une IP inconnue.',   'haute',    4, 2, 'ouverte',  '2026-11-19 03:32:00'),
(3, 'Logiciel non autorisé détecté',  'Installation d''un logiciel hors politique SIEM.',     'moyenne',  2, 1, 'resolue',  '2026-11-14 10:20:00'),
(4, 'Certificat SSL expiré',          'Le certificat du portail client arrive à expiration.',  'moyenne',  3, 1, 'ouverte',  '2026-11-17 08:00:00'),
(5, 'Compte à privilèges inactif',     'Compte admin sans activité depuis 90 jours.',          'faible',   6, 3, 'resolue',  '2026-11-10 12:45:00'),
(6, 'Malware bloqué sur poste',       'Menace bloquée par l''antivirus sur le poste logistique.','haute',  5, 2, 'en_cours', '2026-11-19 06:18:00'),
(7, 'Port suspect ouvert',            'Le port 3389 est exposé sur Internet.',                 'critique', 4, 2, 'ouverte',  '2026-11-19 07:05:00'),
(8, 'Échec de sauvegarde',            'La tâche de sauvegarde nocturne a échoué.',             'moyenne',  7, 4, 'ouverte',  '2026-11-18 02:00:00');

INSERT INTO reports (id, titre, intervention_id, client_id, technicien_id, contenu, created_at) VALUES
(1, 'Rapport audit réseau Atlas Telecom', 1, 1, 1, 'Synthèse : 3 failles critiques, 7 moyennes et 12 recommandations. Toutes les failles critiques ont été corrigées et validées.', '2026-07-10 17:30:00'),
(2, 'Rapport de sauvegarde Sfax Logistics',6, 2, 2, 'Plan de sauvegarde quotidien opérationnel. Restauration de test réussie. Prochaine revue dans 6 mois.', '2026-08-04 13:00:00'),
(3, 'Rapport durcissement des mots de passe',8, 1, 1, 'Politique de mots de passe renforcée appliquée sur 42 comptes. 5 comptes faibles ont été désactivés.', '2026-09-07 16:30:00'),
(4, 'Rapport de surveillance SIEM',       10, 3, 3, 'Revue des journaux : aucun événement critique. 14 événements mineurs analysés et classés sans suite.', '2026-10-20 14:00:00'),
(5, 'Rapport d''analyse intrusion',        2, 2, 2, 'Analyse en cours : tentative d''intrusion depuis une IP étrangère. Des mesures de confinement ont été appliquées.', '2026-11-11 09:15:00');

INSERT INTO services (id, nom, description, prix, icone, active) VALUES
(1, 'Audit de sécurité',        'Évaluation complète de votre infrastructure et de vos applications.',  2500.00, 'search', 1),
(2, 'Test de pénétration',      'Simulation d''attaques pour détecter les vulnérabilités exploitables.',  3500.00, 'target', 1),
(3, 'Surveillance 24/7 (SOC)',  'Détection et réponse aux incidents en temps réel, 7j/7.',              1800.00, 'eye',    1),
(4, 'Gestion des incidents',    'Intervention d''urgence en cas d''attaque ou de compromission.',        1200.00, 'alert',  1),
(5, 'Conformité & RGPD',        'Accompagnement juridique et technique vers la conformité.',             2000.00, 'document',1),
(6, 'Formation du personnel',   'Sensibilisation aux risques cyber et aux bonnes pratiques.',            900.00, 'users',  1);
