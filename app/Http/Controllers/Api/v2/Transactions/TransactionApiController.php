<?php

namespace App\Http\Controllers\Api\v2\Transactions;

use App\Models\Book\Book;
use Illuminate\Http\Request;
use App\Models\BookStock\BookStock;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Models\Transaction\Transaction;
use App\Http\Helpers\ResponseFormatterHelper;
use App\Models\TransactionDetail\TransactionDetail;

class TransactionApiController extends Controller
{
    private function decrypted_id(String $id){
        $decrypted_id = $id != '' ? is_int(Crypt::decrypt($id)) ? Crypt::decrypt($id) != 0 ? Crypt::decrypt($id) : NULL : NULL : NULL;
    
        return $decrypted_id;
    }

    public function get_status_transaction(String $userid)
    {
        $decrypted_id = $userid != '' ? is_int(Crypt::decrypt($userid)) ? Crypt::decrypt($userid) : NULL : NULL;
    
        if ($decrypted_id == NULL && $decrypted_id == 0) {
            return ResponseFormatterHelper::error(NULL, 'Maaf akun anda tidak ditemukan', 400);
        }
    
        # get transaction data
        $transactionData = Transaction::select(
            'transactions.id as transaksiid',
            'transactions.created_at as transaction_date',
            'transactions.status_approval as status',
            'transactions.userid'
        )
        ->leftJoin('transaction_details as td', 'transactions.id', '=', 'td.transaction_id')
        ->where('userid', $decrypted_id)
        ->groupBy('transactions.id')
        ->get();
    
        // Prepare the final result
        $result = [];
        foreach ($transactionData as $transaction) {
            // Get book_ids for each transaction
            $bookIds = TransactionDetail::where('transaction_id', $transaction->transaksiid)
                ->pluck('book_id'); // Assuming 'book_id' is the column name in transaction_details
    
            for ($i=0; $i < count($bookIds); $i++) { 
                $bookIds[$i] = Crypt::encrypt($bookIds);
            }

            // Add the transaction data to the result
            $result[] = [
                'transaksiid' => Crypt::encrypt($transaction->transaksiid),
                'no_transaksi' => '0001/LTR-I/2025', // Replace with actual logic to get no_transaksi
                'transaction_date' => $transaction->created_at, // Format date
                'status' => $transaction->status,
                'loaning_total' => TransactionDetail::where('transaction_id', $transaction->transaksiid)->count(),
                'userid' => Crypt::encrypt($transaction->userid),
                'book_ids' => $bookIds
            ];
        }
    
        if (empty($result)) {
            return ResponseFormatterHelper::error(NULL, 'Data tidak ditemukan', 404);
        }
    
        return ResponseFormatterHelper::success($result, 'Data transaksi berhasil diterima');
    }

    public function store_transaction(Request $request)
    {
        $validatedData = $request->validate([
            'userid' => 'required|string',
            'bookids' => 'required|array',
            'bookids.*' => 'string', // Validasi setiap book_id dalam array
            // Tambahkan validasi lain sesuai kebutuhan
        ]);
    
        $decryptedUserId = $this->decrypted_id($validatedData['userid']);
        $decryptBookIds = [];
    
        // Dekripsi setiap book_id
        foreach ($validatedData['bookids'] as $bookId) {
            $decryptBookIds[] = Crypt::decrypt($bookId);
        }
    
        // Ambil data buku berdasarkan ID
        $bookData = Book::whereIn('id', $decryptBookIds)->get();
    
        $category_id = [];
        foreach ($bookData as $list) {
            $category_id[] = $list->category_id;
        }
    
        // Ambil stok buku berdasarkan category_id
        $stockExist = BookStock::whereIn('category_id', $category_id)->get();
    
        // Cek stok buku
        foreach ($stockExist as $list) {
            if ($list->total <= 0) {
                return ResponseFormatterHelper::error(NULL, 'Maaf total dari stok salah satu buku yang ingin anda pinjam tidak mencukupi', 400);
            }
        }
    
        return ResponseFormatterHelper::success($stockExist, 'Transaksi berhasil, stok buku telah diperbarui');
    }
}
