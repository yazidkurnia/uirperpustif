<?php

namespace App\Http\Controllers\ApiDataTable;

use App\Models\User;
use Illuminate\Http\Request;
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
}