<?php

namespace App\Repository;

use App\Controller\Base;
use App\Model\User;

class UserRepository extends Base 
{
    protected string $table = '"user"';
    protected array $validColumns = ['id', 'name', 'last_name', 'email', 'is_admin', 'is_active', 'password'];

    public function create(User $user): string|false {
        return $this->dbInsert($this->table, [
            'name'  => $user->getName(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'is_admin' => $user->getIsAdmin(),
            'created_at' => date('Y-m-d'),
        ]);
    }

    public function dataById(int $id): array|false {
        return $this->dbFindById($this->table, $id);
    }

    public function updateColumn(array $data, int $userId): bool {
        return $this->dbUpdate($this->table, $data, $userId);
    }

    public function getData(array $columns): array {
        return $this->dbFindByColumns($this->table, $columns);
    }

    // columns = all columns we need, where = filter sql, criteria = value  to compare
    public function getByCol(array $columns, string $where, string $criteria): array {
        $data = $this->dbFindByColumnsWhere($this->table, $columns, [$where => $criteria]);
        return $data[0] ?? [];
    }

    public function getFirstByCol(string $column, string $where, mixed $criteria): mixed {
        $data = $this->dbFindByColumnsWhere($this->table, [$column], [$where => $criteria]);
        return $data[0][$column] ?? null;
    }

    public function verifyPassword(string $password, string $email): bool {
        $rows = $this->dbFindByColumnsWhere($this->table, ['password'], ['email' => $email]);
        if (empty($rows)) {
            return false;
        }
        return password_verify($password, $rows[0]['password']);
    }
    public function delete(int $userId): bool {
        return $this->dbDelete($this->table, $userId);
    }
}