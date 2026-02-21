<?php

namespace App\Controller;

use App\Controller\Base;
use App\Service\EmailVerificationService;
use App\Service\AuthService;
use App\Controller\Auth;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

class EmailVerification extends Base
{

    private $errors = [];
    public function sendVerificationMail($email, $token, $subject, $path){
        $activationLink = "http://localhost:8080/".$path."?email=".$email."&token=".$token;
        $mail = new PHPMailer(true);
            try {
                $mail->SMTPDebug = 0;
                $mail->isSMTP();
                $mail->Host       = 'mailpit';
                $mail->SMTPAuth   = false;
                $mail->Port       = 1025;

                $mail->setFrom('from@example.com', 'Mailer');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body    = 'Cliquez sur ce lien : <a href="'.$activationLink.'">ici!</a>';
                $mail->AltBody = $activationLink;

                $mail->send();
                echo 'Un mail de confirmation vient de vous être envoyé';
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
    }

    public function sendResetPwdMail(){
        $auth = new Auth();
        $email = $auth->clearEmail($_POST['email']);
        $verifiyMail = new AuthService();
        if($verifiyMail->verifyEmail($email)){
            $userId = $verifiyMail->getUserIdFromMail($email);
            $is_active = $verifiyMail->getIsActiveFromId($userId);
            if($is_active === false){
                $this->errors[]= "Vous devez d'abord activer votre compte par mail";
                $this->renderPage("resetPassword", "frontoffice", ["errors" => $this->errors]);
            } else {
                $token = hash("sha256", bin2hex(random_bytes(32)));
                $emailVerificationService = new EmailVerificationService();
                $emailVerificationService->updateUserToken($userId, $token);
                $this->sendVerificationMail($email, $token, "Veuillez modifier votre mot de passe", 'modifyPassword');
            }
        } else{
            $this->errors[]= "L'email n'existe pas en bdd";
            $this->renderPage("resetPassword", "frontoffice", ["errors" => $this->errors]);
        }
    }

    public function activateAccount(){
        $token = isset($_GET["token"]) ? $_GET["token"] : null;
        $isActiveToken = $this->verifyIfTokenExist($token);
        if($isActiveToken){
            $emailverificationService = new EmailVerificationService();
            $userId = $emailverificationService->getUserIdFromToken($token);
            if(!empty($userId)){
                $auth = new AuthService();
                $emailverificationService->activeAccount($userId);
                $userData = $auth->getUserDataFromId($userId);
                $this->setSessionData($userData);
            }
            $this->renderPage("dashboard", "backoffice");
        } 
    }

    public function verifyIfTokenExist($token){
        if (!isset($token)) {
            $this->renderHome();
        } else{
            $emailService = new EmailVerificationService();
            $tokenExist = $emailService->getUserIdFromToken($token);
            if(!$tokenExist){
                $this->renderHome();
            } else{
                return true;
            }
        }
    }

}