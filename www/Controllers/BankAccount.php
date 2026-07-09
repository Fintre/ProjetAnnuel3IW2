<?php

namespace App\Controller;

use App\Controller\Base;
use App\Model\Account;
use App\Repository\BankAccountRepository;

class BankAccount extends Base
{
    private BankAccountRepository $repository;

    public function __construct()
    {
        parent::__construct();
        $this->repository = new BankAccountRepository();
    }

    public function create(): void
    {
        $this->isAuth();

        if (
            !empty($_POST["short_name"]) &&
            isset($_POST["annual_interest_rate"]) &&
            isset($_POST["tax_rate"]) &&
            isset($_POST["solde"])
        ) {
            $userId = $this->getCurrentUserId();

            if ($userId) {
                $account = new Account();
                $account->setUserId($userId);
                $account->setShortName($_POST['short_name']);
                $account->setDescription($_POST['description'] ?? '');
                $account->setAnnualInterestRate((float) $_POST['annual_interest_rate']);
                $account->setTaxRate((float) $_POST['tax_rate']);
                $account->setSolde((float) $_POST['solde']);
                $account->setCreationDate(date('Y-m-d'));
                $account->setRegisteredAt(date('Y-m-d H:i:s'));

                $this->repository->store($account);

                header("Location: /accounts");
                exit;
            }
        }

        header("Location: /formBankAccount");
        exit;
    }

    public function formCreate(): void
    {
        $this->isAuth();
        $this->renderPage("formCreateAccount", "headerFooter");
    }

    public function index(): void
    {
        $this->isAuth();

        $accounts = $this->repository->findByUser($this->getCurrentUserId());

        $this->renderPage("accounts", "headerFooter", ['accounts' => $accounts]);
    }

    public function show(int $id): void
    {
        $this->isAuth();

        $account = $this->repository->findById($id);

        if (!$account) {
            return;
        }

        $this->renderPage("account", "headerFooter", ['account' => $account]);
    }

    public function update(int $id): void
    {
        $this->isAuth();

        if (
            !empty($_POST["short_name"]) &&
            !empty($_POST["annual_interest_rate"]) &&
            !empty($_POST["tax_rate"]) &&
            !empty($_POST["solde"])
        ) {
            $account = new Account();
            $account->setId($id);
            $account->setShortName($_POST['short_name']);
            $account->setDescription($_POST['description'] ?? '');
            $account->setAnnualInterestRate((float) $_POST['annual_interest_rate']);
            $account->setTaxRate((float) $_POST['tax_rate']);
            $account->setSolde((float) $_POST['solde']);

            $success = $this->repository->update($account);

            if ($success) {
                // Redirection
            }
        }
    }

    public function destroy(int $id): void
    {
        $this->isAuth();

        $success = $this->repository->destroy($id);

        if ($success) {
            // Redirection
        }
    }

    public function renderAccounts(): void{
        $this->isAuth();
        $accounts = $this->repository->findByUser($this->getCurrentUserId());
        $this->renderPage("accounts", "headerFooter", ['accounts' => $accounts]);
    }

    public function renderManageAccounts(): void{
        $this->isAuth();
        $accounts = $this->repository->findByUser($this->getCurrentUserId());
        $this->renderPage("manageAccounts", "headerFooter", ['accounts' => $accounts]);
    }

    public function renderAccountDetails(): void {
    $this->isAuth();

    // 1. Récupérer l'id du compte dans l'URL (?id=...)
    $accountId = isset($_GET['id']) ? (int)$_GET['id'] : null;
    $account   = $accountId ? $this->repository->findById($accountId) : null;

    // 2. Sécurité : le compte doit exister ET appartenir à l'utilisateur connecté
    if (!$account || $account['user_id'] != $this->getCurrentUserId()) {
        header("Location: /accounts");
        exit;
    }

    // 3. Remplacement du tableau en dur par les vraies transactions de la BD
    $transactionRepo = new \App\Repository\TransactionRepository();
    $allTransactions = $transactionRepo->findByAccount($accountId);

    // 4. Lecture des filtres dans l'URL (inchangé)
    $typeFilter     = $_GET['type'] ?? 'all';
    $search         = trim($_GET['search'] ?? '');
    $categoryFilter = $_GET['category'] ?? '';

    // 5. Application des filtres — SEULS les noms de clés changent
    $transactions = array_values(array_filter($allTransactions, function ($t) use ($typeFilter, $search, $categoryFilter) {
        if ($typeFilter === 'income'  && $t['type'] !== 'income')  return false;
        if ($typeFilter === 'expense' && $t['type'] !== 'expense') return false;
        if ($search !== '' && stripos($t['short_name'], $search) === false) return false; // avant : $t['label']
        if ($categoryFilter !== '' && $t['category'] !== $categoryFilter) return false;    // inchangé, category existe en BD
        return true;
    }));

    // 6. Calcul des totaux — adapté car amount est TOUJOURS positif en BD (contrainte CHECK amount >= 0)
    $entrées  = array_sum(array_map(fn($t) => $t['type'] === 'income'  ? (float)$t['amount'] : 0, $allTransactions));
    $sorties  = array_sum(array_map(fn($t) => $t['type'] === 'expense' ? (float)$t['amount'] : 0, $allTransactions));
    $soldeNet = (float) $account['solde'];
    // 7. Liste des catégories disponibles pour le filtre déroulant
    $categories = array_unique(array_filter(array_column($allTransactions, 'category')));
    sort($categories);

    // 8. Regroupement par date — clé renommée de 'date' à 'start_date'
    $grouped = [];
    foreach ($transactions as $t) {
        $grouped[$t['start_date']][] = $t;
    }

    $this->renderPage("accountDetails", "headerFooter", [
        'account'         => $account,
        'accountId'       => $accountId,
        'allTransactions' => $allTransactions,
        'transactions'    => $transactions,
        'grouped'         => $grouped,
        'entrées'         => $entrées,
        'sorties'         => $sorties,
        'soldeNet'        => $soldeNet,
        'categories'      => $categories,
        'typeFilter'      => $typeFilter,
        'search'          => $search,
        'categoryFilter'  => $categoryFilter,
    ]);
}
}

