<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class recieveModel extends Model
{
    use HasFactory;

    protected $table = 'recieve';
    protected $fillable = ['transaction_id', 'amount', 'transaction_date'];
    
}
