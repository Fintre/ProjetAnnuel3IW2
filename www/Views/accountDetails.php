    <?php
    $accountId = $accountId ?? null;
    $account   = $account ?? null;
    $typeFilter     = $typeFilter ?? 'all';
    $search         = $search ?? '';
    $categoryFilter = $categoryFilter ?? '';
    $allTransactions = $allTransactions ?? [];
    $transactions    = $transactions ?? [];
    $grouped         = $grouped ?? [];
    $entrees         = $entrees ?? 0;
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

    function categoryClass(?string $cat): string {
        if ($cat === null) return 'acdetail-badge--autre';
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
            <div class="acdetail-head-row">
                <h1 class="acdetail-head-title">Opériations</h1>
                <button type="button" class="acdetail-head-cta" onclick="document.getElementById('modal-add-transaction').classList.add('acdetail-modal--open')">+ Ajouter une transaction</button>
            </div>
        </section>

        <section class="acdetail-stats screen-size">
            <div class="acdetail-stats-card">
                <p class="acdetail-stats-label">ENTRÉES</p>
                <p class="acdetail-stats-value acdetail-stats-value--pos">+ <?= number_format($entrees, 2, ',', ' ') ?> €</p>
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
<span class="acdetail-row-date"><?= (int)date('j', strtotime($t['start_date'])) ?></span>        <span class="acdetail-row-libelle"><?= htmlspecialchars($t['short_name']) ?></span>
        <span class="acdetail-row-cat">
    <span class="acdetail-badge <?= categoryClass($t['category'] ?? null) ?>"><?= htmlspecialchars($t['category'] ?? '') ?></span>    </span>
    <span class="acdetail-row-amount <?= $t['type'] === 'income' ? 'acdetail-row-amount--pos' : 'acdetail-row-amount--neg' ?>">
        <?= $t['type'] === 'income' ? '+ ' : '− ' ?><?= number_format(abs($t['amount']), 2, ',', ' ') ?> €
    </span>
        <span class="acdetail-row-actions">
            <a href="/transactions/edit?id=<?= htmlspecialchars($t['id']) ?>" class="acdetail-row-btn acdetail-row-btn--edit">✎</a>
            <form method="POST" action="/transactions/delete" style="display:inline" onsubmit="return confirm('Supprimer cette transaction ?')">
                <input type="hidden" name="id" value="<?= htmlspecialchars($t['id']) ?>">
                <button type="submit" class="acdetail-row-btn acdetail-row-btn--delete">✕</button>
            </form>
        </span>
    </div>

                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>






    <section class="proj screen-size">
    
        <div class="proj-head">
            <span class="proj-tag">PROJECTION</span>
            <h2 class="proj-title">Simulation.</h2>
            <p class="proj-desc">Estimez l'état de ce compte à une date future en tenant compte des transactions récurrentes, des intérêts annuels (<?= number_format($account['annual_interest_rate'] ?? 0, 2) ?>%) et du taux d'imposition (<?= number_format($account['tax_rate'] ?? 0, 2) ?>%).</p>
        </div>
    
        <div class="proj-controls">
            <div class="proj-control-group">
                <label class="proj-label" for="proj-date">DATE CIBLE</label>
                <input type="date" id="proj-date" class="proj-date-input"
                    min="<?= date('Y-m-d', strtotime('+1 month')) ?>"
                    value="<?= date('Y-m-d', strtotime('+12 months')) ?>">
                <span class="proj-date-hint" id="proj-date-hint"></span>
            </div>
        </div>
    
        <div class="proj-sim">
            <div class="proj-sim-head">
                <span class="proj-sim-label">TRANSACTIONS SIMULÉES <span class="proj-sim-hint">(non sauvegardées)</span></span>
                <button type="button" class="proj-sim-add" onclick="projAddRow()">+ Ajouter</button>
            </div>
            <div id="proj-sim-rows" class="proj-sim-rows-wrap"></div>
        </div>
    
        <div class="proj-results">
            <div class="proj-result-card proj-result-card--main">
                <p class="proj-result-label">SOLDE ESTIMÉ</p>
                <p class="proj-result-value" id="proj-final-solde">—</p>
                <p class="proj-result-sub" id="proj-months-count"></p>
            </div>
            <div class="proj-result-card">
                <p class="proj-result-label">INTÉRÊTS NETS CUMULÉS</p>
                <p class="proj-result-value proj-result-value--pos" id="proj-interests">—</p>
                <p class="proj-result-sub">Après impôt (<?= number_format($account['tax_rate'] ?? 0, 2) ?>%)</p>
            </div>
            <div class="proj-result-card">
                <p class="proj-result-label">FLUX RÉCURRENTS CUMULÉS</p>
                <p class="proj-result-value" id="proj-recurring">—</p>
                <p class="proj-result-sub">Entrées − Sorties récurrentes</p>
            </div>
            <div class="proj-result-card">
                <p class="proj-result-label">REVENUS CUMULÉS</p>
                <p class="proj-result-value proj-result-value--pos" id="proj-total-income">—</p>
                <p class="proj-result-sub">Toutes entrées récurrentes</p>
            </div>
            <div class="proj-result-card">
                <p class="proj-result-label">DÉPENSES CUMULÉES</p>
                <p class="proj-result-value proj-result-value--neg" id="proj-total-expense">—</p>
                <p class="proj-result-sub">Toutes sorties récurrentes</p>
            </div>
        </div>
    
        <div class="proj-chart-wrap">
            <canvas id="proj-chart"></canvas>
        </div>
    
    </section>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <script>
    const SOLDE_INITIAL = <?= json_encode((float)($account['solde'] ?? 0)) ?>;
    const TAUX_INTERET  = <?= json_encode((float)($account['annual_interest_rate'] ?? 0)) ?>;
    const TAUX_IMPOT    = <?= json_encode((float)($account['tax_rate'] ?? 0)) ?>;
const TRANSACTIONS  = <?= json_encode(array_values($allTransactions ?? [])) ?>;    
    const dateEl = document.getElementById('proj-date');
    const dateHint = document.getElementById('proj-date-hint');
    
    function monthsBetween(from, to) {
        return (to.getFullYear() - from.getFullYear()) * 12 + (to.getMonth() - from.getMonth());
    }
    
    function updateHint(months) {
        const y = Math.floor(months / 12);
        const m = months % 12;
        let parts = [];
        if (y > 0) parts.push(y + ' an' + (y > 1 ? 's' : ''));
        if (m > 0) parts.push(m + ' mois');
        dateHint.textContent = parts.length ? '→ ' + parts.join(' et ') : '';
    }
    
    dateEl.addEventListener('change', runProjection);
    
    let simRows = [];
    let simRowId = 0;
    
    function projAddRow() {
        const id = ++simRowId;
        simRows.push({ id, label: '', type: 'expense', amount: 0, interval: 1 });
    
        const container = document.getElementById('proj-sim-rows');
        const div = document.createElement('div');
        div.className = 'proj-sim-row';
        div.id = 'sim-row-' + id;
        div.innerHTML = `
            <input class="proj-sim-input" type="text" placeholder="Libellé (ex : Loyer)" oninput="simUpdate(${id},'label',this.value)">
            <select class="proj-sim-select" onchange="simUpdate(${id},'type',this.value)">
                <option value="expense">SORTIE</option>
                <option value="income">ENTRÉE</option>
            </select>
            <input class="proj-sim-input" type="number" placeholder="Montant €" min="0" step="0.01" oninput="simUpdate(${id},'amount',parseFloat(this.value)||0)">
            <div style="display:flex;align-items:center;gap:0.3rem">
                <span style="font-size:0.7rem;opacity:0.5;white-space:nowrap">/ </span>
                <input class="proj-sim-input" type="number" placeholder="1" min="1" step="1" value="1" oninput="simUpdate(${id},'interval',parseInt(this.value)||1)" style="width:50px">
                <span style="font-size:0.7rem;opacity:0.5;white-space:nowrap">mois</span>
            </div>
            <button class="proj-sim-remove" onclick="projRemoveRow(${id})" title="Supprimer">✕</button>
        `;
        container.appendChild(div);
        runProjection();
    }
    
    function simUpdate(id, key, value) {
        const row = simRows.find(r => r.id === id);
        if (row) { row[key] = value; runProjection(); }
    }
    
    function projRemoveRow(id) {
        simRows = simRows.filter(r => r.id !== id);
        const el = document.getElementById('sim-row-' + id);
        if (el) el.remove();
        runProjection();
    }
    
    function runProjection() {
        const today    = new Date();
        today.setDate(1); 
    
        const target = dateEl.value ? new Date(dateEl.value) : null;
        if (!target || isNaN(target)) return;
    
        const months = monthsBetween(today, target);
        if (months <= 0) return;
    
        updateHint(months);
    
        const tauxMensuel    = (TAUX_INTERET / 100) / 12;
        const tauxImpotFrac  = TAUX_IMPOT / 100;
    
        let solde           = SOLDE_INITIAL;
        let totalInterests  = 0;
        let totalRecurring  = 0;
        let totalIncome     = 0;
        let totalExpense    = 0;
        const history = [{ label: 'Aujourd\'hui', solde: solde }];
    
        for (let m = 1; m <= months; m++) {
            const simDate = new Date(today.getFullYear(), today.getMonth() + m, 1);
    
    for (const t of TRANSACTIONS) {
        const start = new Date(t.start_date);

        if (t.frequency === 'ONE_TIME') {
            // Appliquer uniquement si ce mois correspond à la date de la transaction
            if (simDate.getFullYear() === start.getFullYear() && simDate.getMonth() === start.getMonth()) {
                const delta = t.type === 'income' ? parseFloat(t.amount) : -parseFloat(t.amount);
                solde += delta;
                totalRecurring += delta;
                if (t.type === 'income') totalIncome  += parseFloat(t.amount);
                else                     totalExpense += parseFloat(t.amount);
            }
        } else {
            // RECURRING
            const end = t.end_date ? new Date(t.end_date) : null;
            if (simDate < start) continue;
            if (end && simDate > end) continue;
            const interval   = parseInt(t.interval_months) || 1;
            const diffMonths = (simDate.getFullYear() - start.getFullYear()) * 12 + (simDate.getMonth() - start.getMonth());
            if (diffMonths % interval !== 0) continue;
            const delta = t.type === 'income' ? parseFloat(t.amount) : -parseFloat(t.amount);
            solde += delta;
            totalRecurring += delta;
            if (t.type === 'income') totalIncome  += parseFloat(t.amount);
            else                     totalExpense += parseFloat(t.amount);
        }
    }
    
            for (const r of simRows) {
                if (!r.amount || r.amount <= 0) continue;
                const interval = r.interval || 1;
                if ((m - 1) % interval !== 0) continue;
                const delta = r.type === 'income' ? r.amount : -r.amount;
                solde += delta;
                totalRecurring += delta;
                if (r.type === 'income') totalIncome  += r.amount;
                else                     totalExpense += r.amount;
            }
    
            if (solde > 0 && tauxMensuel > 0) {
                const interetsBruts = solde * tauxMensuel;
                const interetsNets  = interetsBruts * (1 - tauxImpotFrac);
                solde += interetsNets;
                totalInterests += interetsNets;
            }
    
            const monthNames = ['Jan','Fév','Mar','Avr','Mai','Jun','Jul','Aoû','Sep','Oct','Nov','Déc'];
            const label = (months <= 24 || m % 6 === 0 || m === months)
                ? monthNames[simDate.getMonth()] + ' ' + simDate.getFullYear()
                : '';
            history.push({ label, solde: Math.round(solde * 100) / 100 });
        }
    
        const fmt = v => (v >= 0 ? '+ ' : '− ') + Math.abs(v).toLocaleString('fr-FR', { minimumFractionDigits: 2 }) + ' €';
        const fmtSolde = v => v.toLocaleString('fr-FR', { minimumFractionDigits: 2 }) + ' €';
    
        document.getElementById('proj-final-solde').textContent   = fmtSolde(solde);
        document.getElementById('proj-interests').textContent     = fmt(totalInterests);
        document.getElementById('proj-recurring').textContent     = fmt(totalRecurring);
        document.getElementById('proj-months-count').textContent  = 'Dans ' + months + ' mois';
        document.getElementById('proj-total-income').textContent  = '+ ' + totalIncome.toLocaleString('fr-FR', { minimumFractionDigits: 2 }) + ' €';
        document.getElementById('proj-total-expense').textContent = '− ' + totalExpense.toLocaleString('fr-FR', { minimumFractionDigits: 2 }) + ' €';
    
        updateChart(history);
    }
    
    let chartInstance = null;
    
    function updateChart(history) {
        const labels = history.map(h => h.label);
        const data   = history.map(h => h.solde);
    
        if (chartInstance) {
            chartInstance.data.labels = labels;
            chartInstance.data.datasets[0].data = data;
            chartInstance.update('none');
            return;
        }
    
        const ctx = document.getElementById('proj-chart').getContext('2d');
        chartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    label: 'Solde estimé',
                    data,
                    borderColor: '#1a3a2a',
                    backgroundColor: 'rgba(26,58,42,0.07)',
                    borderWidth: 2,
                    pointRadius: history.length > 60 ? 0 : 3,
                    pointBackgroundColor: '#1a3a2a',
                    fill: true,
                    tension: 0.35,
                }]
            },
            options: {
                responsive: true,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ctx.parsed.y.toLocaleString('fr-FR', { minimumFractionDigits: 2 }) + ' €',
                            title: ctx => ctx[0].label || ''
                        },
                        filter: item => item.label !== ''
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            maxTicksLimit: 12,
                            font: { size: 11 },
                            callback: function(val, idx) {
                                return this.getLabelForValue(val) || null;
                            }
                        },
                        grid: { display: false }
                    },
                    y: {
                        ticks: {
                            callback: v => v.toLocaleString('fr-FR') + ' €',
                            font: { size: 11 }
                        },
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    }
                }
            }
        });
    }
    
    runProjection();
    </script>






    </main>

    <div class="acdetail-modal" id="modal-add-transaction">
        <div class="acdetail-modal-overlay" onclick="document.getElementById('modal-add-transaction').classList.remove('acdetail-modal--open')"></div>
        <div class="acdetail-modal-box">

            <div class="acdetail-modal-head">
                <div class="acdetail-modal-head-left">
                    <span class="acdetail-modal-tag">NOUVELLE TRANSACTION</span>
                    <h2 class="acdetail-modal-title">Ajouter.</h2>
                </div>
                <button type="button" class="acdetail-modal-close" onclick="document.getElementById('modal-add-transaction').classList.remove('acdetail-modal--open')">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                </button>
            </div>

                <form method="POST" action="/transactions/create" class="acdetail-modal-form">            <?php if ($accountId): ?>
                    <input type="hidden" name="account_id" value="<?= (int)$accountId ?>">
                <?php endif; ?>

                <div class="manage-field">
                    <div class="manage-field-head">
                        <label class="manage-field-label">TYPE *</label>
                    </div>
                    <div class="acdetail-modal-toggle-row">
                        <label class="acdetail-modal-toggle-btn">
                            <input type="radio" name="type" value="income" required>
                            <span>ENTRÉE</span>
                        </label>
                        <label class="acdetail-modal-toggle-btn">
                            <input type="radio" name="type" value="expense">
                            <span>SORTIE</span>
                        </label>
                    </div>
                </div>

                <div class="manage-field">
                    <div class="manage-field-head">
                        <label for="tx-label" class="manage-field-label">LIBELLÉ *</label>
                        <span class="manage-field-hint">ex : Carrefour Market</span>
                    </div>
                    <input id="tx-label" class="manage-field-input" type="text" name="short_name" required placeholder="Intitulé de la transaction">
                </div>

                <div class="manage-field">
                    <div class="manage-field-head">
                        <label for="tx-category" class="manage-field-label">CATÉGORIE</label>
                        <span class="manage-field-hint">optionnel</span>
                    </div>
                    <input id="tx-category" class="manage-field-input" type="text" name="category" list="tx-category-list" placeholder="COURSES">
                    <datalist id="tx-category-list">
                        <option value="SALAIRE">
                        <option value="COURSES">
                        <option value="ABONNEMENT">
                        <option value="TRANSPORT">
                        <option value="LOGEMENT">
                        <option value="AUTRE">
                    </datalist>
                </div>

                <div class="manage-field">
                    <div class="manage-field-head">
                        <label for="tx-amount" class="manage-field-label">MONTANT *</label>
                        <span class="manage-field-hint">valeur positive</span>
                    </div>
                    <div class="manage-field-inputWrap">
                        <span class="manage-field-prefix">€</span>
                        <input id="tx-amount" class="manage-field-input manage-field-input-amount" type="number" name="amount" step="0.01" min="0" required placeholder="0,00">
                    </div>
                </div>

                <div class="manage-field">
                    <div class="manage-field-head">
                        <label class="manage-field-label">FRÉQUENCE *</label>
                    </div>
                    <div class="acdetail-modal-toggle-row">
                        <label class="acdetail-modal-toggle-btn">
                            <input type="radio" name="frequency" value="ONE_TIME" required checked onchange="txToggleFreq(this.value)">
                            <span>PONCTUELLE</span>
                        </label>
                        <label class="acdetail-modal-toggle-btn">
                            <input type="radio" name="frequency" value="RECURRING" onchange="txToggleFreq(this.value)">
                            <span>RÉCURRENTE</span>
                        </label>
                    </div>
                </div>

                <div id="tx-fields-onetime">
                    <div class="manage-field">
                        <div class="manage-field-head">
                            <label for="tx-date-once" class="manage-field-label">DATE *</label>
                        </div>
    <input id="tx-date-once" class="manage-field-input" type="date" name="start_date" autocomplete="off">                </div>
                </div>

                <div id="tx-fields-recurring" class="acdetail-modal-hidden">
                    <div class="acdetail-modal-recurring-grid">
                        <div class="manage-field">
                            <div class="manage-field-head">
                                <label for="tx-start" class="manage-field-label">DATE DE DÉBUT *</label>
                            </div>
<input id="tx-start" class="manage-field-input" type="date" name="recurrence_start_date" value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="manage-field">
                            <div class="manage-field-head">
                                <label for="tx-end" class="manage-field-label">DATE DE FIN</label>
                                <span class="manage-field-hint">optionnel</span>
                            </div>
                            <input id="tx-end" class="manage-field-input" type="date" name="end_date">
                        </div>
                    </div>
                    <div class="manage-field" style="margin-top:1.1rem">
                        <div class="manage-field-head">
                            <label for="tx-interval" class="manage-field-label">TOUS LES</label>
                            <span class="manage-field-hint">intervalle en mois</span>
                        </div>
                        <div class="manage-field-inputWrap">
                            <input id="tx-interval" class="manage-field-input" type="number" name="interval_months" min="1" max="60" value="1" placeholder="1">
                            <span class="manage-field-suffix">MOIS</span>
                        </div>
                    </div>
                </div>

                <div class="manage-field">
                    <div class="manage-field-head">
                        <label for="tx-desc" class="manage-field-label">DESCRIPTION</label>
                        <span class="manage-field-hint">optionnel</span>
                    </div>
                    <input id="tx-desc" class="manage-field-input" type="text" name="description" placeholder="Précisions supplémentaires…">
                </div>

                <button type="submit" class="manage-form-submit">
                    Ajouter la transaction
                    <svg class="manage-form-arrow" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13.2328 16.4569C12.9328 16.7426 12.9212 17.2173 13.2069 17.5172C13.4926 17.8172 13.9673 17.8288 14.2672 17.5431L13.2328 16.4569ZM19.5172 12.5431C19.8172 12.2574 19.8288 11.7827 19.5431 11.4828C19.2574 11.1828 18.7827 11.1712 18.4828 11.4569L19.5172 12.5431ZM18.4828 12.5431C18.7827 12.8288 19.2574 12.8172 19.5431 12.5172C19.8288 12.2173 19.8172 11.7426 19.5172 11.4569L18.4828 12.5431ZM14.2672 6.4569C13.9673 6.17123 13.4926 6.18281 13.2069 6.48276C12.9212 6.78271 12.9328 7.25744 13.2328 7.5431L14.2672 6.4569ZM19 12.75C19.4142 12.75 19.75 12.4142 19.75 12C19.75 11.5858 19.4142 11.25 19 11.25V12.75ZM5 11.25C4.58579 11.25 4.25 11.5858 4.25 12C4.25 12.4142 4.58579 12.75 5 12.75V11.25ZM14.2672 17.5431L19.5172 12.5431L18.4828 11.4569L13.2328 16.4569L14.2672 17.5431ZM19.5172 11.4569L14.2672 6.4569L13.2328 7.5431L18.4828 12.5431L19.5172 11.4569ZM19 11.25L5 11.25V12.75L19 12.75V11.25Z" fill="#faf6ec"></path></svg>
                </button>
            </form>

        </div>
    </div>

    <script>
        document.getElementById('tx-date-once').value = '';
    function txToggleFreq(val) {
        var onetime   = document.getElementById('tx-fields-onetime');
        var recurring = document.getElementById('tx-fields-recurring');
        var dateOnce  = document.getElementById('tx-date-once');
        var dateStart = document.getElementById('tx-start');

        if (val === 'RECURRING') {
            onetime.classList.add('acdetail-modal-hidden');
            recurring.classList.remove('acdetail-modal-hidden');
            dateOnce.removeAttribute('required');
            dateStart.setAttribute('required', '');
        } else {
            recurring.classList.add('acdetail-modal-hidden');
            onetime.classList.remove('acdetail-modal-hidden');
            dateStart.removeAttribute('required');
            dateOnce.setAttribute('required', '');
        }
    }
    </script>
