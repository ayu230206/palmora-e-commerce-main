<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PenerbitController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProdusenController;
use App\Http\Controllers\TransaksiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('index');

Route::get('/keranjang', [CartController::class, 'index'])->name('keranjang');

Route::get('/detail/{nama_produk}', [HomeController::class, 'detail'])->name('detail_produk');

Route::get('/get-customer-id', [CartController::class, 'getCustomerId']);
Route::post('/add-to-cart/{nama_produk}/{id}', [CartController::class, 'addCart']);
Route::post('/update-keranjang/{id}/{action}', [CartController::class, 'updateCart'])->name('update-keranjang');
Route::delete('/hapus-item-keranjang/{id}', [CartController::class, 'hapusItem'])->name('hapus-item-keranjang');

Route::post('/submit-pembayaran', [TransaksiController::class, 'store'])->name('submit-pembayaran');
Route::get('/history', [TransaksiController::class, 'history_transaksi'])->name('history');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login/auth', [AuthController::class, 'auth'])->name('authenticate');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register/post', [AuthController::class, 'store'])->name('auth.register');
Route::post('/register/post/produsen', [AuthController::class, 'store_produsen'])->name('auth.register.produsen');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/dashboard/produk', [ProdukController::class, 'index'])->name('produk');
    Route::post('/dashboard/produk/post', [ProdukController::class, 'store'])->name('store.produk');
    Route::put('/dashboard/produk/{id}/update', [ProdukController::class, 'update'])->name('update.produk');
    Route::delete('/dashboard/produk/{id}/hapus', [ProdukController::class, 'destroy'])->name('destroy.produk');

    Route::get('/dashboard/customer', [CustomerController::class, 'index'])->name('customer');
    Route::post('/dashboard/customer/post', [CustomerController::class, 'store'])->name('store.customer');
    Route::put('/dashboard/customer/{id}/update', [CustomerController::class, 'update'])->name('update.customer');
    Route::delete('/dashboard/customer/{id}/hapus', [CustomerController::class, 'destroy'])->name('destroy.customer');

    Route::get('/dashboard/kategori', [KategoriController::class, 'index'])->name('kategori');
    Route::post('/dashboard/kategori/post', [KategoriController::class, 'store'])->name('store.kategori');
    Route::put('/dashboard/kategori/{id}/update', [KategoriController::class, 'update'])->name('update.kategori');
    Route::delete('/dashboard/kategori/{id}/hapus', [KategoriController::class, 'destroy'])->name('destroy.kategori');

    Route::get('/dashboard/produsen', [ProdusenController::class, 'index'])->name('produsen');
    Route::post('/dashboard/produsen/post', [ProdusenController::class, 'store'])->name('store.produsen');
    Route::put('/dashboard/produsen/{id}/update', [ProdusenController::class, 'update'])->name('update.produsen');
    Route::delete('/dashboard/produsen/{id}/hapus', [ProdusenController::class, 'destroy'])->name('destroy.produsen');

    Route::get('/dashboard/transaksi', [TransaksiController::class, 'index'])->name('transaksi');
    Route::put('/dashboard/transaksi/{id}/terima', [TransaksiController::class, 'terima'])->name('terima.transaksi');
    Route::put('/dashboard/transaksi/{id}/tolak', [TransaksiController::class, 'tolak'])->name('tolak.transaksi');
    Route::post('/dashboard/transaksi/post', [TransaksiController::class, 'store'])->name('store.transaksi');
    Route::put('/dashboard/transaksi/{id}/update', [TransaksiController::class, 'update'])->name('update.transaksi');
    Route::delete('/dashboard/transaksi/{id}/hapus', [TransaksiController::class, 'destroy'])->name('destroy.transaksi');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
