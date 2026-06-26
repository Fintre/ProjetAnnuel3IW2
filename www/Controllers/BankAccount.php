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

        header("Location: /manageAccounts");
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

        $accountId = isset($_GET['id']) ? (int)$_GET['id'] : null;
        $account   = $accountId ? $this->repository->findById($accountId) : null;

        // TODO: remplacer par TransactionRepository quand le modèle sera créé
        $allTransactions = [
            ['id' => 1,  'date' => '2026-06-01', 'label' => 'Salaire — Université Paris-Saclay', 'category' => 'SALAIRE',    'amount' =>  1200.00, 'type' => 'income'],
            ['id' => 2,  'date' => '2026-05-31', 'label' => 'Carrefour Market',                  'category' => 'COURSES',    'amount' =>   -47.80, 'type' => 'expense'],
            ['id' => 3,  'date' => '2026-05-30', 'label' => 'Netflix',                           'category' => 'ABONNEMENT', 'amount' =>   -13.49, 'type' => 'expense'],
            ['id' => 4,  'date' => '2026-05-29', 'label' => 'SNCF — Paris-Lyon',                 'category' => 'TRANSPORT',  'amount' =>   -68.00, 'type' => 'expense'],
            ['id' => 5,  'date' => '2026-05-28', 'label' => 'Loyer — Studio',                    'category' => 'LOGEMENT',   'amount' =>  -820.00, 'type' => 'expense'],
            ['id' => 6,  'date' => '2026-05-27', 'label' => 'Freelance — Design',                'category' => 'SALAIRE',    'amount' =>   240.00, 'type' => 'income'],
            ['id' => 7,  'date' => '2026-05-26', 'label' => 'Lidl',                              'category' => 'COURSES',    'amount' =>   -32.40, 'type' => 'expense'],
            ['id' => 8,  'date' => '2026-05-25', 'label' => 'Spotify',                           'category' => 'ABONNEMENT', 'amount' =>    -9.99, 'type' => 'expense'],
            ['id' => 9,  'date' => '2026-05-24', 'label' => 'Ratp — Navigo',                     'category' => 'TRANSPORT',  'amount' =>   -86.40, 'type' => 'expense'],
            ['id' => 10, 'date' => '2026-05-23', 'label' => 'Remboursement ami',                 'category' => 'AUTRE',      'amount' =>    50.00, 'type' => 'income'],
            ['id' => 11, 'date' => '2026-05-22', 'label' => 'Prime exceptionnelle',              'category' => 'SALAIRE',    'amount' =>   150.00, 'type' => 'income'],
            ['id' => 12, 'date' => '2026-05-21', 'label' => 'Monoprix',                          'category' => 'COURSES',    'amount' =>  -114.90, 'type' => 'expense'],
        ];

        $typeFilter     = $_GET['type'] ?? 'all';
        $search         = trim($_GET['search'] ?? '');
        $categoryFilter = $_GET['category'] ?? '';

        $transactions = array_values(array_filter($allTransactions, function ($t) use ($typeFilter, $search, $categoryFilter) {
            if ($typeFilter === 'income'  && $t['type'] !== 'income')  return false;
            if ($typeFilter === 'expense' && $t['type'] !== 'expense') return false;
            if ($search !== '' && stripos($t['label'], $search) === false) return false;
            if ($categoryFilter !== '' && $t['category'] !== $categoryFilter) return false;
            return true;
        }));

        $entrées  = array_sum(array_map(fn($t) => $t['amount'] > 0 ? $t['amount'] : 0, $allTransactions));
        $sorties  = array_sum(array_map(fn($t) => $t['amount'] < 0 ? $t['amount'] : 0, $allTransactions));
        $soldeNet = $entrées + $sorties;

        $categories = array_unique(array_column($allTransactions, 'category'));
        sort($categories);

        $grouped = [];
        foreach ($transactions as $t) {
            $grouped[$t['date']][] = $t;
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

    public function accountDelete(): void
    {
        // Vérifier que la requête est bien de type POST
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            // Rediriger ou afficher une erreur si la méthode n\'est pas POST
            header("Location: /dashboard"); // Exemple de redirection
            exit();
        }

        // Récupérer l\'ID du compte à supprimer depuis le formulaire
        $accountId = $_POST["id"];
        // Récupérer l\'ID de l\'utilisateur actuellement connecté
        $currentUserId = $this->getCurrentUserId();

        // Vérifier que l\'ID du compte est valide et que l\'utilisateur est connecté
        if ($accountId === false || $accountId === null) {
            $_SESSION["error_message"] = "ID de compte invalide.";
            header("Location: /dashboard");
            exit();
        }

        // 1. Récupérer les informations du compte pour vérifier la propriété
        $account = $this->dbFindById("account", $accountId);

        if (!$account) {
            header("Location: /dashboard");
            exit();
        }

        // 2. Vérification CRITIQUE : S\'assurer que le compte appartient à l\'utilisateur connecté
        if ($account['user_id'] !== $currentUserId) {
            header("Location: /dashboard");
            exit();
        }

        // 3. Si la vérification de propriété est passée, procéder à la suppression
        $deleted = $this->dbDelete("account", $accountId);

        // Rediriger l\'utilisateur vers une page appropriée
        header("Location: /accounts");
        exit();
    }

    public function accountEdit(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: /dashboard");
            exit();
        }
        $accountId = $_POST["id"];

        $currentUserId = $this->getCurrentUserId();

        if ($accountId === false || $accountId === null) {
            header("Location: /dashboard");
            exit();
        }

        $account = $this->dbFindById("account", $accountId);

        if (!$account) {
            header("Location: /dashboard");
            exit();
        }

        if ($account['user_id'] !== $currentUserId) {
            header("Location: /dashboard");
            exit();
        }

        $data = [
            'short_name' => $_POST["short_name"],
            'description' => $_POST["description"],
            'annual_interest_rate' => $_POST["annual_interest_rate"],
            'tax_rate' => $_POST["tax_rate"],
        ];

        //Est-ce que on considère que c'est au user de changer les valeurs de taxe/taux annuel si ça change ou a nous ?

        $updated = $this->dbUpdate("account", $data, $accountId);

        if ($updated) {
            $_SESSION["success_message"] = "Le compte bancaire a été mis à jour avec succès.";
        } else {
            $_SESSION["error_message"] = "Une erreur est survenue lors de la mise à jour du compte.";
        }

        header("Location: /dashboard");
        exit();
    }
}

