<?php

namespace app\Enums;

use App\CommissionProcessors\BusinessProcessor;
use App\CommissionProcessors\PrivateProcessor;

enum UserType: string
{
    case BUSINESS = 'business';
    case PRIVATE = 'private';


    public function getProcessor(): string
    {
        return match ($this) {
            self::BUSINESS => BusinessProcessor::class,
            self::PRIVATE => PrivateProcessor::class,
        };
    }
}
