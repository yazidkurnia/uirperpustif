<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Lecture\Lecture;
use App\Models\Collager\Collager;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function user_registration(Request $request) {
        // Mulai DB Transaction
        DB::beginTransaction();
    
        try {
            // Cek apakah email sudah pernah digunakan
            if (User::where('email', $request->email)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email has already been taken.',
                ], 400);
            }
    
            // Cek role dan tentukan roleid berdasarkan domain email
            $emailDomain = substr(strrchr($request->email, "@"), 1); 
            $roleid = 0; 
            if ($emailDomain === 'student.uir.ac.id') { 
                $roleid = 3; 
            } elseif ($emailDomain === 'uir.ac.id') { 
                $roleid = 2; 
            }

            $getLectureData   = [];  
            $getCollagerData  = [];  

            $newLectureData = [];
            $newCollagerData = [];
            // Buat record Lecture atau Collager
            if ($roleid == 2) {
                // cek jika nidn telah terdaftar
                $getLectureData = Lecture::where('nidn', $request->usercode)->first();
                if (!empty($getLectureData)) {
                    return response()->json([
                    'success' => false,
                    'message' => 'NIDN telah terdaftar.',
                ], 400);
                }
                $newLectureData = Lecture::create([
                    'nidn' => $request->usercode,
                    'nama' => $request->username,
                    'email' => $request->email
                ]);
            } else {
                // cek jika npm telah terdaftar
                $getCollagerData = Collager::where('npm', $request->usercode)->first();
                if (!empty($getCollagerData)) {
                    return response()->json([
                    'success' => false,
                    'message' => 'NPM telah terdaftar.',
                ], 400);
                }
                $newCollagerData = Collager::create([
                    'npm' => $request->usercode,
                    'nama' => $request->username,
                    'email' => $request->email
                ]);
            }
    
            // Buat user baru
            if(empty($roleid)){
                return response()->json([
                    'success' => false,
                    'message' => 'Role tidak terdaftar.',
                ], 400);
            }

            // dd($roleid);

            $user = User::create([
                'email'      => $request->email,
                'password'   => bcrypt($request->password),
                'name'       => $request->username,
                'roleid'     => $roleid,
                'collagerid' => $newCollagerData->id ?? NULL,
                'lectureid'  => $newLectureData->id ?? NULL
            ]);
    
            // Commit DB Transaction
            DB::commit();
    
            // Response sukses
            return redirect()->route('login');
        } catch (\Exception $e) {
            // Rollback DB Transaction jika terjadi error
            DB::rollBack();
    
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during registration: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    
}
