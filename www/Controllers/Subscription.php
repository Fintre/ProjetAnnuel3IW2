<?php

namespace App\Controller;

use App\Controller\Base;
use App\Service\StripeService;
use App\Repository\SubscriptionRepository;

class Subscription extends Base
{
    private StripeService $stripeService;
    private SubscriptionRepository $subscriptionRepository;

    public function __construct()
    {
        parent::__construct();
        $this->stripeService = new StripeService();
        $this->subscriptionRepository = new SubscriptionRepository();
    }

    public function subscribe(){
        $this->isAuth();

        $plan = strtolower($_POST['plan'] ?? '');
        $priceMap = [
            'plus' => getenv('STRIPE_PRICE_PLUS'),
            'pro'  => getenv('STRIPE_PRICE_PRO'),
        ];

        if (!isset($priceMap[$plan])) {
            header("Location: /abonnement");
            exit;
        }

        $session = $this->stripeService->createCheckoutSession(
            $priceMap[$plan],
            (int) $_SESSION['id'],
            $_SESSION['email'] ?? '',
            $plan
        );

        header("Location: " . $session->url);
        exit;
    }

    public function subscribeSuccess(){
        $this->isAuth();

        $sessionId = $_GET['session_id'] ?? '';
        if (empty($sessionId)) {
            header("Location: /abonnement");
            exit;
        }

        $session = $this->stripeService->retrieveSession($sessionId);

        if ($session->payment_status !== 'paid') {
            header("Location: /abonnement?error=payment_failed");
            exit;
        }

        $newType = $session->metadata['plan'] ?? 'FREE';
        $userId  = (int) $session->client_reference_id;

        $this->subscriptionRepository->updateByUserId([
            'type'                   => $newType,
            'stripe_customer_id'     => $session->customer,
            'stripe_subscription_id' => $session->subscription,
        ], $userId);

        $_SESSION['subscription_type'] = $newType;

        header("Location: /profil");
        exit;
    }

    public function unsubscribe(){
        $this->isAuth();

        $userId = (int) $_SESSION['id'];

        $stripeSubId = $this->subscriptionRepository->getFirstByCol('stripe_subscription_id', 'user_id', $userId);

        if (!empty($stripeSubId)) {
            try {
                $this->stripeService->cancelSubscription($stripeSubId);
            } catch (\Exception $e) {
                // L'abonnement Stripe n'existe peut-être plus, on continue quand même
            }
        }

        $this->subscriptionRepository->updateByUserId([
            'type'                   => 'FREE',
            'stripe_subscription_id' => null,
        ], $userId);

        $_SESSION['subscription_type'] = 'FREE';

        header("Location: /profil");
        exit;
    }
}
