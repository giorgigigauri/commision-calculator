<?php

namespace App\Actions;

use App\Data\UserTransactionData;
use Illuminate\Support\Collection;

class CalculateCommissionAction
{
    private $transactions = [];
    public function __construct($file = 'input.csv')
    {
        $this->transactions = $this->getTransactions($file);
    }
    /**
     * @return Collection<UserTransactionData>
     */
    public function getTransactions($file)
    {
        return (new GetTransactions($file))->execute();
    }

    public function execute(): Collection
    {
        foreach ($this->transactions as $transaction) {
            //            dd($transaction);
            //
            $transaction->process();
        }
        return $this->transactions;
    }
}
