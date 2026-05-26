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
}

