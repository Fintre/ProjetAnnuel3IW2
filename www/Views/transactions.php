<?php $headerType = 'headerFooter'; ?>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8">
            <h1>Transactions - <?= htmlspecialchars($account['short_name']) ?></h1>
            
            <a href="/transactions/form?account_id=<?= $account['id'] ?>" class="btn btn-primary mb-3">
                + Ajouter une transaction
            </a>
            <a href="/transactions/summary?account_id=<?= $account['id'] ?>" class="btn btn-info mb-3">
                Résumé
            </a>

            <?php if (empty($transactions)): ?>
                <p>Aucune transaction pour ce compte.</p>
            <?php else: ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Nom</th>
                            <th>Montant</th>
                            <th>Fréquence</th>
                            <th>Date début</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transactions as $t): ?>
                            <tr>
                                <td>
                                    <span class="badge <?= $t['type'] === 'income' ? 'bg-success' : 'bg-danger' ?>">
                                        <?= $t['type'] === 'income' ? 'Revenu' : 'Dépense' ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($t['short_name']) ?></td>
                                <td><?= number_format($t['amount'], 2, ',', ' ') ?> €</td>
                                <td>
                                    <?php 
                                    $freq = $t['frequency'] === 'ONE_TIME' ? 'Ponctuel' : 
                                            ($t['interval_months'] == 1 ? 'Mensuel' :
                                            ($t['interval_months'] == 12 ? 'Annuel' : 
                                            'Tous les ' . $t['interval_months'] . ' mois'));
                                    echo $freq;
                                    ?>
                                </td>
                                <td><?= $t['start_date'] ?></td>
                                <td>
                                    <a href="/transactions/edit?id=<?= urlencode($t['id']) ?>" class="btn btn-sm btn-warning">✏️</a>
                                    <form method="POST" action="/transactions/delete" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= htmlspecialchars($t['id']) ?>">
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Confirmer ?')">🗑️</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>
