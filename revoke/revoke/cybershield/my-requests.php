<?php
require_once __DIR__ . '/config/database.php';
require_role('client');
$u = current_user();
$self = url('my-requests.php');
$clientId = (int) DB::value('SELECT id FROM clients WHERE user_id = ?', [$u['id']]);
$devices = $clientId ? DB::all('SELECT id, nom FROM devices WHERE client_id = ?', [$clientId]) : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add') {
    $titre = trim($_POST['titre'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    $priorite = $_POST['priorite'] ?? 'moyenne';
    $deviceId = (int) ($_POST['device_id'] ?? 0);
    if ($titre === '' || !in_array($priorite, array_keys(status_options('gravite')), true)) {
        flash('Veuillez renseigner un titre et une priorité valides.', 'danger');
    } else {
        DB::exec("INSERT INTO interventions (titre, description, client_id, device_id, priorite, statut) VALUES (?,?,?,?,?,'en_attente')",
            [$titre, $desc, $clientId, $deviceId ?: null, $priorite]);
        flash('Votre demande a été envoyée. Nous vous contacterons rapidement.');
    }
    redirect('my-requests.php');
}

$q = trim($_GET['q'] ?? '');
$where = 'i.client_id = ?';
$params = [$clientId ?: -1];
if ($q !== '') { $where .= ' AND (i.titre LIKE ? OR i.description LIKE ?)'; $params = array_merge($params, ["%$q%", "%$q%"]); }
$rows = DB::all("SELECT i.*, d.nom device, e.nom tech FROM interventions i LEFT JOIN devices d ON i.device_id=d.id LEFT JOIN employees e ON i.technicien_id=e.id WHERE $where ORDER BY i.date_demande DESC", $params);

$view = null;
if (!empty($_GET['view'])) {
    $view = DB::one('SELECT i.*, d.nom device, e.nom tech, e.specialite FROM interventions i LEFT JOIN devices d ON i.device_id=d.id LEFT JOIN employees e ON i.technicien_id=e.id WHERE i.id=? AND i.client_id=?', [(int) $_GET['view'], $clientId ?: -1]);
}

$title = 'Mes demandes';
$active = 'my-requests.php';
$subtitle = 'Créez une demande et suivez son avancement.';
include __DIR__ . '/includes/header.php';
?>

<div class="page-head">
    <div><h2><?= e($subtitle) ?></h2></div>
    <div class="page-actions">
        <a class="btn btn-outline btn-sm" href="<?= url('reports.php') ?>"><?= icon('report', 15) ?> Mes rapports</a>
        <a class="btn btn-primary btn-sm" href="<?= e($self) ?>?action=add"><?= icon('plus', 15) ?> Nouvelle demande</a>
    </div>
</div>

<?php if (($_GET['action'] ?? '') === 'add'): ?>
<div class="panel">
    <div class="panel-head"><h2><?= icon('plus', 18) ?> Nouvelle demande d'intervention</h2><a class="btn btn-ghost btn-sm" href="<?= e($self) ?>">Fermer</a></div>
    <div class="panel-body">
        <form method="post" action="<?= e($self) ?>">
            <input type="hidden" name="action" value="add">
            <div class="field"><label for="titre">Titre de la demande <span class="req">*</span></label><input class="input" id="titre" name="titre" required placeholder="Ex. Analyse d'une alerte suspecte"></div>
            <div class="field full"><label for="description">Description du problème</label><textarea class="input" id="description" name="description" placeholder="Décrivez ce que vous avez observé..."></textarea></div>
            <div class="form-grid">
                <div class="field"><label for="device_id">Appareil concerné</label><select class="select" name="device_id" id="device_id"><option value="">— Non précisé —</option><?php foreach ($devices as $d): ?><option value="<?= (int) $d['id'] ?>"><?= e($d['nom']) ?></option><?php endforeach; ?></select></div>
                <div class="field"><label for="priorite">Priorité</label><select class="select" name="priorite" id="priorite"><?php foreach (status_options('gravite') as $k => $v): ?><option value="<?= e($k) ?>" <?= $k === 'moyenne' ? 'selected' : '' ?>><?= e($v) ?></option><?php endforeach; ?></select></div>
            </div>
            <div class="form-actions"><a class="btn btn-outline" href="<?= e($self) ?>">Annuler</a><button class="btn btn-primary" type="submit"><?= icon('check', 17) ?> Envoyer la demande</button></div>
        </form>
    </div>
</div>
<?php endif; ?>

<?php if ($view): ?>
<div class="panel">
    <div class="panel-head"><h2><?= icon('wrench', 18) ?> Suivi — <?= e($view['titre']) ?></h2><a class="btn btn-ghost btn-sm" href="<?= e($self) ?>">Fermer</a></div>
    <div class="panel-body">
        <div class="kv" style="margin-bottom:20px">
            <div class="kv-item"><small>Statut actuel</small><strong><?= badge($view['statut']) ?></strong></div>
            <div class="kv-item"><small>Priorité</small><strong><?= badge($view['priorite']) ?></strong></div>
            <div class="kv-item"><small>Technicien</small><strong><?= e($view['tech'] ?? 'Non assigné') ?></strong></div>
            <div class="kv-item"><small>Appareil</small><strong><?= e($view['device'] ?? '—') ?></strong></div>
        </div>
        <p class="muted"><?= e($view['description']) ?></p>
        <h3 style="margin:22px 0 14px">Avancement</h3>
        <ul class="timeline">
            <li><strong>Demande envoyée</strong><small><?= e(format_date($view['date_demande'], 'd/m/Y H:i')) ?></small></li>
            <?php if ($view['date_planifiee']): ?><li><strong>Intervention planifiée</strong><small><?= e(format_date($view['date_planifiee'], 'd/m/Y H:i')) ?></small></li><?php endif; ?>
            <?php if (in_array($view['statut'], ['en_cours', 'termine'], true)): ?><li><strong>Intervention en cours</strong><small>Un technicien est mobilisé sur votre demande</small></li><?php endif; ?>
            <?php if ($view['date_fin']): ?><li><strong>Intervention terminée</strong><small><?= e(format_date($view['date_fin'], 'd/m/Y H:i')) ?></small></li><?php endif; ?>
        </ul>
        <?php if ($view['notes']): ?><div class="demo-box"><strong>Note du technicien</strong><?= e($view['notes']) ?></div><?php endif; ?>
    </div>
</div>
<?php endif; ?>

<div class="panel">
    <div class="panel-head">
        <h2><?= icon('clock', 18) ?> Mes demandes <span class="badge badge-muted"><?= count($rows) ?></span></h2>
        <form class="search-box" method="get"><?= icon('search', 17) ?><input class="input" type="search" name="q" value="<?= e($q) ?>" placeholder="Rechercher..."><button class="btn btn-ghost btn-sm" type="submit">OK</button></form>
    </div>
    <div class="table-wrap">
        <?php if (!$rows): ?><div class="empty"><?= icon('wrench', 34) ?><p>Aucune demande. <a href="<?= e($self) ?>?action=add">Créer une demande</a></p></div><?php else: ?>
        <table class="table">
            <thead><tr><th>#</th><th>Demande</th><th>Priorité</th><th>Statut</th><th>Date</th><th class="right">Suivi</th></tr></thead>
            <tbody>
            <?php foreach ($rows as $r): ?>
                <tr>
                    <td class="muted"><?= (int) $r['id'] ?></td>
                    <td><span class="cell-main"><?= e($r['titre']) ?></span><br><small class="muted"><?= e(mb_strimwidth($r['description'] ?? '', 0, 60, '…')) ?></small></td>
                    <td><?= badge($r['priorite']) ?></td>
                    <td><?= badge($r['statut']) ?></td>
                    <td class="nowrap muted"><?= e(format_date($r['date_demande'])) ?></td>
                    <td class="right"><a class="btn btn-outline btn-sm" href="<?= e($self) ?>?view=<?= (int) $r['id'] ?>"><?= icon('eye', 14) ?> Voir</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
