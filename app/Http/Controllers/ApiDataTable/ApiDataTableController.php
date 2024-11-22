<?php

namespace App\Http\Controllers\ApiDataTable;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Collager\Collager;
use App\Models\BookStock\BookStock;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\Transaction\Transaction;

class ApiDataTableController extends Controller
{
    /**
     * Mengambil data pengguna yang tidak dihapus dan mengembalikannya dalam format JSON.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function api_datatable_users()
    {
        // Mengambil data pengguna yang tidak dihapus
        $userData = User::where('deleted_at', NULL)->get();
    
        // Meng-encrypt id dan employeid untuk setiap pengguna
        $data = [];
        foreach ($userData as $user) {
            $data[] = [
                'id' => Crypt::encryptString($user->id),
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
            ];
        }
    
        // Mengembalikan data pengguna dalam format JSON
        return response()->json([
            'success' => TRUE,
            'message' => 'Data pengguna berhasil diambil.',
            'data' => $data,
        ], 200);
    }

    public function api_datatable_collager(){
        $collagerData = Collager::get();
        $data = [];
        foreach ($collagerData as $list) {
            $data[] = [
                'id' => Crypt::encryptString($list->id),
                'npm' => $list->npm,
                'nama' => $list->nama,
                'email' => $list->email,
                'role_name' => 'mahasiswa'
            ];
        }
        // Mengembalikan data pengguna dalam format JSON
        return response()->json([
            'success' => TRUE,
            'message' => 'Data mahasiswa berhasil diambil.',
            'data' => $data,
        ], 200);
    }

    public function api_datatable_approve_peminjaman(){
        $approvalPeminjaman = Transaction::select('transactions.id as transid', 'users.name', 'transactions.tgl_pinjam', 'transactions.tgl_wajib_kembali', 'transactions.status_approval')->where('userid', Auth::user()->id)
        ->join('users', 'users.id' , '=', 'transactions.userid')
        ->where(['jenis_transaksi' => 'Peminjaman', 'status_approval' =>  'Waiting'])
        ->get();
        
        $data = [];

        foreach ($approvalPeminjaman as $list) {
            // Tentukan status approval berdasarkan kondisi
            if ($list->status_approval == 'Waiting') {
                $statusApproval = '<td><span class="badge rounded-pill bg-warning">'.$list->status_approval.'</span></td>';
            } else if ($list->status_approval == 'Approved') {
                $statusApproval = '<td><span class="badge rounded-pill bg-primary">'.$list->status_approval.'</span></td>';
            } else {
                $statusApproval = '<td><span class="badge rounded-pill bg-secondary">'.$list->status_approval.'</span></td>';
            }
        
            // Tambahkan data ke dalam array $data
            $data[] = [
                'id' => Crypt::encryptString($list->transid),
                'name' => $list->name,
                'tgl_pinjam' => $list->tgl_pinjam,
                'tgl_pengembalian' => $list->tgl_wajib_kembali,
                'status_approval' => $statusApproval, // Tambahkan status approval ke dalam array
            ];
        }

         // Mengembalikan data pengguna dalam format JSON
         return response()->json([
            'success' => TRUE,
            'message' => 'Data mahasiswa berhasil diambil.',
            'data' => $data,
        ], 200);
    }

    public function api_datatable_users_book(){
        $dataBukuPinjam = [];

        if (Auth::user()->roleid == 3) {
            $dataBukuPinjam = Transaction::select('transactions.id', 'users.name as nama', 'collagers.npm as unique_code', 'transactions.tgl_pinjam', 'transactions.tgl_wajib_kembali', 'transactions.status_approval')
            ->join('users', 'users.id', '=', 'transactions.userid')
            ->join('collagers', 'collagers.id', '=', 'users.collagerid')
            ->where('userid', Auth::user()->id)
            ->get();
        }

        if (Auth::user()->roleid == 2) {
            $dataBukuPinjam = Transaction::select('transactions.id', 'users.name as nama', 'lectures.nidn as unique_code', 'transactions.tgl_pinjam', 'transactions.tgl_wajib_kembali', 'transactions.status_approval')->join('users', 'users.id', '=', 'transactions.userid')
            ->join('lectures', 'lectures.id', '=', 'users.lectureid')
            ->where('userid', Auth::user()->id)
            ->get();
        }

        $data = [];

        foreach ($dataBukuPinjam as $list) {

            $dateTglPinjam = date_create($list->tgl_pinjam);
            $tglKembali    = date_create($list->tgl_wajib_kembali);
            $statusApproval = '';

            if ($list->status_approval == 'Waiting'){
                $statusApproval = '<span class="badge bg-label-warning">'.$list->status_approval.'</span>';
            }

            if ($list->status_approval == 'Reject'){
                $statusApproval = '<span class="badge bg-label-danger">'.$list->status_approval.'</span>';
            }

            if ($list->status_approval == 'Approved'){
                $statusApproval = '<span class="badge bg-label-primary">'.$list->status_approval.'</span>';
            }

            $tenggatPengembalian = date_diff($dateTglPinjam, $tglKembali);
            $data[] = [
                'action' => '<td><div class="btn-group">' .
                            '<button type="button" class="btn btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><box-icon name="cog" color="#ffffff"></box-icon></button>' .
                            '<ul class="dropdown-menu dropdown-menu-start" style="">' .
                            '<li>' .
                            '<button type="button" class="btn btn-white" data-bs-toggle="modal" data-bs-target="#basicModal" onclick="cancel_peminjaman(\'' .
                            Crypt::encryptString($list->id) .'\')">Cancel</button>' . '</li>' .
                            // Hapus parameter 'mahasiswa'
                            '</ul>' .
                            '</div>' .
                            '</td>',
                'npm' => $list->unique_code,
                'nama'=> $list->nama,
                'tgl_pinjam' => $list->tgl_pinjam,
                'tgl_wajib_kembali' => $list->tgl_wajib_kembali,
                'tenggat' => $tenggatPengembalian->format("%R%a days"),
                'status_approval' => $statusApproval
            ];
        }

        return response()->json([
            'success' => TRUE,
            'message' => 'Data mahasiswa berhasil diambil.',
            'data' => $data,
        ], 200);
    }

    public function api_datatable_book_stock(){
        $dataBookStock = BookStock::get();

        $data = [];
        foreach ($dataBookStock as $list) {
            $data[]=[
                'id' => Crypt::encryptString($list->id),
                'kategori' => $list->nama_kategori,
                'sisa_stok' => $list->total
            ];
        }    
        
        return response()->json([
            'success' => TRUE,
            'message' => 'Data mahasiswa berhasil diambil.',
            'data' => $data,
        ], 200);
    }

}