<main class="manage">
    <section class="manage-top screen-size">
        <span class="manage-top-count"><?= count($accounts) ?> COMPTE<?= count($accounts) > 1 ? 'S' : '' ?></span>
        <a href="/accounts" class="manage-top-back">← RETOUR À LA VUE D'ENSEMBLE</a>
    </section>

    <section class="accounts-head screen-size" style="padding-top: 2rem;">
        <span class="accounts-head-tag">CONSULTATION —</span>
        <div class="accounts-head-row">
            <h1 class="accounts-head-title">Mes comptes bancaires.</h1>
            <a href="/formBankAccount" class="accounts-head-cta">+ Ajouter un compte</a>
        </div>
    </section>

    <?php if (empty($accounts)): ?>
        <section class="accounts-empty screen-size">
            <div class="accounts-empty-card">
                <span class="accounts-empty-tag">AUCUN COMPTE</span>
                <h2 class="accounts-empty-title">
                    Créez un compte pour<br>
                    <span class="accounts-empty-title-em">tester les transactions.</span>
                </h2>
                <p class="accounts-empty-desc">
                    Ajoutez votre premier compte bancaire, puis accédez à la gestion des transactions depuis cette page.
                </p>
                <a href="/formBankAccount" class="accounts-empty-cta">
                    Créer mon premier compte
                    <svg class="accounts-empty-arrow" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13.2328 16.4569C12.9328 16.7426 12.9212 17.2173 13.2069 17.5172C13.4926 17.8172 13.9673 17.8288 14.2672 17.5431L13.2328 16.4569ZM19.5172 12.5431C19.8172 12.2574 19.8288 11.7827 19.5431 11.4828C19.2574 11.1828 18.7827 11.1712 18.4828 11.4569L19.5172 12.5431ZM18.4828 12.5431C18.7827 12.8288 19.2574 12.8172 19.5431 12.5172C19.8288 12.2173 19.8172 11.7426 19.5172 11.4569L18.4828 12.5431ZM14.2672 6.4569C13.9673 6.17123 13.4926 6.18281 13.2069 6.48276C12.9212 6.78271 12.9328 7.25744 13.2328 7.5431L14.2672 6.4569ZM19 12.75C19.4142 12.75 19.75 12.4142 19.75 12C19.75 11.5858 19.4142 11.25 19 11.25V12.75ZM5 11.25C4.58579 11.25 4.25 11.5858 4.25 12C4.25 12.4142 4.58579 12.75 5 12.75V11.25ZM14.2672 17.5431L19.5172 12.5431L18.4828 11.4569L13.2328 16.4569L14.2672 17.5431ZM19.5172 11.4569L14.2672 6.4569L13.2328 7.5431L18.4828 12.5431L19.5172 11.4569ZM19 11.25L5 11.25V12.75L19 12.75V11.25Z" fill="#faf6ec"></path></svg>
                </a>
            </div>
        </section>
    <?php else: ?>
        <section class="accounts-list screen-size">
            <div class="accounts-list-head">
                <h2 class="accounts-list-title">Sélectionnez un compte</h2>
            </div>

            <div class="accounts-list-cards">
                <?php foreach ($accounts as $account): ?>
                    <div class="accounts-card" style="cursor: default;">
                        <div class="accounts-card-content">
                            <h3 class="accounts-card-bank"><?= htmlspecialchars($account['short_name']) ?></h3>
                            <p class="accounts-card-solde">€<?= number_format($account['solde'], 2, ',', ' ') ?></p>
                            <p class="accounts-card-type"><?= htmlspecialchars($account['description'] ?: 'Compte bancaire') ?></p>
                            <p class="accounts-card-type" style="margin-top: 0.5rem; opacity: 0.6;">
                                Taux : <?= number_format($account['annual_interest_rate'], 2, ',', ' ') ?>% · Impôt : <?= number_format($account['tax_rate'], 2, ',', ' ') ?>%
                            </p>
                        </div>
                        <div class="accounts-card-actions">
                            <a href="/transactions?account_id=<?= (int) $account['id'] ?>" class="accounts-card-link">
                                Transactions →
                            </a>
                            <a href="/accountDetails?id=<?= (int) $account['id'] ?>" class="accounts-card-link">
                                Détails →
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
</main>
