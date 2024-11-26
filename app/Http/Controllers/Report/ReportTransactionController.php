<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Transaction\Transaction;

class ReportTransactionController extends Controller
{
    public function index()
    {
        $data['title'] = 'Laporan Peminjaman dan Pengembalian Buku Perpustakaan TI';
        $data['configHeaderTable'] = [];

        // hitung total peminjaman waiting, approved, rejected
        $data['total_peminjaman_pending']  = count(Transaction::where('status_approval', 'Waiting')->where('jenis_transaksi', 'Peminjaman')->get());
        $data['total_peminjaman_approved'] = count(Transaction::where('status_approval', 'Approved')->where('jenis_transaksi', 'Peminjaman')->get());

        $data['total_pengembalian_pending']  = count(Transaction::where('status_approval', 'Waiting')->where('jenis_transaksi', 'Pengembalian')->get());
        $data['total_pengembalian_approved'] = count(Transaction::where('status_approval', 'Approved')->where('jenis_transaksi', 'Pengembalian')->get());
        
        # lakukan perhitunggan untuk mendapatkan data peminjaman buku perbulanannya
        $getAllPeminjaman = Transaction::where('jenis_transaksi', 'Peminjaman')->get();
        $getAllPengembalian = Transaction::where('jenis_transaksi', 'Pengembalian')->get();

        $chartData = [
            'Jan' => 0, 'Feb' => 0, 'Mar' => 0, 'Apr' => 0,
            'May' => 0, 'Jun' => 0, 'Jul' => 0, 'Aug' => 0,
            'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0,
        ];

        $chartDataPengembalian = [
            'Jan' => 0, 'Feb' => 0, 'Mar' => 0, 'Apr' => 0,
            'May' => 0, 'Jun' => 0, 'Jul' => 0, 'Aug' => 0,
            'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0,
        ];
        
        foreach ($getAllPeminjaman as $list) {
            $month = date('M', strtotime($list->tgl_pinjam));
            if (array_key_exists($month, $chartData)) {
                $chartData[$month]++;
            }
        }
        foreach ($getAllPengembalian as $list) {
            $month = date('M', strtotime($list->tgl_wajib_kembali));
            if (array_key_exists($month, $chartDataPengembalian)) {
                $chartDataPengembalian[$month]++;
            }
        }
        // Jika Anda ingin mengubah formatnya menjadi array yang lebih mudah digunakan untuk chart
      // Jika Anda ingin mengubah formatnya menjadi array yang lebih mudah digunakan untuk chart
        $formattedChartData = [
            'labels' => array_keys($chartData), // Nama bulan
            'data' => array_values($chartData), // Jumlah peminjaman per bulan
            'data_pengembalian' => array_values($chartDataPengembalian)
        ];

        // Pastikan tidak ada nilai null dalam data
        foreach ($formattedChartData['data'] as $key => $value) {
            if ($value === null) {
                $formattedChartData['data'][$key] = 0; // Ubah null menjadi 0
            }
        }
        
        $data['formattedChartData'] = $formattedChartData;
        
        return view('pages.report.index', $data);
    }
}
