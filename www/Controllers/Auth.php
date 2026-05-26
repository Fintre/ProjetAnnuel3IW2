<?php

namespace App\Controller;

use App\Controller\Base;
use App\Repository\UserRepository;
use App\Repository\EmailVerificationRepository;
use App\Controller\EmailVerification;
use App\Model\User;
use App\Model\EmailVerification as EmailVerificationModel;


class Auth extends Base
{

    private $errors = [];

    private UserRepository $userRepository;
    private EmailVerificationRepository $emailRepository;

    public function __construct(){
        parent::__construct();
        $this->userRepository = new UserRepository();
        $this->emailRepository = new EmailVerificationRepository();
    }

    public function signin(): void{   
        if(
            !empty($_POST["email"]) &&
            !empty($_POST["pwd"]) &&
            count($_POST) == 2
        ){
            $email = $this->clearEmail($_POST["email"]);
            
            $userId = $this->userRepository->getFirstByCol('id', 'email', $email);
            if($userId){
                $password = $_POST["pwd"];
                $passwordMatch = $this->userRepository->verifyPassword($password, $email);
                
                if($passwordMatch){
                    $isActive = $this->userRepository->getFirstByCol('is_active', 'id', $userId);
                    
                    if($isActive === true){
                        $userData = $this->userRepository->getByCol(['email', 'name', 'last_name', 'is_active', 'id', 'is_admin'], 'id', $userId);
                        $this->setSessionData($userData);
                        $this->renderPage("userProfil");
                    } else {
                        $this->errors[]="Votre compte n'est pas encore activé";
                        $this->renderPage("login", "headerFooter", ["errors" => $this->errors]);
                    } 
                } else {
                        $this->errors[]="Mot de passe incorrect";
                        $this->renderPage("login", "headerFooter", ["errors" => $this->errors]);
                    }
            } else {
                $this->renderPage("login", "headerFooter", ["errors" => $this->errors]);
            }  
        } else {
            echo "Tentative de XSS";
            $this->renderPage("login");
        }
    }

    public function signup(): void{
        if(
        isset($_POST['name']) &&
        isset($_POST['lastname']) &&
        !empty($_POST['email']) &&
        !empty($_POST['pwd']) &&
        !empty($_POST['pwdConfirm']) &&
        count($_POST) == 5
        ){
        $email = $this->clearEmail($_POST['email']);
        if($this->userRepository->getFirstByCol('id', 'email', $email)){
            $this->errors[]= "L'email existe déjà en bdd";
        } 
        $name = $this->clearName('name');
        $lastname = $this->clearName('lastname');
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
            $user = new User();
            $user->setName($name);
            $user->setLastName($lastname);
            $user->setEmail($email);
            $user->setPassword($pwdHashed);
            $user->setIsAdmin('false');
            $userId = $this->userRepository->create($user);
            
            if(!empty($userId)){
                $userData = $this->userRepository->getByCol(['email', 'name', 'last_name', 'is_active', 'id', 'is_admin'], 'id', $userId);
                $this->setSessionData($userData);
                $token = hash("sha256", bin2hex(random_bytes(32)));
                $emailVerification = new EmailVerificationModel();
                $emailVerification->setUserID($userId);
                $emailVerification->setToken($token);
                $this->emailRepository->create($emailVerification);

                $emailController = new EmailVerification();
                $emailController->sendVerificationMail($email, $token, "Veuillez activer votre compte", 'activation');
            }
            $this->renderPage("home", "headerFooter");
        } else {
            $this->renderPage("signup", "headerFooter", ["errors" => $this->errors]);
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

        if(!empty($_POST['name'])) {
            $name = $this->clearName('name');
            if(empty($this->errors)){
                $this->userRepository->updateColumn(['name' => $name], $_SESSION["id"]);
                $this->setSessionData(["name" => $name]);
            }
        }

        if(!empty($_POST['lastname'])) {
            $lastName = $this->clearName('lastname');
            if(empty($this->errors)){
                $this->userRepository->updateColumn(['last_name' => $lastName], $_SESSION["id"]);
                $this->setSessionData(["last_name" => $lastName]);
            }
        }

        if($_SESSION['email'] !== $_POST['email']){
            if(!empty($_POST['email'])){
                $email = $this->clearEmail($_POST['email']);
                $this->userRepository->updateColumn(['email' => $email], $_SESSION["id"]);
                $this->setSessionData(["email" => $email]);
            }
        }

        $this->renderPage("userProfil", "headerFooter");
    }

    public function clearEmail($email){
        $email = strtolower(trim($_POST['email']));
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $this->errors[]="Votre email n'est pas correct";
        }else{
            return $email;
        }
    }

    public function clearName($type){
        $name = ucwords(strtolower(trim($_POST[$type])));

        if(!empty($name) && strlen($name)<2){
            $this->errors[]="Votre prénom doit faire au minimum 2 caractères";
        } else {
            return $name;
        }
    }
    
   public function deleteUser(){
        $this->isAuth();
        
        $targetId = (int) ($_POST["id"] ?? 0);
        $isSelfDelete = ($_SESSION["id"] == $targetId);
        $isAdmin = !empty($_SESSION["is_admin"]);
        
        if(!$isSelfDelete && !$isAdmin){
            $this->renderHome();
            return;
        }
        
        if($targetId === 0){
            $this->renderUsers();
            return;
        }
        
        $this->userRepository->delete($targetId);
        
        if($isSelfDelete){
            $this->logout();
        } else {
            $this->renderUsers();
        }
    }

    public function renderSignup(): void{
        $this->renderPage("signup", "noHeader");
    }

    public function renderLogin(): void{
         $this->renderPage("login", "noHeader");
    }

    public function renderAbonnement(): void{
        $this->renderPage("abonnement", "headerFooter");
    }

    public function renderProfil(): void{
        $this->isAuth();
        $this->renderPage( "userProfil", "headerFooter");
    }

    public function renderResetPassword(){
        $this->renderPage("resetPassword");
    }

    public function renderModifyPassword(): void {
        $emailService = new EmailVerification();
        $token = isset($_GET["token"]) ? $_GET["token"] : null;
        $isActiveToken = $emailService->verifyIfTokenExist($token);
        if($isActiveToken){ $this->renderPage("modifyPassword", "headerFooter"); }
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
                $pwdHashed = password_hash($_POST["pwd"], PASSWORD_DEFAULT);
                $email = $this->clearEmail($_POST["email"]);
                
                $userId = $this->userRepository->getFirstByCol('id', 'email', $email);
                
                if(!empty($userId)){
                    $this->userRepository->updateColumn(['password' => $pwdHashed], $userId);
                    $_SESSION['error'] = "Votre mot de passe à été modifié";
                    $this->renderPage("login");
                }
            } else {
                $_SESSION['error'] = "Mdp invalid";
                $this->renderPage("modifyPassword", "headerFooter", ["errors" => $this->errors]);
            }
        } else {
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

    public function renderHome() {
         $this->renderPage("home", "headerFooter");
    }
}

