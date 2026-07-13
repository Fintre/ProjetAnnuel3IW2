<?php $headerType = 'headerFooter'; ?>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <h2>Ajouter une transaction</h2>
            
            <form method="POST" action="/transactions/create">
                <input type="hidden" name="account_id" value="<?= (int)$account_id ?>">

                <div class="mb-3">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-control" required>
                        <option value="">-- Sélectionner --</option>
                        <option value="income">Revenu</option>
                        <option value="expense">Dépense</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nom</label>
                    <input type="text" name="short_name" class="form-control" required placeholder="ex: Loyer, Salaire...">
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="2"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Montant (€)</label>
                    <input type="number" name="amount" class="form-control" step="0.01" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Fréquence</label>
                    <select name="frequency" id="frequency" class="form-control" required>
                        <option value="ONE_TIME">Ponctuel</option>
                        <option value="RECURRING">Récurrent</option>
                    </select>
                </div>

                <div class="mb-3" id="interval_div" style="display:none;">
                    <label class="form-label">Intervalle (en mois)</label>
                    <select name="interval_months" class="form-control">
                        <option value="1">Mensuel</option>
                        <option value="3">Trimestrial</option>
                        <option value="6">Semestriel</option>
                        <option value="12">Annuel</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Date de début</label>
                    <input type="date" name="start_date" class="form-control" required value="<?= date('Y-m-d') ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Date de fin (optionnel)</label>
                    <input type="date" name="end_date" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">Ajouter</button>
                <a href="/transactions?account_id=<?= (int)$account_id ?>" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('frequency').addEventListener('change', function() {
        document.getElementById('interval_div').style.display = this.value === 'RECURRING' ? 'block' : 'none';
    });
</script>
