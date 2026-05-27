<?php

namespace App\Service;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Subscription;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));
    }

    public function createCheckoutSession(string $priceId, int $userId, string $userEmail, string $plan): Session
    {
        return Session::create([
            'mode' => 'subscription',
            'line_items' => [[
                'price' => $priceId,
                'quantity' => 1,
            ]],
            'customer_email' => $userEmail,
            'client_reference_id' => (string) $userId,
            'metadata' => [
                'user_id' => $userId,
                'plan'    => strtoupper($plan),
            ],
            'success_url' => 'http://localhost:1001/subscribeSuccess?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => 'http://localhost:1001/abonnement',
        ]);
    }

    public function retrieveSession(string $sessionId): Session {
        return Session::retrieve($sessionId);
    }

    public function cancelSubscription(string $subscriptionId): Subscription {
        return Subscription::retrieve($subscriptionId)->cancel();
    }
}
