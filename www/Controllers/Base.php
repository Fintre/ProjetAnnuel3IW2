<?php

namespace App\Controller;

use App\Core\Render;

class Base
{
    public function index() {
         $this->renderPage("home", "frontoffice");
    }
    protected function renderPage(string $view, string $template = "frontoffice", array $data = []):void{
        $render = new Render($view, $template);  
        if(!empty($data)){
            foreach ($data as $key => $value){
            $render->assign($key, $value);
            }
        }
        $render->render();
    }

    protected function renderHome(){
        $render = new Render("home", "frontoffice");
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

}