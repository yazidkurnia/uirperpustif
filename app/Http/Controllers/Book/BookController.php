<?php

namespace App\Http\Controllers\Book;

use App\Models\Book\Book;
use Illuminate\Http\Request;
use App\Models\Category\Category;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

class BookController extends Controller
{
    public function index(){
        $dataCategory = Category::get();

        $data['data_kategori'] = $dataCategory;
        $data['title'] = 'Data Buku';
        $data['id_table'] = 'tbl_buku';
        $data['configHeaderTable'] = [
            'Action',
            'No',
            'Judul Buku',
            'Nama Penulis',
            'Kategori',
            'Penerbit',
            'Tahun terbit'
        ];
        // dd($dataCategory);

        return view('pages.buku.index', $data);
    }

    public function store(Request $request) {
        Book::create([
            'judul' => $request->judul_buku,
            'penulis' => $request->nama_penulis,
            'category_id' => $request->kategori,
            'penerbit' => $request->penerbit,
            'tahun_terbit' => $request->tahun_terbit,
            'no_revisi' => $request->no_revisi
        ]);

        return response()->json([
            'success' => TRUE,
            'message' => 'Berhasil menyimpan data yang baru ditambahkan.',
            'data' => NULL,
        ], 200);
    }

    public function update(Request $request){
        // dd($request);
        $id = $request->id;

        $validId = $id != '' ? (int)Crypt::decryptString($id) ? (int)Crypt::decryptString($id) != 0 ? (int)Crypt::decryptString($id) : NULL : NULL : NULL;
        $judulBuku = $request->judul;
        $penulis = $request->penulis;
        $penerbit = $request->penerbit;
        $tahun_terbit = $request->tahun_terbit;
        $kategori = $request->kategori;
        $no_revisi = $request->no_revisi;
        // dd($request);
        
        # ambil data buku berdasarkan id yang dipilih
        $dataBookById = Book::find($validId);

        if (empty($dataBookById)) {
            return response()->json([
                'success' => FALSE,
                'message' => 'Data yang akan dirubah tidak ditemukan.',
                'data' => NULL,
            ], 404);
        }

        $dataBookById->judul = $judulBuku;
        $dataBookById->penulis = $penulis;
        $dataBookById->penerbit = $penerbit;
        $dataBookById->tahun_terbit = $tahun_terbit;
        $dataBookById->category_id = $kategori;
        $dataBookById->no_revisi = $no_revisi;
        $dataBookById->save();

        if($validId == NULL) {
            return response()->json([
                'success' => FALSE,
                'message' => 'Data tidak ditemukan.',
                'data' => NULL,
            ], 404);
        }
        
        return response()->json([
            'success' => TRUE,
            'message' => 'Berhasil merubah data yang baru ditambahkan.',
            'data' => NULL,
        ], 200);
    }

    public function destroy(Request $request) {
        $id = $request->id;
    
        // Decrypt and validate the ID
        $validId = $id != '' ? (int)Crypt::decryptString($id) ? (int)Crypt::decryptString($id) != 0 ? (int)Crypt::decryptString($id) : NULL : NULL : NULL;
    
        // If the ID is not valid, return a 404 response
        if ($validId == NULL) {
            return response()->json([
                'success' => FALSE,
                'message' => 'Data yang akan dirubah tidak ditemukan.',
                'data' => NULL,
            ], 404);
        }
    
        // Begin DB transaction
        // DB::beginTransaction();
        
        try {
            // Find the book by ID
            $dataBookById = Book::find($validId);
    
            // If the book is not found, return a 404 response
            if (empty($dataBookById)) {
                return response()->json([
                    'success' => FALSE,
                    'message' => 'Data yang akan dihapus tidak ditemukan.',
                    'data' => NULL,
                ], 404);
            }
    
            // Delete the book
            $dataBookById->delete();
    
            // Commit the transaction
            DB::commit();
    
            // Return a success response
            return response()->json([
                'success' => TRUE,
                'message' => 'Data berhasil dihapus.',
                'data' => NULL,
            ], 200);
    
        } catch (\Exception $e) {
            // Rollback the transaction if an error occurs
            DB::rollBack();
    
            // Return an error response
            return response()->json([
                'success' => FALSE,
                'message' => 'Terjadi kesalahan saat menghapus data.',
                'data' => NULL,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
