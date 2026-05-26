<?php if(empty($_SESSION['is_active'])): ?>



<main class="home">
    <section class="home-info screen-size">
        <span class="home-info-edition home-info-col">ÉDITION N°001 — MAI 2026</span>
        <span class="home-info-tagline home-info-col">LA GESTION BANCAIRE PRÉVISIONNELLE</span>
        <span class="home-info-locale home-info-col">FR · €</span>
    </section>

    <section class="home-hero screen-size">
        <p class="home-hero-pill">
            <span class="home-hero-pill-dot"></span>
            Bêta privée — places limitées
        </p>

        <h1 class="home-hero-title">
            Prévoyez<br>
            <span class="home-hero-title-em">votre épargne</span><br>
            avant<br>
            qu'elle n'arrive.
        </h1>

        <div class="home-hero-row">
            <p class="home-hero-lead">
                Roarr connecte vos comptes, lit vos flux, et vous projette dans
                <span class="home-hero-lead-em">trois, six, douze mois</span>. Une lecture
                claire de votre argent — passée, présent, à venir.
            </p>

            <div class="home-hero-cta">
                <a href="/signup" class="home-hero-btn link-button btn">Créer mon compte gratuitement
                    <svg class="home-hero-arrow" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M13.2328 16.4569C12.9328 16.7426 12.9212 17.2173 13.2069 17.5172C13.4926 17.8172 13.9673 17.8288 14.2672 17.5431L13.2328 16.4569ZM19.5172 12.5431C19.8172 12.2574 19.8288 11.7827 19.5431 11.4828C19.2574 11.1828 18.7827 11.1712 18.4828 11.4569L19.5172 12.5431ZM18.4828 12.5431C18.7827 12.8288 19.2574 12.8172 19.5431 12.5172C19.8288 12.2173 19.8172 11.7426 19.5172 11.4569L18.4828 12.5431ZM14.2672 6.4569C13.9673 6.17123 13.4926 6.18281 13.2069 6.48276C12.9212 6.78271 12.9328 7.25744 13.2328 7.5431L14.2672 6.4569ZM19 12.75C19.4142 12.75 19.75 12.4142 19.75 12C19.75 11.5858 19.4142 11.25 19 11.25V12.75ZM5 11.25C4.58579 11.25 4.25 11.5858 4.25 12C4.25 12.4142 4.58579 12.75 5 12.75V11.25ZM14.2672 17.5431L19.5172 12.5431L18.4828 11.4569L13.2328 16.4569L14.2672 17.5431ZM19.5172 11.4569L14.2672 6.4569L13.2328 7.5431L18.4828 12.5431L19.5172 11.4569ZM19 11.25L5 11.25V12.75L19 12.75V11.25Z" fill="#faf6ec"></path> </g></svg>
                </a>
                <ul class="home-hero-perks">
                    <li>✓ Sans carte bancaire</li>
                    <li>✓ DSP2 agréé</li>
                    <li>✓ 14 jours d'essai</li>
                </ul>
            </div>
        </div>
    </section>

    <section class="home-stats screen-size">
        <p class="home-stats-label">EN CHIFFRES</p>
        <ul class="home-stats-list">
            <li class="home-stats-item">3 mois de dev</li>
            <li class="home-stats-item-point"></li>
            <li class="home-stats-item">12 features livrées</li>
            <li class="home-stats-item-point"></li>
            <li class="home-stats-item">0 fuite de données</li>
        </ul>
    </section>

    <section class="home-method screen-size">
        <div class="home-method-head">
            <h2 class="home-method-title">
                Une approche <span class="home-method-title-em">précise</span><br>
                de vos finances.
            </h2>
            <span class="home-method-tag">01 — MÉTHODE</span>
        </div>

        <div class="home-cards">
            <div class="home-card">
                <div class="home-card-head">
                    <p class="home-card-num">01</p>
                    <p class="home-card-pill">DSP2</p>
                </div>
                <div class="home-card-body">
                    <h3 class="home-card-title">Connectez</h3>
                    <p class="home-card-desc">
                        Reliez tous vos comptes en moins de 2 minutes. Open Banking sécurisé, lecture seule, jamais d'écriture.
                    </p>
                </div>
            </div>
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
        </div>
    </section>
</main>

<?php endif; ?>

