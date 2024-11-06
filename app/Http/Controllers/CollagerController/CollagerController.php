<?php

namespace App\Http\Controllers\CollagerController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CollagerController extends Controller
{
    public function index(){
        # table configuration
        $data['configHeaderTable'] = [
            'Action',
            'No',
            'Npm',
            'Nama'
        ];
        $data['title'] = 'Setting akun mahasiswa';
        $data['id_table'] = 'tbl_collager';
        return view('pages.collagers.index', $data);
    }
}
