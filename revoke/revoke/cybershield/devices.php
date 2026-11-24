<?php
require_once __DIR__ . '/config/database.php';
require_role('admin');

$cfg = [
    'table' => 'devices',
    'from' => 'devices d LEFT JOIN clients c ON d.client_id = c.id',
    'idcol' => 'd.id',
    'item' => 'Appareil',
    'title' => 'Gestion des appareils',
    'icon' => 'device',
    'order' => 'd.id DESC',
    'search' => ['d.nom', 'd.ip_adresse', 'd.os', 'c.nom'],
    'columns' => [
        ['col' => 'd.id', 'label' => '#'],
        ['col' => 'd.nom', 'label' => 'Appareil', 'main' => true],
        ['col' => 'd.type', 'label' => 'Type'],
        ['col' => 'c.nom', 'alias' => 'client_nom', 'label' => 'Client'],
        ['col' => 'd.ip_adresse', 'label' => 'Adresse IP'],
        ['col' => 'd.os', 'label' => 'Système'],
        ['col' => 'd.statut', 'label' => 'Statut', 'type' => 'badge'],
    ],
    'fields' => [
        ['name' => 'nom', 'label' => 'Nom de l\'appareil', 'type' => 'text', 'required' => true, 'placeholder' => 'Ex. SRV-ATLAS-01'],
        ['name' => 'client_id', 'label' => 'Client', 'type' => 'select', 'required' => true, 'options' => DB::options('clients', 'nom')],
        ['name' => 'type', 'label' => 'Type', 'type' => 'select', 'options' => ['Serveur' => 'Serveur', 'Poste de travail' => 'Poste de travail', 'Pare-feu' => 'Pare-feu', 'Switch' => 'Switch', 'Autre' => 'Autre']],
        ['name' => 'ip_adresse', 'label' => 'Adresse IP', 'type' => 'text'],
        ['name' => 'os', 'label' => 'Système d\'exploitation', 'type' => 'text'],
        ['name' => 'localisation', 'label' => 'Localisation', 'type' => 'text'],
        ['name' => 'statut', 'label' => 'Statut', 'type' => 'select', 'options' => status_options('device'), 'default' => 'actif', 'no_empty' => true],
    ],
];

$title = 'Appareils';
$active = 'devices.php';
$subtitle = 'Les actifs informatiques surveillés par CyberShield.';
include __DIR__ . '/includes/header.php';
crud_render($cfg);
include __DIR__ . '/includes/footer.php';
