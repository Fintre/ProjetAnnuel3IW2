<?php

namespace App\Controller;

use App\Core\Render;
use App\Core\Database;

abstract class Base
{
    protected Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function index() {
         $this->renderPage("home", "headerFooter");
    }
    protected function renderPage(string $view, string $template = "headerFooter", array $data = []):void{
        $render = new Render($view, $template);  
        if(!empty($data)){
            foreach ($data as $key => $value){
            $render->assign($key, $value);
            }
        }
        $render->render();
    }

    protected function renderHome(){
        $render = new Render("home", "headerFooter");
        $render->render();
    }

    public function setSessionData($userData) {
    $keysToStore = ['id', 'name', 'email', 'is_active', 'is_admin'];

    foreach ($keysToStore as $key) {
        if (isset($userData[$key])) { 
            $_SESSION[$key] = $userData[$key];
        }
    }
    }


    public function isAuth(): void
    {
        if (!isset($_SESSION["is_active"]) || $_SESSION["is_active"] !== true) {
            $this->renderHome();
            exit;
        }
    }


    protected function getCurrentUserId(): ?int
    {
        
        if (isset($_SESSION['id'])) {
            return (int) $_SESSION['id'];
        }

        
        if (isset($_SESSION['user_id'])) {
            $u = $_SESSION['user_id'];
            if (is_array($u)) {
                $val = $u[0] ?? $u['id'] ?? null;
                return $val !== null ? (int)$val : null;
            }
            return $_SESSION['user_id'] !== null ? (int)$_SESSION['user_id'] : null;
        }

        return null;
    }

    protected function dbInsert(string $table, array $data): string|false
        {
            $columns = implode(', ', array_keys($data));
            $placeholders = implode(', ', array_map(fn($k) => ":$k", array_keys($data)));

            $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";

            $stmt = $this->db->getConnection()->prepare($sql);

            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }

            if ($stmt->execute()) {
                return $this->db->getConnection()->lastInsertId();
            }

            return false;
        }

    protected function dbFindAll(string $table): array
    {
        $stmt = $this->db->getConnection()->prepare(
            "SELECT * FROM {$table}"
        );
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    protected function dbFindBy(string $table, array $criteria, string $orderBy = null): array
    {
        $conditions = implode(' AND ', array_map(fn($k) => "{$k} = :{$k}", array_keys($criteria)));
        $sql        = "SELECT * FROM {$table} WHERE {$conditions}";

        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }

        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute($criteria);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

        protected function dbFindById(string $table, int $id): array|false
    {
        $stmt = $this->db->getConnection()->prepare(
            "SELECT * FROM {$table} WHERE id = :id LIMIT 1"
        );
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }


    protected function dbUpdate(string $table, array $data, int $id): bool
    {
        $fields = implode(', ', array_map(fn($k) => "{$k} = :{$k}", array_keys($data)));

        $stmt = $this->db->getConnection()->prepare(
            "UPDATE {$table} SET {$fields} WHERE id = :id"
        );

        $data['id'] = $id;

        return $stmt->execute($data);
    }

    protected function dbDelete(string $table, int $id): bool
    {
        $stmt = $this->db->getConnection()->prepare(
            "DELETE FROM {$table} WHERE id = :id"
        );

        return $stmt->execute([':id' => $id]);
    }

}