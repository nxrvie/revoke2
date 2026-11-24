<?php
require_once __DIR__ . '/config/database.php';

if (current_user()) redirect('dashboard.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass = $_POST['password'] ?? '';
    $user = DB::one('SELECT * FROM users WHERE email = ?', [$email]);
    if (!$user || !password_verify($pass, $user['password'])) {
        $error = 'Adresse e-mail ou mot de passe incorrect.';
    } elseif ($user['statut'] !== 'actif') {
        $error = 'Ce compte est désactivé. Contactez l\'administrateur.';
    } else {
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $user['id'];
        flash('Bienvenue ' . $user['prenom'] . ' ! Vous êtes connecté.');
        redirect('dashboard.php');
    }
}

$title = 'Connexion';
include __DIR__ . '/includes/header.php';
?>

<div class="auth-shell">
    <div class="auth-visual">
        <a class="brand" href="<?= url('index.php') ?>"><img src="<?= asset('img/logo-mark.svg') ?>" alt="" width="38" height="38"><span>Cyber<span>Shield</span></span></a>
        <div class="auth-visual-content">
            <span class="hero-tag"><?= icon('lock', 14) ?> ESPACE SÉCURISÉ</span>
            <h1>Bienvenue dans votre<br><em>espace sécurisé</em>.</h1>
            <p>Accédez à vos interventions, alertes et rapports. Une plateforme claire et sécurisée pour piloter votre cybersécurité.</p>
            <div class="auth-features">
                <div class="auth-feature"><div class="stat-icon"><?= icon('chart', 18) ?></div> Tableaux de bord et statistiques en temps réel</div>
                <div class="auth-feature"><div class="stat-icon blue"><?= icon('wrench', 18) ?></div> Suivi des interventions à chaque étape</div>
                <div class="auth-feature"><div class="stat-icon purple"><?= icon('report', 18) ?></div> Rapports détaillés accessibles à tout moment</div>
            </div>
            <img class="auth-hero-img" src="<?= asset('img/hero.svg') ?>" alt="">
        </div>
    </div>

    <div class="auth-form-side">
        <div class="auth-card">
            <h2>Connexion</h2>
            <p class="sub">Entrez vos identifiants pour accéder à votre espace.</p>

            <?php if ($error): ?><div class="flash flash-danger"><?= icon('alert', 18) ?><span><?= e($error) ?></span></div><?php endif; ?>

            <form method="post">
                <div class="field">
                    <label for="email">Adresse e-mail</label>
                    <input class="input" type="email" id="email" name="email" placeholder="vous@entreprise.tn" value="<?= e($_POST['email'] ?? '') ?>" required autofocus>
                </div>
                <div class="field">
                    <label for="password">Mot de passe</label>
                    <input class="input" type="password" id="password" name="password" placeholder="••••••••" required>
                </div>
                <div class="auth-links">
                    <a href="<?= url('register.php') ?>">Créer un compte</a>
                    <a href="<?= url('index.php') ?>">Retour à l'accueil</a>
                </div>
                <button class="btn btn-primary btn-block btn-lg" type="submit"><?= icon('lock', 18) ?> Se connecter</button>
            </form>

            <div class="demo-box">
                <strong>Comptes de démonstration — cliquez pour remplir</strong>
                <div style="display:grid;gap:6px;margin-top:8px">
                    <button type="button" class="btn btn-outline btn-sm" data-email="admin@cybershield.tn" data-pass="Admin123!">Admin — admin@cybershield.tn / Admin123!</button>
                    <button type="button" class="btn btn-outline btn-sm" data-email="tech@cybershield.tn" data-pass="Tech123!">Technicien — tech@cybershield.tn / Tech123!</button>
                    <button type="button" class="btn btn-outline btn-sm" data-email="client@cybershield.tn" data-pass="Client123!">Client — client@cybershield.tn / Client123!</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('[data-email]').forEach(function (b) {
    b.addEventListener('click', function () {
        document.getElementById('email').value = b.dataset.email;
        document.getElementById('password').value = b.dataset.pass;
    });
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
