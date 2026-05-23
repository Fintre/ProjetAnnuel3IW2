<?php

namespace App\Model;

class Account
{
    protected int    $id;           
    protected int    $user_id;             
    protected string $short_name;         
    protected string $description;         
    protected string $creation_date;       
    protected float  $annual_interest_rate;
    protected float  $tax_rate;            
    protected float  $balance;             
    protected string $registered_at;       

    public function getId(): int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getUserId(): int { return $this->user_id; }
    public function setUserId(int $user_id): void { $this->user_id = $user_id; }

    public function getShortName(): string { return $this->short_name; }
    public function setShortName(string $short_name): void { $this->short_name = trim($short_name); }

    public function getDescription(): string { return $this->description; }
    public function setDescription(string $description): void { $this->description = trim($description); }

    public function getCreationDate(): string { return $this->creation_date; }
    public function setCreationDate(string $creation_date): void { $this->creation_date = $creation_date; }

    public function getAnnualInterestRate(): float { return $this->annual_interest_rate; }
    public function setAnnualInterestRate(float $rate): void { $this->annual_interest_rate = $rate; }

    public function getTaxRate(): float { return $this->tax_rate; }
    public function setTaxRate(float $tax_rate): void { $this->tax_rate = $tax_rate; }

    public function getBalance(): float { return $this->balance; }
    public function setBalance(float $balance): void { $this->balance = $balance; }

    public function getRegisteredAt(): string { return $this->registered_at; }
    public function setRegisteredAt(string $registered_at): void { $this->registered_at = $registered_at; }
}