<?php

namespace App\Repository;

use App\Controller\Base;
use App\Model\User;

class UserRepository extends Base 
{
    protected string $table = '"user"';
    protected array $validColumns = ['id', 'name', 'last_name', 'email', 'is_admin', 'is_active'];

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

    public function update(User $user): bool {
        return $this->dbUpdate($this->table, [
            'name'  => $user->getName(),
            'last_name'  => $user->getLastName(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'is_admin' => $user->getIsAdmin(),
        ], $user->getId());
    }

    public function getAlldata(): array {
        return $this->dbFindByColumns($this->table, ['id', 'name']);
    }

}