<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AkunPelangganController;
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
use App\Http\Controllers\PromoController;
use App\Http\Controllers\KompensasiController;
use App\Http\Controllers\KomplainController;
use App\Http\Controllers\KompensasiDiberikanController;
use App\Http\Controllers\PembayaranTreatmentController;
use App\Http\Controllers\PembayaranProdukController;
use App\Http\Controllers\RekamMedisController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventarisStokController;




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
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// Route::get('/user/home', function () {
//     if (!session()->has('user') || session('user.role') !== 'pelanggan') {
//         return redirect()->route('login.form');
//     }
//     return view('users-pages.home');
// })->name('user.home');

// Route::get('/dashboard', function () {
//     if (!session()->has('user')) {
//         return redirect()->route('login.form');
//     }

//     $role = session('user.role');
//     if (!in_array($role, ['dokter', 'beautician', 'front office'])) {
//         return redirect()->route('login.form');
//     }

//     return view('dashboard.dashboard');
// })->name('dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/home', function () {
    return view('users-pages.home');
})->name('users.home');

Route::get('/konsultasi', function () {
    return view('users-pages.konsultasi');
})->name('users.konsultasi');


// Form pendaftaran pelanggan
Route::get('/akun-pelanggan', [AkunPelangganController::class, 'index'])
    ->name('register.form');

// Kirim data pendaftaran
Route::post('/register', [AkunPelangganController::class, 'register'])
    ->name('akun.store');

Route::put('akun-pelanggan/{id}/update-password', [AkunPelangganController::class, 'updatePassword'])
    ->name('akun.updatePassword');



Route::get('/konsultasi', [KonsultasiController::class, 'indexWithDoctor'])->name('konsultasi.with-doctor');
// Route::get('/konsultasi/without-doctor', [KonsultasiController::class, 'indexWithoutDoctor'])->name('konsultasi.without-doctor');
Route::get('/konsultasi/create', [KonsultasiController::class, 'create'])->name('konsultasi.create');
Route::post('/konsultasi', [KonsultasiController::class, 'store'])->name('konsultasi.store');
// Route::get('/konsultasi/{id}/edit', [KonsultasiController::class, 'edit'])->name('konsultasi.edit');
// Route::put('/konsultasi/{id}', [KonsultasiController::class, 'update'])->name('konsultasi.update');
Route::delete('/konsultasi/{id}', [KonsultasiController::class, 'destroy'])->name('konsultasi.destroy');
Route::get('/konsultasi/{id}/detail', [KonsultasiController::class, 'show'])->name('konsultasi.show');
// Route::get('/konsultasi/edit-keluhan/{id}', [KonsultasiController::class, 'editKeluhan'])->name('konsultasi.editKeluhan');
// Route::put('/konsultasi/edit-keluhan/{id}', [KonsultasiController::class, 'updateKeluhan'])->name('konsultasi.updateKeluhan');
// Route untuk menampilkan form tambah detail konsultasi
Route::get('/konsultasi/{id}/tambah-detail', [KonsultasiController::class, 'tambahDetail'])->name('konsultasi.tambahDetail');

// Route untuk proses simpan detail konsultasi
Route::post('/konsultasi/{id}/tambah-detail', [KonsultasiController::class, 'simpanDetail'])->name('konsultasi.simpanDetail');

// Jika untuk AJAX dari frontend, bisa di web.php:
Route::post('konsultasi/{id}/update-status', [KonsultasiController::class, 'updateStatus'])
    ->name('konsultasi.updateStatus');




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


// Route::prefix('feedback/treatment')->name('feedback.feedbackTreatment.')->group(function () {
//     Route::get('/', [FeedbackTreatmentController::class, 'index'])->name('index');
//     Route::get('/{id}', [FeedbackTreatmentController::class, 'show'])->name('show');
//     Route::put('/{id}', [FeedbackTreatmentController::class, 'update'])->name('update');
//     Route::delete('/{id}', [FeedbackTreatmentController::class, 'destroy'])->name('destroy');
// });


Route::prefix('bookingTreatment')->name('bookingTreatment.')->group(function () {
    Route::get('/', [DetailBookingTreatmentController::class, 'index'])->name('index');
});

Route::put('/detailBooking/{id}/update-status', [DetailBookingTreatmentController::class, 'updateStatus'])->name('bookingTreatment.updateStatus');
Route::post('/booking', [DetailBookingTreatmentController::class, 'store'])->name('booking.store');
Route::get('/booking/detail/{id}', [DetailBookingTreatmentController::class, 'show'])->name('booking.detail');
Route::put('/detailBooking/update/{id}', [DetailBookingTreatmentController::class, 'update'])->name('detailBooking.update');



Route::get('/pembelian-produk/{paymentId}/invoice', 
    [PembelianProdukController::class, 'generateInvoice']
)->name('pembelian-produk.invoice');
Route::put('pembayaran-produk/{id}/konfirmasi', [PembelianProdukController::class, 'confirmPayment'])->name('pembayaran-produk.confirm');
    // setelah route pembayaran-produk.confirm...
Route::put(
    '/pembelian-produk/{id}/pembayaran',
    [PembelianProdukController::class, 'updatePayment']
)->name('pembelian-produk.updatePayment');

// Route untuk halaman pembelian produk
Route::get('/pembelian-produk', [PembelianProdukController::class, 'index'])->name('pembelianProduk.index');
// Route::get('pembelian-produk/create', [PembelianProdukController::class, 'create'])->name('pembelian-produk.create'); // Menampilkan form tambah pembelian
Route::post('pembelian-produk/store', [PembelianProdukController::class, 'store'])->name('pembelian-produk.store'); // Menyimpan data pembelian
Route::get('/pembelian-produk/{id}', [PembelianProdukController::class, 'show'])->name('pembelian-produk.show'); // Rute untuk menampilkan detail pembelian produk
Route::get('/pembelian-produk/{id}/edit', [PembelianProdukController::class, 'edit'])->name('pembelian-produk.edit');
Route::put('/pembelian-produk/{id}', [PembelianProdukController::class, 'update'])->name('pembelian-produk.update');
Route::delete('/pembelian-produk/{id}/destroy', [PembelianProdukController::class, 'destroy'])->name('pembelian-produk.destroy');
Route::put('/pembelian-produk/{id}/status', [PembelianProdukController::class, 'updateStatus'])->name('pembelian-produk.updateStatus');



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

Route::prefix('promo')->group(function () {
    Route::get('/', [PromoController::class, 'index'])->name('promo.index');
    Route::get('/{id}', [PromoController::class, 'show'])->name('promo.show');
    Route::post('/store', [PromoController::class, 'store'])->name('promo.store');
    Route::put('/{id}', [PromoController::class, 'update'])->name('promo.update');
});

Route::prefix('kompensasi')->group(function () {
    Route::get('/', [KompensasiController::class, 'index'])->name('kompensasi.index'); // Menampilkan daftar kompensasi
    Route::post('/', [KompensasiController::class, 'store'])->name('kompensasi.store'); // Menambahkan kompensasi baru
    Route::put('/{id}', [KompensasiController::class, 'update'])->name('kompensasi.update'); // Memperbarui kompensasi
});

Route::prefix('komplain')->group(function () {
    Route::get('/', [KomplainController::class, 'index'])->name('komplain.index'); // Menampilkan daftar komplain
    Route::get('/{id}', [KomplainController::class, 'show'])->name('komplain.show'); // Menampilkan detail komplain
    Route::put('/{id}', [KomplainController::class, 'update'])->name('komplain.update'); // Memperbarui komplain
});

Route::get('/kompensasi-diberikan', [KompensasiDiberikanController::class, 'index'])->name('kompensasi-diberikan.index');
Route::post('/kompensasi-diberikan', [KompensasiDiberikanController::class, 'store'])->name('kompensasi-diberikan.store');

Route::get('/pembayaran-treatment', [PembayaranTreatmentController::class, 'index'])->name('pembayaran-treatment.index');
Route::get('pembayaran-treatment/invoice/{id}', [PembayaranTreatmentController::class, 'generateInvoice'])->name('invoice.pembayaran-treatment');
Route::get('pembayaran-treatment/{id}', [PembayaranTreatmentController::class, 'show'])->name('pembayaran-treatment.show');
Route::post('/pembayaran-treatment', [PembayaranTreatmentController::class, 'store'])->name('pembayaran-treatment.store');
Route::put('/pembayaran-treatment/{id}', [PembayaranTreatmentController::class, 'update'])->name('pembayaran-treatment.update');
Route::put('pembayaran-treatment/{id}/konfirmasi', [PembayaranTreatmentController::class, 'confirmPaymentTreatment'])->name('pembayaran-treatment.confirm');




Route::prefix('pembayaran-produk')->group(function () {
    Route::get('/', [PembayaranProdukController::class, 'index'])->name('pembayaran-produk.index'); // Tampilkan data pembayaran produk
    Route::post('/', [PembayaranProdukController::class, 'store'])->name('pembayaran-produk.store'); // Tambah pembayaran produk
    Route::put('/{id}', [PembayaranProdukController::class, 'update'])->name('pembayaran-produk.update'); // Update pembayaran produk
    Route::get('/invoice/{id}', [PembayaranProdukController::class, 'generateInvoice'])->name('invoice.pembayaran-produk');
});
// Route::put(
//     'pembayaran-produk/{id}/konfirmasi',
//     [PembayaranProdukController::class, 'confirmPayment']
// )->name('pembayaran-produk.confirm');




Route::get('rekam-medis/{id}/export-pdf', [RekamMedisController::class, 'exportPdf'])->name('rekam-medis.export-pdf');
Route::get('/rekam-medis', [RekamMedisController::class, 'rekamMedis'])->name('rekam-medis.index');
Route::get('/rekam-medis/{id}', [RekamMedisController::class, 'rekamMedisDetail'])->name('rekam-medis.detail');

Route::get('/laporan-penjualan-treatment', [LaporanController::class, 'indexTreatment'])->name('laporan-treatment.index');
Route::get('/laporan-treatment-hari', [LaporanController::class, 'laporanHarianTreatment'])->name('laporan-treatment.harian');
Route::get('/laporan-treatment-bulan', [LaporanController::class, 'laporanBulananTreatment'])->name('laporan-treatment.bulanan');
Route::get('/laporan-treatment/export-harian', [LaporanController::class, 'exportHarianTreatment'])->name('laporan-treatment.export-harian');
Route::get('/laporan-treatment/export-bulanan', [LaporanController::class, 'exportBulananTreatment'])->name('laporan-treatment.export-bulanan');


Route::get('/laporan-penjualan-produk', [LaporanController::class, 'indexProduk'])->name('laporan-produk.index');
Route::get('/laporan-produk-hari', [LaporanController::class, 'laporanHarianProduk'])->name('laporan-produk.harian');
Route::get('/laporan-produk-bulan', [LaporanController::class, 'laporanBulananProduk'])->name('laporan-produk.bulanan');
Route::get('/laporan-produk/export-harian', [LaporanController::class, 'exportHarianProduk'])->name('laporan-produk.export-harian');
Route::get('/laporan-produk/export-bulanan', [LaporanController::class, 'exportBulananProduk'])->name('laporan-produk.export-bulanan');



// Route::get('/inventaris-stok', [InventarisStokController::class, 'index'])->name('inventaris-stok.index');





// Route::get('/jadwal-dokter', [JadwalDokterController::class, 'index'])->name('jadwal-dokter.index');
// Route::post('/jadwal-dokter', [JadwalDokterController::class, 'store'])->name('jadwal-dokter.store');
// Route::put('/jadwal-dokter/{id}', [JadwalDokterController::class, 'update'])->name('jadwal-dokter.update');
// Route::delete('/jadwal-dokter/{id}', [JadwalDokterController::class, 'destroy'])->name('jadwal-dokter.destroy');

// Route::get('/jadwal-beautician', function () {
//     return view('jadwal.jadwalbeautician');
// })->name('beautician.jadwalbeautician');
