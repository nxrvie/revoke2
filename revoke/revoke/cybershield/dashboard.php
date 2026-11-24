<?php
require_once __DIR__ . '/config/database.php';
require_login();
$u = current_user();
$role = $u['role'];
$title = 'Tableau de bord';
$active = 'dashboard.php';

if ($role === 'admin') {
    $stats = [
        ['Comptes actifs', (int) DB::value("SELECT COUNT(*) FROM users WHERE statut='actif'"), 'users', ''],
        ['Clients', (int) DB::value('SELECT COUNT(*) FROM clients'), 'user', 'blue'],
        ['Interventions', (int) DB::value('SELECT COUNT(*) FROM interventions'), 'wrench', 'purple'],
        ['Alertes ouvertes', (int) DB::value("SELECT COUNT(*) FROM alerts WHERE statut <> 'resolue'"), 'alert', 'red'],
    ];
    $byStatus = [];
    foreach (DB::all('SELECT statut, COUNT(*) c FROM interventions GROUP BY statut ORDER BY c DESC') as $r) $byStatus[$r['statut']] = (int) $r['c'];
    $byGravite = [];
    foreach (DB::all('SELECT gravite, COUNT(*) c FROM alerts GROUP BY gravite ORDER BY c DESC') as $r) $byGravite[$r['gravite']] = (int) $r['c'];
    $byMonth = [];
    foreach (DB::all(db_month_query()) as $r) $byMonth[month_label($r['k'])] = (int) $r['c'];
    $recent = DB::all('SELECT i.*, c.nom client, e.nom tech FROM interventions i LEFT JOIN clients c ON i.client_id=c.id LEFT JOIN employees e ON i.technicien_id=e.id ORDER BY i.date_demande DESC LIMIT 6');
    $alerts = DB::all('SELECT a.*, c.nom client FROM alerts a LEFT JOIN clients c ON a.client_id=c.id ORDER BY a.created_at DESC LIMIT 6');

} elseif ($role === 'technicien') {
    $empId = (int) DB::value('SELECT id FROM employees WHERE user_id = ?', [$u['id']]);
    $mine = $empId ? 'technicien_id = ' . $empId : '1=0';
    $stats = [
        ['Mes interventions', (int) DB::value("SELECT COUNT(*) FROM interventions WHERE $mine"), 'wrench', ''],
        ['À réaliser', (int) DB::value("SELECT COUNT(*) FROM interventions WHERE $mine AND statut IN ('en_attente','planifie')"), 'clock', 'warn'],
        ['En cours', (int) DB::value("SELECT COUNT(*) FROM interventions WHERE $mine AND statut='en_cours'"), 'chart', 'blue'],
        ['Terminées', (int) DB::value("SELECT COUNT(*) FROM interventions WHERE $mine AND statut='termine'"), 'check', 'purple'],
    ];
    $byStatus = [];
    foreach (DB::all("SELECT statut, COUNT(*) c FROM interventions WHERE $mine GROUP BY statut ORDER BY c DESC") as $r) $byStatus[$r['statut']] = (int) $r['c'];
    $byGravite = [];
    foreach (DB::all('SELECT gravite, COUNT(*) c FROM alerts GROUP BY gravite ORDER BY c DESC') as $r) $byGravite[$r['gravite']] = (int) $r['c'];
    $byMonth = [];
    foreach (DB::all(db_month_query($mine)) as $r) $byMonth[month_label($r['k'])] = (int) $r['c'];
    $recent = DB::all("SELECT i.*, c.nom client FROM interventions i LEFT JOIN clients c ON i.client_id=c.id WHERE i.$mine ORDER BY i.date_demande DESC LIMIT 6");
    $alerts = DB::all('SELECT a.*, c.nom client FROM alerts a LEFT JOIN clients c ON a.client_id=c.id ORDER BY a.created_at DESC LIMIT 5');

} else {
    $clientId = (int) DB::value('SELECT id FROM clients WHERE user_id = ?', [$u['id']]);
    $mine = $clientId ? 'client_id = ' . $clientId : '1=0';
    $stats = [
        ['Mes demandes', (int) DB::value("SELECT COUNT(*) FROM interventions WHERE $mine"), 'wrench', ''],
        ['En cours', (int) DB::value("SELECT COUNT(*) FROM interventions WHERE $mine AND statut IN ('en_attente','planifie','en_cours')"), 'clock', 'warn'],
        ['Terminées', (int) DB::value("SELECT COUNT(*) FROM interventions WHERE $mine AND statut='termine'"), 'check', 'blue'],
        ['Rapports disponibles', (int) DB::value("SELECT COUNT(*) FROM reports WHERE $mine"), 'report', 'purple'],
    ];
    $byStatus = [];
    foreach (DB::all("SELECT statut, COUNT(*) c FROM interventions WHERE $mine GROUP BY statut ORDER BY c DESC") as $r) $byStatus[$r['statut']] = (int) $r['c'];
    $byGravite = [];
    $byMonth = [];
    foreach (DB::all(db_month_query($mine)) as $r) $byMonth[month_label($r['k'])] = (int) $r['c'];
    $recent = DB::all("SELECT i.*, e.nom tech FROM interventions i LEFT JOIN employees e ON i.technicien_id=e.id WHERE i.$mine ORDER BY i.date_demande DESC LIMIT 6");
    $alerts = DB::all("SELECT * FROM alerts WHERE $mine ORDER BY created_at DESC LIMIT 5");
}

$subtitle = 'Bonjour ' . $u['prenom'] . ' — voici l\'essentiel de votre activité.';
include __DIR__ . '/includes/header.php';
?>

<div class="grid grid-4">
    <?php foreach ($stats as [$label, $val, $ico, $color]): ?>
        <div class="stat-card">
            <div class="stat-top"><span class="stat-label"><?= e($label) ?></span><span class="stat-icon <?= e($color) ?>"><?= icon($ico, 19) ?></span></div>
            <div class="stat-value"><?= (int) $val ?></div>
            <div class="stat-foot">Données à jour · <?= date('d/m/Y') ?></div>
        </div>
    <?php endforeach; ?>
</div>

<div class="grid grid-2" style="margin-top:22px">
    <div class="panel">
        <div class="panel-head"><h2><?= icon('chart', 18) ?> Interventions par statut</h2></div>
        <div class="panel-body"><?= chart_donut($byStatus) ?></div>
    </div>
    <div class="panel">
        <div class="panel-head"><h2><?= icon('chart', 18) ?> Évolution des 6 derniers mois</h2></div>
        <div class="panel-body"><?= chart_bar($byMonth) ?></div>
    </div>
</div>

<?php if ($byGravite): ?>
<div class="panel">
    <div class="panel-head"><h2><?= icon('alert', 18) ?> Alertes par gravité</h2></div>
    <div class="panel-body"><?= chart_donut($byGravite) ?></div>
</div>
<?php endif; ?>

<div class="grid grid-2">
    <div class="panel">
        <div class="panel-head">
            <h2><?= icon('wrench', 18) ?> Dernières interventions</h2>
            <a class="btn btn-ghost btn-sm" href="<?= url($role === 'client' ? 'my-requests.php' : ($role === 'technicien' ? 'my-interventions.php' : 'interventions.php')) ?>">Tout voir <?= icon('arrow', 14) ?></a>
        </div>
        <div class="table-wrap">
            <table class="table">
                <thead><tr><th>Intervention</th><th><?= $role === 'client' ? 'Technicien' : 'Client' ?></th><th>Statut</th><th>Date</th></tr></thead>
                <tbody>
                <?php foreach ($recent as $r): ?>
                    <tr>
                        <td><span class="cell-main"><?= e($r['titre']) ?></span><br><small class="muted"><?= e(humanize($r['priorite'])) ?></small></td>
                        <td><?= e($r['tech'] ?? $r['client'] ?? '—') ?></td>
                        <td><?= badge($r['statut']) ?></td>
                        <td class="nowrap muted"><?= e(format_date($r['date_demande'])) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <h2><?= icon('alert', 18) ?> Alertes récentes</h2>
            <?php if ($role !== 'client'): ?><a class="btn btn-ghost btn-sm" href="<?= url('alerts.php') ?>">Tout voir <?= icon('arrow', 14) ?></a><?php endif; ?>
        </div>
        <div class="table-wrap">
            <table class="table">
                <thead><tr><th>Alerte</th><th>Gravité</th><th>Statut</th></tr></thead>
                <tbody>
                <?php foreach ($alerts as $a): ?>
                    <tr>
                        <td><span class="cell-main"><?= e($a['titre']) ?></span><br><small class="muted"><?= e(format_date($a['created_at'], 'd/m/Y H:i')) ?></small></td>
                        <td><?= badge($a['gravite']) ?></td>
                        <td><?= badge($a['statut']) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if ($role === 'client'): ?>
<div class="panel">
    <div class="panel-head"><h2><?= icon('plus', 18) ?> Nouvelle demande</h2></div>
    <div class="panel-body">
        <p class="muted">Un problème de sécurité ? Envoyez-nous une demande d'intervention, notre équipe vous contactera rapidement.</p>
        <a class="btn btn-primary" href="<?= url('my-requests.php') ?>?action=add"><?= icon('plus', 17) ?> Créer une demande</a>
    </div>
</div>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
