<?php

namespace App\CommissionProcessors;

use App\Actions\GetTransactions;
use app\Enums\Currency;
use app\Enums\TransactionType;
use Illuminate\Support\Carbon;

class PrivateProcessor extends CommissionProcessor
{
    public function __construct(private $transaction)
    {

    }

    public function calculate(): float
    {
        $rates = Currency::GetRates();
        $commission = 0;
        if ($this->transaction->transactionType == TransactionType::WITHDRAW) {

            $trDate = Carbon::createFromFormat('Y-m-d H:i:s', $this->transaction->date);
            $weekStart = $trDate->copy()->startOfWeek();
            $weekEnd   = $trDate->copy()->endOfWeek();
            $transactions = (new GetTransactions())->execute();
            $weeklyTrs = $transactions
                ->where('userId', $this->transaction->userId)
                ->where('id', '<=', $this->transaction->id)
                ->where('transactionType', TransactionType::WITHDRAW)
                ->whereBetween('date', [$weekStart, $weekEnd])
                ->each(function (&$item) {
                    $item->amount = Currency::convertToEur($item->amount, $item->currency);
                });
            $transactionAmount = Currency::convertToEur($this->transaction->amount, $this->transaction->currency);
            $sum = $weeklyTrs->sum('amount');

            if($weeklyTrs->count() > 3) {
                $commission = $this->transaction->amount * 0.003;

            } else {
                $limit = max(0,1000 - $sum + $transactionAmount);
                $currencyLimit = $limit * $rates[$this->transaction->currency->name];

                $taxableAmount = max(0,$this->transaction->amount - $currencyLimit);
                $commission = $taxableAmount * 0.003;
            }
            return $commission;
        } elseif ($this->transaction->transactionType == TransactionType::DEPOSIT) {
            $commission = $this->transaction->amount * 0.0003;
            return $commission;
        }
        return 0;
    }
}
