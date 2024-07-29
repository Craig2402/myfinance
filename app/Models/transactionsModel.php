<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transactionsModel extends Model
{
    use HasFactory;
    protected $fillable = [
        'transaction_id',
        'amount',
        'date',
        'reason',
    ];
}
