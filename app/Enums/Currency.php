<?php

namespace app\Enums;

use App\Actions\GetRates;
use Illuminate\Support\Facades\Cache;

enum Currency: string
{
    case EUR = 'EUR';
    case USD = 'USD';
    case JPY = 'JPY';

    public static function GetRates()
    {
        return Cache::remember('rates', 60, function () {
            return (new GetRates())->execute();
        });
    }

    public static function convertToEur($amount, $currency)
    {
        $rates = self::GetRates();
        //        dd($currency);
        $rate = $rates[$currency->value];
        return $amount / $rate;
    }

    public function getRate()
    {
        $rates = self::GetRates();
        $rate = $rates[$this->value];
        return $rate;
    }
}
