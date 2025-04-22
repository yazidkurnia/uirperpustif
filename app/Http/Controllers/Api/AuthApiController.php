<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use \Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use App\Http\Helpers\ResponseFormatterHelper;
use Illuminate\Support\Facades\Hash; // Pastikan untuk mengimpor Hash

class AuthApiController extends Controller
{
    /**
     * Validate user credentials.
     *
     * @param string|null $username
     * @param string|null $password
     * @return \Illuminate\Http\JsonResponse
     */
    private function validate_credential(?string $username, ?string $password)
    {
        if (is_null($username)) {
            return ResponseFormatterHelper::error(
                null,
                'Username tidak boleh kosong',
                400
            );
        }

        if (is_null($password)) {
            return ResponseFormatterHelper::error(
                null,
                'Password tidak boleh kosong',
                400
            );
        }

        // Mencari user berdasarkan email
        $dataUser  = User::where('email', $username)->first();

        // Jika user tidak ditemukan atau password tidak cocok
        if (empty($dataUser)) {
            return ResponseFormatterHelper::error(
                null,
                'Data user tidak ditemukan',
                404
            );
        }

        if (!Hash::check($password, $dataUser->password)) {
            return ResponseFormatterHelper::error(
                null,
                'Password salah',
                400
            );
        }

        return $dataUser ; // Kembalikan data user jika valid
    }

    /**
     * Sign in the user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sign_in(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return ResponseFormatterHelper::error(
                null,
                'Validation Error',
                400,
                $validator->errors()
            );
        }

        $username = $request->username;
        $password = $request->password;

        // Validasi kredensial
        $validationResponse = $this->validate_credential($username, $password);
        if ($validationResponse instanceof JsonResponse) {
            return $validationResponse; // Kembalikan response jika ada error
        }

        // Jika autentikasi berhasil, ambil data user
        $dataUser   = $validationResponse;

        // Generate token
        $token = $dataUser ->createToken('uirperpustif')->accessToken;

        // Siapkan data untuk response
        $data = [
            'email' => $dataUser ->email,
            'userid' => Crypt::encrypt($dataUser ->id),
            'username' => $dataUser ->username,
            'role' => $dataUser ->roleid,
            'collage_code' => '68879879875',
            'tempat_lahir' => 'Perawang',
            'tgl_lahir' => '1999-01-25',
            'token' => 'Bearer '.$token->token // Hanya mengembalikan token sebagai string
        ];

        return ResponseFormatterHelper::success($data, 'Data user berhasil didapatkan');
    }
}