<?php

namespace App\Http\Controllers\Api\v2\Books;

use App\Models\Book\Book;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Http\Helpers\ResponseFormatterHelper;

class BookDetailApiController extends Controller
{
    public function fetch_data_byid(String $book_id){
        $decrypted_id = $book_id == '' ? NULL : is_int(Crypt::decrypt($book_id));

        if ($decrypted_id == NULL || $decrypted_id == 0) {
           return ResponseFormatterHelper::error(NULL, 'Data buku yang dipilih tidak ditemukan', 404);
        }

        # fetch data book detail
        $bookData = Book::select(
            'books.id as bookid',
            'books.judul as title',
            'books.no_revisi as deskripsi',
            'books.category_id',
            'books.tahun_terbit',
            'books.penulis as penulis')
            ->where('books.id', $decrypted_id)->first();

       $bookData->bookid = Crypt::encrypt($bookData->bookid);
       $bookData->category_id = Crypt::encrypt($bookData->category_id);

       return ResponseFormatterHelper::success($bookData, 'Data buku berhasil ditemukan');
    }
}
