<?php
/**
 * Moteur CRUD générique : affiche liste + recherche + formulaire ajout/édition + suppression.
 * Chaque page définit un tableau $cfg puis appelle crud_render($cfg).
 */

function crud_field_value(array $field, ?array $row): string
{
    $name = $field['name'];
    if ($row !== null && array_key_exists($name, $row) && $field['type'] !== 'password') {
        return (string) $row[$name];
    }
    return (string) ($field['default'] ?? '');
}

function crud_render_field(array $field, ?array $row): string
{
    $type = $field['type'] ?? 'text';
    $name = $field['name'];
    $val = crud_field_value($field, $row);
    if ($type === 'datetime-local' && $val !== '') $val = str_replace(' ', 'T', substr($val, 0, 16));
    $label = e($field['label']);
    $req = !empty($field['required']) ? ' <span class="req">*</span>' : '';
    $full = !empty($field['full']) ? ' full' : '';
    $html = '<div class="field' . $full . '"><label for="f_' . e($name) . '">' . $label . $req . '</label>';

    if ($type === 'textarea') {
        $html .= '<textarea class="input" id="f_' . e($name) . '" name="' . e($name) . '" placeholder="' . e($field['placeholder'] ?? '') . '">' . e($val) . '</textarea>';
    } elseif ($type === 'select') {
        $html .= '<select class="select" id="f_' . e($name) . '" name="' . e($name) . '"' . (!empty($field['required']) ? ' required' : '') . '>';
        if (empty($field['no_empty'])) $html .= '<option value="">— Choisir —</option>';
        foreach ($field['options'] ?? [] as $k => $l) {
            $sel = ((string) $k === $val) ? ' selected' : '';
            $html .= '<option value="' . e($k) . '"' . $sel . '>' . e($l) . '</option>';
        }
        $html .= '</select>';
    } else {
        $step = ($type === 'number') ? ' step="0.01"' : '';
        $html .= '<input class="input" type="' . e($type) . '" id="f_' . e($name) . '" name="' . e($name) . '" value="' . e($val) . '" placeholder="' . e($field['placeholder'] ?? '') . '"' . $step . (!empty($field['required']) ? ' required' : '') . '>';
    }
    if (!empty($field['hint'])) $html .= '<div class="hint">' . e($field['hint']) . '</div>';
    return $html . '</div>';
}

function crud_render(array $cfg): void
{
    $table = $cfg['table'];
    $pk = $cfg['idcol'] ?? $table . '.id';
    $self = url(basename($_SERVER['PHP_SELF']));
    $canAdd = $cfg['can_add'] ?? true;
    $canEdit = $cfg['can_edit'] ?? true;
    $canDelete = $cfg['can_delete'] ?? true;

    $action = $_POST['action'] ?? $_GET['action'] ?? '';
    $id = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);

    /* ------------ Traitement des actions ------------ */
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($action === 'delete' && $canDelete && $id) {
            DB::delete($table, $id);
            flash(($cfg['item'] ?? 'Élément') . ' supprimé avec succès.');
            redirect(basename($_SERVER['PHP_SELF']));
        }
        if ($action === 'save') {
            $data = [];
            foreach ($cfg['fields'] as $f) {
                $name = $f['name'];
                $v = $_POST[$name] ?? '';
                if (is_array($v)) $v = implode(',', $v);
                $v = trim((string) $v);
                if (!empty($f['required']) && $v === '' && !($f['type'] === 'password' && $id)) {
                    flash('Le champ « ' . $f['label'] . ' » est obligatoire.', 'danger');
                    redirect(basename($_SERVER['PHP_SELF']) . '?action=' . ($id ? 'edit' : 'add') . '&id=' . $id);
                }
                if ($f['type'] === 'password' && $v === '') continue;
                if ($f['type'] === 'password') $v = password_hash($v, PASSWORD_DEFAULT);
                if ($f['type'] === 'number' && $v === '') $v = 0;
                if ($f['type'] === 'datetime-local' && $v !== '') $v = str_replace('T', ' ', substr($v, 0, 16)) . ':00';
                $data[$name] = $v;
            }
            if (!empty($cfg['before_save']) && is_callable($cfg['before_save'])) {
                $data = $cfg['before_save']($data, $id);
            }
            if ($id && $canEdit) {
                DB::update($table, $id, $data);
                flash(($cfg['item'] ?? 'Élément') . ' mis à jour avec succès.');
                $savedId = $id;
            } else {
                $savedId = DB::insert($table, $data);
                flash(($cfg['item'] ?? 'Élément') . ' ajouté avec succès.');
            }
            if (!empty($cfg['after_save']) && is_callable($cfg['after_save'])) {
                $cfg['after_save']($data, $savedId);
            }
            redirect(basename($_SERVER['PHP_SELF']));
        }
    }

    /* ------------ Ligne en cours d'édition ------------ */
    $editRow = null;
    if ($action === 'edit' && $id) {
        $editRow = DB::one("SELECT * FROM $table WHERE id = ?", [$id]);
    }

    /* ------------ Liste + recherche ------------ */
    $q = trim($_GET['q'] ?? '');
    $where = $cfg['where'] ?? '1=1';
    $params = $cfg['params'] ?? [];
    if ($q !== '' && !empty($cfg['search'])) {
        $parts = [];
        foreach ($cfg['search'] as $col) { $parts[] = "$col LIKE ?"; $params[] = "%$q%"; }
        $where .= ' AND (' . implode(' OR ', $parts) . ')';
    }
    $selectCols = [$pk . ' AS __id'];
    foreach ($cfg['columns'] as $c) $selectCols[] = $c['col'] . (isset($c['alias']) ? ' AS ' . $c['alias'] : '');
    $sql = 'SELECT ' . implode(', ', $selectCols) . ' FROM ' . $cfg['from'] . ' WHERE ' . $where . ' ORDER BY ' . ($cfg['order'] ?? $pk . ' DESC') . ' LIMIT 300';
    $rows = DB::all($sql, $params);

    /* ------------ Formulaire ------------ */
    if (($action === 'add' && $canAdd) || ($action === 'edit' && $canEdit && $editRow)): ?>
        <div class="panel">
            <div class="panel-head">
                <h2><?= icon($action === 'edit' ? 'edit' : 'plus', 18) ?> <?= $action === 'edit' ? 'Modifier' : 'Ajouter' ?> — <?= e($cfg['item'] ?? $cfg['title']) ?></h2>
                <a class="btn btn-ghost btn-sm" href="<?= e($self) ?>">Fermer</a>
            </div>
            <div class="panel-body">
                <form method="post" action="<?= e($self) ?>">
                    <input type="hidden" name="action" value="save">
                    <input type="hidden" name="id" value="<?= $editRow ? (int) $editRow['id'] : 0 ?>">
                    <div class="form-grid">
                        <?php foreach ($cfg['fields'] as $f) echo crud_render_field($f, $editRow); ?>
                    </div>
                    <div class="form-actions">
                        <a class="btn btn-outline" href="<?= e($self) ?>">Annuler</a>
                        <button class="btn btn-primary" type="submit"><?= icon('check', 17) ?> Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <?php /* ------------ Liste ------------ */ ?>
    <div class="panel">
        <div class="panel-head">
            <h2><?= icon($cfg['icon'] ?? 'users', 18) ?> <?= e($cfg['title']) ?> <span class="badge badge-muted"><?= count($rows) ?></span></h2>
            <div class="page-actions">
                <form class="search-box" method="get">
                    <?= icon('search', 17) ?>
                    <input class="input" type="search" name="q" value="<?= e($q) ?>" placeholder="Rechercher...">
                    <button class="btn btn-ghost btn-sm" type="submit">OK</button>
                </form>
                <?php if ($canAdd): ?>
                    <a class="btn btn-primary btn-sm" href="<?= e($self) ?>?action=add"><?= icon('plus', 16) ?> Ajouter</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="table-wrap">
            <?php if (!$rows): ?>
                <div class="empty"><?= icon('search', 34) ?><p>Aucun résultat<?= $q !== '' ? ' pour « ' . e($q) . ' »' : '' ?>.</p></div>
            <?php else: ?>
                <table class="table">
                    <thead><tr>
                        <?php foreach ($cfg['columns'] as $c): ?><th><?= e($c['label']) ?></th><?php endforeach; ?>
                        <?php if ($canEdit || $canDelete): ?><th class="right">Actions</th><?php endif; ?>
                    </tr></thead>
                    <tbody>
                    <?php foreach ($rows as $r): ?>
                        <tr>
                            <?php foreach ($cfg['columns'] as $c):
                                $key = $c['alias'] ?? basename($c['col']);
                                $v = $r[$key] ?? '';
                                $type = $c['type'] ?? 'text';
                                if ($type === 'badge') $out = badge($v);
                                elseif ($type === 'date') $out = e(format_date($v));
                                elseif ($type === 'datetime') $out = e(format_date($v, 'd/m/Y H:i'));
                                elseif ($type === 'money') $out = e(money($v));
                                elseif (!empty($c['main'])) $out = '<span class="cell-main">' . e($v) . '</span>';
                                else $out = e(mb_strimwidth((string) $v, 0, 70, '…'));
                            ?>
                                <td><?= $out ?></td>
                            <?php endforeach; ?>
                            <?php if ($canEdit || $canDelete): ?>
                            <td class="right">
                                <div class="actions" style="justify-content:flex-end">
                                    <?php if ($canEdit): ?>
                                        <a class="btn-icon" title="Modifier" href="<?= e($self) ?>?action=edit&id=<?= (int) $r['__id'] ?>"><?= icon('edit', 15) ?></a>
                                    <?php endif; ?>
                                    <?php if ($canDelete): ?>
                                        <form method="post" action="<?= e($self) ?>">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?= (int) $r['__id'] ?>">
                                            <button class="btn-icon danger" title="Supprimer" type="submit" data-confirm="Supprimer cet élément ?"><?= icon('trash', 15) ?></button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
<?php
}
