<?php

use App\Http\Controllers\AlphaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HasilPEController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PeramalanController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
use App\Models\produk;
use Illuminate\Support\Facades\Route;








/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    });

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    

    // Produk
    Route::get('/produk', [ProdukController::class, 'index'])->name('produk');
    Route::get('/tambahproduk', [ProdukController::class, 'tambahproduk'])->name('tambahhproduk');
    Route::post('/insertproduk', [ProdukController::class, 'insertproduk'])->name('insertproduk');
    Route::get('/tampilkanproduk/{id_produk}', [ProdukController::class, 'tampilkanproduk'])->name('tampilkanproduk');
    Route::post('/updateproduk/{id_produk}', [ProdukController::class, 'updateproduk'])->name('updateproduk');
    Route::get('/deleteproduk/{id_produk}', [ProdukController::class, 'deleteproduk'])->name('deleteproduk');
    Route::get('/exportpdf', [ProdukController::class, 'exportPDF'])->name('exportpdf');
    Route::post('/importprodukexel', [ProdukController::class, 'importprodukexel'])->name('importprodukexel');

    // Transaksi
    Route::get('/transaksi', [TransaksiController::class, 'transaksi'])->name('transaksi');
    Route::get('/tambahtransaksi', [TransaksiController::class, 'tambahtransaksi'])->name('tambahtransaksi');
    Route::post('/inserttransaksi', [TransaksiController::class, 'inserttransaksi'])->name('inserttransaksi');
    Route::get('/tampilkantransaksi/{id_transaksi}', [TransaksiController::class, 'tampilkantransaksi'])->name('tampilkantransaksi');
    Route::post('/updatetransaksi/{id_transaksi}', [TransaksiController::class, 'updatetransaksi'])->name('updatetransaksi');
    Route::get('/deletetransaksi/{id_transaksi}', [TransaksiController::class, 'deletetransaksi'])->name('deletetransaksi');
    Route::post('/importtransaksiexel', [TransaksiController::class, 'importtransaksiexel'])->name('importtransaksiexel');
    Route::get('/deletesemuatransaksi', [TransaksiController::class, 'deletesemuatransaksi'])->name('deletesemuatransaksi');

    //Peramalan
    Route::get('/peramalan', [PeramalanController::class, 'index'])->name('peramalan');
    Route::get('/peramalanku', [AlphaController::class, 'index'])->name('alpha.index');

    //  Route::get('/peramalan-alpha', [PeramalanController::class, 'alpha']);
    //  Route::get('/hasilpe', [HasilPEController::class, 'index']);


    //User
    Route::get('/user', [UserController::class, 'user'])->name('user');
    Route::get('/tambahuser', [UserController::class, 'tambahuser'])->name('tambahuser');
    Route::post('/insertuser', [UserController::class, 'insertuser'])->name('insertuser');
    Route::get('/deleteuser/{email}', [UserController::class, 'deleteuser'])->name('deleteuser');
});

// Login
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logininsert', [AuthController::class, 'logininsert'])->name('logininsert');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/registeruser', [AuthController::class, 'registeruser'])->name('registeruser');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


