<?php

namespace App\Http\Controllers\Loan;

use Endroid\QrCode\QrCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

    public function makeQrCode($text, QrCode $qrCode){
    
        return $qrCode
            ->setText('jhkhjk')
            ->setSize(300)
            ->setPadding(10)
            ->setErrorCorrection('high')
            ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
            ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
            ->setLabel('My label')
            ->setLabelFontSize(16)
            ->render();
    }
}
