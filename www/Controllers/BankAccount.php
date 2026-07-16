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

        private const ACCOUNT_LIMITS = [
        'FREE' => 2,
        'PLUS' => 5,
        'PRO'  => PHP_INT_MAX,
    ];

    private function getAccountLimit(): int
    {
        $type = $_SESSION['subscription_type'] ?? 'FREE';
        return self::ACCOUNT_LIMITS[$type] ?? self::ACCOUNT_LIMITS['FREE'];
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
                $currentCount = count($this->repository->findByUser($userId));
                $limit = $this->getAccountLimit();

                if ($currentCount >= $limit) {
                    $_SESSION['error_message'] = "Vous avez atteint la limite de {$limit} compte(s) pour votre formule. Passez à une offre supérieure pour en ajouter davantage.";
                    header("Location: /formBankAccount");
                    exit;
                }

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
    public function formCreate(): void{
        $this->renderPage("formCreateAccount");
    }
    
    public function index(): void
    {
        $this->isAuth();

        $accounts = $this->repository->findByUser($this->getCurrentUserId());

        $this->renderPage("accounts", "headerFooter", ['accounts' => $accounts]);
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
    $entrees  = array_sum(array_map(fn($t) => $t['type'] === 'income'  && $t['start_date'] <= date('Y-m-d') ? (float)$t['amount'] : 0, $allTransactions));
    $sorties  = array_sum(array_map(fn($t) => $t['type'] === 'expense' && $t['start_date'] <= date('Y-m-d') ? (float)$t['amount'] : 0, $allTransactions));
    $soldeNet = (float) $account['solde'];
    // 7. Liste des catégories disponibles pour le filtre déroulant
    $categories = array_unique(array_filter(array_column($allTransactions, 'category')));
    sort($categories);

    // 8. Regroupement par date — clé renommée de 'date' à 'start_date'
    usort($transactions, fn($a, $b) => strcmp($b['start_date'], $a['start_date']));

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
        'entrees'         => $entrees,
        'sorties'         => $sorties,
        'soldeNet'        => $soldeNet,
        'categories'      => $categories,
        'typeFilter'      => $typeFilter,
        'search'          => $search,
        'categoryFilter'  => $categoryFilter,
    ]);
}
  
  public function accountDelete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /dashboard');
            exit();
        }

        $accountId     = $_POST['id'] ?? null;
        $currentUserId = $this->getCurrentUserId();

        if (!$accountId) {
            $_SESSION['error_message'] = 'ID de compte invalide.';
            header('Location: /dashboard');
            exit();
        }

        $account = $this->repository->findById((int) $accountId);

        if (!$account) {
            header('Location: /dashboard');
            exit();
        }

        if ((int) $account['user_id'] !== $currentUserId) {
            header('Location: /dashboard');
            exit();
        }

        $this->repository->destroy((int) $accountId);

        header('Location: /accounts');
        exit();
    }

    public function accountEdit(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /dashboard');
            exit();
        }

        $accountId     = $_POST['id'] ?? null;
        $currentUserId = $this->getCurrentUserId();

        if (!$accountId) {
            header('Location: /dashboard');
            exit();
        }

        $existing = $this->repository->findById((int) $accountId);

        if (!$existing) {
            header('Location: /dashboard');
            exit();
        }

        if ((int) $existing['user_id'] !== $currentUserId) {
            header('Location: /dashboard');
            exit();
        }

        $account = new Account();
        $account->setId((int) $accountId);
        $account->setShortName($_POST['short_name']);
        $account->setDescription($_POST['description'] ?? '');
        $account->setAnnualInterestRate((float) $_POST['annual_interest_rate']);
        $account->setTaxRate((float) $_POST['tax_rate']);

        $updated = $this->repository->update($account);

        if ($updated) {
            $_SESSION['success_message'] = 'Le compte bancaire a été mis à jour avec succès.';
        } else {
            $_SESSION['error_message'] = 'Une erreur est survenue lors de la mise à jour du compte.';
        }

        header('Location: /accounts');
        exit();
    }
}

