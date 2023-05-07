<?php

namespace App\Console\Commands;

use App\Actions\CalculateCommissionAction;
use Illuminate\Console\Command;

class CalculateCommissionFees extends Command
{
    protected $signature = 'commission:calculate {file}';

    protected $description = 'Calculate commission fees from CSV file';

    public function handle()
    {
        $file = $this->argument('file');
        $results = (new CalculateCommissionAction($file))->execute();
        foreach ($results as $result) {
            $this->info(number_format($result->commission, 2, '.', ''));
        }
    }
}
