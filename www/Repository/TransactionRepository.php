<?php

namespace App\Repository;

use App\Controller\Base;
use App\Model\Transaction;

class TransactionRepository extends Base
{
    protected string $table = '"transaction"';

    public function store(Transaction $transaction): string|false
    {
        return $this->dbInsert($this->table, [
            'account_id'      => $transaction->getAccountId(),
            'type'            => $transaction->getType(),
            'short_name'      => $transaction->getShortName(),
            'description'     => $transaction->getDescription(),
            'frequency'       => $transaction->getFrequency(),
            'interval_months' => $transaction->getIntervalMonths(),
            'amount'          => $transaction->getAmount(),
            'start_date'      => $transaction->getStartDate(),
            'end_date'        => $transaction->getEndDate(),
            'category'        => $transaction->getCategory(),
        ]);
    }

    public function findByAccount(int $accountId): array
    {
        return $this->dbFindBy($this->table, ['account_id' => $accountId]);
    }

    public function findByAccountAndType(int $accountId, string $type): array
    {
        return $this->dbFindBy($this->table, ['account_id' => $accountId, 'type' => $type]);
    }

    public function findById(string $id): array|false
    {
        return $this->dbFindById($this->table, $id);
    }

    public function update(Transaction $transaction): bool
    {
        return $this->dbUpdate($this->table, [
            'type'            => $transaction->getType(),
            'short_name'      => $transaction->getShortName(),
            'description'     => $transaction->getDescription(),
            'frequency'       => $transaction->getFrequency(),
            'interval_months' => $transaction->getIntervalMonths(),
            'amount'          => $transaction->getAmount(),
            'start_date'      => $transaction->getStartDate(),
            'end_date'        => $transaction->getEndDate(),
            'category'        => $transaction->getCategory(),
        ], $transaction->getId());
    }

    public function destroy(string $id): bool
    {
        return $this->dbDelete($this->table, $id);
    }

    // Calculer le total des transactions pour une période
    public function getTotalByType(int $accountId, string $type, string $startDate, string $endDate): float
    {
        $query = "SELECT COALESCE(SUM(amount), 0) as total FROM {$this->table} 
                  WHERE account_id = :account_id AND type = :type 
                  AND start_date <= :end_date AND (end_date IS NULL OR end_date >= :start_date)";
        
        $result = $this->db->prepare($query);
        $result->execute([
            ':account_id' => $accountId,
            ':type' => $type,
            ':start_date' => $startDate,
            ':end_date' => $endDate
        ]);
        
        return (float) $result->fetch(\PDO::FETCH_ASSOC)['total'];
    }
}
