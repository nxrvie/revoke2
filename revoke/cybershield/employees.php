<?php
require_once __DIR__ . '/config/database.php';
require_role('admin');

$cfg = [
    'table' => 'employees',
    'from' => 'employees e',
    'idcol' => 'e.id',
    'item' => 'Employé',
    'title' => 'Gestion des employés',
    'icon' => 'user',
    'order' => 'e.id DESC',
    'search' => ['e.nom', 'e.prenom', 'e.poste', 'e.specialite', 'e.email'],
    'columns' => [
        ['col' => 'e.id', 'label' => '#'],
        ['col' => 'e.nom', 'label' => 'Nom', 'main' => true],
        ['col' => 'e.prenom', 'label' => 'Prénom'],
        ['col' => 'e.poste', 'label' => 'Poste'],
        ['col' => 'e.specialite', 'label' => 'Spécialité'],
        ['col' => 'e.email', 'label' => 'E-mail'],
        ['col' => 'e.statut', 'label' => 'Statut', 'type' => 'badge'],
        ['col' => 'e.date_embauche', 'label' => 'Embauche', 'type' => 'date'],
    ],
    'fields' => [
        ['name' => 'nom', 'label' => 'Nom', 'type' => 'text', 'required' => true],
        ['name' => 'prenom', 'label' => 'Prénom', 'type' => 'text', 'required' => true],
        ['name' => 'poste', 'label' => 'Poste', 'type' => 'text', 'placeholder' => 'Ex. Technicien réseau'],
        ['name' => 'specialite', 'label' => 'Spécialité', 'type' => 'text'],
        ['name' => 'email', 'label' => 'E-mail', 'type' => 'email'],
        ['name' => 'telephone', 'label' => 'Téléphone', 'type' => 'text'],
        ['name' => 'date_embauche', 'label' => 'Date d\'embauche', 'type' => 'date'],
        ['name' => 'statut', 'label' => 'Statut', 'type' => 'select', 'options' => status_options('statut'), 'default' => 'actif', 'no_empty' => true],
        ['name' => 'user_id', 'label' => 'Compte utilisateur associé', 'type' => 'select', 'options' => DB::options('users', 'email')],
    ],
];

$title = 'Employés';
$active = 'employees.php';
$subtitle = 'Votre équipe d\'experts et leurs spécialités.';
include __DIR__ . '/includes/header.php';
crud_render($cfg);
include __DIR__ . '/includes/footer.php';
