<?php

namespace App\Repository;

use App\Controller\Base;
use App\Model\EmailVerification;

class BankAccountRepository extends Base
{
    protected string $table = 'account';

    public function store(Account $account): string|false
    {
        return $this->dbInsert($this->table, [
            'user_id'              => $account->getUserId(),
            'short_name'           => $account->getShortName(),
            'description'          => $account->getDescription(),
            'creation_date'        => $account->getCreationDate(),
            'annual_interest_rate' => $account->getAnnualInterestRate(),
            'tax_rate'             => $account->getTaxRate(),
            'balance'              => $account->getBalance(),
            'registered_at'        => $account->getRegisteredAt(),
        ]);
    }

    public function findByUser(int $userId): array
    {
        return $this->dbFindBy($this->table, ['user_id' => $userId]);
    }

    public function findById(int $id): array|false
    {
        return $this->dbFindById($this->table, $id);
    }

    public function update(Account $account): bool
    {
        return $this->dbUpdate($this->table, [
            'short_name'           => $account->getShortName(),
            'description'          => $account->getDescription(),
            'annual_interest_rate' => $account->getAnnualInterestRate(),
            'tax_rate'             => $account->getTaxRate(),
            'balance'              => $account->getBalance(),
        ], $account->getId());
    }

    public function destroy(int $id): bool
    {
        return $this->dbDelete($this->table, $id);
    }
}