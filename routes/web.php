<?php

// untuk menggunakan email verified kolom email_verified_at pada table users tidak boleh kosong

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Book\BookController;
use App\Http\Controllers\Loan\LoanController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Stock\StockController;
use App\Http\Controllers\Return\ReturnController;
use App\Http\Controllers\Lecture\LectureController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Middleware\Rolechekmiddleware\CheckUserRole;
use App\Http\Controllers\Transaction\TransactionController;
use App\Http\Controllers\Report\ReportTransactionController;
use App\Http\Controllers\ApiDataTable\ApiDataTableController;
use App\Http\Controllers\CollagerController\CollagerController;
use App\Http\Controllers\ApprovalTransaction\ApprovalTransactionController;

Route::get('/', function () {
    return View('wellcome');
});

// Route::get('/dashboard', function () {
//     if (auth()->check() && auth()->user()->hasVerifiedEmail()) {
//         return Inertia::render('Dashboard');
//     }
Route::get('/greet/{name}', [TransactionController::class, 'greet']);
//     return redirect('/login');
// })->middleware(['auth', 'verified'])->name('dashboard');
// Route::middleware(['auth', 'verified', 'role'])->group(function () { //! dengan pengecekan role
Route::middleware(['auth', 'verified'])->group(function () { //! tanpa pengecekan role
    Route::get('/dashboard', [TransactionController::class, 'index'])->name('dashboard');
});

Route::middleware('auth')->group(function () {
   

    ############################################ all about transaction is here ####################################
    Route::get('/peminjaman-buku', [TransactionController::class, 'index'])->name('transaction.index');
    /**
     * @param string id (id buku, table books)
     */
    Route::get('/pengajuan-peminjaman/{id}', [TransactionController::class, 'pengajuan_peminjaman'])->name('transaction.proses_peminjaman');
    Route::get('/detail-peminjaman/{id}', [TransactionController::class, 'detail_peminjaman'])->name('transaction.peminjaman.detail');
    Route::post('/proses-pengajuan', [TransactionController::class, 'store_data_peminjaman'])->name('transaction.store');
    Route::delete('/cancel/peminjaman', [TransactionController::class, 'cancel_peminjaman'])->name('transaction.cancel_peminjaman');
    Route::get('/generate-qrcode/{id}', [TransactionController::class, 'generate_transaction_qr'])->name('transaction.generate.qr');
   
    ############################################# confirm request ###################################################
    Route::get('/list-peminjaman-pengembalian', [ApprovalTransactionController::class, 'index'])->name('transaction.approval');
    Route::get('/request-process', [TransactionController::class, 'approval_request'])->name('transaction.approval.request');

    ############################################# api datatable ####################################################
    Route::get('/fetch-data-users', [ApiDataTableController::class, 'api_datatable_users'])->name('users.datatable');
    Route::get('/fetch-data-collager', [ApiDataTableController::class, 'api_datatable_collager'])->name('collagers.datatable');
    Route::get('/list-waiting-transaction', [ApiDataTableController::class,'api_datatable_approve_peminjaman'])->name('transaksi_peminjaman.datatable');
    Route::get('/data/buku/peminjaman-user', [ApiDataTableController::class,'api_datatable_users_book'])->name('users.loaning.books');
    Route::get('/list/stok', [ApiDataTableController::class, 'api_datatable_book_stock'])->name('api.data.stock');
    Route::get('/list/category/datatable',  [ApiDataTableController::class, 'api_datatable_category_book'])->name('api.category.datatable');
    Route::get('/data/buku/pengembalian-user', [ApiDataTableController::class,'api_datatable_return_book'])->name('users.return.books');
    Route::get('/data/buku', [ApiDataTableController::class,'api_datatable_book'])->name('api.books.data');

    ############################################# all about approval ###############################################
    Route::post('/approval-peminjaman', [ApprovalTransactionController::class, 'approve_transaksi_peminjaman'])->name('transaction.approval.peminjaman');

    ############################################# all about data peminjaman ########################################
    Route::get('/user/peminjaman',[ LoanController::class, 'index'])->name('data.loaning');
    Route::get('/user/loaning', [LoanController::class, 'peminjaman_by_userid'])->name('loaning.by.userid');
    Route::get('/download/qr/image/{filePath}', [LoanController::class, 'download_qr_image'])->name('download.qr.image');
    Route::get('/pengembalian',[ ReturnController::class, 'index'])->name('data.return');

    ############################################# all about reporting ##############################################
    Route::get('/report/pp', [ReportTransactionController::class, 'index'])->name('report.transaction');

    ############################################# all about stock ##################################################
    Route::get('/all-stock', [StockController::class, 'index'])->name('stock.data');

    ############################################# all about book ###################################################
    Route::get('/buku',[ BookController::class, 'index'])->name('data.book');

});

Route::middleware(['auth', 'role'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/collager', [CollagerController::class, 'index'])->name('list.collager');
    Route::get('/lectures', [LectureController::class, 'index'])->name('list.lectures');
    ############################################ all about setting account ########################################
    Route::put('/update-user-role', [UsersController::class, 'user_update_role'])->name('user.update.role');
    Route::post('/set-account', [UsersController::class, 'set_account'])->name('setting.user.account');
    Route::delete('/user/delete', [UsersController::class, 'delete_user'])->name('user.delete.account');

    ############################################ all about category ##############################################@
    Route::get('/category/list', [CategoryController::class, 'index'])->name('category.list');
    Route::post('/add/category', [CategoryController::class, 'store'])->name('category.store');
    Route::get('/edit/category', [CategoryController::class, 'edit'])->name('category.edit');
    Route::put('/update/category', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/delete/category', [CategoryController::class, 'destroy'])->name('category.destroy');

    ############################################# all about buku ##################################################

    Route::post('/tambah-buku', [BookController::class, 'store'])->name('book.store');
    Route::put('/update/book', [BookController::class, 'update'])->name('book.update');
    Route::delete('/delete/book', [BookController::class, 'destroy'])->name('book.destroy');

    ###########################################@ setting akun admin ###############################################
    Route::get('/api-setup/adm', [ApiDataTableController::class, 'api_datatable_settup_admin_akses'])->name('api.datatable.setup.admin');
    Route::get('/setting-adm-account', [AdminController::class, 'index'])->name('admin.setup.adm.account');
    Route::PUT('/set-as-admin', [AdminController::class, 'update_role_to_admin'])->name('update.role.to.admin');
    Route::get('/api-setup/dosen', [ApiDataTableController::class, 'api_datatable_dosen'])->name('api.datatable.setup.dosen');
});

Route::get('image/qrcode/{text}', [
    LoanController::class,
    'makeQrCode'
])->name('qrcode');

Route::get('/fuel', function () {
    return view('fuel');
});

Route::get('/anomali', function () {
    return view('anomali');
});

Route::get('/ticket', function () {
    return view('ticket');
});

Route::get('/employee', [EmployeController::class, 'index']);

Route::get('/users', [UsersController::class, 'index']);


Route::get('/unit', function () {
    return view('unit');
});

Route::get('/warehouse', function () {
    return view('warehouse');
});

Route::get('/hak', function () {
    return view('hak');
});

Route::get('/approval', function () {
    return view('approval');
});

Route::get('/unauthorization', function() {
    return view('pages.general.unauthorization');
})->name('user.unauthorization');

##################################### route api#########################################
Route::post('api/api-auth', [AuthApiController::class, 'sign_in'])->name('api.auth.sign_in');

require __DIR__.'/auth.php';
