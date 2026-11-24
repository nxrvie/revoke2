<?php
require_once __DIR__ . '/config/database.php';
require_login();
$u = current_user();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $tel = trim($_POST['telephone'] ?? '');
    $pass = $_POST['password'] ?? '';
    if ($nom === '' || $prenom === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Veuillez vérifier les champs obligatoires.';
    } elseif (DB::value('SELECT COUNT(*) FROM users WHERE email=? AND id<>?', [$email, $u['id']])) {
        $error = 'Cette adresse e-mail est déjà utilisée.';
    } else {
        $data = ['nom' => $nom, 'prenom' => $prenom, 'email' => $email, 'telephone' => $tel];
        if ($pass !== '') $data['password'] = password_hash($pass, PASSWORD_DEFAULT);
        DB::update('users', (int) $u['id'], $data);
        flash('Votre profil a été mis à jour.');
        redirect('profile.php');
    }
}

$title = 'Mon profil';
$active = 'profile.php';
$subtitle = 'Gérez vos informations personnelles.';
include __DIR__ . '/includes/header.php';
?>

<div class="grid grid-2">
    <div class="panel" style="margin:0">
        <div class="panel-head"><h2><?= icon('user', 18) ?> Informations personnelles</h2></div>
        <div class="panel-body">
            <?php if ($error): ?><div class="flash flash-danger"><?= icon('alert', 18) ?><span><?= e($error) ?></span></div><?php endif; ?>
            <form method="post">
                <div class="form-grid">
                    <div class="field"><label for="nom">Nom <span class="req">*</span></label><input class="input" id="nom" name="nom" value="<?= e($u['nom']) ?>" required></div>
                    <div class="field"><label for="prenom">Prénom <span class="req">*</span></label><input class="input" id="prenom" name="prenom" value="<?= e($u['prenom']) ?>" required></div>
                </div>
                <div class="field"><label for="email">E-mail <span class="req">*</span></label><input class="input" type="email" id="email" name="email" value="<?= e($u['email']) ?>" required></div>
                <div class="field"><label for="telephone">Téléphone</label><input class="input" id="telephone" name="telephone" value="<?= e($u['telephone']) ?>"></div>
                <div class="field"><label for="password">Nouveau mot de passe</label><input class="input" type="password" id="password" name="password" minlength="6"><div class="hint">Laissez vide pour ne pas le modifier.</div></div>
                <div class="form-actions"><button class="btn btn-primary" type="submit"><?= icon('check', 17) ?> Enregistrer</button></div>
            </form>
        </div>
    </div>

    <div>
        <div class="panel">
            <div class="panel-body center">
                <div class="avatar" style="width:72px;height:72px;font-size:22px;margin:0 auto 14px"><?= e(strtoupper(mb_substr($u['prenom'], 0, 1) . mb_substr($u['nom'], 0, 1))) ?></div>
                <h2><?= e($u['prenom'] . ' ' . $u['nom']) ?></h2>
                <p class="muted"><?= e($u['email']) ?></p>
                <?= badge($u['role']) ?> <?= badge($u['statut']) ?>
            </div>
        </div>
        <div class="panel">
            <div class="panel-head"><h2><?= icon('shield', 18) ?> Votre rôle</h2></div>
            <div class="panel-body">
                <?php
                $desc = [
                    'admin' => 'Vous gérez les clients, employés, appareils, interventions, alertes, rapports et services. Vous avez une vue complète sur l\'activité de CyberShield.',
                    'technicien' => 'Vous consultez les interventions qui vous sont assignées, mettez à jour leur statut et rédigez vos notes d\'intervention.',
                    'client' => 'Vous découvrez nos services, créez des demandes d\'intervention, suivez leur avancement et consultez vos rapports.',
                ];
                ?>
                <p class="muted"><?= e($desc[$u['role']]) ?></p>
                <div class="kv" style="margin-top:14px">
                    <div class="kv-item"><small>Membre depuis</small><strong><?= e(format_date($u['created_at'])) ?></strong></div>
                    <div class="kv-item"><small>Téléphone</small><strong><?= e($u['telephone'] ?: '—') ?></strong></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
