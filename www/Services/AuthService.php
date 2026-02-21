<?php

namespace App\Service;

use App\Model\User;
use App\Service\BaseService;

class AuthService extends BaseService
{  
    
    public function createUser($data) {
        $user = new User();
        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);
        $user->setIsAdmin($data['is_admin']);
        $sql =  'INSERT INTO "user"("name","email","password", "is_admin", "created_at")
                    VALUES (:name,:email,:password,:is_admin,\''.date('Y-m-d').'\')';
        $queryPrepared = $this->pdo->prepare($sql);
        if ($queryPrepared->execute([
            "name" => $user->getName(),
            "email" => $user->getEmail(),
            "password" => $user->getPassword(),
            "is_admin" => $user->getIsAdmin()
        ]) 
        ) {
            $id = $this->pdo->lastInsertId();
            echo"Utilisateur ajouté !";
            return $id;
        } 
    }


    public function getUserDataFromId($userId){
        $sql = 'SELECT email, name, is_active, id, is_admin FROM "user" WHERE id=:id';
        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute(["id" => (int)$userId]);
        $user = $queryPrepared->fetch();
        return $user;
    }

    public function updateUserEmail($email, $userId){
        $user = new User();
        $user->setEmail($email);
        $sql = 'UPDATE "user" SET email=:email WHERE id = :id';
        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute(
        [
            "email" => $user->getEmail(),
            "id" => (int)$userId
        ]
        );
    }


    public function getAllUser(){
        $sql = 'SELECT id, name, email, is_admin FROM "user"';
        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute();
        $allUser = $queryPrepared->fetchall();
        return $allUser;
    }

    public function updateUserName($name, $userId){
        $user = new User();
        $user->setName($name);
        $sql = 'UPDATE "user" SET name=:name WHERE id = :id';
        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute(
        [
            "name" => $user->getName(),
            "id" => (int)$userId
        ]
        );
    }

    public function verifyEmail($email){
        $sql = 'SELECT "id" FROM "user" WHERE email=:email';
        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute(["email"=>$email]);
        $res = $queryPrepared->fetch();
        if($res){
            return true;
        }
        return false;
    }

    
    public function getUserIdFromMail($email){
        $sql = 'SELECT "id" FROM "user" WHERE email=:email';
        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute(["email"=>$email]);
        $id = $queryPrepared->fetchColumn();

        return $id ? (int)$id : false;
    }

    public function verifyPassword($email, $password){
        $sql = 'SELECT "password" FROM "user" WHERE email=:email';
        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute(['email'=>$email]);
        $res = $queryPrepared->fetch();
        if($res){
            return password_verify($password, $res['password']);
        }
        return false;
    }

    public function getIsActiveFromId($userId){
        $sql = 'SELECT "is_active" FROM "user" WHERE id=:id';
        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute(["id"=>(int)$userId]);
        $res = $queryPrepared->fetchColumn();

        return $res ? (bool)$res : false;
    } 
    
    public function deleteUserByID($userId){
        $sql = 'DELETE FROM "user" WHERE id=:id';
        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute(["id"=>(int)$userId]);
    }

    public function updateUserPasswordFromId($userId, $newPassword){
        $user = new User();
        $user->setPassword($newPassword);
        $sql = 'UPDATE "user" SET password=:password WHERE id=:id';
        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute([
            "id" => (int)$userId,
            "password" => $user->getPassword()
        ]);
    }

    public function setUserAdmin($userId){
        $user = new User();
        $user->setIsAdmin(true);
        $sql = 'UPDATE "user" SET is_admin=:is_admin WHERE id = :id';
        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute(
        [
            "is_admin" => $user->getIsAdmin(),
            "id" => (int)$userId
        ]
        );
    }

    public function checkIfUserTableIsEmpty(){
        $sql = 'SELECT COUNT(*) FROM "user"';
        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute();
        $res = $queryPrepared->fetch();
        return $res;
    }
}