<?php if (!empty($errors)): ?>
    <div class="login-errors">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= $error ; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<main class="login-page">
    <section class="login-hero">
        <div class="login-hero-top">
            <a href="/" class="login-hero-logo">Roar<span class="login-hero-logo-accent">r.</span></a>
            <span class="login-hero-badge">CONNEXION</span>
        </div>

        
        <div>
            <span class="login-hero-eyebrow">BIENVENUE</span>
            <h1 class="login-hero-title">
                Reprenez<br>
                <span class="login-hero-title-em">là où vous</span><br>
                l'avez laissé.
            </h1>
        </div>

        <figure class="login-comment">
            <p class="login-comment-quote">
                « En trois mois, j'ai économisé 840 € sans m'en rendre compte. Roarr m'a juste montré où regarder. »
            </p>
            <div class="login-comment-author">
                <span class="login-comment-avatar" aria-hidden="true">C</span>
                <span class="login-comment-meta">
                    <span class="login-comment-name">Camille A.</span>
                    <span class="login-comment-role">UTILISATRICE DEPUIS FÉV. 2026</span>
                </span>
            </div>
        </figure>

        <div class="login-hero-footer">
            <div class="login-hero-legal">
                <span>DSP2 · AGRÉMENT ACPR</span>
                <span>LECTURE SEULE · CHIFFREMENT AES-256</span>
            </div>
            <div class="login-hero-status">
                <span class="login-hero-status-dot" aria-hidden="true"></span>
                <span>Tous services opérationnels</span>
            </div>
        </div>
    </section>

    <section class="login-form-panel">
        <div class="login-form-wrap">
            <div class="login-form-header">
                <h2 class="login-form-title">Se connecter</h2>
                <p class="login-form-sub">ACCÉDEZ À VOTRE ESPACE</p>
            </div>

            <form method="POST" action="/signinUser" class="login-form">
                <div class="login-field">
                    <label for="login-email" class="login-label">ADRESSE EMAIL</label>
                    <input id="login-email" class="login-input" type="email" value="<?= $_POST["email"] ?? "" ?>" required name="email" placeholder="Votre email">
                </div>

                <div class="login-field">
                    <label for="login-pwd" class="login-label">MOT DE PASSE</label>
                    <input id="login-pwd" class="login-input" type="password" required name="pwd" placeholder="Votre mot de passe">
                    <a href="/resetPassword" class="login-forgot">Mot de passe oublié ?</a>
                </div>

                <input class="login-submit" type="submit" value="Se connecter →">
            </form>

            <p class="login-alt">
                Pas encore de compte ? <a href="/signup" class="login-link">Créer un compte</a>
            </p>

            <div class="login-form-footer">
                <span class="login-form-footer-item">SSL/TLS</span>
                <span class="login-form-footer-sep">·</span>
                <span class="login-form-footer-item">DSP2</span>
                <span class="login-form-footer-sep">·</span>
                <span class="login-form-footer-item">EU RGPD</span>
            </div>
        </div>
    </section>
</main>
