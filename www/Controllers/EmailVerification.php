<?php

namespace App\Controller;

use App\Controller\Base;
use App\Controller\Auth;
use App\Repository\EmailVerificationRepository;
use App\Repository\UserRepository;
use App\Repository\SubscriptionRepository;


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

class EmailVerification extends Base
{

    private $errors = [];

    private UserRepository $userRepository;
    private EmailVerificationRepository $emailRepository;
    private SubscriptionRepository $subscriptionRepository;

    public function __construct(){
        parent::__construct();
        $this->userRepository = new UserRepository();
        $this->emailRepository = new EmailVerificationRepository();
        $this->subscriptionRepository = new SubscriptionRepository();
    }

public function sendVerificationMail($email, $token, $subject, $path){
    $activationLink = "http://localhost:1001/".$path."?email=".$email."&token=".$token;
    $mail = new PHPMailer(true);
    try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host     = 'mailpit';
        $mail->SMTPAuth = false;
        $mail->Port     = 1025;

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
        $userId = $this->userRepository->getFirstByCol('id', 'email', $email);
        if($userId){
            $data = $this->userRepository->getByCol(['is_active'], 'id', $userId);
            $isActive = $data['is_active'] ?? false;
            if($isActive === false){
                $this->errors[]= "Vous devez d'abord activer votre compte par mail";
                $this->renderPage("resetPassword", "headerFooter", ["errors" => $this->errors]);
            } else {
                $token = hash("sha256", bin2hex(random_bytes(32)));
                $this->emailRepository->updateToken($userId, $token);
                $sent = $this->sendVerificationMail($email, $token, "Veuillez modifier votre mot de passe", 'modifyPassword');
                if($sent){
                    $_SESSION['flash'] = 'Un mail de confirmation vient de vous être envoyé';
                }
                header('Location: /');
                exit;
            }
        } else{
            $this->errors[]= "L'email n'existe pas en bdd";
            $this->renderPage("resetPassword", "headerFooter", ["errors" => $this->errors]);
        }
    }

    public function activateAccount(){
        $token = isset($_GET["token"]) ? $_GET["token"] : null;
        $isActiveToken = $this->verifyIfTokenExist($token);
        if($isActiveToken){
            $userId = $this->emailRepository->findUserIdByToken($token);
            if(!empty($userId)){
                $this->userRepository->activate($userId);
                $userData = $this->userRepository->getByCol(
                    ['email', 'name', 'last_name', 'is_active', 'id', 'is_admin'],
                    'id',
                    $userId
                );
                $userData['subscription_type'] = $this->subscriptionRepository->getFirstByCol('type', 'user_id', $userId) ?? 'FREE';
                $this->setSessionData($userData);
            }
            $this->renderPage("userProfil");
        }
    }

    public function verifyIfTokenExist($token){
        if (!isset($token)) {
            $this->renderPage("home", "headerFooter");
        } else{
            $tokenExist = $this->emailRepository->findUserIdByToken($token);
            if(!$tokenExist){
                $this->renderPage("home", "headerFooter");
            } else{
                return true;
            }
        }
    }

}