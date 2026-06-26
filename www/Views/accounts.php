<main class="accounts">
    <section class="accounts-head screen-size">
        <span class="accounts-head-tag">BONJOUR <?= strtoupper($_SESSION["name"] ?? "") ?> —</span>
        <div class="accounts-head-row">
            <h1 class="accounts-head-title">Vue d'ensemble.</h1>
            <a href="/manageAccounts" class="accounts-head-cta">+ Ajouter un compte bancaire</a>
        </div>
    </section>

    <?php if (empty($accounts)): ?>
        <section class="accounts-empty screen-size">
            <div class="accounts-empty-card">
                <span class="accounts-empty-tag">PREMIER PAS</span>
                <h2 class="accounts-empty-title">
                    Vous n'avez pas encore<br>
                    <span class="accounts-empty-title-em">de compte.</span>
                </h2>
                <p class="accounts-empty-desc">
                    Commencez dès maintenant en créant votre premier compte bancaire pour suivre vos flux, anticiper vos dépenses et projeter votre épargne.
                </p>
                <a href="/manageAccounts" class="accounts-empty-cta">
                    Créer mon premier compte
                    <svg class="accounts-empty-arrow" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13.2328 16.4569C12.9328 16.7426 12.9212 17.2173 13.2069 17.5172C13.4926 17.8172 13.9673 17.8288 14.2672 17.5431L13.2328 16.4569ZM19.5172 12.5431C19.8172 12.2574 19.8288 11.7827 19.5431 11.4828C19.2574 11.1828 18.7827 11.1712 18.4828 11.4569L19.5172 12.5431ZM18.4828 12.5431C18.7827 12.8288 19.2574 12.8172 19.5431 12.5172C19.8288 12.2173 19.8172 11.7426 19.5172 11.4569L18.4828 12.5431ZM14.2672 6.4569C13.9673 6.17123 13.4926 6.18281 13.2069 6.48276C12.9212 6.78271 12.9328 7.25744 13.2328 7.5431L14.2672 6.4569ZM19 12.75C19.4142 12.75 19.75 12.4142 19.75 12C19.75 11.5858 19.4142 11.25 19 11.25V12.75ZM5 11.25C4.58579 11.25 4.25 11.5858 4.25 12C4.25 12.4142 4.58579 12.75 5 12.75V11.25ZM14.2672 17.5431L19.5172 12.5431L18.4828 11.4569L13.2328 16.4569L14.2672 17.5431ZM19.5172 11.4569L14.2672 6.4569L13.2328 7.5431L18.4828 12.5431L19.5172 11.4569ZM19 11.25L5 11.25V12.75L19 12.75V11.25Z" fill="#faf6ec"></path></svg>
                </a>
            </div>
        </section>
    <?php else: ?>
        <section class="accounts-stats screen-size">
            <div class="accounts-stats-card">
                <p class="accounts-stats-label">PATRIMOINE TOTAL</p>
                <p class="accounts-stats-value">€<?= number_format(array_sum(array_column($accounts, 'solde')), 2, ',', ' ') ?></p>
                <p class="accounts-stats-sub">Cumul de vos comptes</p>
            </div>
            <div class="accounts-stats-card">
                <p class="accounts-stats-label">REVENUS DU MOIS</p>
                <p class="accounts-stats-value">€0</p>
                <p class="accounts-stats-sub">Pas encore de transactions</p>
            </div>
            <div class="accounts-stats-card">
                <p class="accounts-stats-label">DÉPENSES DU MOIS</p>
                <p class="accounts-stats-value">€0</p>
                <p class="accounts-stats-sub">Pas encore de transactions</p>
            </div>
            <div class="accounts-stats-card">
                <p class="accounts-stats-label">ÉPARGNE MENSUELLE</p>
                <p class="accounts-stats-value">€0</p>
                <p class="accounts-stats-sub">Pas encore de transactions</p>
            </div>
        </section>

        <section class="accounts-list screen-size">
            <div class="accounts-list-head">
                <h2 class="accounts-list-title">Mes comptes</h2>
                <a href="/manageAccounts" class="accounts-list-link">GÉRER →</a>
            </div>
<div class="accounts-list-cards">
                <?php foreach($accounts as $account): ?>
                    <div class="accounts-card" data-account-id="<?= $account['id'] ?>">
                        <!-- Lien principal vers les détails -->
                        <a href="/accountDetails?id=<?= $account['id'] ?>" class="accounts-card-link">
                            <h3 class="accounts-card-bank"><?= $account['short_name'] ?></h3>
                            <p class="accounts-card-solde">€<?= number_format($account['solde'], 2, ',', ' ') ?></p>
                            <p class="accounts-card-type"><?= $account['description'] ?></p>
                        </a>

                        <!-- Groupe d'actions (badges) -->
                        <div class="accounts-card-actions">
                            <button class="action-badge edit-badge" 
                                    onclick="openEditModal('<?= $account['id'] ?>', '<?= htmlspecialchars($account['short_name']) ?>', '<?= htmlspecialchars($account['description']) ?>')"
                                    title="Modifier">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
                            </button>
                            <button class="action-badge delete-badge" 
                                    onclick="openDeleteModal('<?= $account['id'] ?>', '<?= htmlspecialchars($account['short_name']) ?>')"
                                    title="Supprimer">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- MODALE D'ÉDITION -->
            <div id="editModal" class="modal-overlay">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title">Modifier le compte</h2>
                        <button class="close-modal" onclick="closeModal('editModal')">&times;</button>
                    </div>
                    <form action="/accountEdit" method="POST" class="manage-form">
                        <input type="hidden" name="id" id="edit-id">

                        <div class="manage-field">
                            <div class="manage-field-head">
                                <label for="edit-name" class="manage-field-label">NOM DU COMPTE *</label>
                            </div>
                            <input type="text" name="short_name" id="edit-name" class="manage-field-input" required>
                        </div>

                        <div class="manage-field">
                            <div class="manage-field-head">
                                <label for="edit-desc" class="manage-field-label">DESCRIPTION / TYPE</label>
                            </div>
                            <input type="text" name="description" id="edit-desc" class="manage-field-input" required>
                        </div>

                        <div class="manage-field">
                            <div class="manage-field-head">
                                <label for="edit-annual_interest_rate" class="manage-field-label">TAUX D'INTÉRÊT ANNUEL *</label>
                            </div>
                            <div class="manage-field-inputWrap">
                                <input type="number" name="annual_interest_rate" id="edit-annual_interest_rate" class="manage-field-input" step="0.01" min="0" required>
                                <span class="manage-field-suffix">%</span>
                            </div>
                        </div>

                        <div class="manage-field">
                            <div class="manage-field-head">
                                <label for="edit-tax_rate" class="manage-field-label">TAUX D'IMPOSITION *</label>
                            </div>
                            <div class="manage-field-inputWrap">
                                <input type="number" name="tax_rate" id="edit-tax_rate" class="manage-field-input" step="0.01" min="0" required>
                                <span class="manage-field-suffix">%</span>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn-secondary" onclick="closeModal('editModal')">Annuler</button>
                            <button type="submit" class="btn-primary">Enregistrer les modifications</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- MODALE DE SUPPRESSION -->
            <div id="deleteModal" class="modal-overlay">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title">Confirmer la suppression</h2>
                        <button class="close-modal" onclick="closeModal('deleteModal')">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p>Êtes-vous sûr de vouloir supprimer le compte <strong id="delete-account-name"></strong> ?</p>
                        <p class="txt-warning">Cette action est irréversible.</p>
                    </div>
                    <form action="/accountDelete" method="POST" class="modal-footer">
                        <input type="hidden" name="id" id="delete-id">
                        <button type="button" class="btn-secondary" onclick="closeModal('deleteModal')">Annuler</button>
                        <button type="submit" class="btn-danger">Supprimer définitivement</button>
                    </form>
                </div>
            </div>
            </div>
        </section>
    <?php endif; ?>
</main>
