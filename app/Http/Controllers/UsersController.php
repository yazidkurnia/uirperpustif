<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
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
}
