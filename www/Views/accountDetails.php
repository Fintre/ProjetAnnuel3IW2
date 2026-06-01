<?php
$accountId = $accountId ?? null;
$account   = $account ?? null;
$typeFilter     = $typeFilter ?? 'all';
$search         = $search ?? '';
$categoryFilter = $categoryFilter ?? '';
$allTransactions = $allTransactions ?? [];
$transactions    = $transactions ?? [];
$grouped         = $grouped ?? [];
$entrées         = $entrées ?? 0;
$sorties         = $sorties ?? 0;
$soldeNet        = $soldeNet ?? 0;
$categories      = $categories ?? [];

$accountName = $account ? $account['short_name'] : 'Compte';
$accountType = $account ? $account['description'] : '';

$totalCount    = count($allTransactions);
$filteredCount = count($transactions);

$frDays   = ['Monday'=>'LUNDI','Tuesday'=>'MARDI','Wednesday'=>'MERCREDI','Thursday'=>'JEUDI','Friday'=>'VENDREDI','Saturday'=>'SAMEDI','Sunday'=>'DIMANCHE'];
$frMonths = [1=>'JANVIER',2=>'FÉVRIER',3=>'MARS',4=>'AVRIL',5=>'MAI',6=>'JUIN',7=>'JUILLET',8=>'AOÛT',9=>'SEPTEMBRE',10=>'OCTOBRE',11=>'NOVEMBRE',12=>'DÉCEMBRE'];

function formatDateLabel(string $date, array $frDays, array $frMonths): string {
    $ts   = strtotime($date);
    $day  = $frDays[date('l', $ts)];
    $num  = (int) date('j', $ts);
    $mon  = $frMonths[(int) date('n', $ts)];
    $year = date('Y', $ts);
    return "{$day} {$num} {$mon} {$year}";
}

function categoryClass(string $cat): string {
    $map = [
        'SALAIRE'    => 'acdetail-badge--salaire',
        'COURSES'    => 'acdetail-badge--courses',
        'ABONNEMENT' => 'acdetail-badge--abonnement',
        'TRANSPORT'  => 'acdetail-badge--transport',
        'LOGEMENT'   => 'acdetail-badge--logement',
    ];
    return $map[$cat] ?? 'acdetail-badge--autre';
}

$baseUrl = '/accountDetails' . ($accountId ? '?id=' . $accountId : '?');
$sep     = $accountId ? '&' : '';
?>

<main class="acdetail">

    <section class="acdetail-top screen-size">
        <div class="acdetail-top-left">
            <a href="/accounts" class="acdetail-top-back">COMPTES</a>
            <span class="acdetail-top-sep">/</span>
            <span class="acdetail-top-current"><?= htmlspecialchars(strtoupper($accountName)) ?><?= $accountType ? ' — ' . htmlspecialchars(strtoupper($accountType)) : '' ?></span>
        </div>
        <span class="acdetail-top-count"><?= $totalCount ?> TRANSACTIONS</span>
        <span class="acdetail-top-sync">SYNCHRONISÉ IL Y A 2 MIN</span>
    </section>

    <section class="acdetail-head screen-size">
        <span class="acdetail-head-tag"><?= $filteredCount ?> TRANSACTIONS</span>
        <div class="acdetail-head-row">
            <h1 class="acdetail-head-title">Mouvements.</h1>
            <a href="/addTransaction<?= $accountId ? '?id=' . $accountId : '' ?>" class="acdetail-head-cta">+ Ajouter une transaction</a>
        </div>
    </section>

    <section class="acdetail-stats screen-size">
        <div class="acdetail-stats-card">
            <p class="acdetail-stats-label">ENTRÉES</p>
            <p class="acdetail-stats-value acdetail-stats-value--pos">+ <?= number_format($entrées, 2, ',', ' ') ?> €</p>
        </div>
        <div class="acdetail-stats-card">
            <p class="acdetail-stats-label">SORTIES</p>
            <p class="acdetail-stats-value acdetail-stats-value--neg">− <?= number_format(abs($sorties), 2, ',', ' ') ?> €</p>
        </div>
        <div class="acdetail-stats-card">
            <p class="acdetail-stats-label">SOLDE NET</p>
            <p class="acdetail-stats-value <?= $soldeNet >= 0 ? 'acdetail-stats-value--pos' : 'acdetail-stats-value--neg' ?>">
                <?= $soldeNet >= 0 ? '+' : '−' ?> <?= number_format(abs($soldeNet), 2, ',', ' ') ?> €
            </p>
        </div>
    </section>

    <section class="acdetail-filters screen-size">
        <form method="GET" action="/accountDetails" class="acdetail-filters-form">
            <?php if ($accountId): ?>
                <input type="hidden" name="id" value="<?= (int)$accountId ?>">
            <?php endif; ?>
            <input type="hidden" name="type" value="<?= htmlspecialchars($typeFilter) ?>">

            <div class="acdetail-filters-search-wrap">
                <svg class="acdetail-filters-search-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M21 21L16.65 16.65M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                <input
                    type="text"
                    name="search"
                    value="<?= htmlspecialchars($search) ?>"
                    placeholder="Rechercher une transaction..."
                    class="acdetail-filters-search"
                >
            </div>

            <div class="acdetail-filters-type">
                <a href="<?= $baseUrl . $sep ?>type=all<?= $search ? '&search=' . urlencode($search) : '' ?><?= $categoryFilter ? '&category=' . urlencode($categoryFilter) : '' ?>"
                   class="acdetail-filters-btn <?= $typeFilter === 'all' ? 'acdetail-filters-btn--active' : '' ?>">TOUT</a>
                <a href="<?= $baseUrl . $sep ?>type=income<?= $search ? '&search=' . urlencode($search) : '' ?><?= $categoryFilter ? '&category=' . urlencode($categoryFilter) : '' ?>"
                   class="acdetail-filters-btn <?= $typeFilter === 'income' ? 'acdetail-filters-btn--active' : '' ?>">ENTRÉES</a>
                <a href="<?= $baseUrl . $sep ?>type=expense<?= $search ? '&search=' . urlencode($search) : '' ?><?= $categoryFilter ? '&category=' . urlencode($categoryFilter) : '' ?>"
                   class="acdetail-filters-btn <?= $typeFilter === 'expense' ? 'acdetail-filters-btn--active' : '' ?>">SORTIES</a>
            </div>

            <select name="category" class="acdetail-filters-cat" onchange="this.form.submit()">
                <option value="">TOUTES CATÉGORIES</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= htmlspecialchars($cat) ?>" <?= $categoryFilter === $cat ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <span class="acdetail-filters-count"><?= $filteredCount ?> RÉSULTAT<?= $filteredCount > 1 ? 'S' : '' ?></span>
        </form>
    </section>

    <section class="acdetail-table screen-size">
        <div class="acdetail-table-head">
            <span class="acdetail-col-label">DATE</span>
            <span class="acdetail-col-label">LIBELLÉ</span>
            <span class="acdetail-col-label">CATÉGORIE</span>
            <span class="acdetail-col-label acdetail-col-label--right">MONTANT</span>
        </div>

        <?php if (empty($grouped)): ?>
            <div class="acdetail-empty">
                <p class="acdetail-empty-text">Aucune transaction ne correspond à vos filtres.</p>
            </div>
        <?php else: ?>
            <?php foreach ($grouped as $date => $rows): ?>
                <div class="acdetail-group">
                    <div class="acdetail-group-label"><?= formatDateLabel($date, $frDays, $frMonths) ?></div>
                    <?php foreach ($rows as $t): ?>
                        <div class="acdetail-row">
                            <span class="acdetail-row-date"><?= (int)date('j', strtotime($t['date'])) ?> <?= strtolower(substr($frMonths[(int)date('n', strtotime($t['date']))], 0, 4)) ?></span>
                            <span class="acdetail-row-libelle"><?= htmlspecialchars($t['label']) ?></span>
                            <span class="acdetail-row-cat">
                                <span class="acdetail-badge <?= categoryClass($t['category']) ?>"><?= htmlspecialchars($t['category']) ?></span>
                            </span>
                            <span class="acdetail-row-amount <?= $t['amount'] >= 0 ? 'acdetail-row-amount--pos' : 'acdetail-row-amount--neg' ?>">
                                <?= $t['amount'] >= 0 ? '+ ' : '− ' ?><?= number_format(abs($t['amount']), 2, ',', ' ') ?> €
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

</main>
