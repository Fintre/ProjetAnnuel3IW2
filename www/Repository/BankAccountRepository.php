<?php

namespace App\Repository;

use App\Controller\Base;
use App\Model\Account;

class BankAccountRepository  extends Base
{
    protected string $table = 'account';
    protected array $validColumns = ["short_name","description","annual_interest_rate","tax_rate"];

    public function store(Account $account): string|false
    {
        return $this->dbInsert($this->table, [
            'user_id'              => $account->getUserId(),
            'short_name'           => $account->getShortName(),
            'description'          => $account->getDescription(),
            'creation_date'        => $account->getCreationDate(),
            'annual_interest_rate' => $account->getAnnualInterestRate(),
            'tax_rate'             => $account->getTaxRate(),
            'solde'                => $account->getSolde(),
            'solde_initial' => $account->getSolde(),
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
        ], $account->getId());
    }

    public function destroy(int $id): bool
    {
        return $this->dbDelete($this->table, $id);
    }


public function adjustSolde(int $accountId): bool
{
    $sql = "
        UPDATE account
        SET solde = solde_initial + (
            SELECT COALESCE(SUM(CASE WHEN t.type = 'income' THEN t.amount ELSE -t.amount END), 0)
            FROM \"transaction\" t
            WHERE t.account_id = :id
            AND t.start_date <= CURRENT_DATE
        )
        WHERE id = :id2
    ";
    $stmt = $this->db->getConnection()->prepare($sql);
    return $stmt->execute([':id' => $accountId, ':id2' => $accountId]);
}
}