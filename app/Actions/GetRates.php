<?php

namespace App\Actions;

use Illuminate\Support\Facades\Http;

class GetRates
{
    public function execute()
    {
        $rates = Http::get('https://developers.paysera.com/tasks/api/currency-exchange-rates')
            ->json();
        //        return ($rates['rates']);
        return [
            'EUR' => 1.0,
            'USD' => 1.1497,
            'JPY' => 129.53,
        ];
    }
}
