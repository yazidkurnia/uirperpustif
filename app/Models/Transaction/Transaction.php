<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'userid', 'book_id', 'jenis_transaksi', 'tgl_pinjam', 'tgl_wajib_kembali', 'status_approval', 'qr_url'
    ];
}
