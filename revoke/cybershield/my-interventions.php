<?php
require_once __DIR__ . '/config/database.php';
require_role('technicien');
$u = current_user();
$empId = (int) DB::value('SELECT id FROM employees WHERE user_id = ?', [$u['id']]);
$self = url('my-interventions.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'save') {
    $id = (int) ($_POST['id'] ?? 0);
    $statut = $_POST['statut'] ?? 'en_attente';
    $notes = trim($_POST['notes'] ?? '');
    $dateFin = trim($_POST['date_fin'] ?? '');
    $allowed = array_keys(status_options('intervention'));
    if ($id && in_array($statut, $allowed, true)) {
        $data = ['statut' => $statut, 'notes' => $notes];
        if ($dateFin !== '') $data['date_fin'] = str_replace('T', ' ', substr($dateFin, 0, 16)) . ':00';
        DB::update('interventions', $id, $data);
        flash('Intervention mise à jour avec succès.');
    } else {
        flash('Données invalides.', 'danger');
    }
    redirect('my-interventions.php');
}

$q = trim($_GET['q'] ?? '');
$filter = $_GET['statut'] ?? '';
$where = 'i.technicien_id = ?';
$params = [$empId];
if ($filter && in_array($filter, array_keys(status_options('intervention')), true)) { $where .= ' AND i.statut = ?'; $params[] = $filter; }
if ($q !== '') { $where .= ' AND (i.titre LIKE ? OR c.nom LIKE ? OR i.description LIKE ?)'; $params = array_merge($params, ["%$q%", "%$q%", "%$q%"]); }
$rows = DB::all("SELECT i.*, c.nom client, d.nom device FROM interventions i LEFT JOIN clients c ON i.client_id=c.id LEFT JOIN devices d ON i.device_id=d.id WHERE $where ORDER BY CASE i.statut WHEN 'en_cours' THEN 0 WHEN 'planifie' THEN 1 WHEN 'en_attente' THEN 2 ELSE 3 END, i.date_demande DESC", $params);

$edit = null;
if (($_GET['action'] ?? '') === 'edit' && !empty($_GET['id'])) {
    $edit = DB::one('SELECT i.*, c.nom client, d.nom device FROM interventions i LEFT JOIN clients c ON i.client_id=c.id LEFT JOIN devices d ON i.device_id=d.id WHERE i.id=? AND i.technicien_id=?', [(int) $_GET['id'], $empId]);
}

$title = 'Mes interventions';
$active = 'my-interventions.php';
$subtitle = 'Interventions qui vous sont assignées.';
include __DIR__ . '/includes/header.php';
?>

<?php if ($edit): ?>
<div class="panel">
    <div class="panel-head">
        <h2><?= icon('edit', 18) ?> Mettre à jour — <?= e($edit['titre']) ?></h2>
        <a class="btn btn-ghost btn-sm" href="<?= e($self) ?>">Fermer</a>
    </div>
    <div class="panel-body">
        <div class="kv" style="margin-bottom:18px">
            <div class="kv-item"><small>Client</small><strong><?= e($edit['client'] ?? '—') ?></strong></div>
            <div class="kv-item"><small>Appareil</small><strong><?= e($edit['device'] ?? '—') ?></strong></div>
            <div class="kv-item"><small>Priorité</small><strong><?= badge($edit['priorite']) ?></strong></div>
            <div class="kv-item"><small>Demandée le</small><strong><?= e(format_date($edit['date_demande'], 'd/m/Y H:i')) ?></strong></div>
        </div>
        <p class="muted"><?= e($edit['description']) ?></p>
        <form method="post" action="<?= e($self) ?>" style="margin-top:18px">
            <input type="hidden" name="action" value="save">
            <input type="hidden" name="id" value="<?= (int) $edit['id'] ?>">
            <div class="form-grid">
                <div class="field">
                    <label for="statut">Statut <span class="req">*</span></label>
                    <select class="select" name="statut" id="statut" required>
                        <?php foreach (status_options('intervention') as $k => $v): ?>
                            <option value="<?= e($k) ?>" <?= $edit['statut'] === $k ? 'selected' : '' ?>><?= e($v) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field">
                    <label for="date_fin">Date de fin</label>
                    <input class="input" type="datetime-local" name="date_fin" id="date_fin" value="<?= $edit['date_fin'] ? e(str_replace(' ', 'T', substr($edit['date_fin'], 0, 16))) : '' ?>">
                </div>
            </div>
            <div class="field"><label for="notes">Notes d'intervention</label><textarea class="input" name="notes" id="notes" placeholder="Vos observations, actions réalisées, recommandations..."><?= e($edit['notes']) ?></textarea></div>
            <div class="form-actions">
                <a class="btn btn-outline" href="<?= e($self) ?>">Annuler</a>
                <button class="btn btn-primary" type="submit"><?= icon('check', 17) ?> Enregistrer</button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<div class="page-head">
    <div><h2>Interventions assignées <span class="badge badge-muted"><?= count($rows) ?></span></h2><p>Modifiez le statut et ajoutez vos notes.</p></div>
</div>

<div class="panel">
    <div class="panel-head">
        <h2><?= icon('wrench', 18) ?> Liste</h2>
        <form class="page-actions" method="get">
            <select class="select" name="statut" data-autosubmit style="width:auto">
                <option value="">Tous les statuts</option>
                <?php foreach (status_options('intervention') as $k => $v): ?>
                    <option value="<?= e($k) ?>" <?= $filter === $k ? 'selected' : '' ?>><?= e($v) ?></option>
                <?php endforeach; ?>
            </select>
            <div class="search-box"><?= icon('search', 17) ?><input class="input" type="search" name="q" value="<?= e($q) ?>" placeholder="Rechercher..."><button class="btn btn-ghost btn-sm" type="submit">OK</button></div>
        </form>
    </div>
    <div class="table-wrap">
        <?php if (!$rows): ?><div class="empty"><?= icon('search', 34) ?><p>Aucune intervention trouvée.</p></div><?php else: ?>
        <table class="table">
            <thead><tr><th>#</th><th>Intervention</th><th>Client</th><th>Priorité</th><th>Statut</th><th>Date</th><th class="right">Action</th></tr></thead>
            <tbody>
            <?php foreach ($rows as $r): ?>
                <tr>
                    <td class="muted"><?= (int) $r['id'] ?></td>
                    <td><span class="cell-main"><?= e($r['titre']) ?></span><br><small class="muted"><?= e(mb_strimwidth($r['description'] ?? '', 0, 55, '…')) ?></small></td>
                    <td><?= e($r['client'] ?? '—') ?></td>
                    <td><?= badge($r['priorite']) ?></td>
                    <td><?= badge($r['statut']) ?></td>
                    <td class="nowrap muted"><?= e(format_date($r['date_demande'])) ?></td>
                    <td class="right"><a class="btn btn-outline btn-sm" href="<?= e($self) ?>?action=edit&id=<?= (int) $r['id'] ?>"><?= icon('edit', 14) ?> Gérer</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
