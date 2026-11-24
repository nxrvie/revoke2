<?php
require_once __DIR__ . '/config/database.php';
require_role('admin', 'technicien');
$isAdmin = is_role('admin');

$cfg = [
    'table' => 'alerts',
    'from' => 'alerts a LEFT JOIN clients c ON a.client_id = c.id LEFT JOIN devices d ON a.device_id = d.id',
    'idcol' => 'a.id',
    'item' => 'Alerte',
    'title' => 'Alertes de sécurité',
    'icon' => 'alert',
    'order' => 'a.created_at DESC',
    'search' => ['a.titre', 'a.description', 'c.nom', 'd.nom'],
    'can_add' => $isAdmin,
    'can_delete' => $isAdmin,
    'columns' => [
        ['col' => 'a.id', 'label' => '#'],
        ['col' => 'a.titre', 'label' => 'Alerte', 'main' => true],
        ['col' => 'c.nom', 'alias' => 'client_nom', 'label' => 'Client'],
        ['col' => 'd.nom', 'alias' => 'device_nom', 'label' => 'Appareil'],
        ['col' => 'a.gravite', 'label' => 'Gravité', 'type' => 'badge'],
        ['col' => 'a.statut', 'label' => 'Statut', 'type' => 'badge'],
        ['col' => 'a.created_at', 'label' => 'Détectée le', 'type' => 'datetime'],
    ],
    'fields' => [
        ['name' => 'titre', 'label' => 'Titre', 'type' => 'text', 'required' => true, 'full' => true],
        ['name' => 'description', 'label' => 'Description', 'type' => 'textarea', 'full' => true],
        ['name' => 'gravite', 'label' => 'Gravité', 'type' => 'select', 'options' => status_options('gravite'), 'default' => 'moyenne', 'no_empty' => true],
        ['name' => 'statut', 'label' => 'Statut', 'type' => 'select', 'options' => status_options('alert'), 'default' => 'ouverte', 'no_empty' => true],
        ['name' => 'client_id', 'label' => 'Client', 'type' => 'select', 'options' => DB::options('clients', 'nom')],
        ['name' => 'device_id', 'label' => 'Appareil', 'type' => 'select', 'options' => DB::options('devices', 'nom')],
    ],
];

$title = 'Alertes';
$active = 'alerts.php';
$subtitle = $isAdmin ? 'Toutes les alertes détectées sur vos systèmes.' : 'Prenez connaissance et mettez à jour les alertes.';
include __DIR__ . '/includes/header.php';
crud_render($cfg);
include __DIR__ . '/includes/footer.php';
