<?php

namespace app\Enums;

enum TransactionType: string
{
    case WITHDRAW = 'withdraw';
    case DEPOSIT = 'deposit';


}
