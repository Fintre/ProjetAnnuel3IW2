<?php $headerType = 'headerFooter'; ?>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <h2>Modifier transaction</h2>
            
            <form method="POST" action="/transactions/update">
                <input type="hidden" name="id" value="<?= htmlspecialchars($transaction['id']) ?>">

                <div class="mb-3">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-control" required>
                        <option value="income" <?= $transaction['type'] === 'income' ? 'selected' : '' ?>>Revenu</option>
                        <option value="expense" <?= $transaction['type'] === 'expense' ? 'selected' : '' ?>>Dépense</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nom</label>
                    <input type="text" name="short_name" class="form-control" required value="<?= htmlspecialchars($transaction['short_name']) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="2"><?= htmlspecialchars($transaction['description']) ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Montant (€)</label>
                    <input type="number" name="amount" class="form-control" step="0.01" required value="<?= $transaction['amount'] ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Fréquence</label>
                    <select name="frequency" id="frequency" class="form-control" required>
                        <option value="ONE_TIME" <?= $transaction['frequency'] === 'ONE_TIME' ? 'selected' : '' ?>>Ponctuel</option>
                        <option value="RECURRING" <?= $transaction['frequency'] === 'RECURRING' ? 'selected' : '' ?>>Récurrent</option>
                    </select>
                </div>

                <div class="mb-3" id="interval_div" style="display:<?= $transaction['frequency'] === 'RECURRING' ? 'block' : 'none' ?>;">
                    <label class="form-label">Intervalle (en mois)</label>
                    <select name="interval_months" class="form-control">
                        <option value="1" <?= $transaction['interval_months'] == 1 ? 'selected' : '' ?>>Mensuel</option>
                        <option value="3" <?= $transaction['interval_months'] == 3 ? 'selected' : '' ?>>Trimestrial</option>
                        <option value="6" <?= $transaction['interval_months'] == 6 ? 'selected' : '' ?>>Semestriel</option>
                        <option value="12" <?= $transaction['interval_months'] == 12 ? 'selected' : '' ?>>Annuel</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Date de début</label>
                    <input type="date" name="start_date" class="form-control" required value="<?= $transaction['start_date'] ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Date de fin (optionnel)</label>
                    <input type="date" name="end_date" class="form-control" value="<?= $transaction['end_date'] ?? '' ?>">
                </div>

                <button type="submit" class="btn btn-primary">Modifier</button>
                <a href="/transactions?account_id=<?= $transaction['account_id'] ?>" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('frequency').addEventListener('change', function() {
        document.getElementById('interval_div').style.display = this.value === 'RECURRING' ? 'block' : 'none';
    });
</script>
