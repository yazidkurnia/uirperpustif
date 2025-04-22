<?php

namespace App\Models\TransactionDetail;

use App\Models\Transaction\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionDetail extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'transaction_id','book_id'
    ];

    /**
     * Get all of the transaction for the TransactionDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transaction(): HasMany
    {
        return $this->hasMany(Transaction::class, 'transaction_id', 'transaction_id');
    }
}
