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

        // periksa apakah user dengan level mahasiswa dari id tersebut sudah memiliki akun
        $checkAccountById = User::where(['collagerid' => $validCollagerId, 'deleted_at' => NULL])->first();
        
        // dd($validCollagerId);
        // Start a transaction
        DB::beginTransaction();

        // periksa apakah email sudah pernah digunakan 
        $checkExistingAccountByEmail = User::where(['email' => $request->email])->first();

        // dd($request->email);

        if (!empty($checkExistingAccountByEmail) && $checkExistingAccountByEmail->email_verified_at == NULL) {
             // Update email_verified_at dengan waktu saat ini
        $checkExistingAccountByEmail->email_verified_at = now(); // Laravel's now() helper
        $checkExistingAccountByEmail->save();
        // Update the email_verified_at field for the user with the specified email
        $updatedRows = DB::table('users')
            ->where(['email'=>$request->email])
            ->update(['email_verified_at' => now()]);

        // dd($updatedRows);
        // Check if any rows were updated
        if ($updatedRows > 0) {
            return response()->json([
                'success' => TRUE,
                'message' => 'Email verification timestamp updated successfully.',
                'data' => [],
            ], 200);
        } else {
            return response()->json([
                'success' => FALSE,
                'message' => 'No changes made. The email may not exist or the timestamp was already set.',
                'data' => [],
            ], 404);
        }

        return response()->json([
            'success' => TRUE,
            'message' => 'Selamat akun telah aktif.',
            'data' => [],
        ], 200);
        }

        // dd($checkExistingAccountByEmail->email_verified_at);

        if($checkExistingAccountByEmail->email_verified_at == NULL){
            $checkExistingAccountByEmail->email_verified_at = date('Y-m-d H:i:s');
            $checkExistingAccountByEmail->save();

            return response()->json([
                'success' => TRUE,
                'message' => 'Selamat akun telah aktif.',
                'data' => [],
            ], 200);
        }
        
        try {
            if (empty($checkAccountById)) {
                // dd($checkAccountById);
                User::create([
                    'name' => $request->nama,
                    'email' => $request->email,
                    'password' => Hash::make('password'),
                    'roleid' => 3,
                    'collagerid' => $validCollagerId,
                    'email_verified_at' => date('Y-m-d H:i:s')
                ]);
                // dd($checkAccountById->email_verified_at);
            }else{
                // dd($checkAccountById);
                if ($checkAccountById->email != $request->email){
                    User::create([
                        'name' => $request->nama,
                        'email' => $request->email,
                        'password' => Hash::make('password'),
                        'roleid' => 3,
                        'collagerid' => $validCollagerId,
                        'email_verified_at' => date('Y-m-d H:i:s')
                    ]);
                }else{
                    $checkAccountById->email_verified_at = date('Y-m-d H:i:s');
                    $checkAccountById->save();
                }
            }

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

    public function delete_user(Request $request)
    {
        $validIdFromRoleLevel = $request->id == '' ? NULL : (is_int((int)Crypt::decryptString($request->id)) ? (int)Crypt::decryptString($request->id) : NULL);
        
        // Validasi ID pengguna
        if ($validIdFromRoleLevel == NULL) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid.',
                'data' => [],
            ], 400);
        }

        $user = [];
        if ($request->role == 'mahasiswa') {
            # code...
            // Cari pengguna berdasarkan ID
            $user = User::where(['collagerid' => $validIdFromRoleLevel, 'deleted_at' => NULL])->first();
        }
        
        if ($request->role == 'dosen'){
            $user = User::where(['lectureid' => $validIdFromRoleLevel, 'deleted_at' => NULL])->first();
        }
        
        // Jika pengguna tidak ditemukan
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna tidak ditemukan.',
                'data' => [],
            ], 404);
        }

        // Lakukan soft delete
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengguna berhasil dihapus.',
            'data' => [],
        ], 200);
    }
}
