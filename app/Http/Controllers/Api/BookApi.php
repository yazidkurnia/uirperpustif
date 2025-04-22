<?php

namespace App\Http\Controllers\Api;

use App\Models\Book\Book;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Http\Helpers\ValidateTokenHelper;
use App\Http\Helpers\ResponseFormatterHelper;

class BookApi extends Controller
{
    /**
     * function digunakan untuk mengambil data buku dengan peminjaman terbanyak
     * dengan limit data 5
     */
    public function get_top_five_book(){
        $headers = apache_request_headers();
        $token   = isset($headers['Authorization']) ? $headers['Authorization'] : null;

        if ($token == NULL) {
            return ResponseFormatterHelper::error(
                NULL,
                'Page Expired',
                409,
                $validator->errors()
            );
        }

        $topFiveBook = Book::getTopBook();

        foreach ($topFiveBook as $list) {
            $list->bookid = Crypt::encryptString($list->bookid);
            $list->category_id = Crypt::encryptString($list->category_id);
            // $list->category_id = $list->category_id;
        }

        return ResponseFormatterHelper::success($topFiveBook, 'Data berhasil diterima');
    }

    public function get_all_book(){
        $headers = apache_request_headers();
        $token   = isset($headers['Authorization']) ? $headers['Authorization'] : null;

        if ($token == NULL) {
            return ResponseFormatterHelper::error(NULL, 'Maaf token tidak ditemukan', 409);
        }

        if (ValidateTokenHelper::validate_token($token) == true) {

            $getAllBook = Book::get_all_book();

            if(empty($getAllBook)){
                return ResponseFormatterHelper::error(NULL, 'Maaf tidak ada data yang ditemukan', 404);
            }

            foreach ($getAllBook as $list) {
                $list->bookid = Crypt::encryptString($list->bookid);
                $list->category_id = Crypt::encryptString($list->category_id);
            }

            return ResponseFormatterHelper::success($getAllBook, 'Berhasil mendapatkan data');
        }
    }

    public function get_books_detail(string $bookId){
        $headers = apache_request_headers();
        $token   = isset($headers['Authorization']) ? $headers['Authorization'] : null;
        $bookId  = Crypt::decryptString($bookId);

        if ($bookId == 0) {
            return ResponseFormatterHelper::error(NULL, 'buku yang anda pilih tidak ditemukan silahkan pilih buku lainya', 404);
        }

        if ($token == NULL) {
            return ResponseFormatterHelper::error(NULL, 'Maaf token tidak ditemukan', 409);
        }

        if (ValidateTokenHelper::validate_token($token) == true) {
            $bookDetail = Book::join('categories as c', 'c.id', '=', 'books.category_id')
                            ->where('books.id', $bookId)
                            ->first();

            if(empty($bookDetail)) {
                return ResponseFormatterHelper::error(NULL, 'Buku yang dipilih tidak ditemukan', 404);
            }

            $data['id'] = Crypt::encryptString($bookDetail->id);
            $data['title'] = $bookDetail->judul;
            $data['no_revisi'] = $bookDetail->no_revisi;
            $data['penulis'] = $bookDetail->penulis;
            $data['tahun_terbit'] = $bookDetail->tahun_terbit;
            $data['penerbit'] = $bookDetail->penerbit;
            $data['img_url'] = $bookDetail->image_url;

            return ResponseFormatterHelper::success($data, 'Data berhasil ditemukan');
        }
    }
}
