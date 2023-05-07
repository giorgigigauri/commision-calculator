<?php

namespace App\Data;

use App\CommissionProcessors\CommissionProcessor;
use app\Enums\Currency;
use app\Enums\TransactionType;
use app\Enums\UserType;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;

class UserTransactionData extends Data
{
    public function __construct(
        public int             $id,
        public string          $date,
        public int             $userId,
        #[WithCast(EnumCast::class)]
        public UserType        $userType,
        #[WithCast(EnumCast::class)]
        public TransactionType $transactionType,
        public float           $amount,
        #[WithCast(EnumCast::class)]
        public Currency        $currency,
        public ?float          $commission,
    ) {

    }

    public function convertedToEur()
    {
        return $this->amount * $this->currency->getRate();
    }

    public function getProcessor(): CommissionProcessor
    {
        return (new ($this->userType->getProcessor())($this));
    }

    public function process(): static
    {
        $res = ($this->getProcessor())->calculate();
        $this->setCommission($res);
        return $this;
    }

    public function setCommission($amount): static
    {
        $this->commission = ceil($amount * 100) / 100;
        return $this;
    }
}
