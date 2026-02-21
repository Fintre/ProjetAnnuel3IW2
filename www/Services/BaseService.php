<?php

namespace App\Service;

use App\Core\Database;
class BaseService
{
    protected $pdo;
    public function __construct(){
        $this->pdo = Database::getInstance()->getConnection();
    }
}