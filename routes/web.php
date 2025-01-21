<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\KonsultasiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\JenisTreatmentController;
use App\Http\Controllers\FeedbackKonsultasiController;
use App\Http\Controllers\FeedbackTreatmentController;

use App\Http\Controllers\DetailBookingTreatmentController;
use App\Http\Controllers\BookingTreatmentController;
use App\Http\Controllers\JadwalDokterController;
use App\Http\Controllers\JadwalBeauticianController;
use App\Http\Controllers\PembelianProdukController;




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

Route::get('/', function () {
    return view('authentikasi.login');
});

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/user/home', function () {
    if (!session()->has('user') || session('user.role') !== 'pelanggan') {
        return redirect()->route('login.form');
    }
    return view('users-pages.home');
})->name('user.home');

Route::get('/dashboard', function () {
    if (!session()->has('user')) {
        return redirect()->route('login.form');
    }

    $role = session('user.role');
    if (!in_array($role, ['dokter', 'beautician', 'front office'])) {
        return redirect()->route('login.form');
    }

    return view('dashboard.dashboard');
})->name('dashboard');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/home', function () {
    return view('users-pages.home');
})->name('users.home');

Route::get('/konsultasi', function () {
    return view('users-pages.konsultasi');
})->name('users.konsultasi');

Route::get('/konsultasi/with-doctor', [KonsultasiController::class, 'indexWithDoctor'])->name('konsultasi.with-doctor');
Route::get('/konsultasi/without-doctor', [KonsultasiController::class, 'indexWithoutDoctor'])->name('konsultasi.without-doctor');
Route::get('/konsultasi/create', [KonsultasiController::class, 'create'])->name('konsultasi.create');
Route::post('/konsultasi', [KonsultasiController::class, 'store'])->name('konsultasi.store');
Route::get('/konsultasi/{id}/edit', [KonsultasiController::class, 'edit'])->name('konsultasi.edit');
Route::put('/konsultasi/{id}', [KonsultasiController::class, 'update'])->name('konsultasi.update');
Route::delete('/konsultasi/{id}', [KonsultasiController::class, 'destroy'])->name('konsultasi.destroy');
Route::get('/konsultasi/{id}/detail', [KonsultasiController::class, 'show'])->name('konsultasi.show');
Route::get('/konsultasi/edit-keluhan/{id}', [KonsultasiController::class, 'editKeluhan'])->name('konsultasi.editKeluhan');
Route::put('/konsultasi/edit-keluhan/{id}', [KonsultasiController::class, 'updateKeluhan'])->name('konsultasi.updateKeluhan');


Route::get('/managemenTreatment', function () {
    return view('managemenTreatment');
});

Route::get('/treatment/types', function () {
    return view('treatment.listJenisTreatment');
});

Route::prefix('treatment')->group(function () {
    Route::get('/types', [JenisTreatmentController::class, 'index'])->name('jenisTreatment.index');

    Route::get('/lists', [TreatmentController::class, 'index'])->name('treatment.index');
});


Route::prefix('treatment')->group(function () {
    Route::get('/types', [TreatmentController::class, 'index'])->name('treatment.index');
    Route::get('/types/{id}', [TreatmentController::class, 'show'])->name('treatment.show');
    Route::post('/types', [TreatmentController::class, 'store'])->name('treatment.store');
    Route::put('/types/{id}', [TreatmentController::class, 'update'])->name('treatment.update');
    Route::delete('/types/{id}', [TreatmentController::class, 'destroy'])->name('treatment.destroy');
});


Route::prefix('treatment')->group(function () {
    Route::get('/', [TreatmentController::class, 'index'])->name('treatment.index');
    Route::get('/{id}', [TreatmentController::class, 'show'])->name('treatment.show');
    Route::post('/', [TreatmentController::class, 'store'])->name('treatment.store');
    Route::put('/{id}', [TreatmentController::class, 'update'])->name('treatment.update');
    Route::delete('/{id}', [TreatmentController::class, 'destroy'])->name('treatment.destroy');
});

Route::prefix('jenis-treatment')->group(function () {
    Route::get('/', [JenisTreatmentController::class, 'index'])->name('jenisTreatment.index');
    Route::get('/{id}', [JenisTreatmentController::class, 'show'])->name('jenisTreatment.show');
    Route::post('/', [JenisTreatmentController::class, 'store'])->name('jenisTreatment.store');
    Route::put('/{id}', [JenisTreatmentController::class, 'update'])->name('jenisTreatment.update');
    Route::delete('/{id}', [JenisTreatmentController::class, 'destroy'])->name('jenisTreatment.destroy');
});

Route::prefix('feedback/konsultasi')->name('feedback.feedbackKonsultasi.')->group(function () {
    Route::get('/', [FeedbackKonsultasiController::class, 'index'])->name('index');
    Route::get('/{id}', [FeedbackKonsultasiController::class, 'show'])->name('show');
    Route::put('/{id}', [FeedbackKonsultasiController::class, 'update'])->name('update');
    Route::delete('/{id}', [FeedbackKonsultasiController::class, 'destroy'])->name('destroy');
});


Route::prefix('feedback/treatment')->name('feedback.feedbackTreatment.')->group(function () {
    Route::get('/', [FeedbackTreatmentController::class, 'index'])->name('index');
    Route::get('/{id}', [FeedbackTreatmentController::class, 'show'])->name('show');
    Route::put('/{id}', [FeedbackTreatmentController::class, 'update'])->name('update');
    Route::delete('/{id}', [FeedbackTreatmentController::class, 'destroy'])->name('destroy');
});

Route::prefix('booking')->name('bookingTreatment.')->group(function () {
    // Route::get('/', [BookingTreatmentController::class, 'index'])->name('index');
    // Route::get('/{id}', [BookingTreatmentController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [BookingTreatmentController::class, 'edit'])->name('edit');
    Route::delete('/{id}', [BookingTreatmentController::class, 'destroy'])->name('destroy');
});


Route::prefix('detailBooking')->name('detailBooking.')->group(function () {
    Route::get('/', [DetailBookingTreatmentController::class, 'index'])->name('index');
    Route::post('/store', [DetailBookingTreatmentController::class, 'store'])->name('store');
    Route::get('/{id}', [DetailBookingTreatmentController::class, 'show'])->name('show');
    Route::delete('/{id}', [DetailBookingTreatmentController::class, 'destroy'])->name('destroy');
});

Route::get('/booking/create', [DetailBookingTreatmentController::class, 'create'])->name('booking.create');
Route::post('/booking', [DetailBookingTreatmentController::class, 'store'])->name('booking.store');
Route::get('/booking/detail/{id}', [DetailBookingTreatmentController::class, 'show'])->name('booking.detail');
Route::put('/detailBooking/update/{id}', [DetailBookingTreatmentController::class, 'update'])->name('detailBooking.update');




// Route untuk halaman pembelian produk
Route::get('/pembelian-produk', [PembelianProdukController::class, 'index'])->name('pembelianProduk.index');
Route::get('pembelian-produk/create', [PembelianProdukController::class, 'create'])->name('pembelian-produk.create'); // Menampilkan form tambah pembelian
Route::post('pembelian-produk/store', [PembelianProdukController::class, 'store'])->name('pembelian-produk.store'); // Menyimpan data pembelian
Route::get('/pembelian-produk/{id}', [PembelianProdukController::class, 'show'])->name('pembelian-produk.show');// Rute untuk menampilkan detail pembelian produk
Route::get('/pembelian-produk/{id}/edit', [PembelianProdukController::class, 'edit'])->name('pembelian-produk.edit');
Route::put('/pembelian-produk/{id}', [PembelianProdukController::class, 'update'])->name('pembelian-produk.update');


// Kategorizes
Route::prefix('kategori')->group(function () {
    Route::get('/', [KategoriController::class, 'index'])->name('kategori.index');
    Route::get('/create', [KategoriController::class, 'create'])->name('kategori.create');
    Route::post('/', [KategoriController::class, 'store'])->name('kategori.store');
    Route::get('/{id}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
    Route::put('/{id}', [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');
});

// Produkiezz
Route::prefix('produk')->group(function () {
    Route::get('/', [ProdukController::class, 'index'])->name('produk.index');
    Route::get('/create', [ProdukController::class, 'create'])->name('produk.create');
    Route::post('/', [ProdukController::class, 'store'])->name('produk.store');
    Route::get('/{id}/detail', [ProdukController::class, 'show'])->name('produk.show');
    Route::get('/{id}/edit', [ProdukController::class, 'edit'])->name('produk.edit');
    Route::put('/{id}', [ProdukController::class, 'update'])->name('produk.update');
    Route::delete('/{id}', [ProdukController::class, 'destroy'])->name('produk.destroy');
});

Route::prefix('jadwal-dokter')->group(function () {
    Route::get('/', [JadwalDokterController::class, 'index'])->name('jadwal-dokter.index');
    Route::post('/', [JadwalDokterController::class, 'store'])->name('jadwal-dokter.store');
    Route::put('/{id}', [JadwalDokterController::class, 'update'])->name('jadwal-dokter.update');
    Route::delete('/{id}', [JadwalDokterController::class, 'destroy'])->name('jadwal-dokter.destroy');
});

Route::prefix('jadwal-beautician')->group(function () {
    Route::get('/', [JadwalBeauticianController::class, 'index'])->name('jadwal-beautician.index');
    Route::post('/', [JadwalBeauticianController::class, 'store'])->name('jadwal-beautician.store');
    Route::put('/{id}', [JadwalBeauticianController::class, 'update'])->name('jadwal-dokter.update');
    Route::delete('/{id}', [JadwalBeauticianController::class, 'destroy'])->name('jadwal-beautician.destroy');
});

// Route::get('/jadwal-dokter', [JadwalDokterController::class, 'index'])->name('jadwal-dokter.index');
// Route::post('/jadwal-dokter', [JadwalDokterController::class, 'store'])->name('jadwal-dokter.store');
// Route::put('/jadwal-dokter/{id}', [JadwalDokterController::class, 'update'])->name('jadwal-dokter.update');
// Route::delete('/jadwal-dokter/{id}', [JadwalDokterController::class, 'destroy'])->name('jadwal-dokter.destroy');

// Route::get('/jadwal-beautician', function () {
//     return view('jadwal.jadwalbeautician');
// })->name('beautician.jadwalbeautician');
