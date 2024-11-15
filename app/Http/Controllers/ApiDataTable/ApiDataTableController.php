<?php

namespace App\Http\Controllers\ApiDataTable;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Collager\Collager;
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
            'success' => true,
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
            'success' => true,
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
        $data = []; // Pastikan untuk menginisialisasi $data sebagai array

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
            'success' => true,
            'message' => 'Data mahasiswa berhasil diambil.',
            'data' => $data,
        ], 200);
    }

}