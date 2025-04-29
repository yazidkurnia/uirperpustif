<?php

namespace App\Http\Controllers\Api\v2\Transactions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Models\Transaction\Transaction;
use App\Http\Helpers\ResponseFormatterHelper;
use App\Models\TransactionDetail\TransactionDetail;

class TransactionDetailApiController extends Controller
{
    private function decrypted_id(String $id){
        $decrypted_id = $id != '' ? is_int(Crypt::decrypt($id)) ? Crypt::decrypt($id) != 0 ? Crypt::decrypt($id) : NULL : NULL : NULL;
    
        return $decrypted_id;
    }

    public function get_detail_transaction(String $transactionId){
        $decrypted_id = $this->decrypted_id($transactionId);

        # Get data transaction by id
        $transactionById = Transaction::select(
            'transactions.id',
            'transactions.created_at as transaction_date',
            'transactions.status_approval as status',
            'transactions.userid'
        )->join('users as u', 'transactions.userid', '=', 'u.id')->where('transactions.id', $decrypted_id)->first();

        $bookIds = TransactionDetail::where('transaction_id', $decrypted_id)
        ->pluck('book_id');

        for ($i=0; $i < count($bookIds); $i++) { 
            $bookIds[$i] = Crypt::encrypt($bookIds);
        }

        $result = [
            'transaksiid'      => Crypt::encrypt($transactionById->id),
            'no_transaksi'     => '0001/TRT-I/2025',
            'transaction_date' => $transactionById->transaction_date,
            'status'           => $transactionById->status,
            'loaning_total'    => TransactionDetail::where('transaction_id', $decrypted_id)->count(),
            'userid'           => Crypt::encrypt($transactionById->userid),
            'user_name'        => $transactionById->name ?? 'Guest',
            'book_ids'         => $bookIds
        ];

        if (empty($result)) {
            return ResponseFormatterHelper::error(NULL, 'Maaf data tidak ditemukan');
        }
        return ResponseFormatterHelper::success($result, 'Data berhasil didapatkan');
    }

}
