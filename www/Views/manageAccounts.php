<main class="manage">
    <section class="manage-top screen-size">
        <span class="manage-top-count"><?= count($accounts) ?> COMPTE<?= count($accounts) > 1 ? 'S' : '' ?> DÉJÀ CONNECTÉ<?= count($accounts) > 1 ? 'S' : '' ?></span>
        <a href="/accounts" class="manage-top-back">← RETOUR AU COMPTES</a>
    </section>

    <section class="manage-body screen-size">
        <div class="manage-intro">
            <span class="manage-intro-tag">§ NOUVEAU COMPTE</span>
            <h1 class="manage-intro-title">
                Ajoutez un<br>
                <span class="manage-intro-title-em">compte.</span>
            </h1>
            <p class="manage-intro-desc">
                Renseignez les caractéristiques de votre compte. Roarr s'occupe du reste — suivi, projections, alertes.
            </p>
            <p class="manage-intro-foot">* CHAMP OBLIGATOIRE</p>
        </div>

        <form method="POST" action="/createBankAccount" class="manage-form">
            <div class="manage-field">
                <div class="manage-field-head">
                    <label for="short_name" class="manage-field-label">NOM DE LA BANQUE *</label>
                    <span class="manage-field-hint">ex : BNP Paribas</span>
                </div>
                <input id="short_name" class="manage-field-input" type="text" name="short_name" required placeholder="BNP Paribas">
            </div>

            <div class="manage-field">
                <div class="manage-field-head">
                    <label for="description" class="manage-field-label">TYPE DE COMPTE</label>
                    <span class="manage-field-hint">optionnel</span>
                </div>
                <input id="description" class="manage-field-input" type="text" name="description" list="account-types" placeholder="COMPTE COURANT">
                <datalist id="account-types">
                    <option value="COMPTE COURANT">
                    <option value="LIVRET A">
                    <option value="LDDS">
                    <option value="PEA">
                    <option value="ASSURANCE VIE">
                    <option value="INVESTISSEMENTS">
                    <option value="AUTRE">
                </datalist>
            </div>

            <div class="manage-field">
                <div class="manage-field-head">
                    <label for="annual_interest_rate" class="manage-field-label">TAUX D'INTÉRÊT ANNUEL *</label>
                    <span class="manage-field-hint">ex : 3.00 pour Livret A</span>
                </div>
                <div class="manage-field-inputWrap">
                    <input id="annual_interest_rate" class="manage-field-input" type="number" name="annual_interest_rate" step="0.01" min="0" max="100" required placeholder="0">
                    <span class="manage-field-suffix">%</span>
                </div>
            </div>

            <div class="manage-field">
                <div class="manage-field-head">
                    <label for="tax_rate" class="manage-field-label">TAUX D'IMPOSITION *</label>
                    <span class="manage-field-hint">ex : 30 pour la flat tax</span>
                </div>
                <div class="manage-field-inputWrap">
                    <input id="tax_rate" class="manage-field-input" type="number" name="tax_rate" step="0.01" min="0" max="100" required placeholder="0">
                    <span class="manage-field-suffix">%</span>
                </div>
            </div>

            <div class="manage-field">
                <div class="manage-field-head">
                    <label for="solde" class="manage-field-label">SOLDE INITIAL *</label>
                    <span class="manage-field-hint">montant actuel du compte</span>
                </div>
                <div class="manage-field-inputWrap">
                    <span class="manage-field-prefix">€</span>
                    <input id="solde" class="manage-field-input manage-field-input-amount" type="number" name="solde" step="0.01" min="0" required placeholder="0,00">
                </div>
            </div>

            <button type="submit" class="manage-form-submit">
                Ajouter ce compte
                <svg class="manage-form-arrow" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13.2328 16.4569C12.9328 16.7426 12.9212 17.2173 13.2069 17.5172C13.4926 17.8172 13.9673 17.8288 14.2672 17.5431L13.2328 16.4569ZM19.5172 12.5431C19.8172 12.2574 19.8288 11.7827 19.5431 11.4828C19.2574 11.1828 18.7827 11.1712 18.4828 11.4569L19.5172 12.5431ZM18.4828 12.5431C18.7827 12.8288 19.2574 12.8172 19.5431 12.5172C19.8288 12.2173 19.8172 11.7426 19.5172 11.4569L18.4828 12.5431ZM14.2672 6.4569C13.9673 6.17123 13.4926 6.18281 13.2069 6.48276C12.9212 6.78271 12.9328 7.25744 13.2328 7.5431L14.2672 6.4569ZM19 12.75C19.4142 12.75 19.75 12.4142 19.75 12C19.75 11.5858 19.4142 11.25 19 11.25V12.75ZM5 11.25C4.58579 11.25 4.25 11.5858 4.25 12C4.25 12.4142 4.58579 12.75 5 12.75V11.25ZM14.2672 17.5431L19.5172 12.5431L18.4828 11.4569L13.2328 16.4569L14.2672 17.5431ZM19.5172 11.4569L14.2672 6.4569L13.2328 7.5431L18.4828 12.5431L19.5172 11.4569ZM19 11.25L5 11.25V12.75L19 12.75V11.25Z" fill="#faf6ec"></path></svg>
            </button>
        </form>
    </section>
</main>
