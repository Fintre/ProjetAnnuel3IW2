<?php
namespace App\Controller;
use App\Model\Transaction as TransactionModel;
use App\Repository\TransactionRepository;
use App\Repository\BankAccountRepository;

class Transaction extends Base
{
    private TransactionRepository $transactionRepo;
    private BankAccountRepository $accountRepo;

    public function __construct()
    {
        parent::__construct();
        $this->transactionRepo = new TransactionRepository();
        $this->accountRepo = new BankAccountRepository();
    }

    public function index(): void
    {
        $this->isAuth();
        $accountId = (int) ($_GET['account_id'] ?? 0);
        $userId = $this->getCurrentUserId();

        if (!$accountId) {
            header('Location: /manageAccounts');
            exit;
        }
    }

public function create(): void
{
    $this->isAuth();
    $accountId = (int) ($_POST['account_id'] ?? 0);
    $userId = $this->getCurrentUserId();

    // Vérifier propriété du compte
    $account = $this->accountRepo->findById($accountId);
    if (!$account || $account['user_id'] != $userId) {
        http_response_code(403);
        exit('Accès refusé');
    }

    if (empty($_POST['type']) || empty($_POST['short_name']) || empty($_POST['amount'])) {
        header("Location: /transactions?account_id=$accountId");
        exit;
    }

$transaction = new TransactionModel();
$transaction->setAccountId($accountId);
$transaction->setType($_POST['type']); // 'expense' ou 'income'
$transaction->setShortName($_POST['short_name']);
$transaction->setDescription($_POST['description'] ?? '');
$transaction->setCategory($_POST['category'] ?? null);
$frequency = $_POST['frequency'] ?? 'ONE_TIME'; // ONE_TIME ou RECURRING
$transaction->setFrequency($frequency);
$transaction->setIntervalMonths((int) ($_POST['interval_months'] ?? 1));
$transaction->setAmount((float) $_POST['amount']);
$startDate = $frequency === 'RECURRING'
    ? (!empty($_POST['recurrence_start_date']) ? $_POST['recurrence_start_date'] : date('Y-m-d'))
    : (!empty($_POST['start_date']) ? $_POST['start_date'] : date('Y-m-d'));
$transaction->setStartDate($startDate);
$transaction->setEndDate(!empty($_POST['end_date']) ? $_POST['end_date'] : null);

    error_log('POST DATA: ' . json_encode($_POST));

    $this->transactionRepo->store($transaction);
    $delta = $transaction->getType() === 'income' ? $transaction->getAmount() : -$transaction->getAmount();
    $this->accountRepo->adjustSolde($accountId, $delta);
    header("Location: /accountDetails?id=$accountId");    
    exit;
}

    public function formCreate(): void
    {
        $this->isAuth();
        $accountId = (int) ($_GET['account_id'] ?? 0);
        $this->renderPage('formCreateTransaction', ['account_id' => $accountId]);
    }

    public function edit(): void
    {
        $this->isAuth();
$id = $_SERVER['REQUEST_METHOD'] === 'POST' 
    ? ($_POST['id'] ?? '') 
    : ($_GET['id'] ?? '');        $userId = $this->getCurrentUserId();

        $transaction = $this->transactionRepo->findById($id);
        if (!$transaction) {
            http_response_code(404);
            exit('Transaction non trouvée');
        }

        $account = $this->accountRepo->findById($transaction['account_id']);
        if ($account['user_id'] != $userId) {
            http_response_code(403);
            exit('Accès refusé');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $transactionObj = new TransactionModel();
            $transactionObj->setId($id);
            $transactionObj->setAccountId($transaction['account_id']);
            $transactionObj->setType($_POST['type']);
            $transactionObj->setShortName($_POST['short_name']);
            $transactionObj->setDescription($_POST['description'] ?? '');
            $transactionObj->setFrequency($_POST['frequency']);
            $transactionObj->setIntervalMonths((int) ($_POST['interval_months'] ?? 1));
            $transactionObj->setAmount((float) $_POST['amount']);
            $transactionObj->setStartDate($_POST['start_date']);
            $transactionObj->setEndDate(!empty($_POST['end_date']) ? $_POST['end_date'] : null);
            $this->transactionRepo->update($transactionObj);
    
$this->accountRepo->adjustSolde((int)$transaction['account_id']);
header("Location: /accountDetails?id=" . $transaction['account_id']);
exit;
        }

$this->renderPage('formEditTransaction', 'headerFooter', ['transaction' => $transaction]);    }

    public function delete(): void
    {
        $this->isAuth();
        $id = $_POST['id'] ?? '';
        $userId = $this->getCurrentUserId();

        $transaction = $this->transactionRepo->findById($id);
        if (!$transaction) {
            http_response_code(404);
            exit('Transaction non trouvée');
        }

        $account = $this->accountRepo->findById($transaction['account_id']);
        if ($account['user_id'] != $userId) {
            http_response_code(403);
            exit('Accès refusé');
        }
$this->transactionRepo->destroy($id);
$this->accountRepo->adjustSolde((int)$transaction['account_id']);
header("Location: /accountDetails?id=" . $transaction['account_id']);
exit;
    }

    public function summary(): void
    {
        $this->isAuth();
        $accountId = (int) ($_GET['account_id'] ?? 0);
        $userId = $this->getCurrentUserId();

        $account = $this->accountRepo->findById($accountId);
        if (!$account || $account['user_id'] != $userId) {
            http_response_code(403);
            exit('Accès refusé');
        }

        $startDate = $_GET['start_date'] ?? date('Y-01-01');
        $endDate = $_GET['end_date'] ?? date('Y-12-31');

        $totalIncome = $this->transactionRepo->getTotalByType($accountId, 'income', $startDate, $endDate);
        $totalExpense = $this->transactionRepo->getTotalByType($accountId, 'expense', $startDate, $endDate);

        $this->renderPage('transactionSummary', [
            'account' => $account,
            'total_income' => $totalIncome,
            'total_expense' => $totalExpense,
            'balance' => $totalIncome - $totalExpense,
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
    }
}
