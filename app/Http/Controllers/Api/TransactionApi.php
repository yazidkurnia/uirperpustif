<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Book\Book;
use Illuminate\Http\Request;
use App\Models\Category\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Models\Transaction\Transaction;
use App\Http\Helpers\ResponseFormatterHelper;
use App\Models\TransactionDetail\TransactionDetail;

class TransactionApi extends Controller
{
    /**
     * Transaction_store
     *
     * @param Request $request
     * @return void
     * 
     * melakukan proses penginputan data transaksi serta transaksi detail
     */
    public function transaction_store(Request $request) {
        $headers         = apache_request_headers();
        $token           = isset($headers['Authorization']) ? $headers['Authorization'] : null;
        $requestedby     = Crypt::decryptString($request->input('requestedby')); // user id dari peminjam
        $book_ids        = $request->input('book_ids'); // array buku yang akan dipinjam
        $total_item      = $request->input('total_item'); // total item buku yang akan dipinjam
        $jenis_transaksi = $request->input('jenis_transaksi'); // jenis transaksi
        $user_role       = $request->input('user_role');

        if ($token == NULL) {
            return ResponseFormatterHelper::error(NULL, 'Maaf token tidak ditemukan', 404);
        }

        if ($requestedby == 0) {
            return ResponseFormatterHelper::error(NULL, 'User id tidak valid', 400);
        }

        $encrypted_book_ids = [];
        for ($i=0; $i < count($book_ids); $i++) { 
            $encrypted_book_ids[] = Crypt::decryptString($book_ids[$i]);
        }

        // Menghitung frekuensi setiap elemen dalam array
        $counts = array_count_values($encrypted_book_ids);

        // Memfilter elemen yang memiliki frekuensi lebih dari satu (duplikat)
        $duplicates = array_filter($counts, function($count) {
            return $count > 1;
        });

        // Mendapatkan kunci (book_id) dari elemen yang duplikat
        $duplicate_ids = array_keys($duplicates);

        if (!empty($duplicate_ids)) {
            return ResponseFormatterHelper::error(NULL, 'Maaf tidak dapat meminjam buku yang sama', 400);
        }

        $latestTransaction = NULL;
        $checkIsBookExist = Book::find($encrypted_book_ids); // lakukan pengecekan berdasarkan multi id terkait buku

        $getCategoryId = $checkIsBookExist->map(function ($category){
            return $category->category_id;
        });

        $getDataCategoryById = Category::find($getCategoryId);

        if(empty($getDataCategoryById)) {
            return ResponseFormatterHelper::error(NULL, 'Maaf buku dengan kategori tersebut tidak ditemukan');
        }
    
        // check ketersediaan buku berdasarkan id
        if (empty($checkIsBookExist)) {
            return ResponseFormatterHelper::error(NULL, 'Maaf buku yang anda pilih tidak ditemukan', 404);
        }
    
        // check apakah user role adalah mahasiswa dan apakah jumlah buku yang dipinjam lebih dari 2
        if (count($checkIsBookExist) > 2 && $user_role == 2) {
            return ResponseFormatterHelper::error(NULL, 'Maaf mahasiswa hanya dapat meminjam maksimal 2 buah buku', 400);
        }
    
        $newTransactionData = [
            'userid' => $requestedby, // Menggunakan user id dari peminjam
            'jenis_transaksi' => $jenis_transaksi,
            'tgl_pinjam' => Carbon::now(), // Menggunakan Carbon untuk mendapatkan waktu sekarang
            'tgl_wajib_kembali' => Carbon::now()->addDays(7), // Menambahkan 7 hari dari tanggal pinjam
            'status_approval' => 'Waiting',
        ];
    
        try {
            DB::beginTransaction();
            
            // Lakukan penambahan data transaksi
            $latestTransaction = Transaction::create($newTransactionData);
            
            // Ambil ID dari transaksi yang baru saja dibuat
            $latestTransactionId = $latestTransaction->id;
    
            $result = array_map(function($item) use ($latestTransactionId) {
                return [
                    'book_id' => Crypt::decryptString($item),
                    'transaction_id' => $latestTransactionId, // Menggunakan ID yang benar
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                ];
            }, $book_ids);
    
            // Simpan detail transaksi di sini, misalnya:
            TransactionDetail::insert($result);
    
            DB::commit();
            return ResponseFormatterHelper::success($latestTransaction, 'Transaksi berhasil disimpan');
        } catch (\Throwable $h) {
            DB::rollback();
            return ResponseFormatterHelper::error($h, 'Data tidak berhasil disimpan', 500);
        }
    }

    /**
     * Function get_transaction_by_author
     * 
     * @param string $requested_by
     */
    public function get_transaction_by_author(string $requested_by){
        $headers = apache_request_headers();
        $token = isset($headers['Authorization']) ? $headers['Authorization'] : NULL;
        $requested_by = Crypt::decryptString($requested_by);
    
        if ($token == NULL) {
            return ResponseFormatterHelper::error(NULL, 'Maaf token tidak ditemukan', 409);
        }
    
        if (!is_numeric($requested_by) || $requested_by <= 0) {
            return ResponseFormatterHelper::error(NULL, 'Maaf data anda tidak ditemukan', 400);
        }
    
        $getTransactionFromUserId = Transaction::select('transactions.id', 'transactions.userid')->where('userid', $requested_by)->get();
    
        if ($getTransactionFromUserId->isEmpty()) {
            return ResponseFormatterHelper::error(NULL, 'Maaf data history transaksi tidak ditemukan');
        }
    
        foreach ($getTransactionFromUserId as $list) {
            // Log nilai ID sebelum enkripsi
            Log::info('Transaction ID before encryption: ' . $list->id);
    
            // Enkripsi ID
            $encryptedId = Crypt::encryptString($list->id);
    
            // Log nilai ID setelah enkripsi
            Log::info('Transaction ID after encryption: ' . $encryptedId);
    
            // Set ID yang terenkripsi
            $list->id = Crypt::encryptString($list->id);
            $list->userid = Crypt::encryptString($list->userid);
        }
    
        return ResponseFormatterHelper::success($getTransactionFromUserId, 'Data berhasil ditemukan');
    }
}
