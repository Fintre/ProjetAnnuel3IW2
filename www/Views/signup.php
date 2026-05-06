<?php if (!empty($errors)): ?>
    <div class="signup-errors">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= $error ; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<section class="signup-page">
    <div class="signup-hero">
        <div class="signup-hero-top">
            <a href="/" class="signup-hero-logo">Roar<span class="signup-hero-logo-accent">r.</span></a>
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

        <div class="signup-hero-footer">
            <div class="signup-hero-legal">
                <span>DSP2 · AGRÉMENT ACPR</span>
                <span>LECTURE SEULE · CHIFFREMENT AES-256</span>
            </div>
            <div class="signup-hero-stat">
                <span class="signup-hero-stat-num">24k+</span>
                <span class="signup-hero-stat-label">UTILISATEURS</span>
            </div>
        </div>
    </div>

    <main class="signup-form-panel">
        <div class="signup-form-wrap">
            <div class="signup-form-header">
                <h2 class="signup-form-title">Créer un compte</h2>
                <p class="signup-form-sub">GRATUIT · SANS CB · 30 SECONDES</p>
            </div>

            <form method="POST" action="/addUser" class="signup-form">
                <div class="signup-field">
                    <label for="signup-name" class="signup-label">PRÉNOM</label>
                    <input id="signup-name" class="signup-input" type="text" value="<?= $_POST["firstname"] ?? "" ?>" name="name" placeholder="Votre prénom">
                </div>

                <div class="signup-field">
                    <label for="signup-email" class="signup-label">ADRESSE EMAIL</label>
                    <input id="signup-email" class="signup-input" type="email" value="<?= $_POST["email"] ?? "" ?>" required name="email" placeholder="Votre email">
                </div>

                <div class="signup-field">
                    <label for="signup-pwd" class="signup-label">MOT DE PASSE</label>
                    <input id="signup-pwd" class="signup-input" type="password" required name="pwd" placeholder="Votre mot de passe">
                </div>

                <div class="signup-field">
                    <label for="signup-pwd-confirm" class="signup-label">CONFIRMER LE MOT DE PASSE</label>
                    <input id="signup-pwd-confirm" class="signup-input" type="password" required name="pwdConfirm" placeholder="Confirmation du mot de passe">
                </div>

                <input class="signup-submit" type="submit" value="Créer mon compte →">
            </form>

            <p class="signup-alt">
                Déjà un compte ? <a href="/login" class="signup-link">Se connecter</a>
            </p>

            <div class="signup-form-footer">
                <span class="signup-form-footer-item">SSL/TLS</span>
                <span class="signup-form-footer-sep">·</span>
                <span class="signup-form-footer-item">DSP2</span>
                <span class="signup-form-footer-sep">·</span>
                <span class="signup-form-footer-item">EU RGPD</span>
            </div>
        </div>
    </main>
</section>

    </form>
</div>