<?php

namespace App\Repository;

use App\Controller\Base;
use App\Model\Subscription;

class SubscriptionRepository extends Base
{
    protected string $table = 'subscription';
    protected array $validColumns = ['id', 'user_id', 'type', 'stripe_customer_id', 'stripe_subscription_id', 'start_date', 'expiration_date', 'created_at'];

    public function create(Subscription $sub): string|false {
        return $this->dbInsert($this->table, [
            'user_id' => $sub->getUserId(),
            'type'    => $sub->getType(),
        ]);
    }

    public function updateColumn(array $data, int $subId): bool {
        return $this->dbUpdate($this->table, $data, $subId);
    }

    public function updateByUserId(array $data, int $userId): bool {
        return $this->dbUpdateBy($this->table, $data, ['user_id' => $userId]);
    }

    public function getByCol(array $columns, string $where, string $criteria): array {
        $data = $this->dbFindByColumnsWhere($this->table, $columns, [$where => $criteria]);
        return $data[0] ?? [];
    }

    public function getFirstByCol(string $column, string $where, mixed $criteria): mixed {
        $data = $this->dbFindByColumnsWhere($this->table, [$column], [$where => $criteria]);
        return $data[0][$column] ?? null;
    }

    public function delete(int $subId): bool {
        return $this->dbDelete($this->table, $subId);
    }
}
