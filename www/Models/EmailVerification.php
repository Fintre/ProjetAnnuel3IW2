<?php

namespace App\Model;


class EmailVerification
{
    protected $user_id;
    protected $token;

    function setUserID($user_id){
        $this->user_id = $user_id;
    }

    function getUserID(){
        return $this->user_id;
    }

    function setToken($token){  
        $this->token = $token;
    }

    function getToken(){
        return $this->token;
    }
}