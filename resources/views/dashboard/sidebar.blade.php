@php
    // ambil user & token dari session
    $user = session('user', []);
    $role = data_get($user, 'role', '');
    $token = session('token', null);
@endphp

<script>
    // ini akan dicetak sebagai "null" atau sebagai string token yang sudah proper JS-escaped
    console.log('Logged-in user token:', @json($token));
</script>

<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center">
        <div class="mx-3">Klinik Neshnavya</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ url('dashboard') }}">
            <span>Dashboard</span>
        </a>
    </li>

    {{-- HANYA untuk kasir --}}
    @if ($role === 'kasir')
        <hr class="sidebar-divider">

        <!-- Penjualan Produk -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('pembelianProduk.index') }}">
                <span>Penjualan Produk</span>
            </a>
        </li>

        <hr class="sidebar-divider">

        <!-- Penjualan Produk -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('pembayaran-treatment.index') }}">
                <span>Pembayaran Treatment</span>
            </a>
        </li>

        <hr class="sidebar-divider">

        <!-- Penjualan Produk -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('penjualanPaketTreatment.index') }}">
                <span>Pembayaran Treatment (Paket)</span>
            </a>
        </li>


        {{-- <hr class="sidebar-divider">

        <!-- Pembayaran -->
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" data-target="#collapsePembayaran" aria-expanded="true">
                <span>Pembayaran</span>
            </a>
            <div id="collapsePembayaran" class="collapse" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="{{ route('pembayaran-treatment.index') }}">
                        Pembayaran Treatment
                    </a>
                    <a class="collapse-item" href="{{ route('pembayaran-produk.index') }}">
                        Pembayaran Produk
                    </a>
                </div>
            </div>
        </li> --}}
    @elseif ($role === 'front office')
        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Nav Item - Akun Pelanggan -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('register.form') }}">
                {{-- <i class="fas fa-users"></i> --}}
                <span>Akun Pelanggan</span>
            </a>
        </li>


        <!-- Divider -->
        {{-- <hr class="sidebar-divider"> --}}

        <!-- Nav Item - Produk -->
        {{-- <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" data-target="#collapseJadwal" aria-expanded="true"
                aria-controls="collapseJadwal">
                <span>Jadwal</span>
            </a>
            <div id="collapseJadwal" class="collapse" aria-labelledby="headingJadwal" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="{{ url('/jadwal-dokter') }}">Jadwal Dokter</a>
                    <a class="collapse-item" href="{{ url('/jadwal-beautician') }}">Jadwal Beautician</a>
                </div>
            </div>
        </li> --}}

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Nav Item - Promo -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('konsultasi.with-doctor') }}">
                <span>Booking Konsultasi</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Nav Item - Treatment -->
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" data-target="#collapseTreatment" aria-expanded="true"
                aria-controls="collapseTreatment">
                <span>Treatment</span>
            </a>
            <div id="collapseTreatment" class="collapse" aria-labelledby="headingTreatment"
                data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <!-- Menggunakan nama rute -->
                    <a class="collapse-item" href="{{ route('jenisTreatment.index') }}">Jenis Treatment</a>
                    <a class="collapse-item" href="{{ route('treatment.index') }}">Treatment</a>
                    <a class="collapse-item" href="{{ route('paketTreatment.index') }}">Paket Treatment</a>
                    {{-- ⬇️ Pecah jadi 2 baris --}}
                    <a class="collapse-item" href="{{ route('ptp.index') }}">
                        Paket Treatment
                        <span class="d-block">Pelanggan</span>
                    </a>
                    <a class="collapse-item" href="{{ route('bookingTreatment.index') }}">Booking Treatment</a>
                    {{-- ⬇️ Pecah jadi 2 baris --}}
                    <a class="collapse-item" href="{{ route('bookingTreatmentPaket.index') }}">
                        Booking Treatment
                        <span class="d-block">(Paket)</span>
                    </a>                    
                </div>
            </div>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Nav Item - Produk -->
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" data-target="#collapseProduk" aria-expanded="true"
                aria-controls="collapseProduk">
                <span>Produk</span>
            </a>
            <div id="collapseProduk" class="collapse" aria-labelledby="headingProduk" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="{{ url('/kategori') }}">Kategori Produk</a>
                    <a class="collapse-item" href="{{ url('/produk') }}">Produk</a>
                </div>
            </div>
        </li>



        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Nav Item - Feedback -->
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" data-target="#collapseFeedback" aria-expanded="true"
                aria-controls="collapseFeedback">
                <span>Feedback</span>
            </a>
            <div id="collapseFeedback" class="collapse" aria-labelledby="headingFeedback"
                data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <!-- Menggunakan nama rute -->
                    <a class="collapse-item" href="{{ route('feedback.feedbackKonsultasi.index') }}">Feedback
                        Konsultasi</a>
                    {{-- <a class="collapse-item" href="{{ route('feedback.feedbackTreatment.index') }}">Feedback
                        Treatment</a> --}}
                </div>
            </div>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Nav Item - Feedback -->
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" data-target="#collapseKomplain" aria-expanded="true"
                aria-controls="collapseKomplain">
                <span>Komplain</span>
            </a>
            <div id="collapseKomplain" class="collapse" aria-labelledby="headingKomplain"
                data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <!-- Menggunakan nama rute -->
                    <a class="collapse-item" href="{{ route('kompensasi.index') }}">Kompensasi</a>
                    <a class="collapse-item" href="{{ route('komplain.index') }}">Komplain Pelanggan</a>
                    <a class="collapse-item" href="{{ route('kompensasi-diberikan.index') }}">Kompensasi
                        Pelanggan</a>
                </div>
            </div>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Nav Item - Promo -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('promo.index') }}">
                <span>Promo</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Nav Item - Feedback -->
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" data-target="#collapseLaporan" aria-expanded="true"
                aria-controls="collapseLaporan">
                <span>Laporan</span>
            </a>
            <div id="collapseLaporan" class="collapse" aria-labelledby="headingLaporan"
                data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <!-- Menggunakan nama rute -->
                    {{-- <a class="collapse-item" href="{{ route('inventaris-stok.index') }}">Inventaris Stok</a> --}}
                    <a class="collapse-item" href="{{ route('rekam-medis.index') }}">Rekam Medis</a>
                    <a class="collapse-item" href="{{ route('laporan-treatment.index') }}">Penjualan Treatment</a>
                    <a class="collapse-item" href="{{ route('laporan-paket.index') }}">Penjualan Paket Treatment</a>
                    <a class="collapse-item" href="{{ route('laporan-produk.index') }}">Penjualan Produk</a>
                </div>
            </div>
        </li>


        {{-- ================= DOKTER ================= --}}
    @elseif ($role === 'dokter')
        <hr class="sidebar-divider">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('konsultasi.with-doctor') }}">
                <span>Konsultasi</span>
            </a>
        </li>

        <hr class="sidebar-divider">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('bookingTreatment.index') }}">
                <span>Booking Treatment</span>
            </a>
        </li>

        {{-- ================= BEAUTICIAN ================= --}}
    @elseif ($role === 'beautician')
        <hr class="sidebar-divider">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('bookingTreatment.index') }}">
                <span>Booking Treatment</span>
            </a>
        </li>
    @endif
</ul>
<!-- End of Sidebar -->
