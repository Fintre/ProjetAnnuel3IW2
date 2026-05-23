<main class="profil">
    <section class="profil-head screen-size">
        <span class="profil-head-tag">MON PROFIL</span>
        <h1 class="profil-head-title">Préférences.</h1>
    </section>

    <section class="profil-body screen-size">
        <aside class="profil-side">
            <div class="profil-side-user">
                <span class="profil-side-avatar"><svg class="profil-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M16 7C16 9.20914 14.2091 11 12 11C9.79086 11 8 9.20914 8 7C8 4.79086 9.79086 3 12 3C14.2091 3 16 4.79086 16 7Z" stroke="#faf6ec" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M12 14C8.13401 14 5 17.134 5 21H19C19 17.134 15.866 14 12 14Z" stroke="#faf6ec" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg></span>
                <div class="profil-side-user-meta">
                    <p class="profil-side-user-name"><?= $_SESSION["name"] ?? "" ?> <?= $_SESSION["lastname"] ?? "" ?></p>
                    <p class="profil-side-user-email"><?= $_SESSION["email"] ?? "" ?></p>
                </div>
            </div>

            <ul class="profil-side-nav">
                <li>
                    <a href="/profil" class="profil-side-link profil-side-link-active">
                        <svg class="profil-side-icon" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="8" cy="8" r="6" fill="#d35220"/>
                            <path d="M8 3 A5 5 0 0 1 8 13 Z" fill="#faf6ec"/>
                        </svg>
                        Profil
                    </a>
                </li>
                <li>
                    <a href="/abonnement" class="profil-side-link">
                        <svg class="profil-side-icon" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                            <rect x="4" y="4" width="8" height="8" transform="rotate(45 8 8)" stroke="currentColor" stroke-width="1.2" fill="none"/>
                        </svg>
                        Abonnement
                    </a>
                </li>
            </ul>

            <div class="profil-side-plan">
                <p class="profil-side-plan-label">PLAN ACTUEL</p>
                <p class="profil-side-plan-name">Roarr Plus</p>
                <p class="profil-side-plan-price">€4,90/mois</p>
            </div>
        </aside>

        <div class="profil-main">
            <div class="profil-main-head">
                <h2 class="profil-main-title">Profil personnel</h2>
                <p class="profil-main-sub">Tes informations de base. Visibles uniquement par toi.</p>
            </div>

            <form method="POST" action="/updateUser" class="profil-form">
                <div class="profil-field">
                    <span class="profil-field-label">PRÉNOM</span>
                    <input class="profil-field-input" type="text" name="name" value="<?= $_SESSION["name"] ?? "" ?>" placeholder="Votre prénom">
                    <button type="submit" class="profil-field-btn">MODIFIER</button>
                </div>
                <div class="profil-field">
                    <span class="profil-field-label">NOM</span>
                    <input class="profil-field-input" type="text" name="lastname" value="<?= $_SESSION["lastname"] ?? "" ?>" placeholder="Votre nom de famille">
                    <button type="submit" class="profil-field-btn">MODIFIER</button>
                </div>
                <div class="profil-field">
                    <span class="profil-field-label">EMAIL</span>
                    <input class="profil-field-input" type="email" name="email" value="<?= $_SESSION["email"] ?? "" ?>" placeholder="Votre email">
                    <button type="submit" class="profil-field-btn">MODIFIER</button>
                </div>
            </form>
        </div>
    </section>
</main>
