<?php
/** Menu latéral : affiché uniquement pour les utilisateurs connectés */
$u = current_user();
$role = $u['role'];

$menus = [
    'admin' => [
        ['dashboard.php', 'home', 'Tableau de bord'],
        ['clients.php', 'users', 'Clients'],
        ['employees.php', 'user', 'Employés'],
        ['devices.php', 'device', 'Appareils'],
        ['interventions.php', 'wrench', 'Interventions'],
        ['alerts.php', 'alert', 'Alertes'],
        ['reports.php', 'report', 'Rapports'],
        ['services.php', 'shield', 'Services'],
        ['users.php', 'settings', 'Utilisateurs'],
    ],
    'technicien' => [
        ['dashboard.php', 'home', 'Tableau de bord'],
        ['my-interventions.php', 'wrench', 'Mes interventions'],
        ['alerts.php', 'alert', 'Alertes'],
        ['reports.php', 'report', 'Rapports'],
    ],
    'client' => [
        ['dashboard.php', 'home', 'Tableau de bord'],
        ['my-requests.php', 'wrench', 'Mes demandes'],
        ['services.php', 'shield', 'Nos services'],
        ['reports.php', 'report', 'Mes rapports'],
    ],
];
?>
<aside class="sidebar" id="sidebar">
    <a class="brand" href="<?= url('dashboard.php') ?>">
        <img src="<?= asset('img/logo-mark.svg') ?>" alt="CyberShield" width="38" height="38">
        <span>Cyber<span>Shield</span></span>
    </a>

    <div class="sidebar-label">MENU PRINCIPAL</div>
    <nav class="side-nav">
        <?php foreach ($menus[$role] as [$file, $ico, $label]): ?>
            <a href="<?= url($file) ?>" class="<?= (($active ?? '') === $file) ? 'active' : '' ?>">
                <?= icon($ico, 19) ?><span><?= e($label) ?></span>
            </a>
        <?php endforeach; ?>
    </nav>

    <div class="sidebar-label">COMPTE</div>
    <nav class="side-nav">
        <a href="<?= url('profile.php') ?>" class="<?= (($active ?? '') === 'profile.php') ? 'active' : '' ?>">
            <?= icon('user', 19) ?><span>Mon profil</span>
        </a>
        <a href="<?= url('logout.php') ?>"><?= icon('logout', 19) ?><span>Déconnexion</span></a>
    </nav>

    <div class="sidebar-user">
        <div class="avatar"><?= e(strtoupper(mb_substr($u['prenom'], 0, 1) . mb_substr($u['nom'], 0, 1))) ?></div>
        <div>
            <strong><?= e($u['prenom'] . ' ' . $u['nom']) ?></strong>
            <small><?= e(humanize($u['role'])) ?></small>
        </div>
    </div>
</aside>
