<?php

namespace App\Actions;

use App\Data\UserTransactionData;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class GetTransactions
{
    private $file = 'input.csv';
    public function __construct($file = 'input.csv')
    {
        $this->file = $file;
    }
    /**
     * @return Collection<UserTransactionData>
     */
    public function execute(): Collection
    {
        $storage = Storage::disk('local');
        $action = new ParseCsvAction();
        $csv = $storage->get($this->file);
        return collect($action->setCsv($csv)->execute());
    }
}
