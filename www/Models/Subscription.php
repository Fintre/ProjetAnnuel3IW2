<?php

namespace App\Model;


class Subscription
{
    protected $id;
    protected $user_id;
    protected $type;

    protected $stripe_customer_id;
    protected $stripe_subscription_id;

    protected $start_date;
    protected $expiration_date;
    protected $created_at;

    function setId($id){
        $this->id = $id;
    }

    function setUserId($user_id){
        $this->user_id = $user_id;
    }

    function setType($type){
        $this->type = $type;
    }

    function setStripeCustomerId($stripe_customer_id){
        $this->stripe_customer_id = $stripe_customer_id;
    }

    function setStripeSubscriptionId($stripe_subscription_id){
        $this->stripe_subscription_id = $stripe_subscription_id;
    }

    function setStartDate($start_date){
        $this->start_date = $start_date;
    }

    function setExpirationDate($expiration_date){
        $this->expiration_date = $expiration_date;
    }

    function setCreatedAt($created_at){
        $this->created_at = $created_at;
    }

    function getId(){
        return $this->id;
    }

    function getUserId(){
        return $this->user_id;
    }

    function getType(){
        return $this->type;
    }

    function getStripeCustomerId(){
        return $this->stripe_customer_id;
    }

    function getStripeSubscriptionId(){
        return $this->stripe_subscription_id;
    }

    function getStartDate(){
        return $this->start_date;
    }

    function getExpirationDate(){
        return $this->expiration_date;
    }

    function getCreatedAt(){
        return $this->created_at;
    }
}
