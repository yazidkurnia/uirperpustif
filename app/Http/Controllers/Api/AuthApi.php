<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Lecture\Lecture;
use App\Models\Collager\Collager;
use \Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use App\Http\Helpers\ResponseFormatterHelper;
use Illuminate\Support\Facades\Hash; // Pastikan untuk mengimpor Hash

class AuthApi extends Controller
{
        /**
     * Validate user credentials.
     *
     * @param string|NULL $username
     * @param string|NULL $password
     * @return \Illuminate\Http\JsonResponse
     */
    private function validate_credential(?string $username, ?string $password)
    {
        if (is_NULL($username)) {
            return ResponseFormatterHelper::error(
                NULL,
                'Username tidak boleh kosong',
                400
            );
        }

        if (is_NULL($password)) {
            return ResponseFormatterHelper::error(
                NULL,
                'Password tidak boleh kosong',
                400
            );
        }

        // Mencari user berdasarkan email
        $dataUser  = User::where('email', $username)->first();

        // Jika user tidak ditemukan atau password tidak cocok
        if (empty($dataUser)) {
            return ResponseFormatterHelper::error(
                NULL,
                'Data user tidak ditemukan',
                404
            );
        }

        if (!Hash::check($password, $dataUser->password)) {
            return ResponseFormatterHelper::error(
                NULL,
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
                NULL,
                'Validation Error',
                400,
            );
        }

        $username = $request->username;
        $password = $request->password;

        $validationResponse = $this->validate_credential($username, $password);
        if ($validationResponse instanceof JsonResponse) {
            return $validationResponse; // Kembalikan response jika ada error
        }

        $dataUser   = $validationResponse;
        $token = $dataUser ->createToken('uirperpustif')->accessToken;

        $data = [
            'email' => $dataUser ->email,
            'userid' => Crypt::encryptString($this->validate_credential($username, $password)->id),
            'username' => $dataUser ->username,
            'role' => Crypt::encryptString($dataUser->roleid),
            'collage_code' => '68879879875',
            'tempat_lahir' => 'Perawang',
            'tgl_lahir' => '1999-01-25',
            'token' => 'Bearer '.$token->token // Hanya mengembalikan token sebagai string
        ];

        return ResponseFormatterHelper::success($data, 'Data user berhasil didapatkan');
    }

    public function sign_up(Request $request){
        $username = $request->username;
        $email    = $request->email;    
        $password = $request->password;
        $cuc      = $request->cuc; # collage unique code like npm or nidn

        $insertedDataLevel = ['nama' => $username, 'email' => $email];
        // check email
        $data = [];
        $roleid = NULL;

        // DB::beginTransaction();
        // try {
            if (str_contains($email ,'@student.uir.ac.id')) {
                $data['roleid'] = 3;
                $insertedDataLevel['npm'] = $cuc;
                $roleid = 3;
            }else if(str_contains($email ,'@uir.ac.id')){
                $data['roleid'] = 2;
                $insertedDataLevel['nidn'] = $cuc;
                $roleid = 2;
            } else{
                $errorData = [
                    'code' => 400,
                    'message' => 'Email yang digunakan tidak valid',
                    'status' => 'failed'
                ];
    
                return ResponseFormatterHelper::error($errorData, 'Email yang anda gunakan tidak memenuhi standar', 400);
            }
    
            # buat data collager jika user merupakan mahasiswa
            $latestCollager = NULL;
            if ($roleid == 2) {
                $latestCollager = Collager::created($insertedDataLevel);
            }
            # buat data lecture jika user merupakan dosen
            $latestLecture = NULL;
            if ($roleid == 3) {
                $latestLecture = Lecture::created($insertedDataLevel);
            }

            # buat akun user
            $dataNewUser = [
                'name' => $username,
                'email' => $email,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'password' => Hash::make($password),
                'roleid' => $roleid,
                'remember_token' => Str::random(12)
            ];

            if ($latestCollager != NULL) {
                $dataNewUser['collagerid'] = $latestCollager->id;
            }

            if ($latestLecture != NULL) {
                $dataNewUser['lectureid'] = $latestLecture->id;
            }

            // var_dump('mahasiswa: ' . $latestCollager . ' lecture: ' . $latestLecture);
            // die;

            if ($latestCollager == NULL && $latestLecture == NULL) {
                $errorData = [
                    'data_mahasiswa_baru' => $latestCollager,
                    'data_dosen_baru' => $latestLecture
                ];
                return ResponseFormatterHelper::error($errorData, 'Periksa kembali email anda', 400);
            }

            User::created($dataNewUser);

            return  ResponseFormatterHelper::success($dataNewUser, 'Berhasil membuat akun');

            // DB::commit();
        // } catch (\Throwable $th) {
            $errorData = ['error' => $th];
            // DB::rollback();
            // return ResponseFormatterHelper::error($th, 'Maaf terjadi kesalahan dalam pembuatan akun, silahkan coba lagi, jika permasalahan masih berulang silahkan hubungi contact support');
        // }

        return ResponseFormatterHelper::success($insertedDataLevel, 'Berhasil menambahkan data');
    }
}
