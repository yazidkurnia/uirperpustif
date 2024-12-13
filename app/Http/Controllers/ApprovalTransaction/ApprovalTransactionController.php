<?php

namespace App\Http\Controllers\ApprovalTransaction;

use App\Models\Book\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\BookStock\BookStock;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Models\Transaction\Transaction;
use App\Models\TransactionDetail\TransactionDetail;

class ApprovalTransactionController extends Controller
{
    public function index(){
        # table configuration
        $data['configHeaderTable'] = [
            'No',
            'Action',
            'Nama Peminjam',
            'Tanggal Pinjam',
            'Tanggal Pengembalian',
            'Status Persetujuan'
        ];

        $data['id_table'] = 'tbl_peminjaman_approve';

        $data['title'] = 'Approval Peminjaman';
        return view('pages.approval_peminjaman.index', $data);
    }

    public function approve_transaksi_peminjaman(Request $request) {
        // Validasi dan dekripsi ID transaksi
        $validIdTransaksi = $request->id != '' ? is_int((int)Crypt::decryptString($request->id)) ? (int)Crypt::decryptString($request->id) != 0 ? (int)Crypt::decryptString($request->id) : NULL : NULL : NULL;
        $statusApproval   = '';

        if ($request->status_approval == 'Approved'){
            $statusApproval = $request->status_approval;
        }else if ($request->status_approval == 'Reject'){
            $statusApproval = $request->status_approval;
        }else {
            $statusApproval = NULL;
        }

        if ($statusApproval == NULL) {
            return response()->json([
                'success' => FALSE,
                'message' => 'Gagal, status approval yang dipilih tidak valid',
                'data'    => [],
            ], 500);
        }

        if ($validIdTransaksi == NULL) {
            return response()->json([
                'success' => FALSE,
                'message' => 'Gagal, terjadi kesalahan pada data transaksi',
                'data'    => [],
            ], 500);
        }
    
        // Ambil data transaksi berdasarkan ID yang dikirim
        $getDataTransaksi = Transaction::find($validIdTransaksi);
    
        // Ambil data detail transaksi berdasarkan ID transaksi
        $getDetailTransaksi = TransactionDetail::where('transaction_id', $getDataTransaksi->id)->get();
        // Mengambil sekumpulan book_id yang unik
        $bookIds = $getDetailTransaksi->pluck('book_id')->unique();

        $bookCategory = Book::whereIn('id', $bookIds)->get();

        $categoryIds = $bookCategory->pluck('category_id')->unique();
        // Mengambil data dari tabel book_stocks berdasarkan sekumpulan book_id
        $bookStocks = BookStock::whereIn('category_id', $categoryIds)->get();
        // cek apakah data transaksi telah di approve sebelumnya
        if($getDataTransaksi->status_approval == 'Approved' && $getDataTransaksi->status_return == 'Approved'){
            return response()->json([
                'success' => FALSE,
                'message' => 'Gagal, data transaksi yang dipilih tidak dapat diapprove karena telah diapprove sebelumnya',
                'data'    => [],
            ], 500);
        }
    
        // Mulai transaksi database
        DB::beginTransaction();
    
        try {

            if ($request->status_approval == 'Reject'){
                $getDataTransaksi->status_approval = $request->status_approval;
                $getDataTransaksi->save();

                return response()->json([
                    'success' => TRUE,
                    'message' => 'Permintaan peminjaman berhasil ditolak',
                    'data'    => [],
                ], 200);
                die;
            }

            // dd($getDataTransaksi);
            if($getDataTransaksi->status_approval == 'Approved' && $getDataTransaksi->status_return == 'Waiting'){
                $getDataTransaksi->status_approval = $request->status_approval;
                $getDataTransaksi->status_return = 'Approved';
            }else{
                $getDataTransaksi->status_approval = $request->status_approval;
                $getDataTransaksi->status_return = 'Waiting';
            }

            $getDataTransaksi->save();

            // Update stock untuk setiap book_stock
            foreach ($bookStocks as $bookStock) {
                $i = 0;
                // Misalnya, kita ingin mengurangi total stock berdasarkan jumlah yang dipinjam
                // Anda perlu menyesuaikan logika ini sesuai dengan kebutuhan Anda
                $jumlahDipinjam = count($getDetailTransaksi->where('book_id', $bookIds[$i])); // Asumsi ada kolom 'jumlah' di TransactionDetail
                var_dump($bookIds[$i]);
                // var_dump($getDetailTransaksi);
                var_dump($bookStock->book_id);
                // Update total stock
                if($getDataTransaksi->status_approval == 'Approved' && $getDataTransaksi->status_return == 'Approved'){
                $bookStock->total += $jumlahDipinjam;
                } else {
                    $bookStock->total -= $jumlahDipinjam;
                }
                // dd($bookStock->total);
                // Simpan perubahan
                $bookStock->save();
                $i++;
            }
    // die;
            // Commit transaksi jika semua operasi berhasil
            DB::commit();
    
            return response()->json([
                'success' => TRUE,
                'message' => 'Peminjaman telah diterima dan stok telah diperbarui',
                'data'    => [],
            ], 200);
            
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();
    
            return response()->json([
                'success' => FALSE,
                'message' => 'Gagal memperbarui stok: ' . $e->getMessage(),
                'data'    => [],
            ], 500);
        }
    }
}
