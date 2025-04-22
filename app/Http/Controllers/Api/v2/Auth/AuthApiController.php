<?php

namespace App\Http\Controllers\Api\v2\Auth;

use App\Models\User;
use Nette\Utils\Random;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use App\Http\Helpers\ResponseFormatterHelper;

class AuthApiController extends Controller
{
    public function sign_in(Request $request){
        $request->validate([
            'email'    => 'required|string',
            'password' => 'required|string',
        ]);

        $username = $request->email;
        $password = $request->password;

        # check user data by email
        $userData = User::select(
                    'users.id', 
                    'users.email', 
                    'users.name',
                    'users.roleid',
                    'users.password',
                    'c.npm',
                    'l.nidn',
                    's.id as sessid')
                    ->where('users.email', '=', $username)
                    ->orWhere('c.npm', $username)
                    ->orWhere('l.nidn', $username)
                    ->leftjoin('collagers as c', 'users.collagerid', '=', 'c.id')
                    ->leftjoin('lectures as l', 'users.lectureid', '=', 'l.id')
                    ->leftjoin('sessions as s', 'users.id', '=', 's.user_id')
                    ->first();

        if (empty($userData)) {
            return ResponseFormatterHelper::error(NULL, 'Undefined user data', 404);
        }

        $token = Str::random(60);

        # validasi password
        if (!Hash::check($password, $userData->password)){
            return ResponseFormatterHelper::error(NULL, 'Maaf password salah', 403);             
        }

        $data = [
            'userid'          => Crypt::encrypt($userData->id),
            'email'           => $username,
            'username'        => $userData->name,
            'role'            => $userData->roleid,
            'collage_code'    => $userData->npm ?? $userData->nidn,
            'tempat_lahir'    => 'Perawang',
            'tgl_lahir'       => '10 Januari 1999',
            'alamat_domisili' => 'Pekanbaru',
            'token'           => 'Bearer ' . $token
        ];

        return ResponseFormatterHelper::success($data, 'Berhasil mendapatkan data');
    }
}
