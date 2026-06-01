<?php

namespace App\Model;

class Transaction
{
    protected string $id;
    protected int $account_id;
    protected string $type; // 'expense' ou 'income'
    protected string $short_name;
    protected string $description;
    protected string $frequency; // 'ONE_TIME' ou 'RECURRING'
    protected int $interval_months;
    protected float $amount;
    protected string $start_date;
    protected ?string $end_date;
    protected string $created_at;

    public function getId(): string { return $this->id; }
    public function setId(string $id): void { $this->id = $id; }

    public function getAccountId(): int { return $this->account_id; }
    public function setAccountId(int $account_id): void { $this->account_id = $account_id; }

    public function getType(): string { return $this->type; }
    public function setType(string $type): void { $this->type = $type; }

    public function getShortName(): string { return $this->short_name; }
    public function setShortName(string $short_name): void { $this->short_name = trim($short_name); }

    public function getDescription(): string { return $this->description ?? ''; }
    public function setDescription(string $description): void { $this->description = trim($description); }

    public function getFrequency(): string { return $this->frequency; }
    public function setFrequency(string $frequency): void { $this->frequency = $frequency; }

    public function getIntervalMonths(): int { return $this->interval_months ?? 1; }
    public function setIntervalMonths(int $interval_months): void { $this->interval_months = $interval_months; }

    public function getAmount(): float { return $this->amount; }
    public function setAmount(float $amount): void { $this->amount = $amount; }

    public function getStartDate(): string { return $this->start_date; }
    public function setStartDate(string $start_date): void { $this->start_date = $start_date; }

    public function getEndDate(): ?string { return $this->end_date; }
    public function setEndDate(?string $end_date): void { $this->end_date = $end_date; }

    public function getCreatedAt(): string { return $this->created_at; }
    public function setCreatedAt(string $created_at): void { $this->created_at = $created_at; }
}
