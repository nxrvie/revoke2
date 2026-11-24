<?php $u = current_user(); ?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="CyberShield — Société de cybersécurité, audit, SOC et gestion des incidents.">
    <title><?= e($title ?? APP_NAME) ?> · <?= APP_NAME ?></title>
    <link rel="icon" type="image/svg+xml" href="<?= asset('img/logo-mark.svg') ?>">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body class="<?= $u ? 'app-body' : 'public-body' ?>">

<?php if ($u): ?>
<div class="app">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <div class="main">
        <header class="topbar">
            <button class="menu-toggle" id="menuToggle" aria-label="Ouvrir le menu"><?= icon('menu', 22) ?></button>
            <div class="topbar-title">
                <h1><?= e($title ?? 'Tableau de bord') ?></h1>
                <p><?= e($subtitle ?? 'Bienvenue sur votre espace CyberShield') ?></p>
            </div>
            <div class="topbar-right">
                <?php if (in_array($u['role'], ['admin', 'technicien'], true)):
                    $notif = (int) DB::value("SELECT COUNT(*) FROM alerts WHERE statut <> 'resolue'"); ?>
                    <a class="icon-btn" href="<?= url('alerts.php') ?>" title="Alertes ouvertes">
                        <?= icon('alert', 20) ?><?php if ($notif): ?><span class="dot"><?= $notif ?></span><?php endif; ?>
                    </a>
                <?php endif; ?>
                <div class="user-chip">
                    <div class="avatar avatar-sm"><?= e(strtoupper(mb_substr($u['prenom'], 0, 1) . mb_substr($u['nom'], 0, 1))) ?></div>
                    <div class="user-chip-text"><strong><?= e($u['prenom']) ?></strong><small><?= e(humanize($u['role'])) ?></small></div>
                </div>
            </div>
        </header>
        <main class="content">
<?php else: ?>
<main class="public-main">
<?php endif; ?>

<?php foreach (get_flashes() as $f): ?>
    <div class="flash flash-<?= e($f['type']) ?>">
        <?= icon($f['type'] === 'danger' ? 'alert' : 'check', 18) ?>
        <span><?= e($f['message']) ?></span>
        <button onclick="this.parentElement.remove()" aria-label="Fermer">×</button>
    </div>
<?php endforeach; ?>
