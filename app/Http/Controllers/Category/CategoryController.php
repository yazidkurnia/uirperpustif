<?php

namespace App\Http\Controllers\Category;

use Illuminate\Http\Request;
use App\Models\Category\Category;
use App\Http\Controllers\Controller;

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
        $getCategoryData = Category::where('category_name', $request->namaKategori)->get();
        
        if (!empty($getCategoryData)) {
            return response()->json([
                'success' => FALSE,
                'message' => 'Maaf ;tidak berhasil melakukan pengambilan data kategori silahkan periksa kembali data yang anda inputkan.',
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
}
