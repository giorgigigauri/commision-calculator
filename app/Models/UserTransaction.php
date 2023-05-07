<?php

namespace App\Models;

use app\Enums\UserType;
use Illuminate\Database\Eloquent\Model;

class UserTransaction extends Model
{
    protected $table = null;

    protected $fillable = [
        'date',
        'user_id',
        'user_type',
        'transaction_type' => UserType::class,
        'amount',
        'currency',
    ];
}
