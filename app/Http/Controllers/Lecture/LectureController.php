<?php

namespace App\Http\Controllers\Lecture;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LectureController extends Controller
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
        $data['title']              = 'Setting Account Dosen';
        return view('pages.lectures.index', $data);
    }
}
