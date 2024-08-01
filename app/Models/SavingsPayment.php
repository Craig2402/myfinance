<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavingsPayment extends Model
{
    use HasFactory;

    protected $table = 'savings_payments';

    protected $fillable = [
        'savings_target_id',
        'amount',
        'payment_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function savingsTarget()
    {
        return $this->belongsTo(SavingsTarget::class, 'savings_target_id');
    }

}
