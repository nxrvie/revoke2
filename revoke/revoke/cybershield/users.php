<?php
require_once __DIR__ . '/config/database.php';
require_role('admin');

$cfg = [
    'table' => 'users',
    'from' => 'users u',
    'idcol' => 'u.id',
    'item' => 'Utilisateur',
    'title' => 'Gestion des comptes',
    'icon' => 'settings',
    'order' => 'u.id DESC',
    'search' => ['u.nom', 'u.prenom', 'u.email', 'u.role'],
    'columns' => [
        ['col' => 'u.id', 'label' => '#'],
        ['col' => 'u.prenom', 'label' => 'Prénom'],
        ['col' => 'u.nom', 'label' => 'Nom', 'main' => true],
        ['col' => 'u.email', 'label' => 'E-mail'],
        ['col' => 'u.role', 'label' => 'Rôle', 'type' => 'badge'],
        ['col' => 'u.statut', 'label' => 'Statut', 'type' => 'badge'],
        ['col' => 'u.created_at', 'label' => 'Créé le', 'type' => 'date'],
    ],
    'fields' => [
        ['name' => 'nom', 'label' => 'Nom', 'type' => 'text', 'required' => true],
        ['name' => 'prenom', 'label' => 'Prénom', 'type' => 'text', 'required' => true],
        ['name' => 'email', 'label' => 'E-mail', 'type' => 'email', 'required' => true],
        ['name' => 'password', 'label' => 'Mot de passe', 'type' => 'password', 'required' => true, 'hint' => 'Laissez vide lors d\'une modification pour conserver le mot de passe actuel.'],
        ['name' => 'telephone', 'label' => 'Téléphone', 'type' => 'text'],
        ['name' => 'role', 'label' => 'Rôle', 'type' => 'select', 'options' => status_options('role'), 'default' => 'client', 'no_empty' => true],
        ['name' => 'statut', 'label' => 'Statut du compte', 'type' => 'select', 'options' => status_options('statut'), 'default' => 'actif', 'no_empty' => true],
    ],
    'after_save' => function (array $data, int $id): void {
        if (($data['role'] ?? '') === 'client') {
            $exists = DB::value('SELECT COUNT(*) FROM clients WHERE user_id = ?', [$id]);
            if (!$exists) {
                DB::exec('INSERT INTO clients (user_id, nom, email, telephone) VALUES (?,?,?,?)',
                    [$id, ($data['prenom'] ?? '') . ' ' . ($data['nom'] ?? ''), $data['email'] ?? '', $data['telephone'] ?? '']);
            }
        }
        if (($data['role'] ?? '') === 'technicien') {
            $exists = DB::value('SELECT COUNT(*) FROM employees WHERE user_id = ?', [$id]);
            if (!$exists) {
                DB::exec('INSERT INTO employees (user_id, nom, prenom, email, telephone, poste) VALUES (?,?,?,?,?,?)',
                    [$id, $data['nom'] ?? '', $data['prenom'] ?? '', $data['email'] ?? '', $data['telephone'] ?? '', 'Technicien']);
            }
        }
    },
];

$title = 'Utilisateurs';
$active = 'users.php';
$subtitle = 'Créez et gérez les comptes Admin, Technicien et Client.';
include __DIR__ . '/includes/header.php';
crud_render($cfg);
include __DIR__ . '/includes/footer.php';
