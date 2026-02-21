<?php

namespace App\Model;


class User
{
    protected $id;
    protected $name;
    protected $email;
    protected $password;

    protected $is_active;
    protected $is_admin;
    function setName($name){
        $this->name = $name;
    }

    function setEmail($email){
        $this->email = $email;
    }
    
    function setPassword($password){
        $this->password = $password;
    }

    function setIsActive($data){
        $this->is_active = $data;
    }

    function setIsAdmin($data){
        $this->is_admin = $data;
    }
    function getId(){ 
        return $this->id;
    }

    function getName(){
        return $this->name;
    }
    function getEmail(){
        return $this->email;
    }

    function getPassword(){
        return $this->password;
    }

    function getIsActive(){
        return $this->is_active;
    }
    function getIsAdmin(){ 
        return $this->is_admin;
    }
}  