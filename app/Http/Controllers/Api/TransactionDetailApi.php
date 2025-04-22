<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseFormatterHelper;
use App\Models\TransactionDetail\TransactionDetail;

class TransactionDetailApi extends Controller
{
    public function get_transactiondetail_bytransactionid(string $id_transaction){
        $headers      = apache_request_headers();
        $token        = isset($headers['Authorization']) ? $headers['Authorization'] : NULL;

        if ($token == NULL) {
            return ResponseFormatterHelper::error(NULL, 'Token tidak berhasil ditemukan', 409);
        }

        $id_transaction = Crypt::decryptString($id_transaction);

        # get data detail transaction
        $itemTransactionDetail = TransactionDetail::join('transactions t', 't.id', '=', 'transaction_details.transaction_id')->where('transaction_id')->get();

        if (empty($itemTransactionDetail)) {
            return ResponseFormatterHelper::error(NULL, 'Maaf data tidak ditemukan', 404);
        }

        return ResponseFormatterHelper::success($itemTransactionDetail, 'Data berhasil ditemukan');
    }
}
