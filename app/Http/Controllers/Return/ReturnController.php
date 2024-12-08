<?php

namespace App\Http\Controllers\Return;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    public function index(){
        $data['title'] = 'Data Pengembalian';
        $data['id_table'] = 'tbl_peminjaman';
        $data['configHeaderTable'] = [
            'Action',
            'No',
            'Npm',
            'Nama',
            'Tanggal Pinjam',
            'Tanggal Pengembalian',
            'Sisa Waktu Pengembalian',
            'Status'
        ];

        return view('pages.pengembalian.index', $data);
    }
}
