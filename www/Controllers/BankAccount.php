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
        if (
            !empty($_POST["short_name"]) &&
            !empty($_POST["annual_interest_rate"]) &&
            !empty($_POST["tax_rate"]) &&
            !empty($_POST["balance"])
        ) {
            $userId = $this->getCurrentUserId();

            if ($userId) {
                $account = new Account();
                    $account->setUserId($userId);
                    $account->setShortName($_POST['short_name']);
                    $account->setDescription($_POST['description'] ?? '');
                    $account->setAnnualInterestRate((float) $_POST['annual_interest_rate']);
                    $account->setTaxRate((float) $_POST['tax_rate']);
                    $account->setBalance((float) $_POST['balance']);
                    $account->setCreationDate(date('Y-m-d'));
                    $account->setRegisteredAt(date('Y-m-d H:i:s'));

                    $repository = new BankAccountRepository();
                    $repository->store($account);

                if ($account) {
                    // Redirection
                } else {
                    // Erreur, faut créer un Erreur.php dans Core.
                }
            }
        }
    }
    public function formCreate(): void{
        $this->renderPage("formCreateAccount");
    }

    public function index(): void
    {
        $this->isAuth();

        $accounts = $this->repository->findByUser($this->getCurrentUserId());

        $this->renderPage("accounts", "frontoffice", ['accounts' => $accounts]);
    }

    public function show(int $id): void
    {
        $this->isAuth();

        $account = $this->repository->findById($id);

        if (!$account) {
            return;
        }

        $this->renderPage("account", "frontoffice", ['account' => $account]);
    }

    public function update(int $id): void
    {
        $this->isAuth();

        if (
            !empty($_POST["short_name"]) &&
            !empty($_POST["annual_interest_rate"]) &&
            !empty($_POST["tax_rate"]) &&
            !empty($_POST["balance"])
        ) {
            $account = new Account();
            $account->setId($id);
            $account->setShortName($_POST['short_name']);
            $account->setDescription($_POST['description'] ?? '');
            $account->setAnnualInterestRate((float) $_POST['annual_interest_rate']);
            $account->setTaxRate((float) $_POST['tax_rate']);
            $account->setBalance((float) $_POST['balance']);

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
}

