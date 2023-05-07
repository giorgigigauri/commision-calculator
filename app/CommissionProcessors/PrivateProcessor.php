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
            $this->transaction->amount = Currency::convertToEur($this->transaction->amount, $this->transaction->currency);
            $sum = $weeklyTrs->sum('amount');

            if($weeklyTrs->count() > 3 || $sum - $this->transaction->amount >= 1000) {
                $commission = $this->transaction->amount * 0.003;
            } else {
                if($sum - 1000 > 0) {
                    $commission = ($sum - 1000) * 0.003;
                }
            }
            if($this->transaction->currency->name != Currency::EUR->name) {
                $rates = Currency::GetRates();
                $commission = $commission * $rates[$this->transaction->currency->name];
            }
            return $commission;
        } elseif ($this->transaction->transactionType == TransactionType::DEPOSIT) {
            $commission = $this->transaction->amount * 0.0003;
            return $commission;
        }
        return 0;
    }
}
