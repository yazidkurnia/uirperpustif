<?php

// untuk menggunakan email verified kolom email_verified_at pada table users tidak boleh kosong

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\Transaction\TransactionController;
use App\Http\Controllers\ApiDataTable\ApiDataTableController;
use App\Http\Controllers\CollagerController\CollagerController;
use App\Http\Middleware\Rolechekmiddleware\CheckUserRole;

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
    Route::get('/fetch-data-collager', [ApiDataTableController::class, 'api_datatable_collager'])->name('collagers.datatable');

    ############################################# all about setting account ########################################
    Route::put('/update-user-role', [UsersController::class, 'user_update_role'])->name('user.update.role');
    Route::post('/set-account', [UsersController::class, 'set_account'])->name('setting.user.account');
    Route::delete('/user/delete', [UsersController::class, 'delete_user'])->name('user.delete.account');

    ############################################# all about transaction is here ####################################
    Route::get('/peminjaman-buku', [TransactionController::class, 'index'])->name('transaction.index');
    /**
     * @param string id (id buku, table books)
     */
    Route::get('/pengajuan-peminjaman/{id}', [TransactionController::class, 'pengajuan_peminjaman'])->name('transaction.proses_peminjaman');
    Route::post('/proses-pengajuan', [TransactionController::class, 'store_data_peminjaman'])->name('transaction.store');

    ############################################ confirm request ###################################################
    Route::get('/request-process', [TransactionController::class, 'approval_request'])->name('transaction.approval.request');

    ############################################# api datatable ####################################################
    Route::get('/fetch-data-users', [ApiDataTableController::class, 'api_datatable_users'])->name('users.datatable');

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
