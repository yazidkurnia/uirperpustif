<?php

namespace App\Models\TransactionDetail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'transaction_id','book_id'
    ];
}
