<?php

namespace App\Http\Controllers\Category;

use Illuminate\Http\Request;
use App\Models\Category\Category;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

class CategoryController extends Controller
{
    public function index(){
        $data['title'] = 'Data Category';
        $data['id_table'] = 'tbl_peminjaman';
        $data['configHeaderTable'] = [
            'Action',
            'No',
            'Nama Kategori',
        ];
        return view('pages.category.index', $data);
    }

    public function store(Request $request){
        // lakukan pengecekan apakah nama kategori yang ditambahkan sudah ada
        $getCategoryData = Category::where('category_name', $request->namaKategori)->first();
        
        if (!empty($getCategoryData)) {
            return response()->json([
                'success' => FALSE,
                'message' => 'Maaf tidak berhasil melakukan pengambilan data kategori silahkan periksa kembali data yang anda inputkan.',
                'data' => NULL,
            ], 500);
        }

        Category::create([
            'category_name' => $request->namaKategori
        ]);

        return response()->json([
            'success' => TRUE,
            'message' => 'Berhasil menyimpan data yang baru ditambahkan.',
            'data' => NULL,
        ], 200);
    }

    public function update(Request $request){
        $id = $request->id;

        $validId = $id != '' ? (int)Crypt::decryptString($id) ? (int)Crypt::decryptString($id) != 0 ? (int)Crypt::decryptString($id) : NULL : NULL : NULL;
        $namaKategori = $request->nama_kategori;
        
        # ambil data category berdasarkan id yang dipilih
        $dataCategoryById = Category::find($validId);

        if (empty($dataCategoryById)) {
            return response()->json([
                'success' => FALSE,
                'message' => 'Data yang akan dirubah tidak ditemukan.',
                'data' => NULL,
            ], 404);
        }

        $dataCategoryById->category_name = $namaKategori;
        $dataCategoryById->save();

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
        DB::beginTransaction();
        
        try {
            // Find the category by ID
            $dataCategoryById = Category::find($validId);
    
            // If the category is not found, return a 404 response
            if (empty($dataCategoryById)) {
                return response()->json([
                    'success' => FALSE,
                    'message' => 'Data yang akan dihapus tidak ditemukan.',
                    'data' => NULL,
                ], 404);
            }
    
            // Delete the category
            $dataCategoryById->delete();
    
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
