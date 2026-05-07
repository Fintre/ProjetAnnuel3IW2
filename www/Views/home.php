<?php if(empty($_SESSION['is_active'])): ?>

<div class="home-info">
    <span class="home-info-edition">ÉDITION N°001 — MAI 2026</span>
    <span class="home-info-tagline">LA GESTION BANCAIRE PRÉVISIONNELLE</span>
    <span class="home-info-locale">FR · €</span>
</div>

<main class="home">
    <section class="home-hero">
        <span class="home-hero-pill">
            <span class="home-hero-pill-dot" aria-hidden="true"></span>
            Bêta privée — places limitées
        </span>

        <h1 class="home-hero-title">
            Anticipez<br>
            <span class="home-hero-title-em">votre épargne</span><br>
            avant<br>
            qu'ils n'arrivent.
        </h1>

        <div class="home-hero-row">
            <p class="home-hero-lead">
                Roarr connecte vos comptes, lit vos flux, et vous projette dans
                <em class="home-hero-lead-em">trois, six, douze mois</em>. Une lecture
                claire de votre argent — passée, présente, à venir.
            </p>

            <div class="home-hero-cta">
                <a href="/signup" class="home-hero-btn">
                    <span>Créer mon compte gratuitement</span>
                    <span class="home-hero-btn-arrow" aria-hidden="true">→</span>
                </a>
                <ul class="home-hero-perks">
                    <li>✓ Sans carte bancaire</li>
                    <li>✓ DSP2 agréé</li>
                    <li>✓ 14 jours d'essai</li>
                </ul>
                <a href="/admin/pages" class="home-hero-admin">Voir toutes les pages</a>
            </div>
        </div>
    </section>

    <section class="home-stats">
        <span class="home-stats-label">EN CHIFFRES</span>
        <ul class="home-stats-list">
            <li class="home-stats-item">3 mois de dev</li>
            <li class="home-stats-item">12 features livrées</li>
            <li class="home-stats-item">0 fuite de données</li>
        </ul>
    </section>

    <section class="home-method">
        <header class="home-method-head">
            <h2 class="home-method-title">
                Une approche <span class="home-method-title-em">chirurgicale</span><br>
                de vos finances.
            </h2>
            <span class="home-method-tag">§ 01 — MÉTHODE</span>
        </header>

        <ol class="home-cards">
            <li class="home-card">
                <header class="home-card-head">
                    <span class="home-card-num">01</span>
                    <span class="home-card-pill">DSP2</span>
                </header>
                <div class="home-card-body">
                    <h3 class="home-card-title">Connectez</h3>
                    <p class="home-card-desc">
                        Reliez tous vos comptes en moins de 2 minutes. Open Banking sécurisé, lecture seule, jamais d'écriture.
                    </p>
                </div>
            </li>
            <li class="home-card">
                <header class="home-card-head">
                    <span class="home-card-num">02</span>
                    <span class="home-card-pill">IA CONTEXTUELLE</span>
                </header>
                <div class="home-card-body">
                    <h3 class="home-card-title">Catégorisez</h3>
                    <p class="home-card-desc">
                        Notre moteur reconnaît loyers, salaires, abonnements. Vous affinez d'un geste — il apprend de vous.
                    </p>
                </div>
            </li>
            <li class="home-card">
                <header class="home-card-head">
                    <span class="home-card-num">03</span>
                    <span class="home-card-pill">PRÉVISIONNEL</span>
                </header>
                <div class="home-card-body">
                    <h3 class="home-card-title">Projetez</h3>
                    <p class="home-card-desc">
                        Visualisez vos soldes futurs sur 3, 6 ou 12 mois. Simulez un achat, un revenu, un imprévu.
                    </p>
                </div>
            </li>
        </ol>
    </section>
</main>

<?php endif; ?>

<?php include("isActiveNav.php"); ?>
