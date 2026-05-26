<?php

namespace App\Repository;

use App\Controller\Base;
use App\Model\EmailVerification;

class EmailVerificationRepository extends Base
{
    protected string $table = 'email_verification';
    protected array $validColumns = ['id', 'user_id', 'token', 'created_at'];

    public function create(EmailVerification $email): string|false {
        return $this->dbInsert($this->table, [
            'user_id'    => $email->getUserID(),
            'token'      => $email->getToken(),
            'created_at' => date('Y-m-d'),
        ]);
    }

    public function findUserIdByToken(string $token): ?int{
        $rows = $this->dbFindByColumnsWhere($this->table, ['user_id'], ['token' => $token]);
        return $rows[0]['user_id'] ?? null;
    }
    public function updateToken(int $userId, string $token): bool {
        return $this->dbUpdateBy($this->table, ['token' => $token], ['user_id' => $userId]);
    }
}