/* ============================================================
   CyberShield — Démo interactive (HTML/CSS/JS, données localStorage)
   Siwar Hbibi & Marwa Mejri — 2TSGI 2
   ============================================================ */
(function () {
    'use strict';

    const KEY = 'cybershield_demo_db';
    const SKEY = 'cybershield_demo_session';
    const app = document.getElementById('app');

    const ICONS = {
        shield: '<path d="M12 2 4 5v6c0 5 3.4 9.4 8 11 4.6-1.6 8-6 8-11V5l-8-3Z"/><path d="m9 12 2 2 4-4"/>',
        home: '<path d="m3 10 9-7 9 7v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M9 21v-7h6v7"/>',
        users: '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>',
        user: '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
        device: '<rect x="2" y="4" width="20" height="14" rx="2"/><path d="M8 21h8M12 18v3"/>',
        wrench: '<path d="M14.7 6.3a4 4 0 0 0-5.4 5.4L3 18l3 3 6.3-6.3a4 4 0 0 0 5.4-5.4l-3 3-3-3z"/>',
        alert: '<path d="M12 9v4M12 17h.01"/><path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z"/>',
        report: '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M8 13h8M8 17h5"/>',
        settings: '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.9l.1.1-2.8 2.8-.1-.1a1.7 1.7 0 0 0-1.9-.3 1.7 1.7 0 0 0-1 1.6V21h-4v-.1A1.7 1.7 0 0 0 8.9 19a1.7 1.7 0 0 0-1.9.3l-.1.1-2.8-2.8.1-.1a1.7 1.7 0 0 0 .3-1.9 1.7 1.7 0 0 0-1.6-1H3v-4h.1A1.7 1.7 0 0 0 5 8.9a1.7 1.7 0 0 0-.3-1.9l-.1-.1 2.8-2.8.1.1a1.7 1.7 0 0 0 1.9.3 1.7 1.7 0 0 0 1-1.6V3h4v.1a1.7 1.7 0 0 0 1 1.6 1.7 1.7 0 0 0 1.9-.3l.1-.1 2.8 2.8-.1.1a1.7 1.7 0 0 0-.3 1.9 1.7 1.7 0 0 0 1.6 1H21v4h-.1a1.7 1.7 0 0 0-1.5 1z"/>',
        logout: '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/>',
        search: '<circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/>',
        plus: '<path d="M12 5v14M5 12h14"/>',
        edit: '<path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/>',
        trash: '<path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6M10 11v5M14 11v5"/>',
        eye: '<path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/>',
        chart: '<path d="M3 3v18h18M7 16v-5M12 16V7M17 16v-3"/>',
        lock: '<rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/>',
        check: '<path d="m5 12 4 4L19 6"/>',
        clock: '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        arrow: '<path d="M5 12h14M13 6l6 6-6 6"/>',
        menu: '<path d="M3 6h18M3 12h18M3 18h18"/>',
        spark: '<path d="m12 2 1.8 6.2L20 10l-6.2 1.8L12 18l-1.8-6.2L4 10l6.2-1.8z"/>'
    };

    const ROLE_LABEL = { admin: 'Admin', technicien: 'Technicien', client: 'Client' };
    const STATUT_LABEL = {
        actif: 'Actif', inactif: 'Inactif', ouverte: 'Ouverte', en_attente: 'En attente',
        planifie: 'Planifiée', en_cours: 'En cours', termine: 'Terminée', annule: 'Annulée',
        resolue: 'Résolue', faible: 'Faible', moyenne: 'Moyenne', haute: 'Haute',
        critique: 'Critique', en_maintenance: 'En maintenance', compromis: 'Compromis',
        '1': 'Oui', '0': 'Non'
    };

    const SEED = {
        users: [
            { id: 1, nom: 'Hbibi', prenom: 'Siwar', email: 'admin@cybershield.tn', password: 'Admin123!', role: 'admin', telephone: '+216 98 112 233', statut: 'actif', created_at: '2026-01-10 09:00:00' },
            { id: 2, nom: 'Mejri', prenom: 'Marwa', email: 'tech@cybershield.tn', password: 'Tech123!', role: 'technicien', telephone: '+216 96 445 120', statut: 'actif', created_at: '2026-01-12 10:30:00' },
            { id: 3, nom: 'Slimani', prenom: 'Karim', email: 'tech2@cybershield.tn', password: 'Tech123!', role: 'technicien', telephone: '+216 94 220 118', statut: 'actif', created_at: '2026-02-03 08:15:00' },
            { id: 4, nom: 'Ferjani', prenom: 'Nadia', email: 'client@cybershield.tn', password: 'Client123!', role: 'client', telephone: '+216 71 330 909', statut: 'actif', created_at: '2026-03-18 14:20:00' },
            { id: 5, nom: 'Trabelsi', prenom: 'Mehdi', email: 'client2@cybershield.tn', password: 'Client123!', role: 'client', telephone: '+216 93 550 671', statut: 'actif', created_at: '2026-05-06 11:05:00' }
        ],
        clients: [
            { id: 1, user_id: 4, nom: 'Nadia Ferjani', entreprise: 'Atlas Telecom', email: 'nadia@atlastelecom.tn', telephone: '+216 71 330 909', secteur: 'Télécommunications', adresse: '12 Av. Habib Bourguiba, Tunis', created_at: '2026-03-18 14:20:00' },
            { id: 2, user_id: 5, nom: 'Mehdi Trabelsi', entreprise: 'Sfax Logistics', email: 'mehdi@sfaxlog.tn', telephone: '+216 93 550 671', secteur: 'Logistique', adresse: 'Zone Industrielle, Sfax', created_at: '2026-05-06 11:05:00' },
            { id: 3, user_id: null, nom: 'Leïla Bouzid', entreprise: 'Banque Sahara', email: 'leila@banquesahara.tn', telephone: '+216 71 120 440', secteur: 'Banque', adresse: '45 Rue de la République, Tunis', created_at: '2026-04-02 09:40:00' },
            { id: 4, user_id: null, nom: 'Amine Gharbi', entreprise: 'MedTech Solutions', email: 'amine@medtech.tn', telephone: '+216 52 660 310', secteur: 'Santé / Tech', adresse: 'Technopole El Ghazela, Ariana', created_at: '2026-06-14 16:10:00' }
        ],
        employees: [
            { id: 1, user_id: 2, nom: 'Mejri', prenom: 'Marwa', poste: 'Technicienne senior', specialite: 'Pentest & audit', email: 'tech@cybershield.tn', telephone: '+216 96 445 120', statut: 'actif', date_embauche: '2026-01-12' },
            { id: 2, user_id: 3, nom: 'Slimani', prenom: 'Karim', poste: 'Technicien réseau', specialite: 'Firewall & architecture', email: 'tech2@cybershield.tn', telephone: '+216 94 220 118', statut: 'actif', date_embauche: '2026-02-03' },
            { id: 3, user_id: null, nom: 'Kallel', prenom: 'Inès', poste: 'Analyste SOC', specialite: 'SIEM & détection', email: 'ines@cybershield.tn', telephone: '+216 90 778 201', statut: 'actif', date_embauche: '2026-03-01' },
            { id: 4, user_id: null, nom: 'Ben Ali', prenom: 'Youssef', poste: 'Consultant', specialite: 'Conformité & RGPD', email: 'youssef@cybershield.tn', telephone: '+216 97 334 552', statut: 'inactif', date_embauche: '2025-11-20' }
        ],
        devices: [
            { id: 1, client_id: 1, nom: 'SRV-ATLAS-01', type: 'Serveur', ip_adresse: '192.168.10.5', os: 'Ubuntu Server 24.04', localisation: 'Salle serveur Tunis', statut: 'actif', created_at: '2026-03-20 10:00:00' },
            { id: 2, client_id: 1, nom: 'PC-FINANCE-07', type: 'Poste de travail', ip_adresse: '192.168.10.47', os: 'Windows 11 Pro', localisation: 'Bureau Finance', statut: 'en_maintenance', created_at: '2026-04-11 08:30:00' },
            { id: 3, client_id: 1, nom: 'FW-ATLAS-EDGE', type: 'Pare-feu', ip_adresse: '192.168.10.1', os: 'FortiOS 7.4', localisation: 'DMZ', statut: 'actif', created_at: '2026-03-22 12:00:00' },
            { id: 4, client_id: 2, nom: 'SRV-SFAX-DB', type: 'Serveur', ip_adresse: '10.0.0.12', os: 'Debian 12', localisation: 'Datacenter Sfax', statut: 'compromis', created_at: '2026-07-02 07:45:00' },
            { id: 5, client_id: 2, nom: 'PC-LOG-12', type: 'Poste de travail', ip_adresse: '10.0.0.88', os: 'Windows 10', localisation: 'Entrepôt', statut: 'actif', created_at: '2026-05-10 13:15:00' },
            { id: 6, client_id: 3, nom: 'SW-CORE-BS', type: 'Switch', ip_adresse: '172.16.0.2', os: 'Cisco IOS', localisation: 'Armoire réseau', statut: 'actif', created_at: '2026-04-05 09:20:00' },
            { id: 7, client_id: 4, nom: 'PC-MED-03', type: 'Poste de travail', ip_adresse: '172.20.1.33', os: 'Windows 11 Pro', localisation: 'Laboratoire', statut: 'actif', created_at: '2026-06-16 15:00:00' }
        ],
        interventions: [
            { id: 1, titre: 'Audit de sécurité réseau', description: 'Audit complet du réseau interne et des accès distants.', client_id: 1, device_id: 1, technicien_id: 1, priorite: 'haute', statut: 'termine', notes: 'Audit terminé, 3 failles critiques corrigées.', date_demande: '2026-07-04 09:00:00', date_planifiee: '2026-07-08 09:00:00', date_fin: '2026-07-10 17:00:00' },
            { id: 2, titre: "Analyse d'une alerte intrusion", description: 'Investigation sur tentative de connexion suspecte.', client_id: 2, device_id: 4, technicien_id: 2, priorite: 'critique', statut: 'en_cours', notes: 'Analyse des logs en cours, IP bloquée provisoirement.', date_demande: '2026-11-10 08:30:00', date_planifiee: '2026-11-10 10:00:00', date_fin: '' },
            { id: 3, titre: 'Mise à jour pare-feu', description: 'Mise à jour des règles du pare-feu périmétrique.', client_id: 1, device_id: 3, technicien_id: 2, priorite: 'moyenne', statut: 'planifie', notes: '', date_demande: '2026-11-12 14:00:00', date_planifiee: '2026-11-20 09:00:00', date_fin: '' },
            { id: 4, titre: 'Test de pénétration applicatif', description: "Test d'intrusion sur l'application métier.", client_id: 4, device_id: 7, technicien_id: 1, priorite: 'haute', statut: 'en_attente', notes: '', date_demande: '2026-11-15 11:20:00', date_planifiee: '', date_fin: '' },
            { id: 5, titre: 'Réparation poste infecté', description: 'Nettoyage et réinstallation du poste finance.', client_id: 1, device_id: 2, technicien_id: 1, priorite: 'haute', statut: 'en_cours', notes: 'Antivirus mis à jour, analyse en cours.', date_demande: '2026-11-16 07:50:00', date_planifiee: '2026-11-17 08:00:00', date_fin: '' },
            { id: 6, titre: 'Configuration sauvegardes', description: "Mise en place d'un plan de sauvegarde automatique.", client_id: 2, device_id: 5, technicien_id: 2, priorite: 'moyenne', statut: 'termine', notes: 'Sauvegardes quotidiennes actives.', date_demande: '2026-08-01 10:00:00', date_planifiee: '2026-08-03 10:00:00', date_fin: '2026-08-04 12:30:00' },
            { id: 7, titre: 'Audit conformité RGPD', description: 'Vérification de la conformité des traitements.', client_id: 3, device_id: 6, technicien_id: 4, priorite: 'faible', statut: 'en_attente', notes: '', date_demande: '2026-11-18 15:45:00', date_planifiee: '', date_fin: '' },
            { id: 8, titre: 'Renforcement mot de passe', description: 'Politique de mots de passe forts.', client_id: 1, device_id: 1, technicien_id: 1, priorite: 'moyenne', statut: 'termine', notes: 'Politique appliquée à 42 comptes.', date_demande: '2026-09-05 09:10:00', date_planifiee: '2026-09-07 09:00:00', date_fin: '2026-09-07 16:00:00' },
            { id: 9, titre: 'Installation EDR', description: "Déploiement d'un antivirus nouvelle génération.", client_id: 4, device_id: 7, technicien_id: 2, priorite: 'haute', statut: 'planifie', notes: '', date_demande: '2026-11-19 08:00:00', date_planifiee: '2026-11-25 09:00:00', date_fin: '' },
            { id: 10, titre: 'Examen des journaux SIEM', description: 'Revue hebdomadaire des événements de sécurité.', client_id: 3, device_id: 6, technicien_id: 3, priorite: 'faible', statut: 'termine', notes: 'Aucune anomalie détectée.', date_demande: '2026-10-20 10:00:00', date_planifiee: '2026-10-20 11:00:00', date_fin: '2026-10-20 13:00:00' }
        ],
        alerts: [
            { id: 1, titre: 'Tentative de brute force SSH', description: 'Plus de 200 connexions échouées en 10 minutes.', gravite: 'critique', device_id: 1, client_id: 1, statut: 'en_cours', created_at: '2026-11-18 22:14:00' },
            { id: 2, titre: 'Trafic réseau anormal', description: 'Pic de trafic sortant vers une IP inconnue.', gravite: 'haute', device_id: 4, client_id: 2, statut: 'ouverte', created_at: '2026-11-19 03:32:00' },
            { id: 3, titre: 'Logiciel non autorisé détecté', description: 'Installation hors politique SIEM.', gravite: 'moyenne', device_id: 2, client_id: 1, statut: 'resolue', created_at: '2026-11-14 10:20:00' },
            { id: 4, titre: 'Certificat SSL expiré', description: 'Le certificat du portail client expire bientôt.', gravite: 'moyenne', device_id: 3, client_id: 1, statut: 'ouverte', created_at: '2026-11-17 08:00:00' },
            { id: 5, titre: 'Compte à privilèges inactif', description: 'Compte admin sans activité depuis 90 jours.', gravite: 'faible', device_id: 6, client_id: 3, statut: 'resolue', created_at: '2026-11-10 12:45:00' },
            { id: 6, titre: 'Malware bloqué sur poste', description: 'Menace bloquée par l\'antivirus.', gravite: 'haute', device_id: 5, client_id: 2, statut: 'en_cours', created_at: '2026-11-19 06:18:00' },
            { id: 7, titre: 'Port suspect ouvert', description: 'Le port 3389 est exposé sur Internet.', gravite: 'critique', device_id: 4, client_id: 2, statut: 'ouverte', created_at: '2026-11-19 07:05:00' },
            { id: 8, titre: 'Échec de sauvegarde', description: 'La tâche nocturne a échoué.', gravite: 'moyenne', device_id: 7, client_id: 4, statut: 'ouverte', created_at: '2026-11-18 02:00:00' }
        ],
        reports: [
            { id: 1, titre: 'Rapport audit réseau Atlas Telecom', intervention_id: 1, client_id: 1, technicien_id: 1, contenu: 'Synthèse : 3 failles critiques, 7 moyennes et 12 recommandations. Toutes les failles critiques ont été corrigées.', created_at: '2026-07-10 17:30:00' },
            { id: 2, titre: 'Rapport de sauvegarde Sfax Logistics', intervention_id: 6, client_id: 2, technicien_id: 2, contenu: 'Plan de sauvegarde quotidien opérationnel. Restauration de test réussie.', created_at: '2026-08-04 13:00:00' },
            { id: 3, titre: 'Rapport durcissement des mots de passe', intervention_id: 8, client_id: 1, technicien_id: 1, contenu: 'Politique renforcée appliquée sur 42 comptes. 5 comptes faibles désactivés.', created_at: '2026-09-07 16:30:00' },
            { id: 4, titre: 'Rapport de surveillance SIEM', intervention_id: 10, client_id: 3, technicien_id: 3, contenu: 'Aucun événement critique. 14 événements mineurs analysés.', created_at: '2026-10-20 14:00:00' },
            { id: 5, titre: "Rapport d'analyse intrusion", intervention_id: 2, client_id: 2, technicien_id: 2, contenu: 'Analyse en cours : tentative depuis une IP étrangère. Mesures de confinement appliquées.', created_at: '2026-11-11 09:15:00' }
        ],
        services: [
            { id: 1, nom: 'Audit de sécurité', description: 'Évaluation complète de votre infrastructure et de vos applications.', prix: 2500, icone: 'search', active: '1' },
            { id: 2, nom: 'Test de pénétration', description: "Simulation d'attaques pour détecter les vulnérabilités.", prix: 3500, icone: 'target', active: '1' },
            { id: 3, nom: 'Surveillance 24/7 (SOC)', description: 'Détection et réponse aux incidents en temps réel.', prix: 1800, icone: 'eye', active: '1' },
            { id: 4, nom: 'Gestion des incidents', description: "Intervention d'urgence en cas d'attaque.", prix: 1200, icone: 'alert', active: '1' },
            { id: 5, nom: 'Conformité & RGPD', description: 'Accompagnement vers la conformité.', prix: 2000, icone: 'report', active: '1' },
            { id: 6, nom: 'Formation du personnel', description: 'Sensibilisation aux risques cyber.', prix: 900, icone: 'users', active: '1' }
        ]
    };

    /* ---------------- Helpers ---------------- */
    function clone(o) { return JSON.parse(JSON.stringify(o)); }
    function loadDb() {
        try {
            const d = JSON.parse(localStorage.getItem(KEY));
            if (d && Array.isArray(d.users) && Array.isArray(d.interventions)) return d;
        } catch (e) { /* ignore */ }
        const d = clone(SEED);
        localStorage.setItem(KEY, JSON.stringify(d));
        return d;
    }
    let db = loadDb();
    let session = null;
    try { session = JSON.parse(localStorage.getItem(SKEY)); } catch (e) { session = null; }
    let state = { page: 'dashboard', edit: null, q: '', view: null, flash: null, regError: '' };

    function save() { localStorage.setItem(KEY, JSON.stringify(db)); }
    function setSession(u) {
        session = u ? { id: u.id } : null;
        if (u) localStorage.setItem(SKEY, JSON.stringify(session)); else localStorage.removeItem(SKEY);
    }
    function user() { return session ? db.users.find(u => u.id === session.id) || null : null; }
    function role() { const u = user(); return u ? u.role : null; }
    function nextId(table) { return db[table].reduce((m, r) => Math.max(m, r.id || 0), 0) + 1; }
    function byId(table, id) { return db[table].find(r => r.id === Number(id)); }
    function flash(msg, type) { state.flash = { msg, type: type || 'success' }; }
    function h(s) { return String(s == null ? '' : s).replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c])); }
    function icon(n, size) { return '<svg class="icon" width="' + (size || 20) + '" height="' + (size || 20) + '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">' + (ICONS[n] || ICONS.shield) + '</svg>'; }
    function humanize(v) { return STATUT_LABEL[v] || String(v || '').replace(/_/g, ' '); }
    function badge(v) {
        const map = { actif: 'success', termine: 'success', resolue: 'success', faible: 'success', '1': 'success',
            en_attente: 'warning', moyenne: 'warning', en_maintenance: 'warning',
            planifie: 'purple', en_cours: 'info', ouverte: 'info',
            critique: 'danger', haute: 'danger', compromis: 'danger', annule: 'danger', inactif: 'muted', '0': 'muted' };
        return '<span class="badge badge-' + (map[v] || 'muted') + '">' + h(humanize(v)) + '</span>';
    }
    function fd(v, withTime) {
        if (!v) return '—';
        const parts = String(v).split(' ');
        const d = parts[0].split('-');
        return d[2] + '/' + d[1] + '/' + d[0] + (withTime && parts[1] ? ' ' + parts[1].slice(0, 5) : '');
    }
    function money(v) { return Number(v || 0).toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' DT'; }
    function now() {
        const d = new Date(), p = n => String(n).padStart(2, '0');
        return d.getFullYear() + '-' + p(d.getMonth() + 1) + '-' + p(d.getDate()) + ' ' + p(d.getHours()) + ':' + p(d.getMinutes()) + ':00';
    }
    function initials(u) { return ((u.prenom || '').charAt(0) + (u.nom || '').charAt(0)).toUpperCase(); }
    function myClientId() { const u = user(); const c = u ? db.clients.find(x => x.user_id === u.id) : null; return c ? c.id : 0; }
    function myEmpId() { const u = user(); const e = u ? db.employees.find(x => x.user_id === u.id) : null; return e ? e.id : 0; }

    /* ---------------- Graphiques SVG ---------------- */
    function chartDonut(data) {
        const entries = Object.entries(data).filter(x => x[1] > 0);
        if (!entries.length) return '<p class="muted">Aucune donnée</p>';
        const colors = ['#22d3ee', '#3b82f6', '#f59e0b', '#a855f7', '#ef4444', '#10b981'];
        const total = entries.reduce((s, e) => s + e[1], 0);
        const circ = 2 * Math.PI * 62;
        let off = 0;
        let svg = '<svg viewBox="0 0 180 180" class="donut"><circle cx="90" cy="90" r="62" fill="none" stroke="#152544" stroke-width="26"/>';
        entries.forEach((e, i) => {
            const len = circ * e[1] / total;
            svg += '<circle cx="90" cy="90" r="62" fill="none" stroke="' + colors[i % colors.length] + '" stroke-width="26" stroke-dasharray="' + (len - 2) + ' ' + (circ - len + 2) + '" stroke-dashoffset="' + (-off) + '" transform="rotate(-90 90 90)"/>';
            off += len;
        });
        svg += '<text x="90" y="86" fill="#e6f0ff" font-size="24" font-weight="700" text-anchor="middle">' + total + '</text><text x="90" y="104" fill="#8fa6c8" font-size="11" text-anchor="middle">Total</text></svg>';
        const legend = entries.map((e, i) => '<li><span style="background:' + colors[i % colors.length] + '"></span>' + h(humanize(e[0])) + ' <b>' + e[1] + '</b></li>').join('');
        return '<div class="donut-wrap">' + svg + '<ul class="legend">' + legend + '</ul></div>';
    }
    function chartBar(data) {
        const entries = Object.entries(data);
        if (!entries.length) return '<p class="muted">Aucune donnée</p>';
        const max = Math.max(1, ...entries.map(e => e[1]));
        const w = 560, hh = 220, pad = 34, bw = (w - pad * 2) / entries.length;
        let svg = '<svg viewBox="0 0 ' + w + ' ' + hh + '" class="chart">';
        for (let i = 0; i <= 4; i++) {
            const y = pad + (hh - pad * 2) * i / 4;
            svg += '<line x1="' + pad + '" y1="' + y + '" x2="' + (w - pad) + '" y2="' + y + '" stroke="#1e3257" stroke-dasharray="3 4"/><text x="4" y="' + (y + 4) + '" fill="#8fa6c8" font-size="11">' + Math.round(max * (4 - i) / 4) + '</text>';
        }
        entries.forEach((e, i) => {
            const bh = (hh - pad * 2) * e[1] / max, x = pad + i * bw + bw * .2, y = hh - pad - bh;
            svg += '<rect x="' + x + '" y="' + y + '" width="' + (bw * .6) + '" height="' + bh + '" rx="4" fill="#22d3ee" opacity=".85"/><text x="' + (x + bw * .3) + '" y="' + (y - 6) + '" fill="#e6f0ff" font-size="11" text-anchor="middle">' + e[1] + '</text><text x="' + (x + bw * .3) + '" y="' + (hh - 10) + '" fill="#8fa6c8" font-size="10" text-anchor="middle">' + h(e[0]) + '</text>';
        });
        return svg + '</svg>';
    }

    /* ---------------- Configuration CRUD ---------------- */
    const ENT = {
        clients: {
            title: 'Gestion des clients', item: 'Client', icon: 'users',
            columns: [
                { key: 'id', label: '#' }, { key: 'nom', label: 'Client', main: true },
                { key: 'entreprise', label: 'Entreprise' }, { key: 'email', label: 'E-mail' },
                { key: 'telephone', label: 'Téléphone' }, { key: 'secteur', label: 'Secteur' }
            ],
            search: ['nom', 'entreprise', 'email', 'secteur'],
            fields: [
                { name: 'nom', label: 'Nom du contact', required: true },
                { name: 'entreprise', label: 'Entreprise' }, { name: 'email', label: 'E-mail', type: 'email' },
                { name: 'telephone', label: 'Téléphone' }, { name: 'secteur', label: 'Secteur' },
                { name: 'adresse', label: 'Adresse', full: true }
            ]
        },
        employees: {
            title: 'Gestion des employés', item: 'Employé', icon: 'user',
            columns: [
                { key: 'id', label: '#' }, { key: 'nom', label: 'Nom', main: true },
                { key: 'prenom', label: 'Prénom' }, { key: 'poste', label: 'Poste' },
                { key: 'specialite', label: 'Spécialité' }, { key: 'email', label: 'E-mail' },
                { key: 'statut', label: 'Statut', type: 'badge' }, { key: 'date_embauche', label: 'Embauche', type: 'date' }
            ],
            search: ['nom', 'prenom', 'poste', 'specialite', 'email'],
            fields: [
                { name: 'nom', label: 'Nom', required: true }, { name: 'prenom', label: 'Prénom', required: true },
                { name: 'poste', label: 'Poste' }, { name: 'specialite', label: 'Spécialité' },
                { name: 'email', label: 'E-mail', type: 'email' }, { name: 'telephone', label: 'Téléphone' },
                { name: 'date_embauche', label: "Date d'embauche", type: 'date' },
                { name: 'statut', label: 'Statut', type: 'select', options: [['actif', 'Actif'], ['inactif', 'Inactif']], default: 'actif' },
                { name: 'user_id', label: 'Compte associé', type: 'select', options: () => db.users.map(u => [u.id, u.email]) }
            ]
        },
        devices: {
            title: 'Gestion des appareils', item: 'Appareil', icon: 'device',
            columns: [
                { key: 'id', label: '#' }, { key: 'nom', label: 'Appareil', main: true },
                { key: 'type', label: 'Type' }, { label: 'Client', get: r => (byId('clients', r.client_id) || {}).nom || '—' },
                { key: 'ip_adresse', label: 'Adresse IP' }, { key: 'os', label: 'Système' },
                { key: 'statut', label: 'Statut', type: 'badge' }
            ],
            search: ['nom', 'ip_adresse', 'os'],
            fields: [
                { name: 'nom', label: "Nom de l'appareil", required: true },
                { name: 'client_id', label: 'Client', type: 'select', required: true, options: () => db.clients.map(c => [c.id, c.nom]) },
                { name: 'type', label: 'Type', type: 'select', options: ['Serveur', 'Poste de travail', 'Pare-feu', 'Switch', 'Autre'].map(x => [x, x]) },
                { name: 'ip_adresse', label: 'Adresse IP' }, { name: 'os', label: "Système d'exploitation" },
                { name: 'localisation', label: 'Localisation' },
                { name: 'statut', label: 'Statut', type: 'select', options: [['actif', 'Actif'], ['en_maintenance', 'En maintenance'], ['inactif', 'Inactif'], ['compromis', 'Compromis']], default: 'actif' }
            ]
        },
        interventions: {
            title: 'Toutes les interventions', item: 'Intervention', icon: 'wrench',
            columns: [
                { key: 'id', label: '#' }, { key: 'titre', label: 'Intervention', main: true },
                { label: 'Client', get: r => (byId('clients', r.client_id) || {}).nom || '—' },
                { label: 'Technicien', get: r => { const e = byId('employees', r.technicien_id); return e ? e.prenom + ' ' + e.nom : '—'; } },
                { key: 'priorite', label: 'Priorité', type: 'badge' }, { key: 'statut', label: 'Statut', type: 'badge' },
                { key: 'date_demande', label: 'Demandée le', type: 'date' }
            ],
            search: ['titre', 'description'],
            fields: [
                { name: 'titre', label: 'Titre', required: true, full: true },
                { name: 'description', label: 'Description', type: 'textarea', full: true },
                { name: 'client_id', label: 'Client', type: 'select', required: true, options: () => db.clients.map(c => [c.id, c.nom]) },
                { name: 'device_id', label: 'Appareil', type: 'select', options: () => db.devices.map(d => [d.id, d.nom]) },
                { name: 'technicien_id', label: 'Technicien', type: 'select', options: () => db.employees.map(e => [e.id, e.prenom + ' ' + e.nom]) },
                { name: 'priorite', label: 'Priorité', type: 'select', options: ['faible', 'moyenne', 'haute', 'critique'].map(x => [x, humanize(x)]), default: 'moyenne' },
                { name: 'statut', label: 'Statut', type: 'select', options: ['en_attente', 'planifie', 'en_cours', 'termine', 'annule'].map(x => [x, humanize(x)]), default: 'en_attente' },
                { name: 'date_planifiee', label: 'Date planifiée', type: 'datetime-local' },
                { name: 'date_fin', label: 'Date de fin', type: 'datetime-local' },
                { name: 'notes', label: 'Notes du technicien', type: 'textarea', full: true }
            ]
        },
        alerts: {
            title: 'Alertes de sécurité', item: 'Alerte', icon: 'alert',
            columns: [
                { key: 'id', label: '#' }, { key: 'titre', label: 'Alerte', main: true },
                { label: 'Client', get: r => (byId('clients', r.client_id) || {}).nom || '—' },
                { key: 'gravite', label: 'Gravité', type: 'badge' }, { key: 'statut', label: 'Statut', type: 'badge' },
                { key: 'created_at', label: 'Détectée le', type: 'datetime' }
            ],
            search: ['titre', 'description'],
            fields: [
                { name: 'titre', label: 'Titre', required: true, full: true },
                { name: 'description', label: 'Description', type: 'textarea', full: true },
                { name: 'gravite', label: 'Gravité', type: 'select', options: ['faible', 'moyenne', 'haute', 'critique'].map(x => [x, humanize(x)]), default: 'moyenne' },
                { name: 'statut', label: 'Statut', type: 'select', options: ['ouverte', 'en_cours', 'resolue'].map(x => [x, humanize(x)]), default: 'ouverte' },
                { name: 'client_id', label: 'Client', type: 'select', options: () => db.clients.map(c => [c.id, c.nom]) },
                { name: 'device_id', label: 'Appareil', type: 'select', options: () => db.devices.map(d => [d.id, d.nom]) }
            ]
        },
        reports: {
            title: 'Rapports', item: 'Rapport', icon: 'report',
            columns: [
                { key: 'id', label: '#' }, { key: 'titre', label: 'Rapport', main: true },
                { label: 'Client', get: r => (byId('clients', r.client_id) || {}).nom || '—' },
                { label: 'Intervention', get: r => (byId('interventions', r.intervention_id) || {}).titre || '—' },
                { key: 'created_at', label: 'Créé le', type: 'date' }
            ],
            search: ['titre', 'contenu'],
            fields: [
                { name: 'titre', label: 'Titre', required: true, full: true },
                { name: 'contenu', label: 'Contenu du rapport', type: 'textarea', full: true },
                { name: 'client_id', label: 'Client', type: 'select', options: () => db.clients.map(c => [c.id, c.nom]) },
                { name: 'intervention_id', label: 'Intervention liée', type: 'select', options: () => db.interventions.map(i => [i.id, i.titre]) },
                { name: 'technicien_id', label: 'Technicien', type: 'select', options: () => db.employees.map(e => [e.id, e.prenom + ' ' + e.nom]) }
            ]
        },
        services: {
            title: 'Nos services', item: 'Service', icon: 'shield',
            columns: [
                { key: 'id', label: '#' }, { key: 'nom', label: 'Service', main: true },
                { key: 'description', label: 'Description' }, { key: 'prix', label: 'Tarif', type: 'money' },
                { key: 'active', label: 'Actif', type: 'badge' }
            ],
            search: ['nom', 'description'],
            fields: [
                { name: 'nom', label: 'Nom du service', required: true },
                { name: 'description', label: 'Description', type: 'textarea', full: true },
                { name: 'prix', label: 'Tarif de base (DT)', type: 'number', default: 0 },
                { name: 'icone', label: 'Icône', type: 'select', options: [['shield', 'Bouclier'], ['search', 'Audit'], ['eye', 'Surveillance'], ['alert', 'Alerte'], ['report', 'Document'], ['users', 'Équipe']] },
                { name: 'active', label: 'Service actif', type: 'select', options: [['1', 'Oui'], ['0', 'Non']], default: '1' }
            ]
        },
        users: {
            title: 'Gestion des comptes', item: 'Utilisateur', icon: 'settings',
            columns: [
                { key: 'id', label: '#' }, { key: 'prenom', label: 'Prénom' },
                { key: 'nom', label: 'Nom', main: true }, { key: 'email', label: 'E-mail' },
                { key: 'role', label: 'Rôle', type: 'badge' }, { key: 'statut', label: 'Statut', type: 'badge' },
                { key: 'created_at', label: 'Créé le', type: 'date' }
            ],
            search: ['nom', 'prenom', 'email', 'role'],
            fields: [
                { name: 'nom', label: 'Nom', required: true }, { name: 'prenom', label: 'Prénom', required: true },
                { name: 'email', label: 'E-mail', type: 'email', required: true },
                { name: 'password', label: 'Mot de passe', type: 'password', required: true, hint: 'Laissez vide lors d\'une modification pour conserver le mot de passe.' },
                { name: 'telephone', label: 'Téléphone' },
                { name: 'role', label: 'Rôle', type: 'select', options: [['admin', 'Admin'], ['technicien', 'Technicien'], ['client', 'Client']], default: 'client' },
                { name: 'statut', label: 'Statut', type: 'select', options: [['actif', 'Actif'], ['inactif', 'Inactif']], default: 'actif' }
            ]
        }
    };

    const MENUS = {
        admin: [['dashboard', 'home', 'Tableau de bord'], ['clients', 'users', 'Clients'], ['employees', 'user', 'Employés'], ['devices', 'device', 'Appareils'], ['interventions', 'wrench', 'Interventions'], ['alerts', 'alert', 'Alertes'], ['reports', 'report', 'Rapports'], ['services', 'shield', 'Services'], ['users', 'settings', 'Utilisateurs']],
        technicien: [['dashboard', 'home', 'Tableau de bord'], ['my-interventions', 'wrench', 'Mes interventions'], ['alerts', 'alert', 'Alertes'], ['reports', 'report', 'Rapports']],
        client: [['dashboard', 'home', 'Tableau de bord'], ['my-requests', 'wrench', 'Mes demandes'], ['services', 'shield', 'Nos services'], ['reports', 'report', 'Mes rapports']]
    };

    /* ---------------- Interface ---------------- */
    function flashHtml() {
        if (!state.flash) return '';
        return '<div class="flash flash-' + h(state.flash.type) + '">' + icon(state.flash.type === 'danger' ? 'alert' : 'check', 18) + '<span>' + h(state.flash.msg) + '</span><button data-act="dismiss" aria-label="Fermer">×</button></div>';
    }

    function sidebar(active) {
        const u = user();
        const nav = (MENUS[role()] || []).map(m =>
            '<a href="#" data-nav="' + m[0] + '" class="' + (active === m[0] ? 'active' : '') + '">' + icon(m[1], 19) + '<span>' + h(m[2]) + '</span></a>').join('');
        return '<aside class="sidebar" id="sidebar">' +
            '<a class="brand" href="#" data-nav="dashboard"><img src="logo-mark.svg" alt="" width="38" height="38"><span>Cyber<span>Shield</span></span></a>' +
            '<div class="sidebar-label">MENU PRINCIPAL</div><nav class="side-nav">' + nav + '</nav>' +
            '<div class="sidebar-label">COMPTE</div><nav class="side-nav">' +
            '<a href="#" data-nav="profile" class="' + (active === 'profile' ? 'active' : '') + '">' + icon('user', 19) + '<span>Mon profil</span></a>' +
            '<a href="#" data-act="logout">' + icon('logout', 19) + '<span>Déconnexion</span></a></nav>' +
            '<div class="sidebar-user"><div class="avatar">' + h(initials(u)) + '</div><div><strong>' + h(u.prenom + ' ' + u.nom) + '</strong><small>' + h(humanize(u.role)) + '</small></div></div></aside>';
    }

    function shell(content, title, subtitle, active) {
        const u = user();
        const notif = (role() === 'admin' || role() === 'technicien') ? db.alerts.filter(a => a.statut !== 'resolue').length : 0;
        return '<div class="app">' + sidebar(active) + '<div class="main"><header class="topbar">' +
            '<button class="menu-toggle" data-act="menu">' + icon('menu', 22) + '</button>' +
            '<div class="topbar-title"><h1>' + h(title) + '</h1><p>' + h(subtitle || 'Bienvenue sur votre espace CyberShield') + '</p></div>' +
            '<div class="topbar-right">' + (notif ? '<a class="icon-btn" href="#" data-nav="alerts" title="Alertes">' + icon('alert', 20) + '<span class="dot">' + notif + '</span></a>' : '') +
            '<div class="user-chip"><div class="avatar avatar-sm">' + h(initials(u)) + '</div><div class="user-chip-text"><strong>' + h(u.prenom) + '</strong><small>' + h(humanize(u.role)) + '</small></div></div></div></header>' +
            '<main class="content">' + flashHtml() + content + '</main></div></div>';
    }

    function statCard(label, value, ico, color) {
        return '<div class="stat-card"><div class="stat-top"><span class="stat-label">' + h(label) + '</span><span class="stat-icon ' + h(color || '') + '">' + icon(ico, 19) + '</span></div><div class="stat-value">' + value + '</div><div class="stat-foot">Données à jour · démo locale</div></div>';
    }

    function dashboardPage() {
        const r = role(), u = user();
        let stats, byStatus, byGravite = {}, byMonth = {}, recent, alerts, target;
        if (r === 'admin') {
            const mine = db.interventions;
            stats = [
                statCard('Comptes actifs', db.users.filter(x => x.statut === 'actif').length, 'users', ''),
                statCard('Clients', db.clients.length, 'user', 'blue'),
                statCard('Interventions', mine.length, 'wrench', 'purple'),
                statCard('Alertes ouvertes', db.alerts.filter(a => a.statut !== 'resolue').length, 'alert', 'red')
            ];
            byStatus = countBy(mine, 'statut');
            byGravite = countBy(db.alerts, 'gravite');
            byMonth = months(mine);
            recent = mine.slice().sort((a, b) => b.date_demande.localeCompare(a.date_demande)).slice(0, 6);
            alerts = db.alerts.slice().sort((a, b) => b.created_at.localeCompare(a.created_at)).slice(0, 6);
            target = 'interventions';
        } else if (r === 'technicien') {
            const eid = myEmpId();
            const mine = db.interventions.filter(i => i.technicien_id === eid);
            stats = [
                statCard('Mes interventions', mine.length, 'wrench', ''),
                statCard('À réaliser', mine.filter(i => i.statut === 'en_attente' || i.statut === 'planifie').length, 'clock', 'warn'),
                statCard('En cours', mine.filter(i => i.statut === 'en_cours').length, 'chart', 'blue'),
                statCard('Terminées', mine.filter(i => i.statut === 'termine').length, 'check', 'purple')
            ];
            byStatus = countBy(mine, 'statut');
            byGravite = countBy(db.alerts, 'gravite');
            byMonth = months(mine);
            recent = mine.slice().sort((a, b) => b.date_demande.localeCompare(a.date_demande)).slice(0, 6);
            alerts = db.alerts.slice().sort((a, b) => b.created_at.localeCompare(a.created_at)).slice(0, 5);
            target = 'my-interventions';
        } else {
            const cid = myClientId();
            const mine = db.interventions.filter(i => i.client_id === cid);
            stats = [
                statCard('Mes demandes', mine.length, 'wrench', ''),
                statCard('En cours', mine.filter(i => ['en_attente', 'planifie', 'en_cours'].includes(i.statut)).length, 'clock', 'warn'),
                statCard('Terminées', mine.filter(i => i.statut === 'termine').length, 'check', 'blue'),
                statCard('Rapports disponibles', db.reports.filter(x => x.client_id === cid).length, 'report', 'purple')
            ];
            byStatus = countBy(mine, 'statut');
            byMonth = months(mine);
            recent = mine.slice().sort((a, b) => b.date_demande.localeCompare(a.date_demande)).slice(0, 6);
            alerts = db.alerts.filter(a => a.client_id === cid).slice(0, 5);
            target = 'my-requests';
        }

        const rowsRecent = recent.map(i => '<tr><td><span class="cell-main">' + h(i.titre) + '</span><br><small class="muted">' + h(humanize(i.priorite)) + '</small></td><td>' + h(r === 'client' ? ((byId('employees', i.technicien_id) || {}).prenom || 'Non assigné') : ((byId('clients', i.client_id) || {}).nom || '—')) + '</td><td>' + badge(i.statut) + '</td><td class="nowrap muted">' + fd(i.date_demande) + '</td></tr>').join('');
        const rowsAlerts = alerts.map(a => '<tr><td><span class="cell-main">' + h(a.titre) + '</span><br><small class="muted">' + fd(a.created_at, true) + '</small></td><td>' + badge(a.gravite) + '</td><td>' + badge(a.statut) + '</td></tr>').join('');

        let html = '<div class="grid grid-4">' + stats.join('') + '</div>' +
            '<div class="grid grid-2" style="margin-top:22px"><div class="panel"><div class="panel-head"><h2>' + icon('chart', 18) + ' Interventions par statut</h2></div><div class="panel-body">' + chartDonut(byStatus) + '</div></div>' +
            '<div class="panel"><div class="panel-head"><h2>' + icon('chart', 18) + ' Évolution des 6 derniers mois</h2></div><div class="panel-body">' + chartBar(byMonth) + '</div></div></div>';
        if (Object.keys(byGravite).length) html += '<div class="panel"><div class="panel-head"><h2>' + icon('alert', 18) + ' Alertes par gravité</h2></div><div class="panel-body">' + chartDonut(byGravite) + '</div></div>';
        html += '<div class="grid grid-2"><div class="panel"><div class="panel-head"><h2>' + icon('wrench', 18) + ' Dernières interventions</h2><a class="btn btn-ghost btn-sm" href="#" data-nav="' + target + '">Tout voir ' + icon('arrow', 14) + '</a></div><div class="table-wrap"><table class="table"><thead><tr><th>Intervention</th><th>' + (r === 'client' ? 'Technicien' : 'Client') + '</th><th>Statut</th><th>Date</th></tr></thead><tbody>' + (rowsRecent || '<tr><td colspan="4" class="empty">Aucune donnée</td></tr>') + '</tbody></table></div></div>' +
            '<div class="panel"><div class="panel-head"><h2>' + icon('alert', 18) + ' Alertes récentes</h2>' + (r !== 'client' ? '<a class="btn btn-ghost btn-sm" href="#" data-nav="alerts">Tout voir ' + icon('arrow', 14) + '</a>' : '') + '</div><div class="table-wrap"><table class="table"><thead><tr><th>Alerte</th><th>Gravité</th><th>Statut</th></tr></thead><tbody>' + (rowsAlerts || '<tr><td colspan="3" class="empty">Aucune alerte</td></tr>') + '</tbody></table></div></div></div>';
        if (r === 'client') html += '<div class="panel"><div class="panel-head"><h2>' + icon('plus', 18) + ' Nouvelle demande</h2></div><div class="panel-body"><p class="muted">Un problème de sécurité ? Envoyez-nous une demande, notre équipe vous contactera rapidement.</p><a class="btn btn-primary" href="#" data-nav="my-requests" data-act="add-request">' + icon('plus', 17) + ' Créer une demande</a></div></div>';
        return shell(html, 'Tableau de bord', 'Bonjour ' + u.prenom + ' — voici l\'essentiel de votre activité.', 'dashboard');
    }

    function countBy(arr, key) {
        const out = {};
        arr.forEach(x => { const k = x[key] || 'inconnu'; out[k] = (out[k] || 0) + 1; });
        return out;
    }
    function months(arr) {
        const out = {};
        arr.forEach(x => {
            if (!x.date_demande) return;
            const d = x.date_demande.slice(0, 7);
            const names = ['janv.', 'févr.', 'mars', 'avr.', 'mai', 'juin', 'juil.', 'août', 'sept.', 'oct.', 'nov.', 'déc.'];
            const label = names[Number(d.slice(5, 7)) - 1] + ' ' + d.slice(0, 4);
            out[label] = (out[label] || 0) + 1;
        });
        return out;
    }

    /* ---------------- CRUD générique ---------------- */
    function can(kind, entity) {
        const r = role();
        if (r === 'admin') return true;
        if (r === 'technicien') return kind === 'edit' && (entity === 'alerts' || entity === 'reports');
        return false; // client : lecture seule dans le CRUD générique
    }
    function visibleRows(entity) {
        let rows = db[entity].slice();
        const r = role();
        if (entity === 'reports') {
            if (r === 'client') rows = rows.filter(x => x.client_id === myClientId());
            if (r === 'technicien') rows = rows.filter(x => x.technicien_id === myEmpId());
        }
        if (entity === 'alerts' && r === 'client') rows = rows.filter(x => x.client_id === myClientId());
        if (entity === 'services' && r === 'client') rows = rows.filter(x => String(x.active) === '1');
        if (entity === 'devices' && r === 'client') rows = rows.filter(x => x.client_id === myClientId());
        const q = state.q.toLowerCase();
        if (q) rows = rows.filter(x => ENT[entity].search.some(k => String(x[k] == null ? '' : x[k]).toLowerCase().includes(q)));
        return rows.sort((a, b) => b.id - a.id);
    }
    function fieldHtml(f, row) {
        let val = row ? (row[f.name] == null ? '' : row[f.name]) : (f.default == null ? '' : f.default);
        if (f.type === 'datetime-local' && val) val = String(val).replace(' ', 'T').slice(0, 16);
        let input;
        if (f.type === 'textarea') input = '<textarea class="input" name="' + f.name + '" placeholder="' + h(f.label) + '">' + h(val) + '</textarea>';
        else if (f.type === 'select') {
            const opts = typeof f.options === 'function' ? f.options() : f.options;
            input = '<select class="select" name="' + f.name + '"' + (f.required ? ' required' : '') + '><option value="">— Choisir —</option>' +
                opts.map(o => '<option value="' + h(o[0]) + '"' + (String(val) === String(o[0]) ? ' selected' : '') + '>' + h(o[1]) + '</option>').join('') + '</select>';
        } else input = '<input class="input" type="' + (f.type || 'text') + '" name="' + f.name + '" value="' + h(val) + '"' + (f.type === 'password' ? ' autocomplete="new-password"' : '') + (f.required && !(row && f.type === 'password') ? ' required' : '') + '>';
        const hint = f.hint ? '<div class="hint">' + h(f.hint) + '</div>' : '';
        return '<div class="field' + (f.full ? ' full' : '') + '"><label>' + h(f.label) + (f.required ? ' <span class="req">*</span>' : '') + '</label>' + input + hint + '</div>';
    }
    function cellHtml(c, row) {
        let v = c.get ? c.get(row) : row[c.key];
        if (c.type === 'badge') return badge(v);
        if (c.type === 'date') return h(fd(v));
        if (c.type === 'datetime') return h(fd(v, true));
        if (c.type === 'money') return h(money(v));
        if (c.main) return '<span class="cell-main">' + h(v) + '</span>';
        return h(String(v == null ? '' : v).slice(0, 70));
    }
    function crudPage(entity) {
        const c = ENT[entity];
        const rows = visibleRows(entity);
        const editRow = state.edit && state.edit.entity === entity && state.edit.id ? byId(entity, state.edit.id) : null;
        const adding = state.edit && state.edit.entity === entity && !state.edit.id;
        let html = '';
        if ((adding && can('add', entity)) || (editRow && can('edit', entity))) {
            html += '<div class="panel"><div class="panel-head"><h2>' + icon(editRow ? 'edit' : 'plus', 18) + ' ' + (editRow ? 'Modifier' : 'Ajouter') + ' — ' + h(c.item) + '</h2><a class="btn btn-ghost btn-sm" href="#" data-act="cancel">Fermer</a></div><div class="panel-body">' +
                '<form data-form="crud" data-entity="' + entity + '" data-id="' + (editRow ? editRow.id : 0) + '"><div class="form-grid">' + c.fields.map(f => fieldHtml(f, editRow)).join('') + '</div>' +
                '<div class="form-actions"><a class="btn btn-outline" href="#" data-act="cancel">Annuler</a><button class="btn btn-primary" type="submit">' + icon('check', 17) + ' Enregistrer</button></div></form></div></div>';
        }
        const addBtn = can('add', entity) ? '<a class="btn btn-primary btn-sm" href="#" data-act="add" data-entity="' + entity + '">' + icon('plus', 16) + ' Ajouter</a>' : '';
        const table = rows.length ? '<table class="table"><thead><tr>' + c.columns.map(x => '<th>' + h(x.label) + '</th>').join('') + ((can('edit', entity) || can('del', entity)) ? '<th class="right">Actions</th>' : '') + '</tr></thead><tbody>' +
            rows.map(r => '<tr>' + c.columns.map(x => '<td>' + cellHtml(x, r) + '</td>').join('') +
                ((can('edit', entity) || can('del', entity)) ? '<td class="right"><div class="actions" style="justify-content:flex-end">' +
                    (can('edit', entity) ? '<a class="btn-icon" href="#" data-act="edit" data-entity="' + entity + '" data-id="' + r.id + '" title="Modifier">' + icon('edit', 15) + '</a>' : '') +
                    (can('del', entity) ? '<button class="btn-icon danger" type="button" data-act="del" data-entity="' + entity + '" data-id="' + r.id + '" title="Supprimer">' + icon('trash', 15) + '</button>' : '') +
                    '</div></td>' : '') + '</tr>').join('') + '</tbody></table>' : '<div class="empty">' + icon('search', 34) + '<p>Aucun résultat' + (state.q ? ' pour « ' + h(state.q) + ' »' : '') + '.</p></div>';
        html += '<div class="panel"><div class="panel-head"><h2>' + icon(c.icon, 18) + ' ' + h(c.title) + ' <span class="badge badge-muted">' + rows.length + '</span></h2><div class="page-actions">' +
            '<form class="search-box" data-form="search">' + icon('search', 17) + '<input class="input" type="search" name="q" value="' + h(state.q) + '" placeholder="Rechercher..."><button class="btn btn-ghost btn-sm" type="submit">OK</button></form>' + addBtn + '</div></div><div class="table-wrap">' + table + '</div></div>';
        return shell(html, c.title, (role() === 'client' ? 'Consultez les informations mises à disposition par CyberShield.' : 'Ajoutez, modifiez, supprimez et recherchez.'), entity);
    }

    /* ---------------- Espace technicien ---------------- */
    function myInterventionsPage() {
        const eid = myEmpId();
        let rows = db.interventions.filter(i => i.technicien_id === eid);
        if (state.q) rows = rows.filter(i => (i.titre + ' ' + (i.description || '') + ' ' + ((byId('clients', i.client_id) || {}).nom || '')).toLowerCase().includes(state.q.toLowerCase()));
        const order = { en_cours: 0, planifie: 1, en_attente: 2, termine: 3, annule: 4 };
        rows.sort((a, b) => (order[a.statut] - order[b.statut]) || b.date_demande.localeCompare(a.date_demande));
        const editRow = state.edit && state.edit.id ? byId('interventions', state.edit.id) : null;
        let html = '';
        if (editRow && editRow.technicien_id === eid) {
            html += '<div class="panel"><div class="panel-head"><h2>' + icon('edit', 18) + ' Mettre à jour — ' + h(editRow.titre) + '</h2><a class="btn btn-ghost btn-sm" href="#" data-act="cancel">Fermer</a></div><div class="panel-body">' +
                '<div class="kv" style="margin-bottom:18px"><div class="kv-item"><small>Client</small><strong>' + h((byId('clients', editRow.client_id) || {}).nom || '—') + '</strong></div><div class="kv-item"><small>Appareil</small><strong>' + h((byId('devices', editRow.device_id) || {}).nom || '—') + '</strong></div><div class="kv-item"><small>Priorité</small><strong>' + badge(editRow.priorite) + '</strong></div><div class="kv-item"><small>Demandée le</small><strong>' + fd(editRow.date_demande, true) + '</strong></div></div>' +
                '<p class="muted">' + h(editRow.description) + '</p><form data-form="tech" style="margin-top:18px"><input type="hidden" name="id" value="' + editRow.id + '"><div class="form-grid">' +
                '<div class="field"><label>Statut <span class="req">*</span></label><select class="select" name="statut" required>' + ['en_attente', 'planifie', 'en_cours', 'termine', 'annule'].map(s => '<option value="' + s + '"' + (editRow.statut === s ? ' selected' : '') + '>' + h(humanize(s)) + '</option>').join('') + '</select></div>' +
                '<div class="field"><label>Date de fin</label><input class="input" type="datetime-local" name="date_fin" value="' + (editRow.date_fin ? h(editRow.date_fin.replace(' ', 'T').slice(0, 16)) : '') + '"></div></div>' +
                '<div class="field"><label>Notes d\'intervention</label><textarea class="input" name="notes" placeholder="Vos observations, actions réalisées...">' + h(editRow.notes) + '</textarea></div><div class="form-actions"><a class="btn btn-outline" href="#" data-act="cancel">Annuler</a><button class="btn btn-primary" type="submit">' + icon('check', 17) + ' Enregistrer</button></div></form></div></div>';
        }
        const table = rows.length ? '<table class="table"><thead><tr><th>#</th><th>Intervention</th><th>Client</th><th>Priorité</th><th>Statut</th><th>Date</th><th class="right">Action</th></tr></thead><tbody>' +
            rows.map(r => '<tr><td class="muted">' + r.id + '</td><td><span class="cell-main">' + h(r.titre) + '</span><br><small class="muted">' + h(String(r.description || '').slice(0, 55)) + '</small></td><td>' + h((byId('clients', r.client_id) || {}).nom || '—') + '</td><td>' + badge(r.priorite) + '</td><td>' + badge(r.statut) + '</td><td class="nowrap muted">' + fd(r.date_demande) + '</td><td class="right"><a class="btn btn-outline btn-sm" href="#" data-act="edit-tech" data-id="' + r.id + '">' + icon('edit', 14) + ' Gérer</a></td></tr>').join('') + '</tbody></table>' : '<div class="empty">' + icon('wrench', 34) + '<p>Aucune intervention assignée.</p></div>';
        html += '<div class="panel"><div class="panel-head"><h2>' + icon('wrench', 18) + ' Interventions assignées <span class="badge badge-muted">' + rows.length + '</span></h2><form class="search-box" data-form="search">' + icon('search', 17) + '<input class="input" type="search" name="q" value="' + h(state.q) + '" placeholder="Rechercher..."><button class="btn btn-ghost btn-sm" type="submit">OK</button></form></div><div class="table-wrap">' + table + '</div></div>';
        return shell(html, 'Mes interventions', 'Interventions qui vous sont assignées.', 'my-interventions');
    }

    /* ---------------- Espace client ---------------- */
    function myRequestsPage() {
        const cid = myClientId();
        const devices = db.devices.filter(d => d.client_id === cid);
        let rows = db.interventions.filter(i => i.client_id === cid);
        if (state.q) rows = rows.filter(i => (i.titre + ' ' + (i.description || '')).toLowerCase().includes(state.q.toLowerCase()));
        rows.sort((a, b) => b.date_demande.localeCompare(a.date_demande));
        const adding = state.edit && state.edit.entity === 'request' && !state.edit.id;
        const viewRow = state.view ? byId('interventions', state.view) : null;
        let html = '<div class="page-head"><div><h2>Créez une demande et suivez son avancement.</h2></div><div class="page-actions"><a class="btn btn-outline btn-sm" href="#" data-nav="reports">' + icon('report', 15) + ' Mes rapports</a><a class="btn btn-primary btn-sm" href="#" data-act="add-request">' + icon('plus', 15) + ' Nouvelle demande</a></div></div>';
        if (adding) {
            html += '<div class="panel"><div class="panel-head"><h2>' + icon('plus', 18) + ' Nouvelle demande d\'intervention</h2><a class="btn btn-ghost btn-sm" href="#" data-act="cancel">Fermer</a></div><div class="panel-body"><form data-form="request">' +
                '<div class="field"><label>Titre de la demande <span class="req">*</span></label><input class="input" name="titre" required placeholder="Ex. Analyse d\'une alerte suspecte"></div>' +
                '<div class="field"><label>Description du problème</label><textarea class="input" name="description" placeholder="Décrivez ce que vous avez observé..."></textarea></div><div class="form-grid">' +
                '<div class="field"><label>Appareil concerné</label><select class="select" name="device_id"><option value="">— Non précisé —</option>' + devices.map(d => '<option value="' + d.id + '">' + h(d.nom) + '</option>').join('') + '</select></div>' +
                '<div class="field"><label>Priorité</label><select class="select" name="priorite">' + ['faible', 'moyenne', 'haute', 'critique'].map(p => '<option value="' + p + '"' + (p === 'moyenne' ? ' selected' : '') + '>' + h(humanize(p)) + '</option>').join('') + '</select></div></div>' +
                '<div class="form-actions"><a class="btn btn-outline" href="#" data-act="cancel">Annuler</a><button class="btn btn-primary" type="submit">' + icon('check', 17) + ' Envoyer la demande</button></div></form></div></div>';
        }
        if (viewRow && viewRow.client_id === cid) {
            let timeline = '<li><strong>Demande envoyée</strong><small>' + fd(viewRow.date_demande, true) + '</small></li>';
            if (viewRow.date_planifiee) timeline += '<li><strong>Intervention planifiée</strong><small>' + fd(viewRow.date_planifiee, true) + '</small></li>';
            if (['en_cours', 'termine'].includes(viewRow.statut)) timeline += '<li><strong>Intervention en cours</strong><small>Un technicien est mobilisé sur votre demande</small></li>';
            if (viewRow.date_fin) timeline += '<li><strong>Intervention terminée</strong><small>' + fd(viewRow.date_fin, true) + '</small></li>';
            html += '<div class="panel"><div class="panel-head"><h2>' + icon('wrench', 18) + ' Suivi — ' + h(viewRow.titre) + '</h2><a class="btn btn-ghost btn-sm" href="#" data-act="cancel">Fermer</a></div><div class="panel-body"><div class="kv" style="margin-bottom:20px">' +
                '<div class="kv-item"><small>Statut actuel</small><strong>' + badge(viewRow.statut) + '</strong></div><div class="kv-item"><small>Priorité</small><strong>' + badge(viewRow.priorite) + '</strong></div>' +
                '<div class="kv-item"><small>Technicien</small><strong>' + h(((byId('employees', viewRow.technicien_id) || {}).prenom) || 'Non assigné') + '</strong></div><div class="kv-item"><small>Appareil</small><strong>' + h((byId('devices', viewRow.device_id) || {}).nom || '—') + '</strong></div></div>' +
                '<p class="muted">' + h(viewRow.description) + '</p><h3 style="margin:22px 0 14px">Avancement</h3><ul class="timeline">' + timeline + '</ul>' +
                (viewRow.notes ? '<div class="demo-box"><strong>Note du technicien</strong>' + h(viewRow.notes) + '</div>' : '') + '</div></div>';
        }
        const table = rows.length ? '<table class="table"><thead><tr><th>#</th><th>Demande</th><th>Priorité</th><th>Statut</th><th>Date</th><th class="right">Suivi</th></tr></thead><tbody>' +
            rows.map(r => '<tr><td class="muted">' + r.id + '</td><td><span class="cell-main">' + h(r.titre) + '</span><br><small class="muted">' + h(String(r.description || '').slice(0, 60)) + '</small></td><td>' + badge(r.priorite) + '</td><td>' + badge(r.statut) + '</td><td class="nowrap muted">' + fd(r.date_demande) + '</td><td class="right"><a class="btn btn-outline btn-sm" href="#" data-act="view" data-id="' + r.id + '">' + icon('eye', 14) + ' Voir</a></td></tr>').join('') + '</tbody></table>' : '<div class="empty">' + icon('wrench', 34) + '<p>Aucune demande. <a href="#" data-act="add-request">Créer une demande</a></p></div>';
        html += '<div class="panel"><div class="panel-head"><h2>' + icon('clock', 18) + ' Mes demandes <span class="badge badge-muted">' + rows.length + '</span></h2><form class="search-box" data-form="search">' + icon('search', 17) + '<input class="input" type="search" name="q" value="' + h(state.q) + '" placeholder="Rechercher..."><button class="btn btn-ghost btn-sm" type="submit">OK</button></form></div><div class="table-wrap">' + table + '</div></div>';
        return shell(html, 'Mes demandes', 'Créez une demande et suivez son avancement.', 'my-requests');
    }

    function profilePage() {
        const u = user();
        const r = role();
        const desc = {
            admin: 'Vous gérez les clients, employés, appareils, interventions, alertes, rapports et services. Vous avez une vue complète sur l\'activité de CyberShield.',
            technicien: 'Vous consultez les interventions qui vous sont assignées, mettez à jour leur statut et rédigez vos notes.',
            client: 'Vous découvrez nos services, créez des demandes d\'intervention, suivez leur avancement et consultez vos rapports.'
        };
        const html = '<div class="grid grid-2"><div class="panel" style="margin:0"><div class="panel-head"><h2>' + icon('user', 18) + ' Informations personnelles</h2></div><div class="panel-body"><form data-form="profile">' +
            '<div class="form-grid"><div class="field"><label>Nom <span class="req">*</span></label><input class="input" name="nom" value="' + h(u.nom) + '" required></div><div class="field"><label>Prénom <span class="req">*</span></label><input class="input" name="prenom" value="' + h(u.prenom) + '" required></div></div>' +
            '<div class="field"><label>E-mail <span class="req">*</span></label><input class="input" type="email" name="email" value="' + h(u.email) + '" required></div><div class="field"><label>Téléphone</label><input class="input" name="telephone" value="' + h(u.telephone) + '"></div>' +
            '<div class="field"><label>Nouveau mot de passe</label><input class="input" type="password" name="password" minlength="6"><div class="hint">Laissez vide pour ne pas le modifier.</div></div><div class="form-actions"><button class="btn btn-primary" type="submit">' + icon('check', 17) + ' Enregistrer</button></div></form></div></div>' +
            '<div><div class="panel"><div class="panel-body center"><div class="avatar" style="width:72px;height:72px;font-size:22px;margin:0 auto 14px">' + h(initials(u)) + '</div><h2>' + h(u.prenom + ' ' + u.nom) + '</h2><p class="muted">' + h(u.email) + '</p>' + badge(u.role) + ' ' + badge(u.statut) + '</div></div>' +
            '<div class="panel"><div class="panel-head"><h2>' + icon('shield', 18) + ' Votre rôle</h2></div><div class="panel-body"><p class="muted">' + h(desc[r]) + '</p><div class="kv" style="margin-top:14px"><div class="kv-item"><small>Membre depuis</small><strong>' + fd(u.created_at) + '</strong></div><div class="kv-item"><small>Téléphone</small><strong>' + h(u.telephone || '—') + '</strong></div></div></div></div></div></div>';
        return shell(html, 'Mon profil', 'Gérez vos informations personnelles.', 'profile');
    }

    /* ---------------- Login / Register ---------------- */
    function loginPage() {
        return '<div class="auth-shell"><div class="auth-visual"><a class="brand" href="#"><img src="logo-mark.svg" alt="" width="38" height="38"><span>Cyber<span>Shield</span></span></a>' +
            '<div class="auth-visual-content"><span class="hero-tag">' + icon('lock', 14) + ' ESPACE SÉCURISÉ</span><h1>Bienvenue dans votre<br><em>espace sécurisé</em>.</h1><p>Accédez à vos interventions, alertes et rapports. Une plateforme claire et sécurisée pour piloter votre cybersécurité.</p>' +
            '<div class="auth-features"><div class="auth-feature"><div class="stat-icon">' + icon('chart', 18) + '</div> Tableaux de bord et statistiques en temps réel</div><div class="auth-feature"><div class="stat-icon blue">' + icon('wrench', 18) + '</div> Suivi des interventions à chaque étape</div><div class="auth-feature"><div class="stat-icon purple">' + icon('report', 18) + '</div> Rapports détaillés accessibles à tout moment</div></div>' +
            '<img class="auth-hero-img" src="hero.svg" alt=""></div></div><div class="auth-form-side"><div class="auth-card"><h2>Connexion</h2><p class="sub">Entrez vos identifiants pour accéder à votre espace.</p>' +
            flashHtml() + '<form data-form="login"><div class="field"><label>Adresse e-mail</label><input class="input" type="email" name="email" placeholder="vous@entreprise.tn" required autofocus></div><div class="field"><label>Mot de passe</label><input class="input" type="password" name="password" placeholder="••••••••" required></div>' +
            '<div class="auth-links"><a href="#" data-nav="register">Créer un compte</a><span>Démo locale</span></div><button class="btn btn-primary btn-block btn-lg" type="submit">' + icon('lock', 18) + ' Se connecter</button></form>' +
            '<div class="demo-box"><strong>Comptes de démonstration — cliquez pour remplir</strong><div style="display:grid;gap:6px;margin-top:8px">' +
            '<button class="btn btn-outline btn-sm" type="button" data-act="demo" data-email="admin@cybershield.tn" data-pass="Admin123!">Admin — admin@cybershield.tn / Admin123!</button>' +
            '<button class="btn btn-outline btn-sm" type="button" data-act="demo" data-email="tech@cybershield.tn" data-pass="Tech123!">Technicien — tech@cybershield.tn / Tech123!</button>' +
            '<button class="btn btn-outline btn-sm" type="button" data-act="demo" data-email="client@cybershield.tn" data-pass="Client123!">Client — client@cybershield.tn / Client123!</button></div>' +
            '<button class="btn btn-ghost btn-sm" style="margin-top:10px" data-act="reset">Réinitialiser les données de démo</button></div></div></div></div>';
    }
    function registerPage() {
        return '<div class="auth-shell"><div class="auth-visual"><a class="brand" href="#"><img src="logo-mark.svg" alt="" width="38" height="38"><span>Cyber<span>Shield</span></span></a>' +
            '<div class="auth-visual-content"><span class="hero-tag">' + icon('shield', 14) + ' REJOIGNEZ CYBERSHIELD</span><h1>Devenez <em>client</em><br>en quelques secondes.</h1><p>Un compte pour demander vos interventions, suivre leur avancement et consulter vos rapports de sécurité.</p>' +
            '<div class="auth-features"><div class="auth-feature"><div class="stat-icon">' + icon('plus', 18) + '</div> Créez une demande d\'intervention en ligne</div><div class="auth-feature"><div class="stat-icon blue">' + icon('clock', 18) + '</div> Suivez l\'avancement en temps réel</div><div class="auth-feature"><div class="stat-icon purple">' + icon('report', 18) + '</div> Consultez vos rapports à tout moment</div></div>' +
            '<img class="auth-hero-img" src="hero.svg" alt=""></div></div><div class="auth-form-side"><div class="auth-card"><h2>Créer un compte</h2><p class="sub">Inscription en tant que client. Les comptes Admin et Technicien sont créés par l\'administrateur.</p>' +
            flashHtml() + '<form data-form="register"><div class="form-grid"><div class="field"><label>Nom <span class="req">*</span></label><input class="input" name="nom" required></div><div class="field"><label>Prénom <span class="req">*</span></label><input class="input" name="prenom" required></div></div>' +
            '<div class="field"><label>Adresse e-mail <span class="req">*</span></label><input class="input" type="email" name="email" required></div><div class="field"><label>Téléphone</label><input class="input" name="telephone"></div><div class="form-grid">' +
            '<div class="field"><label>Mot de passe <span class="req">*</span></label><input class="input" type="password" name="password" minlength="6" required></div><div class="field"><label>Confirmer <span class="req">*</span></label><input class="input" type="password" name="password2" minlength="6" required></div></div>' +
            '<button class="btn btn-primary btn-block btn-lg" type="submit">' + icon('check', 18) + ' Créer mon compte</button></form><p class="center" style="margin-top:18px;color:var(--muted)">Déjà inscrit ? <a href="#" data-nav="login">Se connecter</a></p></div></div></div>';
    }

    /* ---------------- Routeur ---------------- */
    function render() {
        const u = user();
        if (!u) { app.innerHTML = state.page === 'register' ? registerPage() : loginPage(); return; }
        const pages = {
            dashboard: dashboardPage, clients: () => crudPage('clients'), employees: () => crudPage('employees'),
            devices: () => crudPage('devices'), interventions: () => crudPage('interventions'),
            alerts: () => crudPage('alerts'), reports: () => crudPage('reports'), services: () => crudPage('services'),
            users: () => crudPage('users'), 'my-interventions': myInterventionsPage,
            'my-requests': myRequestsPage, profile: profilePage
        };
        const allowed = (MENUS[role()] || []).some(m => m[0] === state.page) || state.page === 'profile';
        app.innerHTML = (pages[state.page] && allowed ? pages[state.page] : dashboardPage)();
        window.scrollTo(0, 0);
    }

    /* ---------------- Événements ---------------- */
    app.addEventListener('click', function (e) {
        const nav = e.target.closest('[data-nav]');
        if (nav) {
            e.preventDefault();
            state.page = nav.dataset.nav; state.edit = null; state.view = null; state.q = '';
            if (nav.dataset.act === 'add-request') state.edit = { entity: 'request', id: null };
            render(); return;
        }
        const el = e.target.closest('[data-act]');
        if (!el) return;
        const a = el.dataset;
        if (a.act === 'demo') {
            const form = app.querySelector('[data-form="login"]');
            form.elements.email.value = a.email; form.elements.password.value = a.pass;
        } else if (a.act === 'menu') {
            const s = document.getElementById('sidebar'); if (s) s.classList.toggle('open');
        } else if (a.act === 'logout') {
            e.preventDefault(); setSession(null); state.page = 'dashboard'; state.edit = null; state.q = ''; render();
        } else if (a.act === 'dismiss') {
            state.flash = null; render();
        } else if (a.act === 'add') {
            state.edit = { entity: a.entity, id: null }; render();
        } else if (a.act === 'add-request') {
            state.page = 'my-requests'; state.edit = { entity: 'request', id: null }; state.view = null; render();
        } else if (a.act === 'edit') {
            state.edit = { entity: a.entity, id: Number(a.id) }; render();
        } else if (a.act === 'edit-tech') {
            state.edit = { entity: 'interventions', id: Number(a.id) }; render();
        } else if (a.act === 'view') {
            state.view = Number(a.id); render();
        } else if (a.act === 'cancel') {
            e.preventDefault(); state.edit = null; state.view = null; render();
        } else if (a.act === 'del') {
            if (confirm('Supprimer cet élément ?')) {
                db[a.entity] = db[a.entity].filter(r => r.id !== Number(a.id));
                save(); flash('Élément supprimé avec succès.'); render();
            }
        } else if (a.act === 'reset') {
            if (confirm('Réinitialiser toutes les données de démonstration ?')) {
                db = clone(SEED); save(); setSession(null); state = { page: 'dashboard', edit: null, q: '', view: null, flash: null, regError: '' };
                flash('Données de démonstration réinitialisées.'); render();
            }
        }
    });

    app.addEventListener('submit', function (e) {
        const form = e.target;
        const kind = form.dataset.form;
        if (!kind) return;
        e.preventDefault();
        const v = n => form.elements[n] ? form.elements[n].value.trim() : '';

        if (kind === 'search') { state.q = v('q'); render(); return; }

        if (kind === 'login') {
            const u = db.users.find(x => x.email.toLowerCase() === v('email').toLowerCase() && x.password === v('password'));
            if (!u) { flash('Adresse e-mail ou mot de passe incorrect.', 'danger'); render(); return; }
            setSession(u); state.page = 'dashboard'; state.edit = null; state.q = ''; flash('Bienvenue ' + u.prenom + ' ! Vous êtes connecté.'); render(); return;
        }

        if (kind === 'register') {
            const nom = v('nom'), prenom = v('prenom'), email = v('email'), tel = v('telephone'), pass = v('password');
            if (!nom || !prenom || !email) { flash('Veuillez remplir tous les champs obligatoires.', 'danger'); render(); return; }
            if (!/^\S+@\S+\.\S+$/.test(email)) { flash('Adresse e-mail invalide.', 'danger'); render(); return; }
            if (pass.length < 6) { flash('Le mot de passe doit contenir au moins 6 caractères.', 'danger'); render(); return; }
            if (pass !== v('password2')) { flash('Les deux mots de passe ne correspondent pas.', 'danger'); render(); return; }
            if (db.users.some(u => u.email.toLowerCase() === email.toLowerCase())) { flash('Cette adresse e-mail est déjà utilisée.', 'danger'); render(); return; }
            const uid = nextId('users');
            db.users.push({ id: uid, nom, prenom, email, password: pass, role: 'client', telephone: tel, statut: 'actif', created_at: now() });
            db.clients.push({ id: nextId('clients'), user_id: uid, nom: prenom + ' ' + nom, entreprise: 'Client individuel', email, telephone: tel, secteur: '', adresse: '', created_at: now() });
            save();
            const u = db.users.find(x => x.id === uid); setSession(u);
            state.page = 'dashboard'; flash('Votre compte a été créé. Bienvenue sur CyberShield !'); render(); return;
        }

        if (kind === 'crud') {
            const entity = form.dataset.entity, id = Number(form.dataset.id);
            const row = id ? byId(entity, id) : { id: nextId(entity) };
            const fields = ENT[entity].fields;
            for (const f of fields) {
                let val = form.elements[f.name] ? form.elements[f.name].value.trim() : '';
                if (f.required && !val && !(f.type === 'password' && id)) { flash('Le champ « ' + f.label + ' » est obligatoire.', 'danger'); render(); return; }
                if (f.type === 'password') { if (val) row[f.name] = val; }
                else if (f.type === 'number') row[f.name] = Number(val || 0);
                else if (f.type === 'datetime-local') row[f.name] = val ? val.replace('T', ' ').slice(0, 16) + ':00' : '';
                else row[f.name] = val;
            }
            if (!id) {
                if (entity === 'interventions' || entity === 'alerts' || entity === 'reports') row.created_at = now();
                if (entity === 'interventions') { row.date_demande = now(); row.date_planifiee = row.date_planifiee ? row.date_planifiee.replace('T', ' ') : ''; row.date_fin = row.date_fin ? row.date_fin.replace('T', ' ') : ''; }
                if (entity === 'clients') row.created_at = now();
                if (entity === 'devices') row.created_at = now();
                if (entity === 'users') row.created_at = now();
                db[entity].push(row);
            }
            if (entity === 'users' && row.role === 'client' && !db.clients.some(c => c.user_id === row.id)) {
                db.clients.push({ id: nextId('clients'), user_id: row.id, nom: (row.prenom || '') + ' ' + (row.nom || ''), entreprise: 'Client individuel', email: row.email || '', telephone: row.telephone || '', secteur: '', adresse: '', created_at: now() });
            }
            if (entity === 'users' && row.role === 'technicien' && !db.employees.some(c => c.user_id === row.id)) {
                db.employees.push({ id: nextId('employees'), user_id: row.id, nom: row.nom || '', prenom: row.prenom || '', poste: 'Technicien', specialite: '', email: row.email || '', telephone: row.telephone || '', statut: 'actif', date_embauche: now().slice(0, 10) });
            }
            save(); state.edit = null; flash(ENT[entity].item + (id ? ' mis à jour' : ' ajouté') + ' avec succès.'); render(); return;
        }

        if (kind === 'tech') {
            const row = byId('interventions', Number(v('id')));
            if (row) {
                row.statut = v('statut'); row.notes = v('notes');
                const df = v('date_fin'); row.date_fin = df ? df.replace('T', ' ') : '';
                save(); state.edit = null; flash('Intervention mise à jour avec succès.'); render();
            }
            return;
        }

        if (kind === 'request') {
            const titre = v('titre');
            if (!titre) { flash('Le titre est obligatoire.', 'danger'); render(); return; }
            db.interventions.push({
                id: nextId('interventions'), titre, description: v('description'), client_id: myClientId(),
                device_id: Number(v('device_id')) || null, technicien_id: null, priorite: v('priorite') || 'moyenne',
                statut: 'en_attente', notes: '', date_demande: now(), date_planifiee: '', date_fin: ''
            });
            save(); state.edit = null; flash('Votre demande a été envoyée. Nous vous contacterons rapidement.'); render(); return;
        }

        if (kind === 'profile') {
            const u = user();
            const email = v('email');
            if (!v('nom') || !v('prenom') || !/^\S+@\S+\.\S+$/.test(email)) { flash('Veuillez vérifier les champs obligatoires.', 'danger'); render(); return; }
            if (db.users.some(x => x.email.toLowerCase() === email.toLowerCase() && x.id !== u.id)) { flash('Cette adresse e-mail est déjà utilisée.', 'danger'); render(); return; }
            u.nom = v('nom'); u.prenom = v('prenom'); u.email = email; u.telephone = v('telephone');
            if (v('password')) u.password = v('password');
            save(); flash('Votre profil a été mis à jour.'); render(); return;
        }
    });

    render();
})();
