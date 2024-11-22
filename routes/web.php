<?php

// untuk menggunakan email verified kolom email_verified_at pada table users tidak boleh kosong

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Loan\LoanController;
use App\Http\Controllers\Stock\StockController;
use App\Http\Middleware\Rolechekmiddleware\CheckUserRole;
use App\Http\Controllers\Transaction\TransactionController;
use App\Http\Controllers\Report\ReportTransactionController;
use App\Http\Controllers\ApiDataTable\ApiDataTableController;
use App\Http\Controllers\CollagerController\CollagerController;
use App\Http\Controllers\ApprovalTransaction\ApprovalTransactionController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Route::get('/dashboard', function () {
//     if (auth()->check() && auth()->user()->hasVerifiedEmail()) {
//         return Inertia::render('Dashboard');
//     }

//     return redirect('/login');
// })->middleware(['auth', 'verified'])->name('dashboard');
// Route::middleware(['auth', 'verified', 'role'])->group(function () { //! dengan pengecekan role
Route::middleware(['auth', 'verified'])->group(function () { //! tanpa pengecekan role
    Route::get('/dashboard', [TransactionController::class, 'index'])->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/collager', [CollagerController::class, 'index'])->name('list.collager');
    ############################################ all about setting account ########################################
    Route::put('/update-user-role', [UsersController::class, 'user_update_role'])->name('user.update.role');
    Route::post('/set-account', [UsersController::class, 'set_account'])->name('setting.user.account');
    Route::delete('/user/delete', [UsersController::class, 'delete_user'])->name('user.delete.account');

    ############################################ all about transaction is here ####################################
    Route::get('/peminjaman-buku', [TransactionController::class, 'index'])->name('transaction.index');
    /**
     * @param string id (id buku, table books)
     */
    Route::get('/pengajuan-peminjaman/{id}', [TransactionController::class, 'pengajuan_peminjaman'])->name('transaction.proses_peminjaman');
    Route::get('/detail-peminjaman/{id}', [TransactionController::class, 'detail_peminjaman'])->name('transaction.peminjaman.detail');
    Route::post('/proses-pengajuan', [TransactionController::class, 'store_data_peminjaman'])->name('transaction.store');
    Route::delete('/cancel/peminjaman', [TransactionController::class, 'cancel_peminjaman'])->name('transaction.cancel_peminjaman');
    ############################################# confirm request ###################################################
    Route::get('/list-peminjaman-pengembalian', [ApprovalTransactionController::class, 'index'])->name('transaction.approval');
    Route::get('/request-process', [TransactionController::class, 'approval_request'])->name('transaction.approval.request');

    ############################################# api datatable ####################################################
    Route::get('/fetch-data-users', [ApiDataTableController::class, 'api_datatable_users'])->name('users.datatable');
    Route::get('/fetch-data-collager', [ApiDataTableController::class, 'api_datatable_collager'])->name('collagers.datatable');
    Route::get('/list-waiting-transaction', [ApiDataTableController::class,'api_datatable_approve_peminjaman'])->name('transaksi_peminjaman.datatable');
    Route::get('/data/buku/peminjaman-user', [ApiDataTableController::class,'api_datatable_users_book'])->name('users.loaning.books');
    Route::get('/list/stok', [ApiDataTableController::class, 'api_datatable_book_stock'])->name('api.data.stock');

    ############################################# all about approval ###############################################
    Route::post('/approval-peminjaman', [ApprovalTransactionController::class, 'approve_transaksi_peminjaman'])->name('transaction.approval.peminjaman');

    ############################################# all about data peminjaman ########################################
    Route::get('/user/peminjaman',[ LoanController::class, 'index'])->name('data.loaning');

    ############################################# all about reporting ##############################################
    Route::get('/report/pp', [ReportTransactionController::class, 'index'])->name('report.transaction');

    ############################################# all about stock ##################################################
    Route::get('/all-stock', [StockController::class, 'index'])->name('stock.data');

});

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



require __DIR__.'/auth.php';
