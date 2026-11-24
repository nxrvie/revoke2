<?php
require_once __DIR__ . '/config/database.php';
require_role('admin');

$cfg = [
    'table' => 'clients',
    'from' => 'clients c',
    'idcol' => 'c.id',
    'item' => 'Client',
    'title' => 'Gestion des clients',
    'icon' => 'users',
    'order' => 'c.id DESC',
    'search' => ['c.nom', 'c.entreprise', 'c.email', 'c.telephone', 'c.secteur'],
    'columns' => [
        ['col' => 'c.id', 'label' => '#'],
        ['col' => 'c.nom', 'label' => 'Client', 'main' => true],
        ['col' => 'c.entreprise', 'label' => 'Entreprise'],
        ['col' => 'c.email', 'label' => 'E-mail'],
        ['col' => 'c.telephone', 'label' => 'Téléphone'],
        ['col' => 'c.secteur', 'label' => 'Secteur'],
        ['col' => 'c.created_at', 'label' => 'Créé le', 'type' => 'date'],
    ],
    'fields' => [
        ['name' => 'nom', 'label' => 'Nom du contact', 'type' => 'text', 'required' => true, 'placeholder' => 'Ex. Nadia Ferjani'],
        ['name' => 'entreprise', 'label' => 'Entreprise', 'type' => 'text', 'placeholder' => 'Ex. Atlas Telecom'],
        ['name' => 'email', 'label' => 'E-mail', 'type' => 'email'],
        ['name' => 'telephone', 'label' => 'Téléphone', 'type' => 'text'],
        ['name' => 'secteur', 'label' => 'Secteur d\'activité', 'type' => 'text'],
        ['name' => 'adresse', 'label' => 'Adresse', 'type' => 'text', 'full' => true],
        ['name' => 'user_id', 'label' => 'Compte utilisateur associé', 'type' => 'select', 'options' => DB::options('users', 'email')],
    ],
];

$title = 'Clients';
$active = 'clients.php';
$subtitle = 'Ajoutez, modifiez et recherchez vos clients.';
include __DIR__ . '/includes/header.php';
crud_render($cfg);
include __DIR__ . '/includes/footer.php';
