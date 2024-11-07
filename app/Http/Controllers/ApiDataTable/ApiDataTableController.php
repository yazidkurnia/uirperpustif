<?php

namespace App\Http\Controllers\ApiDataTable;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Collager\Collager;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

class ApiDataTableController extends Controller
{
    /**
     * Mengambil data pengguna yang tidak dihapus dan mengembalikannya dalam format JSON.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function api_datatable_users()
    {
        // Mengambil data pengguna yang tidak dihapus
        $userData = User::where('deleted_at', NULL)->get();
    
        // Meng-encrypt id dan employeid untuk setiap pengguna
        $data = [];
        foreach ($userData as $user) {
            $data[] = [
                'id' => Crypt::encryptString($user->id),
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
            ];
        }
    
        // Mengembalikan data pengguna dalam format JSON
        return response()->json([
            'success' => true,
            'message' => 'Data pengguna berhasil diambil.',
            'data' => $data,
        ], 200);
    }

    public function api_datatable_collager(){
        $collagerData = Collager::get();
        $data = [];
        foreach ($collagerData as $list) {
            $data[] = [
                'id' => Crypt::encryptString($list->id),
                'npm' => $list->npm,
                'nama' => $list->nama,
                'email' => $list->email,
                'role_name' => 'mahasiswa'
            ];
        }
        // Mengembalikan data pengguna dalam format JSON
        return response()->json([
            'success' => true,
            'message' => 'Data mahasiswa berhasil diambil.',
            'data' => $data,
        ], 200);
    }
}