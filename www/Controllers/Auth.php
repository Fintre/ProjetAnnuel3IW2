<?php

namespace App\Controller;

use App\Controller\Base;
use App\Service\AuthService;
use App\Service\EmailVerificationService;
use App\Controller\EmailVerification;

class Auth extends Base
{

    private $errors = [];
    public function signin(): void{   
        if(
            !empty($_POST["email"]) &&
            !empty($_POST["pwd"]) &&
            count($_POST) == 2
        ){
            $email = $this->clearEmail($_POST["email"]);
            $auth = new AuthService();
            $userId = $auth->getUserIdFromMail($email);
            if($userId){
                $password = $_POST["pwd"];
                $passwordMatch = $auth->verifyPassword($email, $password);
                
                if($passwordMatch){
                    $isActive = $auth->getIsActiveFromId($userId);
                    
                    if($isActive === true){
                        $userData = $auth->getUserDataFromId($userId);
                        $this->setSessionData($userData);
                        $this->renderPage("dashboard");
                    } else {
                        $this->errors[]="Votre compte n'est pas encore activé";
                        $this->renderPage("login", "frontoffice", ["errors" => $this->errors]);
                    } 
                } else {
                        $this->errors[]="Mot de passe incorrect";
                        $this->renderPage("login", "frontoffice", ["errors" => $this->errors]);
                    }
            } else {
                $this->renderPage("login", "frontoffice", ["errors" => $this->errors]);
            }  
        } else {
            echo "Tentative de XSS";
            $this->renderPage("login");
        }
    }

    public function signup(): void{
        if(
        isset($_POST['name']) &&
        !empty($_POST['email']) &&
        !empty($_POST['pwd']) &&
        !empty($_POST['pwdConfirm']) &&
        count($_POST) == 4
        ){
        $email = $this->clearEmail($_POST['email']);
        $verifiyMail = new AuthService();
        if($verifiyMail->verifyEmail($email)){
            $this->errors[]= "L'email existe déjà en bdd";
        } 
        $name = $this->clearName($_POST['name']);

        if(strlen($_POST["pwd"]) < 8 ||
            !preg_match('/[a-z]/', $_POST["pwd"] ) ||
            !preg_match('/[A-Z]/', $_POST["pwd"]) ||
            !preg_match('/[0-9]/', $_POST["pwd"])
        ){
            $this->errors[]="Votre mot de passe doit faire au minimum 8 caractères avec min, maj, chiffres";
        }
        if($_POST["pwd"] != $_POST["pwdConfirm"]){
            $this->errors[]="Votre mot de passe de confirmation ne correspond pas";
        }  
        if(empty($this->errors)){
            $pwdHashed = password_hash($_POST["pwd"], PASSWORD_DEFAULT );
            $data = [
                "name"=> $name,
                "email"=> $email,
                "password"=> $pwdHashed,
                "is_admin"=> 'false',
            ];
            $auth = new AuthService();
            $isUserEmpty = $auth->checkIfUserTableIsEmpty();
            if($isUserEmpty['count'] == 0) {
                $data["is_admin"] = 'true';
                var_dump($data);
            }
            $userId = $auth->createUser($data);
            $userData = $auth->getUserDataFromId($userId);
            if(!empty($userId)){
                $this->setSessionData($userData);
                $token = hash("sha256", bin2hex(random_bytes(32)));
                $data = [
                "user_id"=> $userId,
                "token"=> $token,
            ];
                $emailService = new EmailVerificationService();
                $emailService->createUserToken($data);  
                $emailController = new EmailVerification();
                $emailController->sendVerificationMail($email, $token, "Veuillez activer votre compte", 'activation');
            }
            $this->renderHome();
        } else {
            $this->renderPage("signup", "frontoffice", ["errors" => $this->errors]);
        }
        }else{
            echo "Tentative de XSS";
            $this->renderPage("signup");
        }
    }

    public function logout(){
        session_unset();
        session_destroy();
        $this->renderPage( "home");
    }

    public function updateUser(){
        $this->isAuth();
        $auth = new AuthService();
         if(!empty($_POST['name'])) {
            $name = $this->clearName( $_POST['name'] );
            if(empty($this->errors)){
                $auth->updateUserName( $name, $_SESSION["id"]);
                $value = ["name" => $name];
                $this->setSessionData($value);
            }
        }  
        if($_SESSION['email'] !== $_POST['email']){
            if(!empty($_POST['email'])){
                $email = $this->clearEmail( $_POST['email'] );
                $auth->updateUserEmail( $email, $_SESSION["id"]);
                $value = ["email" => $email];
                $this->setSessionData($value);
            }
        }
        $this->renderPage("user", "backoffice");
    }

    public function clearEmail($email){
        $email = strtolower(trim($_POST['email']));
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $this->errors[]="Votre email n'est pas correct";
        }else{
            return $email;
        }
    }

    public function clearName($name){
        $name = ucwords(strtolower(trim($_POST['name'])));

        if(!empty($name) && strlen($name)<2){
            $this->errors[]="Votre prénom doit faire au minimum 2 caractères";
        } else {
            return $name;
        }
    }
    
    public function deleteUser(){
        $auth = new AuthService();
        if(isset($_POST["id"])){
            $userId = $_POST["id"];
        }
        $auth->deleteUserByID( $userId );
        if($_SESSION["id"] == $userId){
            $this->logout();
        } else {
            $this->renderUsers();
        }
    }

    public function setAdminUser(){
        $auth = new AuthService();
        if(isset($_POST["id"])){
            $auth->setUserAdmin($_POST['id']);
        }
        $this->renderUsers();
        
    }

    public function renderSignup(): void{
        $this->renderPage("signup");
    }

    public function renderLogin(): void{
         $this->renderPage("login");
    }

    public function renderProfil(): void{
        $this->isAuth();
        $this->renderPage( "user", "backoffice");
    }

    public function renderDashboard(): void {
        $this->isAuth();
        $this->renderPage("dashboard", "backoffice");
    }   


    public function renderUsers() {
        $this->isAuth();
        $auth = new AuthService();
        $allusers = $auth->getAllUser();
        $this->renderPage("allUser", "backoffice", ["users"=> $allusers]);

    }

    public function renderResetPassword(){
        $this->renderPage("resetPassword");
    }

    public function renderModifyPassword(): void {
        $emailService = new EmailVerification();
        $token = isset($_GET["token"]) ? $_GET["token"] : null;
        $isActiveToken = $emailService->verifyIfTokenExist($token);
        if($isActiveToken){ $this->renderPage("modifyPassword", "frontoffice"); }
    }

    public function updatePassword() {
         if(
            !empty($_POST["email"]) &&
            !empty($_POST['pwd']) &&
            !empty($_POST['pwdConfirm']) &&
            count($_POST) == 3
         ){
            $this->verifyPassword($_POST['pwd'], $_POST['pwdConfirm']);
            if(empty($this->errors)){
            $pwdHashed = password_hash($_POST["pwd"], PASSWORD_DEFAULT );
            $email = $this->clearEmail( $_POST["email"] );
            $auth = new AuthService();
            $userId = $auth->getUserIdFromMail($email);
            if(!empty($userId)){
                $auth->updateUserPasswordFromId($userId, $pwdHashed);
                $_SESSION['error']="Votre mot de passe à été modifié";
                $this->renderPage("login");
            } 
            }else{
                $_SESSION['error']="Mdp invalid";
                $this->renderPage("modifyPassword", "frontoffice", ["errors" => $this->errors]);
            } 
        } else{
            echo "Tentative de XSS";
            $this->renderPage("signup");
        }
    }

    public function verifyPassword($pwd, $pwdConfirm) {
        if(strlen($pwd) < 8 ||
            !preg_match('/[a-z]/', $pwd ) ||
            !preg_match('/[A-Z]/', $pwd) ||
            !preg_match('/[0-9]/', $pwd)
        ){
                $this->errors[]="Votre mot de passe doit faire au minimum 8 caractères avec min, maj, chiffres";
        }
        if($pwd != $pwdConfirm){ 
            $this->errors[]="Votre mot de passe de confirmation ne correspond pas";
        }  
    }
}

