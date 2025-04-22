<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthApi;
use App\Http\Controllers\Api\BookApi;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TransactionApi;
use App\Http\Controllers\Api\TransactionDetailApi;
use App\Http\Controllers\Api\V2\Auth\AuthApiController;

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

Route::post('/v2/sign-in', [AuthApiController::class, 'sign_in']);