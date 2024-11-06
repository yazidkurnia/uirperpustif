<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class UsersController extends Controller
{
    public function index(){

        # table configuration
        $data['configHeaderTable'] = [
            'Action',
            'No',
            'Nama',
            'Email',
            'Tanggal Email Terverifikasi'
        ];
        $data['title'] = 'Setting Account';
        $data['id_table'] = 'table_user';
    
        return view('pages.users.index', $data);
    }

    /**
     * function documentation
     *
     * @param Request $request
     * @return void
     */
    public function user_update_role(Request $request){
        $validUserId = $request->user_id == '' ? NULL : (is_int((int)Crypt::decryptString($request->user_id)) ? (int)Crypt::decryptString($request->user_id) : NULL);
        
        // put unhappy path first
        if ($validUserId == NULL) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid.',
                'data' => [],
            ], 400);
        }

        $userById = User::find($validUserId);
        $userById->roleid = 1;
        $userById->save();
    }

    public function set_account(Request $request) {
        $validCollagerId = $request->id != '' ? is_int((int)Crypt::decryptString($request->id)) == TRUE ? (int)Crypt::decryptString($request->id) != 0 ? (int)Crypt::decryptString($request->id) : NULL : NULL : NULL;
        
        if ($validCollagerId == NULL) {
            return response()->json([
                'success' => FALSE,
                'message' => 'Terjadi kesalahan yang disebabkan oleh data mahasiswa.',
                'data' => [],
            ], 500);
        }
    
        // Start a transaction
        DB::beginTransaction();
    
        try {
            User::create([
                'name' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make('password'),
                'roleid' => 3,
                'collagerid' => $validCollagerId,
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
