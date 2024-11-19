<?php

namespace App\Http\Controllers\Transaction;

use DateTime;
use App\Models\Book\Book;
use Endroid\QrCode\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Crypt;
use App\Models\Transaction\Transaction;
use Endroid\QrCode\ErrorCorrectionLevel;
use App\Models\TransactionDetail\TransactionDetail;

class TransactionController extends Controller
{
    /**
     * pada fungsi berikut menampilkan halaman peminjaman dari sisi user
     * dapat diakase oleh semua role user
     */
    public function index(Request $request){
        $dataBuku = Book::select(
            'books.id as book_id', 
            'book_stocks.id as stock_id', 
            'books.judul',
            'books.penulis',
            'books.tahun_terbit',
            'books.penerbit')
            ->join('book_stocks', 'book_stocks.book_id', '=', 'books.id')->get();
        foreach ($dataBuku as $list) {
            $list->book_id = Crypt::encryptString($list->book_id);
            $list->stock_id = Crypt::encryptString($list->stock_id);
        }

        $data['title'] = 'Daftar Buku';
        $data['book_list'] = $dataBuku;

        return view('pages.peminjaman.index', $data);
    }

    /**
     * pada fungsi ini akan menampilkan data buku berdasarkan id dari buku yang dipilih
     * @param id string (id buku dari table books)
     */
    public function pengajuan_peminjaman($bookId){
        $validBookId = $bookId != '' ? is_int((int)Crypt::decryptString($bookId)) ? (int)Crypt::decryptString($bookId) != 0 ? (int)Crypt::decryptString($bookId) : NULL  : NULL : NULL;
    
        if ($validBookId == NULL) {
            return redirect()->back()->withErrors('Data buku tidak valid')->withInput();
        }
    
        $dataBuku = Book::find($validBookId);
        $data['id_buku'] = $bookId;
        $data['detail_buku'] = $dataBuku;
        $data['title'] = 'Proses Peminjaman';
    
        // Encrypt the IDs and create a new array
        $dataOpsiBuku = Book::get()->map(function($list) {
            return [
                'id' => Crypt::encryptString($list->id),
                'judul' => $list->judul,
                'no_revisi' => $list->no_revisi,
                'penulis' => $list->penulis,
                'tahun_terbit' => $list->tahun_terbit,
                'penerbit' => $list->penerbit,
                'created_at' => $list->created_at,
                'updated_at' => $list->updated_at,
                'image_url' => $list->image_url,
            ];
        });
    
        $data['books'] = $dataOpsiBuku;
    
        return view('pages.peminjaman.view_detail_book', $data);
    }

    public function store_data_peminjaman(Request $request){
        $bookId          = $request->book_id != '' ? is_int((int)Crypt::decryptString($request->book_id)) ? (int)Crypt::decryptString($request->book_id) != 0 ? (int)Crypt::decryptString($request->book_id) : NULL : NULL : NULL;
        $tglPeminjaman   = $request->tanggal_pinjam;
        $additionalBooks  = $request->addional_books;
        // Initialize an array to hold the decrypted values
        $decryptedBooks = [];

        foreach ($additionalBooks as $book) {
            try {
                // Decrypt the book ID
                $decryptedBookId = Crypt::decryptString($book);
                $decryptedBooks[] = $decryptedBookId; // Store the decrypted ID
            } catch (DecryptException $e) {
                // Handle the exception if decryption fails
                return redirect()->back()->withErrors('Failed to decrypt book ID: ' . $e->getMessage());
            }
        }

        if (in_array($bookId, $decryptedBooks)) {
            $duplikatBook = Book::find($bookId);
            return response()->json([
                'success' => FALSE,
                'message' => 'Terjadi kesalahan, buku dengan judul ' . $duplikatBook->judul . ' telah dipilih lebih dari satu kali, ketentuan hanya boleh meminjam 1 buku perjudul!.',
                'data'    => [],
            ], 400);
        }
        
        if(!$request->tanggal_pinjam){
            return response()->json([
                'success' => FALSE,
                'message' => 'Terjadi kesalahan, Tanggal peminjaman tidak boleh kosong!.',
                'data'    => [],
            ], 400);
        }

        // Membuat objek DateTime dari tanggal yang diberikan
        $tglPengembalian = new DateTime($tglPeminjaman);

        // Menambahkan 5 hari
        $tglPengembalian->modify('+5 days');

        if ($bookId == NULL){
            return response()->json([
                'success' => FALSE,
                'message' => 'Terjadi kesalahan, pada data buku!.',
                'data'    => [],
            ], 500);
        }
        
        DB::beginTransaction();
    
        try {

            $transaction = Transaction::create([
                'userid'            => 1,
                'jenis_transaksi'   => 'Peminjaman',
                'tgl_pinjam'        => $request->tanggal_pinjam,
                'tgl_wajib_kembali' => $tglPengembalian,
                'status_approval'   => 'Waiting'
            ]);

            // additional book
            for($i = 0; $i < count($decryptedBooks); $i++){
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'book_id'        => $decryptedBooks[$i]
                ]);
            }
    
            // choosed book
            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'book_id'        => $bookId
            ]);

            // Commit the transaction
            DB::commit();
    
            return response()->json([
                'success' => TRUE,
                'message' => 'Berhasil menambahkan akun.',
                'data'    => [],
            ], 200);
        } catch (\Exception $e) {
            // Rollback the transaction if something goes wrong
            DB::rollBack();
    
            return response()->json([
                'success' => FALSE,
                'message' => 'Terjadi kesalahan saat menambahkan akun: ' . $e->getMessage(),
                'data'    => [],
            ], 500);
        }
    }

    /**
     * @param integer id [transaksi id]
     */
    public function detail_peminjaman($id){
        $validTransactionId = $id != '' ? is_int((int)Crypt::decryptString($id)) ? (int)Crypt::decryptString($id) != 0 ? (int)Crypt::decryptString($id) : NULL : NULL : NULL;
        $data['title'] = 'Detail Peminjaman Buku';
        
        // cek apakah id dai data yang dipilih tidak null
        if ($validTransactionId == NULL) {
            return redirect()->back()->withErrors(array('error', 'Data yang dipilih tidak ditemukan'));
        }

        // ambil data setiap buku yang dipinjam yang berasal dari transaksi peminjaman
        $detailTransaksiItem = TransactionDetail::join('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
        ->join('books', 'books.id', '=', 'transaction_details.book_id')
        ->where('transaction_id', $validTransactionId)->get();

        $dataTransaksi = Transaction::select('transactions.id', 'users.name', 'users.email', 'transactions.jenis_transaksi', 'transactions.tgl_pinjam', 'transactions.tgl_wajib_kembali')
        ->where('transactions.id', $validTransactionId)
        ->join('users', 'users.id', '=', 'transactions.userid')
        ->first();

        $data['transaksi'] = $dataTransaksi;
        $data['transaksi_detail'] = $detailTransaksiItem;
        $data['transaction_id'] = $id;

        // dd($detailTransaksiItem);
        return view('pages.approval_peminjaman.view', $data);
    }

    public function cancel_peminjaman(Request $request)
    {
        // Decrypt and validate transaction ID
        $validTransactionId = $request->id != '' ? is_int((int)Crypt::decryptString($request->id)) ? (int)Crypt::decryptString($request->id) != 0 ? (int)Crypt::decryptString($request->id) : NULL  : NULL : NULL;
    
        // Start the database transaction
        DB::beginTransaction();
    
        try {
            // Find the transaction
            $transaction = Transaction::find($validTransactionId);
    
            // If transaction not found, rollback and return error
            if (!$transaction) {
                DB::rollBack();
                return response()->json([
                    'success' => FALSE,
                    'message' => 'Maaf, transaksi tidak ditemukan.',
                    'data'    => [],
                ], 404);
            }
    
            // Get transaction details
            $transactionDetail = TransactionDetail::where('transaction_id', $validTransactionId)->get();
    
            // Delete the transaction details
            foreach ($transactionDetail as $detail) {
                $detail->delete();
            }
    
            // Delete the transaction
            $transaction->delete();
    
            // Commit the transaction
            DB::commit();
    
            // Return a successful response
            return response()->json([
                'success' => TRUE,
                'message' => 'Data berhasil dihapus.',
                'data'    => [],
            ], 200);
    
        } catch (\Exception $e) {
            // If anything fails, rollback the transaction
            DB::rollBack();
            return response()->json([
                'success' => FALSE,
                'message' => 'Terjadi kesalahan, silahkan hubungi administrator.',
                'data'    => [],
            ], 500);
        }
    }
}
