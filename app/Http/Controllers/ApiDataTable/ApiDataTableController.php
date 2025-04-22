<?php

namespace App\Http\Controllers\ApiDataTable;

use App\Models\User;
use App\Models\Book\Book;
use Illuminate\Http\Request;
use App\Models\Lecture\Lecture;
use App\Models\Category\Category;
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
        $approvalPeminjaman = [];
        if (Auth::user()->roleid == 1) {
            # code...
            $approvalPeminjaman = Transaction::select('transactions.id as transid', 'users.name', 'transactions.tgl_pinjam', 'transactions.tgl_wajib_kembali', 'transactions.status_approval')
            ->join('users', 'users.id' , '=', 'transactions.userid')
            ->where(['jenis_transaksi' => 'Peminjaman', 'status_approval' =>  'Waiting'])
            ->get();
        }else{
            $approvalPeminjaman = Transaction::select('transactions.id as transid', 'users.name', 'transactions.tgl_pinjam', 'transactions.tgl_wajib_kembali', 'transactions.status_approval')->where('userid', Auth::user()->id)
            ->join('users', 'users.id' , '=', 'transactions.userid')
            ->where(['jenis_transaksi' => 'Peminjaman', 'status_approval' =>  'Waiting'])
            ->get();
        }
        
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
            $dataBukuPinjam = Transaction::select('transactions.id', 'users.name as nama', 'collagers.npm as unique_code', 'transactions.tgl_pinjam', 'transactions.tgl_wajib_kembali', 'transactions.status_approval', 'transactions.qr_url', 'transactions.reject_note')
            ->join('users', 'users.id', '=', 'transactions.userid')
            ->join('collagers', 'collagers.id', '=', 'users.collagerid')
            ->where('userid', Auth::user()->id)
            ->get();
        }

        if (Auth::user()->roleid == 2) {
            $dataBukuPinjam = Transaction::select('transactions.id', 'users.name as nama', 'lectures.nidn as unique_code', 'transactions.tgl_pinjam', 'transactions.tgl_wajib_kembali', 'transactions.status_approval', 'transactions.qr_url', 'transactions.reject_note')->join('users', 'users.id', '=', 'transactions.userid')
            ->join('lectures', 'lectures.id', '=', 'users.lectureid')
            ->where('userid', Auth::user()->id)
            ->get();
        }

        if (Auth::user()->roleid == 1) {
            $dataBukuPinjam = Transaction::select('transactions.id', 'users.name as nama', 'transactions.tgl_pinjam', 'transactions.tgl_wajib_kembali', 'transactions.status_approval', 'transactions.qr_url', 'transactions.reject_note')
            ->join('users', 'users.id', '=', 'transactions.userid')
            ->where('users.roleid', Auth::user()->roleid)
            ->get();
        }

        $data = [];
        foreach ($dataBukuPinjam as $list) {
            // var_dump($list->qr_url);
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
            if (Auth::user()->roleid == 1) {
                $data[] = [
                    'action' => '<td><div class="btn-group">' .
                                '<button type="button" class="btn btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><box-icon name="cog" color="#ffffff"></box-icon></button>' .
                                '<ul class="dropdown-menu dropdown-menu-start" style="">' .
                                '<li>' .
                                '<button type="button" class="btn btn-white btn-sm" data-bs-toggle="modal" data-bs-target="#basicModal" onclick="cancel_peminjaman(\'' .
                                Crypt::encryptString($list->id) .'\')">Cancel</button>' . 
                                '</li>' .
                                '<li>' .
                                '<a href="' . asset($list->qr_url) . '" target="_blank class="text-dark text-center"><span class="btn btn-sm bg-transparant">View Qr Code</span></a>' . 
                                '</li>' .
                                '</ul>' .
                                '</div>' .
                                '</td>',
                    'npm' => 'admin-account',
                    'nama'=> $list->nama,
                    'tgl_pinjam' => $list->tgl_pinjam,
                    'tgl_wajib_kembali' => $list->tgl_wajib_kembali,
                    'tenggat' => $tenggatPengembalian->format("%R%a days"),
                    'status_approval' => $statusApproval,
                    'alasan' => $list->reject_note
                ];
            }else{
                if($statusApproval == 'Approved'){
                $data[] = [
                    'action' => '<td><div class="btn-group">' .
                                '<button type="button" class="btn btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><box-icon name="cog" color="#ffffff"></box-icon></button>' .
                                '<ul class="dropdown-menu dropdown-menu-start" style="">' .
                                '<li>' .
                                '<button type="button" class="btn btn-white btn-sm" data-bs-toggle="modal" data-bs-target="#basicModal" onclick="cancel_peminjaman(\'' .
                                Crypt::encryptString($list->id) .'\')">Cancel</button>' . 
                                '</li>' .
                                '<li>' .
                                '<a href="' . asset($list->qr_url) . '" target="_blank class="text-dark text-center"><span class="btn btn-sm bg-transparant">View Qr Code</span></a>' . 
                                '</li>' .
                                // Hapus parameter 'mahasiswa'
                                '</ul>' .
                                '</div>' .
                                '</td>',
                    'npm' => $list->unique_code,
                    'nama'=> $list->nama,
                    'tgl_pinjam' => $list->tgl_pinjam,
                    'tgl_wajib_kembali' => $list->tgl_wajib_kembali,
                    'tenggat' => $tenggatPengembalian->format("%R%a days"),
                    'status_approval' => $statusApproval,
                    'alasan' => $list->reject_note
                ];
                }else{
                    $data[] = [
                        'action' => '<td><div class="btn-group">' .
                                    '<button type="button" class="btn btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><box-icon name="cog" color="#ffffff"></box-icon></button>' .
                                    '<ul class="dropdown-menu dropdown-menu-start" style="">' .
                                    // '<li>' .
                                    // '<button type="button" class="btn btn-white btn-sm" data-bs-toggle="modal" data-bs-target="#basicModal" onclick="cancel_peminjaman(\'' .
                                    // Crypt::encryptString($list->id) .'\')">Cancel</button>' . 
                                    // '</li>' .
                                    '<li>' .
                                    '<a href="' . asset($list->qr_url) . '" target="_blank class="text-dark text-center"><span class="btn btn-sm bg-transparant">View Qr Code</span></a>' . 
                                    '</li>' .
                                    // Hapus parameter 'mahasiswa'
                                    '</ul>' .
                                    '</div>' .
                                    '</td>',
                        'npm' => $list->unique_code,
                        'nama'=> $list->nama,
                        'tgl_pinjam' => $list->tgl_pinjam,
                        'tgl_wajib_kembali' => $list->tgl_wajib_kembali,
                        'tenggat' => $tenggatPengembalian->format("%R%a days"),
                        'status_approval' => $statusApproval,
                        'alasan' => $list->reject_note ?? '-'
                    ];
                }

            }
  
        }

        return response()->json([
            'success' => TRUE,
            'message' => 'Data mahasiswa berhasil diambil.',
            'data' => $data,
        ], 200);
    }

    public function api_datatable_book_stock(){
        $dataBookStock = BookStock::select('book_stocks.*', 'categories.category_name as nama_kategori')
        ->join('categories', 'categories.id','=', 'book_stocks.category_id')
        ->get();

        $data = [];
        foreach ($dataBookStock as $list) {
            if(Auth::user()->roleid == 1){
                $data[]=[
                    'action' => '<td><div class="btn-group">' .
                    '<button type="button" class="btn btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><box-icon name="cog" color="#ffffff"></box-icon></button>' .
                    '<ul class="dropdown-menu dropdown-menu-start" style="">' .
                    '<li>' .
                    '<button type="button" class="btn btn-white" data-bs-toggle="modal" data-bs-target="#basicModal" onclick="set_value_toform(\'' .
                    $list->id . '\', \'' . $list->npm . '\', \'' . $list->nama .
                    '\', \'' . $list->email . '\')">Aktifasi Akun</button>' . '</li>' .
                    '<li>' .
                    '<button type="button" class="btn btn-white" onclick="confirm_to_delete(\'' .
                    $list->id . '\', \'' . $list->role_name .
                    '\')">Disaktif Akun</button>' . '</li>' .
                    // Hapus parameter 'mahasiswa'
                    '</ul>' .
                    '</div>' .
                    '</td>',
                    'id' => Crypt::encryptString($list->id),
                    'kategori' => $list->nama_kategori,
                    'sisa_stok' => $list->total
                ];
            }else{
                $data[]=[
                    'action' => '<td><div class="btn-group">' .
                    '<button type="button" class="btn btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><box-icon name="cog" color="#ffffff"></box-icon></button>' .
                    '<ul class="dropdown-menu dropdown-menu-start" style="">' .
                    '<li>' .
                    '<span class="px-3 align-left text-sm text-danger"><small>tidak memiliki akses</small></span>'.
                    '</li>' .
                    '</ul>' .
                    '</div>' .
                    '</td>',
                    'id' => Crypt::encryptString($list->id),
                    'kategori' => $list->nama_kategori,
                    'sisa_stok' => $list->total
                ];
            }
        }
        
        return response()->json([
            'success' => TRUE,
            'message' => 'Data mahasiswa berhasil diambil.',
            'data' => $data,
        ], 200);
    }

    public function api_datatable_category_book(){
        $dataCategory = Category::get();
        $data = [];
    
        foreach ($dataCategory as $list) {
            if(Auth::user()->roleid == 1){
                $data[] = [
                    'action' => '<td><div class="btn-group">' .
                    '<button type="button" class="btn btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><box-icon name="cog" color="#ffffff"></box-icon></button>' .
                    '<ul class="dropdown-menu dropdown-menu-start" style="">' .
                    '<li>' .
                    '<button type="button" class="btn btn-white btn-sm" data-bs-toggle="modal" data-bs-target="#basicModal">Cancel</button>' . 
                    '</li>' .
                    '<li>' .
                    '<a type="button" id="btnEdit" class="btn btn-white text-left btn-sm mx-5" onclick="edit(\'' . Crypt::encryptString($list->id) . '\', \'' .$list->category_name. '\')">'.'<i class="bx bxs-edit-alt"></i>'.' Edit</a>' . 
                    '</li>' .
                    '<li>' .
                    '<button type="button" class="btn btn-white text-left btn-sm" onclick="remove(\'' . Crypt::encryptString($list->id) . '\')">Delete</button>' . 
                    '</li>' .
                    '</ul>' .
                    '</div>' .
                    '</td>',
                    'id' => Crypt::encryptString($list->id),
                    'category_name' => $list->category_name
                ];
            }else{
                $data[] = [
                    'action' => '<td><div class="btn-group">' .
                    '<ul class="dropdown-menu dropdown-menu-start" style="">' .
                    '<li>' .
                    '<span>No action allowed</span>' . 
                    '</li>' .
                    '</ul>' .
                    '</div>' .
                    '</td>',
                    'id' => Crypt::encryptString($list->id),
                    'category_name' => $list->category_name
                ];
            }
        }
    
        return response()->json([
            'success' => TRUE,
            'message' => 'Data category berhasil didapatkan.',
            'data' => $data,
        ], 200);
    }

    public function api_datatable_settup_admin_akses(){
        $dataAkunDosen = Lecture::all();

        $data = [];
        foreach ($dataAkunDosen as $list) {
            $data[] = [
                'action' => '<td><div class="btn-group">' .
                '<button type="button" class="btn btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><box-icon name="cog" color="#ffffff"></box-icon></button>' .
                '<ul class="dropdown-menu dropdown-menu-start" style="">' .
                '<li>' .
                '<button type="button" class="btn btn-white btn-sm" onclick="setAsAdm(/'.Crypt::encryptString($list->id).'/)">Set as admin</button>' . 
                '</li>' .
                // Hapus parameter 'mahasiswa'
                '</ul>' .
                '</div>' .
                '</td>',
                'nidn' => $list->nidn,
                'id' => Crypt::encryptString($list->id),
                'nama' => $list->nama
            ];
        }

        return response()->json([
            'success' => TRUE,
            'message' => 'Data category berhasil didapatkan.',
            'data' => $data,
        ], 200);
    }

    public function api_datatable_dosen(){
        $dataAkunDosen = Lecture::all();

        $data = [];
        foreach ($dataAkunDosen as $list) {
            $data[] = [
                'action' => '<td><div class="btn-group">' .
                '<button type="button" class="btn btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><box-icon name="cog" color="#ffffff"></box-icon></button>' .
                '<ul class="dropdown-menu dropdown-menu-start" style="">' .
                '<li>' .
                '<button type="button" class="btn btn-white btn-sm" onclick="#(/'.Crypt::encryptString($list->id).'/)">Aktivasi Akun</button>' . 
                '</li>' .
                // Hapus parameter 'mahasiswa'
                '</ul>' .
                '</div>' .
                '</td>',
                'nidn' => $list->nidn,
                'id' => Crypt::encryptString($list->id),
                'nama' => $list->nama,
                'email' => $list->email
            ];
        }

        return response()->json([
            'success' => TRUE,
            'message' => 'Data category berhasil didapatkan.',
            'data' => $data,
        ], 200);
    }

    public function api_datatable_book(){
        $dataBuku = Book::select(
            'books.id as book_id', 
            'category_name as nama_kategori', 
            'books.judul',
            'books.penulis',
            'books.tahun_terbit',
            'books.no_revisi',
            'books.penerbit',
            'books.tahun_terbit')
            ->join('categories', 'categories.id', '=', 'category_id')
            // ->join('book_stocks', 'book_stocks.book_id', '=', 'books.id')
            ->get();
        $data=[];

        foreach ($dataBuku as $list) {
            if(Auth::user()->roleid == 1){
                $data[]=[
                    'action' => '<td><div class="btn-group">' .
                    '<button type="button" class="btn btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><box-icon name="cog" color="#ffffff"></box-icon></button>' .
                    '<ul class="dropdown-menu dropdown-menu-start" style="">' .
                    '<li>' .
                    '<button type="button" class="btn btn-white btn-sm" data-bs-toggle="modal" data-bs-target="#">Cancel</button>' . 
                    '</li>' .
                    '<li>' .
                    '<a type="button" id="btnEdit" class="btn btn-white text-left btn-sm mx-5" onclick="edit(\'' . Crypt::encryptString($list->book_id) . '\', \'' .$list->judul. '\', \'' .$list->penulis. '\', \'' .$list->penerbit. '\', \'' .$list->tahun_terbit. '\', \'' .$list->no_revisi. '\')">'.'<i class="bx bxs-edit-alt"></i>'.' Edit</a>' . 
                    '</li>' .
                    '<li>' .
                    '<button type="button" class="btn btn-white text-left btn-sm" onclick="remove(\'' . Crypt::encryptString($list->book_id) . '\')">Delete</button>' . 
                    '</li>' .
                    '</ul>' .
                    '</div>' .
                    '</td>', 
                    'id' => Crypt::encryptString($list->book_id),
                    'judul' => $list->judul,
                    'penulis' => $list->penulis,
                    'kategori' => $list->nama_kategori,
                    'penerbit' => $list->penerbit,
                    'tahun_terbit' => $list->tahun_terbit
                ];
            }else{
                $data[]=[
                    'action' => '<td><div class="btn-group">' .
                    '<button type="button" class="btn btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><box-icon name="cog" color="#ffffff"></box-icon></button>' .
                    '<ul class="dropdown-menu dropdown-menu-start" style="">' .
                    '<li>' .
                    '<span>Action is not allowed</span>' . 
                    '</li>' .
                    '</ul>' .
                    '</div>' .
                    '</td>', 
                    'id' => Crypt::encryptString($list->book_id),
                    'judul' => $list->judul,
                    'penulis' => $list->penulis,
                    'kategori' => $list->nama_kategori,
                    'penerbit' => $list->penerbit,
                    'tahun_terbit' => $list->tahun_terbit
                ];
            }
        }

        return response()->json([
            'success' => TRUE,
            'message' => 'Data category berhasil didapatkan.',
            'data' => $data,
        ], 200);
        
    }

    public function api_datatable_return_book(){

        $dataBukuPinjam = [];

        if (Auth::user()->roleid == 3) {
            $dataBukuPinjam = Transaction::select('transactions.id', 'users.name as nama', 'collagers.npm as unique_code', 'transactions.tgl_pinjam', 'transactions.tgl_wajib_kembali', 'transactions.status_approval', 'transactions.qr_url')
            ->join('users', 'users.id', '=', 'transactions.userid')
            ->join('collagers', 'collagers.id', '=', 'users.collagerid')
            ->where('userid', Auth::user()->id)
            ->get();
        }

        if (Auth::user()->roleid == 2) {
            $dataBukuPinjam = Transaction::select('transactions.id', 'users.name as nama', 'lectures.nidn as unique_code', 'transactions.tgl_pinjam', 'transactions.tgl_wajib_kembali', 'transactions.status_approval', 'transactions.qr_url')->join('users', 'users.id', '=', 'transactions.userid')
            ->join('lectures', 'lectures.id', '=', 'users.lectureid')
            ->where('userid', Auth::user()->id)
            ->get();
        }

        if (Auth::user()->roleid == 1) {
            $dataBukuPinjam = Transaction::select('transactions.id', 'users.name as nama', 'transactions.tgl_pinjam', 'transactions.tgl_wajib_kembali', 'transactions.status_return', 'transactions.qr_url')->join('users', 'users.id', '=', 'transactions.userid')
            ->where('status_approval','Approved')
            // ->where('status_return', 'Waiting')
            ->get();
        }

        // dd($dataBukuPinjam);

        $data = [];
        foreach ($dataBukuPinjam as $list) {
            // var_dump($list->qr_url);
            $dateTglPinjam = date_create($list->tgl_pinjam);
            $tglKembali    = date_create($list->tgl_wajib_kembali);
            $statusApproval = '';

            if ($list->status_return == 'Waiting'){
                $statusApproval = '<span class="badge bg-label-warning">'.$list->status_return.'</span>';
            }

            if ($list->status_return == 'Reject'){
                $statusApproval = '<span class="badge bg-label-danger">'.$list->status_return.'</span>';
            }

            if ($list->status_return == 'Approved'){
                $statusApproval = '<span class="badge bg-label-primary">'.$list->status_return.'</span>';
            }

            $tenggatPengembalian = date_diff($dateTglPinjam, $tglKembali);
            if (Auth::user()->roleid == 1) {
                $data[] = [
                    'action' => '<td><div class="btn-group">' .
                                '<button type="button" class="btn btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><box-icon name="cog" color="#ffffff"></box-icon></button>' .
                                '<ul class="dropdown-menu dropdown-menu-start" style="">' .
                                '<li>' .
                                '<button type="button" class="btn btn-white btn-sm" data-bs-toggle="modal" data-bs-target="#basicModal" onclick="cancel_peminjaman(\'' .
                                Crypt::encryptString($list->id) .'\')">Cancel</button>' . 
                                '</li>' .
                                '<li>' .
                                '<button type="button" class="btn btn-white text-left btn-sm" onclick="show_qr_image(\''. asset($list->qr_url) .'\')">View Qr Code</button>' . 
                                '</li>' .
                                '<li>' .
                                '<button type="button" class="btn btn-white text-left btn-sm">Download Qr Code</button>' . 
                                '</li>' .
                                // Hapus parameter 'mahasiswa'
                                '</ul>' .
                                '</div>' .
                                '</td>',
                    'id' => Crypt::encryptString($list->id),
                    'npm' => 'admin-account',
                    'nama'=> $list->nama,
                    'tgl_pinjam' => $list->tgl_pinjam,
                    'tgl_wajib_kembali' => $list->tgl_wajib_kembali,
                    'tenggat' => $tenggatPengembalian->format("%R%a days"),
                    'status_return' => $statusApproval
                ];
            }else{
                $data[] = [
                    'action' => '<td><div class="btn-group">' .
                                '<button type="button" class="btn btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><box-icon name="cog" color="#ffffff"></box-icon></button>' .
                                '<ul class="dropdown-menu dropdown-menu-start" style="">' .
                                '<li>' .
                                '<button type="button" class="btn btn-white btn-sm" data-bs-toggle="modal" data-bs-target="#basicModal" onclick="cancel_peminjaman(\'' .
                                Crypt::encryptString($list->id) .'\')">Cancel</button>' . 
                                '</li>' .
                                '<li>' .
                                '<button type="button" class="btn btn-white text-left btn-sm" onclick="show_qr_image(\''. asset($list->qr_url) .'\')">View Qr Code</button>' . 
                                '</li>' .
                                '<li>' .
                                '<button type="button" class="btn btn-white text-left btn-sm">Download Qr Code</button>' . 
                                '</li>' .
                                // Hapus parameter 'mahasiswa'
                                '</ul>' .
                                '</div>' .
                                '</td>',
                                'id' => Crypt::encryptString($list->id),
                    'npm' => $list->unique_code,
                    'nama'=> $list->nama,
                    'tgl_pinjam' => $list->tgl_pinjam,
                    'tgl_wajib_kembali' => $list->tgl_wajib_kembali,
                    'tenggat' => $tenggatPengembalian->format("%R%a days"),
                    'status_approval' => $statusApproval
                ];
            }
  
        }

        return response()->json([
            'success' => TRUE,
            'message' => 'Data mahasiswa berhasil diambil.',
            'data' => $data,
        ], 200);
    }
}