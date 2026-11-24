<?php
require_once __DIR__ . '/config/database.php';
require_login();
$isAdmin = is_role('admin');

$cfg = [
    'table' => 'services',
    'from' => 'services s',
    'idcol' => 's.id',
    'item' => 'Service',
    'title' => $isAdmin ? 'Gestion des services' : 'Nos services',
    'icon' => 'shield',
    'order' => 's.id ASC',
    'where' => $isAdmin ? '1=1' : 's.active = 1',
    'search' => ['s.nom', 's.description'],
    'can_add' => $isAdmin,
    'can_edit' => $isAdmin,
    'can_delete' => $isAdmin,
    'columns' => [
        ['col' => 's.id', 'label' => '#'],
        ['col' => 's.nom', 'label' => 'Service', 'main' => true],
        ['col' => 's.description', 'label' => 'Description'],
        ['col' => 's.prix', 'label' => 'Tarif', 'type' => 'money'],
        ['col' => 's.icone', 'label' => 'Icône'],
        ['col' => 's.active', 'label' => 'Actif', 'type' => 'badge'],
    ],
    'fields' => [
        ['name' => 'nom', 'label' => 'Nom du service', 'type' => 'text', 'required' => true],
        ['name' => 'description', 'label' => 'Description', 'type' => 'textarea', 'full' => true],
        ['name' => 'prix', 'label' => 'Tarif de base (DT)', 'type' => 'number', 'default' => 0],
        ['name' => 'icone', 'label' => 'Icône (nom)', 'type' => 'select', 'options' => ['shield' => 'Bouclier', 'search' => 'Audit', 'target' => 'Cible', 'eye' => 'Surveillance', 'alert' => 'Alerte', 'document' => 'Document', 'users' => 'Équipe', 'lock' => 'Serrure']],
        ['name' => 'active', 'label' => 'Service actif', 'type' => 'select', 'options' => [1 => 'Oui', 0 => 'Non'], 'default' => 1, 'no_empty' => true],
    ],
];

$title = 'Services';
$active = 'services.php';
$subtitle = $isAdmin ? 'Les offres proposées par CyberShield.' : 'Découvrez nos prestations et leurs tarifs.';
include __DIR__ . '/includes/header.php';
crud_render($cfg);
include __DIR__ . '/includes/footer.php';
