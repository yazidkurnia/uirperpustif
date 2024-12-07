<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Lecture\Lecture;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class AdminController extends Controller
{
    public function index()
    {
        $data['id_table']           = 'tbl_set_admin';
        $data['configHeaderTable']  = [
            'Setting',
            'No',
            'NIDN',
            'Nama Akun Pengguna',
        ];
        $data['title']              = 'Setting Account Admin';
        return View('pages.setting_adm_account.index', $data);
    }

    public function update_role_to_admin(Request $request){
        $lectureId         = $request->id;
        $validateLectureId = $lectureId != '' ? (int)Crypt::decryptString(str_replace(' ', '', $lectureId)) : NULL;
        
        if ($validateLectureId == NULL || $validateLectureId == 0) {
            return response()->json([
                'success' => FALSE,
                'message' => 'Data category berhasil didapatkan.',
                'data' => $data,
            ], 500);
        }

        $findLectureById     = Lecture::find($validateLectureId);
        $checkIsAccountExist = User::where('lectureid', $findLectureById->id)->first();
        // dd($checkIsAccountExist);
        if(empty($checkIsAccountExist)){
            # create an account
            User::create([
                'name' => $findLectureById->nama,
                'email' => $findLectureById->email,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'password' => Hash::make(date('Y-m-d')),
                'roleid' => 1,
                'lectureid' => $validateLectureId
            ]);
        }else{

            if ($checkIsAccountExist->roleid == 1) {
                return response()->json([
                    'success' => FALSE,
                    'message' => 'akun telah memiliki akses admin, nothing to set.',
                    'data' => NULL,
                ], 500);
            }
            # if role is not admin change to admin
            $checkIsAccountExist->roleid = 1;
            $checkIsAccountExist->save();
        }

        return response()->json([
            'success' => TRUE,
            'message' => 'akun telah diatur sebagai admin.',
            'data' => NULL,
        ], 200);

    }
}
