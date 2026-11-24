<?php
require_once __DIR__ . '/config/database.php';
require_role('admin');

$cfg = [
    'table' => 'interventions',
    'from' => 'interventions i LEFT JOIN clients c ON i.client_id = c.id LEFT JOIN employees e ON i.technicien_id = e.id LEFT JOIN devices d ON i.device_id = d.id',
    'idcol' => 'i.id',
    'item' => 'Intervention',
    'title' => 'Toutes les interventions',
    'icon' => 'wrench',
    'order' => 'i.date_demande DESC',
    'search' => ['i.titre', 'i.description', 'c.nom', 'e.nom', 'd.nom'],
    'columns' => [
        ['col' => 'i.id', 'label' => '#'],
        ['col' => 'i.titre', 'label' => 'Intervention', 'main' => true],
        ['col' => 'c.nom', 'alias' => 'client_nom', 'label' => 'Client'],
        ['col' => 'e.nom', 'alias' => 'tech_nom', 'label' => 'Technicien'],
        ['col' => 'i.priorite', 'label' => 'Priorité', 'type' => 'badge'],
        ['col' => 'i.statut', 'label' => 'Statut', 'type' => 'badge'],
        ['col' => 'i.date_demande', 'label' => 'Demandée le', 'type' => 'date'],
    ],
    'fields' => [
        ['name' => 'titre', 'label' => 'Titre', 'type' => 'text', 'required' => true, 'full' => true],
        ['name' => 'description', 'label' => 'Description', 'type' => 'textarea', 'full' => true],
        ['name' => 'client_id', 'label' => 'Client', 'type' => 'select', 'required' => true, 'options' => DB::options('clients', 'nom')],
        ['name' => 'device_id', 'label' => 'Appareil concerné', 'type' => 'select', 'options' => DB::options('devices', 'nom')],
        ['name' => 'technicien_id', 'label' => 'Technicien assigné', 'type' => 'select', 'options' => DB::options('employees', 'nom')],
        ['name' => 'priorite', 'label' => 'Priorité', 'type' => 'select', 'options' => status_options('gravite'), 'default' => 'moyenne', 'no_empty' => true],
        ['name' => 'statut', 'label' => 'Statut', 'type' => 'select', 'options' => status_options('intervention'), 'default' => 'en_attente', 'no_empty' => true],
        ['name' => 'date_planifiee', 'label' => 'Date planifiée', 'type' => 'datetime-local'],
        ['name' => 'date_fin', 'label' => 'Date de fin', 'type' => 'datetime-local'],
        ['name' => 'notes', 'label' => 'Notes du technicien', 'type' => 'textarea', 'full' => true],
    ],
];

$title = 'Interventions';
$active = 'interventions.php';
$subtitle = 'Planifiez, assignez et suivez toutes les interventions.';
include __DIR__ . '/includes/header.php';
crud_render($cfg);
include __DIR__ . '/includes/footer.php';
