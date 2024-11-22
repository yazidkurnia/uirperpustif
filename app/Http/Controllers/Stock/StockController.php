<?php

namespace App\Http\Controllers\Stock;

use Illuminate\Http\Request;
use App\Models\BookStock\BookStock;
use App\Http\Controllers\Controller;

class StockController extends Controller
{
    public function index(){
        $data['title'] = 'Data Stok Buku';
        # config header table
        $data['configHeaderTable'] = [
            'Action',
            'No',
            'Kategori',
            'Total Ketersediaan'
        ];

        // config table id
        $data['id_table'] = 'tbl_stok';

        return view('pages.stocks.index', $data);
    }
}
