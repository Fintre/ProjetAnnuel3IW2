<?php

namespace App\Service;
use App\Model\EmailVerification;
use App\Model\User;
use App\Service\BaseService;

class EmailVerificationService extends BaseService
{

    public function createUserToken($data) {
        $emailVerification = new EmailVerification();
        $emailVerification->setUserID($data["user_id"]);
        $emailVerification->setToken($data["token"]);
        $sql =  'INSERT INTO email_verification("user_id","token", "created_at")
                    VALUES (:user_id,:token,\''.date('Y-m-d').'\')';
        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute([
            "user_id"=> $emailVerification->getUserID(),
            "token"=> $emailVerification->getToken(),
        ]);
    }

    public function updateUserToken($userId, $token){
        $emailVerification = new EmailVerification();
        $emailVerification->setToken($token);
        $sql = 'UPDATE "email_verification" SET token=:token WHERE user_id = :id';
        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute(
        [
            "token" => $emailVerification->getToken(),
            "id" => (int)$userId
        ]
        );
    }

    public function getUserIdFromToken($token){
        $sql =  'SELECT "user_id" FROM email_verification WHERE token=:token';
        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute(["token" => $token]);
        return $queryPrepared->fetchColumn();
    }

    public function activeAccount($userId){
        $user = new User();
        $user->setIsActive(true);
        $sql = 'UPDATE "user" SET is_active=:is_active WHERE id = :id';
        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute(["is_active" => $user->getIsActive(), "id" => $userId]);
    }

}