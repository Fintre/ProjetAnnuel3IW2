<?php if (!empty($errors)): ?>
    <div class="signup-errors">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= $error ; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<main class="signup">
    <section class="signup-hero">
        <div class="signup-hero-top">
            <a href="/" class="signup-hero-logo">Roar<span class="signup-hero-logo-color">r.</span></a>
            <span class="signup-hero-badge">INSCRIPTION</span>
        </div>

        <h1 class="signup-hero-title">
            Prenez<br>
            <span class="signup-hero-title-em">l'avantage</span><br>
            sur vos<br>
            finances.
        </h1>

        <ul class="signup-hero-list">
            <li class="signup-hero-item">
                <span class="signup-hero-num">01</span>
                <span class="signup-hero-text">Connectez tous vos comptes en 2 min.</span>
            </li>
            <li class="signup-hero-item">
                <span class="signup-hero-num">02</span>
                <span class="signup-hero-text">Visualisez vos flux passés et futurs.</span>
            </li>
            <li class="signup-hero-item">
                <span class="signup-hero-num">03</span>
                <span class="signup-hero-text">Simulez l'imprévu avant qu'il arrive.</span>
            </li>
        </ul>

        <div class="signup-hero-foot">
            <div class="signup-hero-legal">
                <p>DSP2 · AGRÉMENT ACPR</p>
                <p>LECTURE SEULE · CHIFFREMENT AES-256</p>
            </div>
            <div class="signup-hero-stat">
                <p class="signup-hero-stat-num">24k+</p>
                <p class="signup-hero-stat-label">UTILISATEURS</p>
            </div>
        </div>
    </section>

    <section class="signup-form">
        <div class="signup-form-head">
            <h2 class="signup-form-title">Créer un compte</h2>
            <p class="signup-form-sub">GRATUIT · SANS CB · 30 SECONDES</p>
        </div>

        <form method="POST" action="/addUser" class="signup-fields">
            <div class="signup-field">
                <label for="signup-name" class="signup-field-label">PRÉNOM</label>
                <input id="signup-name" class="signup-field-input" type="text" value="<?= $_POST["firstname"] ?? "" ?>" name="name" placeholder="Votre prénom">
            </div>

            <div class="signup-field">
                <label for="signup-email" class="signup-field-label">ADRESSE EMAIL</label>
                <input id="signup-email" class="signup-field-input" type="email" value="<?= $_POST["email"] ?? "" ?>" required name="email" placeholder="Votre email">
            </div>

            <div class="signup-field">
                <label for="signup-pwd" class="signup-field-label">MOT DE PASSE</label>
                <input id="signup-pwd" class="signup-field-input" type="password" required name="pwd" placeholder="Votre mot de passe">
            </div>

            <div class="signup-field">
                <label for="signup-pwd-confirm" class="signup-field-label">CONFIRMER LE MOT DE PASSE</label>
                <input id="signup-pwd-confirm" class="signup-field-input" type="password" required name="pwdConfirm" placeholder="Confirmation du mot de passe">
            </div>

            <input class="signup-submit" type="submit" value="Créer mon compte →">
        </form>

        <p class="signup-form-alt">
            Déjà un compte ? <a href="/login" class="signup-form-link">Se connecter</a>
        </p>

        <ul class="signup-form-foot">
            <li class="signup-form-foot-item">SSL/TLS</li>
            <li class="signup-form-foot-point"></li>
            <li class="signup-form-foot-item">DSP2</li>
            <li class="signup-form-foot-point"></li>
            <li class="signup-form-foot-item">EU RGPD</li>
        </ul>
    </section>
</main>
