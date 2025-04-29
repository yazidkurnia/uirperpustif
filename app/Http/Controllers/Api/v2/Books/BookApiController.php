<?php

namespace App\Http\Controllers\Api\v2\Books;

use App\Models\Book\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Http\Helpers\ResponseFormatterHelper;

class BookApiController extends Controller
{
    /**
    * this function use to get 5 book with max transaction
    **/
    public function fetch_best_five_book(String $category_id) {
        // Mengambil lima buku teratas berdasarkan jumlah transaksi
        $get_books = Book::select(
                        'books.id as bookid',
                        'books.judul as title',
                        'books.no_revisi as deskripsi',
                        'books.category_id as category_id',
                        'books.image_url',
                        \DB::raw('COUNT(transaction_details.id) as transaction_count')
                    )
                    ->leftJoin('transaction_details', 'books.id', '=', 'transaction_details.book_id')
                    ->where('books.category_id', $category_id)
                    ->groupBy('books.id')
                    ->orderBy('transaction_count', 'DESC')
                    ->limit(5)
                    ->get();
    
        // Enkripsi ID buku
        foreach ($get_books as $list) {
            $list->bookid = Crypt::encrypt($list->bookid);
        }
    
        // Cek jika data buku tidak ditemukan
        if ($get_books->isEmpty()) {
            return ResponseFormatterHelper::error(NULL, 'Data buku tidak ditemukan', 404);
        }
    
        // Mengembalikan data buku yang ditemukan
        return ResponseFormatterHelper::success($get_books, 'Data buku berhasil ditemukan');
    }

    public function fetch_all_book(int $category_id) {
        // Mengambil lima buku teratas berdasarkan jumlah transaksi
        $get_books = Book::select(
                        'books.id as bookid',
                        'books.judul as title',
                        'books.no_revisi as deskripsi',
                        'books.category_id as category_id',
                        'books.image_url',
                        \DB::raw('COUNT(transaction_details.id) as transaction_count')
                    )
                    ->leftJoin('transaction_details', 'books.id', '=', 'transaction_details.book_id')
                    ->where('books.category_id', $category_id)
                    ->groupBy('books.id')
                    ->orderBy('transaction_count', 'DESC')
                    ->get();
    
        // Enkripsi ID buku
        foreach ($get_books as $list) {
            $list->bookid = Crypt::encrypt($list->bookid);
            $list->category_id = Crypt::encrypt($list->category_id);
        }
    
        // Cek jika data buku tidak ditemukan
        if ($get_books->isEmpty()) {
            return ResponseFormatterHelper::error(NULL, 'Data buku tidak ditemukan', 404);
        }
    
        // Mengembalikan data buku yang ditemukan
        return ResponseFormatterHelper::success($get_books, 'Data buku berhasil ditemukan');
    }
}
