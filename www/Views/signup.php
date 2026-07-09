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

        <div class="signup-hero-body">
            <h1 class="signup-hero-title">
                Prenez<br>
                <span class="signup-hero-title-em">l'avantage</span><br>
                sur vos<br>
                finances.
            </h1>
            <p class="signup-hero-lead">
                Créez votre espace en quelques secondes. Ajoutez vos comptes, catégorisez vos flux et projetez votre épargne sur trois, six, douze mois et plus.
            </p>
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
                <input id="signup-name" class="signup-field-input" type="text" value="<?= $_POST["firstname"] ?? "" ?>" name="name" placeholder="Prénom">
            </div>

            <div class="signup-field">
                <label for="signup-lastname" class="signup-field-label">NOM</label>
                <input id="signup-lastname" class="signup-field-input" type="text" value="<?= $_POST["lastname"] ?? "" ?>" name="lastname" placeholder="Nom">
            </div>

            <div class="signup-field">
                <label for="signup-email" class="signup-field-label">ADRESSE EMAIL</label>
                <input id="signup-email" class="signup-field-input" type="email" value="<?= $_POST["email"] ?? "" ?>" required name="email" placeholder="Email">
            </div>

            <div class="signup-field">
                <label for="signup-pwd" class="signup-field-label">MOT DE PASSE</label>
                <input id="signup-pwd" class="signup-field-input" type="password" required name="pwd" placeholder="Mot de passe">
            </div>

            <div class="signup-field">
                <label for="signup-pwd-confirm" class="signup-field-label">CONFIRMER LE MOT DE PASSE</label>
                <input id="signup-pwd-confirm" class="signup-field-input" type="password" required name="pwdConfirm" placeholder="Confirmation du mot de passe">
            </div>

            <button class="signup-submit" type="submit">
                Créer mon compte
                <svg class="signup-submit-arrow" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M13.2328 16.4569C12.9328 16.7426 12.9212 17.2173 13.2069 17.5172C13.4926 17.8172 13.9673 17.8288 14.2672 17.5431L13.2328 16.4569ZM19.5172 12.5431C19.8172 12.2574 19.8288 11.7827 19.5431 11.4828C19.2574 11.1828 18.7827 11.1712 18.4828 11.4569L19.5172 12.5431ZM18.4828 12.5431C18.7827 12.8288 19.2574 12.8172 19.5431 12.5172C19.8288 12.2173 19.8172 11.7426 19.5172 11.4569L18.4828 12.5431ZM14.2672 6.4569C13.9673 6.17123 13.4926 6.18281 13.2069 6.48276C12.9212 6.78271 12.9328 7.25744 13.2328 7.5431L14.2672 6.4569ZM19 12.75C19.4142 12.75 19.75 12.4142 19.75 12C19.75 11.5858 19.4142 11.25 19 11.25V12.75ZM5 11.25C4.58579 11.25 4.25 11.5858 4.25 12C4.25 12.4142 4.58579 12.75 5 12.75V11.25ZM14.2672 17.5431L19.5172 12.5431L18.4828 11.4569L13.2328 16.4569L14.2672 17.5431ZM19.5172 11.4569L14.2672 6.4569L13.2328 7.5431L18.4828 12.5431L19.5172 11.4569ZM19 11.25L5 11.25V12.75L19 12.75V11.25Z" fill="#faf6ec"></path> </g></svg>
            </button>
        </form>

        <p class="signup-form-alt">
            Déjà un compte ? <a href="/login" class="signup-form-link">Se connecter</a>
        </p>
    </section>
</main>
