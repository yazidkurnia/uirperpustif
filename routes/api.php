<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthApi;
use App\Http\Controllers\Api\BookApi;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TransactionApi;
use App\Http\Controllers\Api\TransactionDetailApi;
use App\Http\Controllers\Api\V2\Auth\AuthApiController;
use App\Http\Controllers\Api\v2\Books\BookApiController;
use App\Http\Controllers\Api\v2\Books\BookDetailApiController;
use App\Http\Controllers\Api\v2\Transactions\TransactionApiController;
use App\Http\Controllers\Api\v2\Transactions\TransactionDetailApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/**
 * api dapat digunakan untuk melakukan login kedalam aplikasi (authentication)
 */
Route::post('/sign-in', [AuthApi::class, 'sign_in'])->name('api.auth.signin');
Route::post('/sign-up', [AuthApi::class, 'sign_up'])->name('api.auth.signup');
Route::post('/store-book-api', [TransactionApi::class, 'transaction_store'])->name('api.transaction.store');

/**
 * api dapat digunakan untuk melakukan pengambilan data buku dengan peminjaman terbanyak
 */
Route::get('/top-five-book', [BookApi::class, 'get_top_five_book'])->name('api.book.top.five');
Route::get('/all-books', [BookApi::class, 'get_all_book'])->name('api.book.all');
Route::get('/detail-book/{id}', [BookApi::class, 'get_books_detail'])->name('api.book.detail');
Route::get('/transaction-history/{id}', [TransactionApi::class, 'get_transaction_by_author'])->name('api.transaction.history');
Route::get('/transaction-detail/{id}', [TransactionDetailApi::class, 'get_transactiondetail_bytransactionid'])->name('api.transaction.detail.all');

/**
 * --------------------------------------------------------------------------
 *                      berikut merupakan versi kedua dari api              |
 * --------------------------------------------------------------------------
 */ 

# api auth v2
Route::post('/v2/sign-in', [AuthApiController::class, 'sign_in']);


# api book v2
Route::get('/v2/best_five_book/{id}', [BookApiController::class, 'fetch_best_five_book']);
Route::get('/v2/book/{id}', [BookApiController::class, 'fetch_all_book']);

# api book detail v2
Route::get('/v2/detail/{id}', [BookDetailApiController::class, 'fetch_data_byid']);

# all about api transaction

# get transactions all

/**
 * @param userid String (encrypted int)
 */
Route::get('/v2/get/status-transaksi/{id}', [TransactionApiController::class, 'get_status_transaction']);
Route::get('/v2/transaction-detail/{id}', [TransactionDetailApiController::class, 'get_detail_transaction']);
Route::post('/v2/send-data', [TransactionApiController::class, 'store_transaction']);