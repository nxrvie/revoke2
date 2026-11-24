<?php
require_once __DIR__ . '/config/database.php';
require_login();
$u = current_user();
$role = $u['role'];

$clientWhere = '1=1';
$clientParams = [];
if ($role === 'client') {
    $cid = (int) DB::value('SELECT id FROM clients WHERE user_id = ?', [$u['id']]);
    $clientWhere = 'r.client_id = ?';
    $clientParams = [$cid ?: -1];
}

$cfg = [
    'table' => 'reports',
    'from' => 'reports r LEFT JOIN clients c ON r.client_id = c.id LEFT JOIN employees e ON r.technicien_id = e.id LEFT JOIN interventions i ON r.intervention_id = i.id',
    'idcol' => 'r.id',
    'item' => 'Rapport',
    'title' => $role === 'client' ? 'Mes rapports' : 'Rapports',
    'icon' => 'report',
    'order' => 'r.created_at DESC',
    'where' => $clientWhere,
    'params' => $clientParams,
    'search' => ['r.titre', 'r.contenu', 'c.nom', 'i.titre'],
    'can_add' => $role !== 'client',
    'can_edit' => $role !== 'client',
    'can_delete' => $role === 'admin',
    'columns' => [
        ['col' => 'r.id', 'label' => '#'],
        ['col' => 'r.titre', 'label' => 'Rapport', 'main' => true],
        ['col' => 'c.nom', 'alias' => 'client_nom', 'label' => 'Client'],
        ['col' => 'i.titre', 'alias' => 'intervention_titre', 'label' => 'Intervention'],
        ['col' => 'e.nom', 'alias' => 'tech_nom', 'label' => 'Technicien'],
        ['col' => 'r.created_at', 'label' => 'Créé le', 'type' => 'date'],
    ],
    'fields' => [
        ['name' => 'titre', 'label' => 'Titre', 'type' => 'text', 'required' => true, 'full' => true],
        ['name' => 'contenu', 'label' => 'Contenu du rapport', 'type' => 'textarea', 'full' => true],
        ['name' => 'client_id', 'label' => 'Client', 'type' => 'select', 'options' => DB::options('clients', 'nom')],
        ['name' => 'intervention_id', 'label' => 'Intervention liée', 'type' => 'select', 'options' => DB::options('interventions', 'titre')],
        ['name' => 'technicien_id', 'label' => 'Technicien', 'type' => 'select', 'options' => DB::options('employees', 'nom')],
    ],
];

$title = 'Rapports';
$active = 'reports.php';
$subtitle = $role === 'client' ? 'Tous vos rapports d\'intervention.' : 'Les comptes rendus rédigés par votre équipe.';
include __DIR__ . '/includes/header.php';
crud_render($cfg);
include __DIR__ . '/includes/footer.php';
