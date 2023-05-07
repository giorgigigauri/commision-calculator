<?php

namespace App\CommissionProcessors;

use app\Enums\TransactionType;

class BusinessProcessor extends CommissionProcessor
{
    public function __construct(private $transaction)
    {

    }

    public function calculate(): float
    {
        if ($this->transaction->transactionType == TransactionType::WITHDRAW) {
            $commission = $this->transaction->amount * 0.005;
            return $commission;
        } elseif ($this->transaction->transactionType == TransactionType::DEPOSIT) {
            $commission = $this->transaction->amount * 0.0003;
            return $commission;
        }
        return 0;
    }
}
