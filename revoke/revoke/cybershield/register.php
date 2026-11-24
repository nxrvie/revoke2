<?php
require_once __DIR__ . '/config/database.php';

if (current_user()) redirect('dashboard.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $tel = trim($_POST['telephone'] ?? '');
    $pass = $_POST['password'] ?? '';
    $pass2 = $_POST['password2'] ?? '';

    if ($nom === '' || $prenom === '' || $email === '' || $pass === '') {
        $error = 'Veuillez remplir tous les champs obligatoires.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Adresse e-mail invalide.';
    } elseif (strlen($pass) < 6) {
        $error = 'Le mot de passe doit contenir au moins 6 caractères.';
    } elseif ($pass !== $pass2) {
        $error = 'Les deux mots de passe ne correspondent pas.';
    } elseif (DB::value('SELECT COUNT(*) FROM users WHERE email = ?', [$email])) {
        $error = 'Cette adresse e-mail est déjà utilisée.';
    } else {
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        DB::exec("INSERT INTO users (nom, prenom, email, password, role, telephone, statut) VALUES (?,?,?,?,?,?,'actif')",
            [$nom, $prenom, $email, $hash, 'client', $tel]);
        $uid = (int) DB::pdo()->lastInsertId();
        DB::exec('INSERT INTO clients (user_id, nom, entreprise, email, telephone) VALUES (?,?,?,?,?)',
            [$uid, $prenom . ' ' . $nom, 'Client individuel', $email, $tel]);
        $_SESSION['user_id'] = $uid;
        flash('Votre compte a été créé. Bienvenue sur CyberShield !');
        redirect('dashboard.php');
    }
}

$title = 'Créer un compte';
include __DIR__ . '/includes/header.php';
?>

<div class="auth-shell">
    <div class="auth-visual">
        <a class="brand" href="<?= url('index.php') ?>"><img src="<?= asset('img/logo-mark.svg') ?>" alt="" width="38" height="38"><span>Cyber<span>Shield</span></span></a>
        <div class="auth-visual-content">
            <span class="hero-tag"><?= icon('shield', 14) ?> REJOIGNEZ CYBERSHIELD</span>
            <h1>Devenez <em>client</em><br>en quelques secondes.</h1>
            <p>Un compte pour demander vos interventions, suivre leur avancement et consulter vos rapports de sécurité.</p>
            <div class="auth-features">
                <div class="auth-feature"><div class="stat-icon"><?= icon('plus', 18) ?></div> Créez une demande d'intervention en ligne</div>
                <div class="auth-feature"><div class="stat-icon blue"><?= icon('clock', 18) ?></div> Suivez l'avancement en temps réel</div>
                <div class="auth-feature"><div class="stat-icon purple"><?= icon('report', 18) ?></div> Consultez vos rapports à tout moment</div>
            </div>
            <img class="auth-hero-img" src="<?= asset('img/hero.svg') ?>" alt="">
        </div>
    </div>

    <div class="auth-form-side">
        <div class="auth-card">
            <h2>Créer un compte</h2>
            <p class="sub">Inscription en tant que client. Les comptes Admin et Technicien sont créés par l'administrateur.</p>

            <?php if ($error): ?><div class="flash flash-danger"><?= icon('alert', 18) ?><span><?= e($error) ?></span></div><?php endif; ?>

            <form method="post">
                <div class="form-grid">
                    <div class="field"><label for="nom">Nom <span class="req">*</span></label><input class="input" id="nom" name="nom" value="<?= e($_POST['nom'] ?? '') ?>" required></div>
                    <div class="field"><label for="prenom">Prénom <span class="req">*</span></label><input class="input" id="prenom" name="prenom" value="<?= e($_POST['prenom'] ?? '') ?>" required></div>
                </div>
                <div class="field"><label for="email">Adresse e-mail <span class="req">*</span></label><input class="input" type="email" id="email" name="email" value="<?= e($_POST['email'] ?? '') ?>" required></div>
                <div class="field"><label for="telephone">Téléphone</label><input class="input" type="tel" id="telephone" name="telephone" value="<?= e($_POST['telephone'] ?? '') ?>" placeholder="+216 ..."></div>
                <div class="form-grid">
                    <div class="field"><label for="password">Mot de passe <span class="req">*</span></label><input class="input" type="password" id="password" name="password" minlength="6" required></div>
                    <div class="field"><label for="password2">Confirmer <span class="req">*</span></label><input class="input" type="password" id="password2" name="password2" minlength="6" required></div>
                </div>
                <button class="btn btn-primary btn-block btn-lg" type="submit"><?= icon('check', 18) ?> Créer mon compte</button>
            </form>
            <p class="center" style="margin-top:18px;color:var(--muted)">Déjà inscrit ? <a href="<?= url('login.php') ?>">Se connecter</a></p>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
