<?php if (!empty($errors)): ?>
    <div class="login-errors">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= $error ; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<main class="login">
    <section class="login-hero">
        <div class="login-hero-top">
            <a href="/" class="login-hero-logo">Roar<span class="login-hero-logo-color">r.</span></a>
            <span class="login-hero-badge">CONNEXION</span>
        </div>

        <span class="login-hero-eyebrow">BIENVENUE</span>

        <h1 class="login-hero-title">
            Reprenez<br>
            <span class="login-hero-title-em">là où vous</span><br>
            l'avez laissé.
        </h1>

        <div class="login-hero-quote">
            <p class="login-hero-quote-text">« En trois mois, j'ai économisé 840 € sans m'en rendre compte. Roarr m'a juste montré où regarder. »</p>
            <div class="login-hero-quote-author">
                <span class="login-hero-quote-avatar">C</span>
                <div class="login-hero-quote-meta">
                    <p class="login-hero-quote-name">Camille A.</p>
                    <p class="login-hero-quote-role">UTILISATRICE DEPUIS FÉV. 2026</p>
                </div>
            </div>
        </div>

        <div class="login-hero-foot">
            <div class="login-hero-legal">
                <p>DSP2 · AGRÉMENT ACPR</p>
                <p>LECTURE SEULE · CHIFFREMENT AES-256</p>
            </div>
            <div class="login-hero-status">
                <span class="login-hero-status-dot"></span>
                <span>Tous services opérationnels</span>
            </div>
        </div>
    </section>

    <section class="login-form">
        <div class="login-form-head">
            <h2 class="login-form-title">Se connecter</h2>
            <p class="login-form-sub">ACCÉDEZ À VOTRE ESPACE</p>
        </div>

        <form method="POST" action="/signinUser" class="login-fields">
            <div class="login-field">
                <label for="login-email" class="login-field-label">ADRESSE EMAIL</label>
                <input id="login-email" class="login-field-input" type="email" value="<?= $_POST["email"] ?? "" ?>" required name="email" placeholder="Votre email">
            </div>

            <div class="login-field">
                <div class="login-field-row">
                    <label for="login-pwd" class="login-field-label">MOT DE PASSE</label>
                    <a href="/resetPassword" class="login-field-forgot">OUBLIÉ ?</a>
                </div>
                <input id="login-pwd" class="login-field-input" type="password" required name="pwd" placeholder="Votre mot de passe">
            </div>

            <button class="login-submit" type="submit">
                Se connecter
                <svg class="login-submit-arrow" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M13.2328 16.4569C12.9328 16.7426 12.9212 17.2173 13.2069 17.5172C13.4926 17.8172 13.9673 17.8288 14.2672 17.5431L13.2328 16.4569ZM19.5172 12.5431C19.8172 12.2574 19.8288 11.7827 19.5431 11.4828C19.2574 11.1828 18.7827 11.1712 18.4828 11.4569L19.5172 12.5431ZM18.4828 12.5431C18.7827 12.8288 19.2574 12.8172 19.5431 12.5172C19.8288 12.2173 19.8172 11.7426 19.5172 11.4569L18.4828 12.5431ZM14.2672 6.4569C13.9673 6.17123 13.4926 6.18281 13.2069 6.48276C12.9212 6.78271 12.9328 7.25744 13.2328 7.5431L14.2672 6.4569ZM19 12.75C19.4142 12.75 19.75 12.4142 19.75 12C19.75 11.5858 19.4142 11.25 19 11.25V12.75ZM5 11.25C4.58579 11.25 4.25 11.5858 4.25 12C4.25 12.4142 4.58579 12.75 5 12.75V11.25ZM14.2672 17.5431L19.5172 12.5431L18.4828 11.4569L13.2328 16.4569L14.2672 17.5431ZM19.5172 11.4569L14.2672 6.4569L13.2328 7.5431L18.4828 12.5431L19.5172 11.4569ZM19 11.25L5 11.25V12.75L19 12.75V11.25Z" fill="#faf6ec"></path> </g></svg>
            </button>
        </form>

        <p class="login-form-alt">
            Pas encore de compte ? <a href="/signup" class="login-form-link">Créer un compte</a>
        </p>

        <ul class="login-form-foot">
            <li class="login-form-foot-item">SSL/TLS</li>
            <li class="login-form-foot-point"></li>
            <li class="login-form-foot-item">DSP2</li>
            <li class="login-form-foot-point"></li>
            <li class="login-form-foot-item">EU RGPD</li>
        </ul>
    </section>
</main>
