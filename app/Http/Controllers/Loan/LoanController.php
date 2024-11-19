<?php

namespace App\Http\Controllers\Loan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index(){
        $data['title'] = 'Data Peminjaman Anda';
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

        return view('pages.loans.index', $data);
    }
}
