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

            <input class="login-submit" type="submit" value="Se connecter →">
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
