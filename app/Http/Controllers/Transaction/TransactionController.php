<?php

namespace App\Http\Controllers\Transaction;

use App\Models\Book\Book;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

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
        return view('pages.peminjaman.view_detail_book', $data);
    }

    public function store_data_peminjaman(Request $request){
        $bookId = $request->book_id != '' ? is_int((int)Crypt::decryptString($request->book_id)) ? (int)Crypt::decryptString($request->book_id) ? (int)Crypt::decryptString($request->book_id) != 0 ? (int)Crypt::decryptString($request->book_id) : NULL : NULL : NULL;
        $tglPeminjaman = $request->tanggal_pinjam;

        // Membuat objek DateTime dari tanggal yang diberikan
        $tglPengembalian = new DateTime($tglPeminjaman);

        // Menambahkan 5 hari
        $tglPengembalian->modify('+5 days');

        if ($bookId == NULL){
            return response()->json([
                'success' => FALSE,
                'message' => 'Terjadi kesalahan, pada data buku!.',
                'data' => [],
            ], 500);
        }

        DB::beginTransaction();
    
        try {
            Transaction::create([
                'userid' => 1,
                'jenis_transaksi' => 'Peminjaman',
                'tgl_pinjam' => $request->tanggal_pinjam,
                'tgl_wajib_kembali' => $tglPengembalian
            ]);
    
            TransactionDetail::create([
                
            ]);

            // Commit the transaction
            DB::commit();
    
            return response()->json([
                'success' => TRUE,
                'message' => 'Berhasil menambahkan akun.',
                'data' => [],
            ], 200);
        } catch (\Exception $e) {
            // Rollback the transaction if something goes wrong
            DB::rollBack();
    
            return response()->json([
                'success' => FALSE,
                'message' => 'Terjadi kesalahan saat menambahkan akun: ' . $e->getMessage(),
                'data' => [],
            ], 500);
        }
    }
}
