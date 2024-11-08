<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\ApiDataTable\ApiDataTableController;
use App\Http\Controllers\CollagerController\CollagerController;

Route::get('/', function () {
    $data['title'] = 'Dashboard';
    return view('index', $data);
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

Route::get('/collager', [CollagerController::class, 'index'])->name('list.collager');
Route::get('/fetch-data-collager', [ApiDataTableController::class, 'api_datatable_collager'])->name('collagers.datatable');

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

############################################# all about setting account ########################################3
Route::put('/update-user-role', [UsersController::class, 'user_update_role'])->name('user.update.role');
Route::post('/set-account', [UsersController::class, 'set_account'])->name('setting.user.account');
Route::delete('/user/delete', [UsersController::class, 'delete_user'])->name('user.delete.account');

############################################# api datatable #####################################################

Route::get('/fetch-data-users', [ApiDataTableController::class, 'api_datatable_users'])->name('users.datatable');
