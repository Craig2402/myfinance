<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class SavingsTarget extends Model
{
    use HasFactory;

    protected $table = 'savings_targets';

    protected $fillable = [
        'group',
        'target_amount',
        'description',
        'target_date',
        'is_achieved',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'target_date' => 'date',
        'is_achieved' => 'boolean',
    ];

}