<?php $headerType = 'headerFooter'; ?>
<div class="container mt-5">
    <h2>Résumé - <?= htmlspecialchars($account['short_name']) ?></h2>
    
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Revenus</h5>
                    <h2 class="text-success"><?= number_format($total_income, 2, ',', ' ') ?> €</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Dépenses</h5>
                    <h2 class="text-danger"><?= number_format($total_expense, 2, ',', ' ') ?> €</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Bilan</h5>
                    <h2 class="<?= $balance >= 0 ? 'text-success' : 'text-danger' ?>">
                        <?= number_format($balance, 2, ',', ' ') ?> €
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <p class="mt-3">Période: <?= $start_date ?> au <?= $end_date ?></p>
    <a href="/transactions?account_id=<?= $account['id'] ?>" class="btn btn-primary">Retour</a>
</div>
