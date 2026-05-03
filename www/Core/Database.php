<?php 

namespace App\Core;

class Database
{
    private static $instance = null;
    private \PDO $pdo;
    private function __construct(){  
        try{
            $this->pdo = new \PDO("pgsql:host=db;port=5432;dbname=" . getenv('POSTGRES_DB'),getenv('POSTGRES_USER'), getenv('POSTGRES_PASSWORD'));
        }catch(\PDOException $e){
            die("Erreur ".$e->getMessage());
        }
    }

    public static function getInstance(){
        if(self::$instance == null){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): \PDO{
        return $this->pdo;
    }
}