<?php
require_once __DIR__ . '/config/database.php';
$services = DB::all('SELECT * FROM services WHERE active = 1 ORDER BY id');
$title = 'Accueil';
include __DIR__ . '/includes/header.php';
?>

<header class="public-nav">
    <a class="brand" href="<?= url('index.php') ?>">
        <img src="<?= asset('img/logo-mark.svg') ?>" alt="" width="36" height="36">
        <span>Cyber<span>Shield</span></span>
    </a>
    <nav class="links">
        <a href="#services">Services</a>
        <a href="#about">À propos</a>
        <a href="#contact">Contact</a>
    </nav>
    <div class="nav-actions">
        <a class="btn btn-ghost btn-sm" href="<?= url('login.php') ?>">Connexion</a>
        <a class="btn btn-primary btn-sm" href="<?= url('register.php') ?>">Créer un compte</a>
    </div>
</header>

<section class="hero">
    <div>
        <span class="hero-tag"><?= icon('shield', 14) ?> CYBERSÉCURITÉ & CONFIANCE</span>
        <h1>Votre sécurité<br>notre <em>mission</em>.</h1>
        <p class="lead">CyberShield protège votre entreprise contre les menaces numériques : audit, surveillance 24/7, tests d'intrusion et réponse aux incidents. Une équipe d'experts à votre service.</p>
        <div class="hero-cta">
            <a class="btn btn-primary btn-lg" href="<?= url('register.php') ?>"><?= icon('arrow', 18) ?> Demander une intervention</a>
            <a class="btn btn-outline btn-lg" href="#services">Découvrir nos services</a>
        </div>
        <div style="display:flex;gap:28px;margin-top:34px;flex-wrap:wrap">
            <div><strong style="font-size:22px">+240</strong><div class="muted" style="font-size:12px">Interventions réalisées</div></div>
            <div><strong style="font-size:22px">24/7</strong><div class="muted" style="font-size:12px">Surveillance continue</div></div>
            <div><strong style="font-size:22px">98 %</strong><div class="muted" style="font-size:12px">Clients satisfaits</div></div>
        </div>
    </div>
    <div class="hero-art"><img src="<?= asset('img/hero.svg') ?>" alt="Illustration de la protection CyberShield"></div>
</section>

<section class="section" id="services">
    <div class="section-head">
        <span class="eyebrow">NOS EXPERTISES</span>
        <h2>Des services pensés pour vous protéger</h2>
        <p>De la prévention à la réponse à incident, nous couvrons tous les besoins de votre sécurité informatique.</p>
    </div>
    <div class="grid grid-3">
        <?php foreach ($services as $s): ?>
            <article class="service-card">
                <div class="stat-icon"><?= icon($s['icone'] ?: 'shield', 22) ?></div>
                <h3><?= e($s['nom']) ?></h3>
                <p><?= e($s['description']) ?></p>
                <div class="price">À partir de <?= e(money($s['prix'])) ?></div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="section" id="about">
    <div class="grid grid-2">
        <div class="panel" style="margin:0">
            <div class="panel-body">
                <span class="eyebrow text-cyan" style="font-size:11px;font-weight:800;letter-spacing:.14em">QUI SOMMES-NOUS ?</span>
                <h2 style="margin:10px 0 14px">Une équipe passionnée par la cybersécurité</h2>
                <p class="muted">CyberShield est née d'une conviction simple : chaque organisation, quelle que soit sa taille, mérite une protection sérieuse et accessible. Nos techniciens interviennent sur site et à distance, avec une méthode rigoureuse et des outils de pointe.</p>
                <p class="muted">Notre plateforme vous donne une visibilité totale sur vos interventions, alertes et rapports, en temps réel.</p>
            </div>
        </div>
        <div class="grid grid-2" style="gap:18px">
            <div class="stat-card"><div class="stat-icon blue"><?= icon('users', 19) ?></div><div class="stat-value">12</div><div class="stat-label">Experts certifiés</div></div>
            <div class="stat-card"><div class="stat-icon purple"><?= icon('device', 19) ?></div><div class="stat-value">350+</div><div class="stat-label">Actifs surveillés</div></div>
            <div class="stat-card"><div class="stat-icon warn"><?= icon('alert', 19) ?></div><div class="stat-value">1 284</div><div class="stat-label">Menaces bloquées</div></div>
            <div class="stat-card"><div class="stat-icon"><?= icon('check', 19) ?></div><div class="stat-value">98 %</div><div class="stat-label">Satisfaction client</div></div>
        </div>
    </div>
</section>

<section class="cta-band" id="contact">
    <h2>Prêt à sécuriser votre entreprise ?</h2>
    <p class="muted" style="max-width:560px;margin:0 auto 22px">Créez votre compte client, découvrez nos services et suivez vos interventions en toute transparence.</p>
    <a class="btn btn-primary btn-lg" href="<?= url('register.php') ?>"><?= icon('shield', 18) ?> Commencer maintenant</a>
</section>

<footer class="footer">
    <strong>Cyber<span class="text-cyan">Shield</span></strong> — Société de cybersécurité · Centre Urbain, Tunis, Tunisie<br>
    <span style="color:var(--muted-2)">Projet réalisé par Siwar Hbibi & Marwa Mejri — Classe 2TSGI 2 · contact@cybershield.tn</span>
</footer>

<?php include __DIR__ . '/includes/footer.php'; ?>
