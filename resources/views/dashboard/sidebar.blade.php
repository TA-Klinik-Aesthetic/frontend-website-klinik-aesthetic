<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center">
        <div class="mx-3">Klinik Aesthetic</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ url('dashboard') }}">
            <span>Dashboard</span>
        </a>
    </li>


    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Produk -->
    <li class="nav-item">
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
    </li>


    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Konsultasi -->
    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" data-target="#collapseKonsultasi" aria-expanded="true"
            aria-controls="collapseKonsultasi">
            <span>Konsultasi</span>
        </a>
        <div id="collapseKonsultasi" class="collapse" aria-labelledby="headingKonsultasi"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ url('/konsultasi/without-doctor') }}">Konsultasi Pelanggan</a>
                <a class="collapse-item" href="{{ url('/konsultasi/with-doctor') }}">Booking Konsultasi</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Treatment -->
    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" data-target="#collapseTreatment" aria-expanded="true"
            aria-controls="collapseTreatment">
            <span>Treatment</span>
        </a>
        <div id="collapseTreatment" class="collapse" aria-labelledby="headingTreatment" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <!-- Menggunakan nama rute -->
                <a class="collapse-item" href="{{ route('jenisTreatment.index') }}">Jenis Treatment</a>
                <a class="collapse-item" href="{{ route('treatment.index') }}">List Treatment</a>
                <a class="collapse-item" href="{{ route('detailBooking.index') }}">Booking Treatment</a>
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
                <a class="collapse-item" href="{{ url('/produk') }}">List Produk</a>
                <a class="collapse-item" href="{{ route('pembelianProduk.index') }}">Penjualan Produk</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Feedback -->
    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" data-target="#collapsePembayaran" aria-expanded="true"
            aria-controls="collapsePembayaran">
            <span>Pembayaran</span>
        </a>
        <div id="collapsePembayaran" class="collapse" aria-labelledby="headingPembayaran"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <!-- Menggunakan nama rute -->
                <a class="collapse-item" href="{{ route('pembayaran-treatment.index') }}">Pembayaran
                    Treatment</a>
                <a class="collapse-item" href="{{ route('pembayaran-produk.index') }}">Pembayaran Produk</a>
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
                <a class="collapse-item" href="{{ route('feedback.feedbackTreatment.index') }}">Feedback
                    Treatment</a>
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
                <a class="collapse-item" href="{{ route('kompensasi-diberikan.index') }}">Kompensasi Diberikan</a>
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

    <!-- Nav Item - Promo -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('rekam-medis.index') }}">
            <span>Rekam Medis</span>
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
                <a class="collapse-item" href="{{ route('laporan-treatment.index') }}">Penjualan Treatment</a>
                <a class="collapse-item" href="{{ route('laporan-produk.index') }}">Penjualan Produk</a>
            </div>
        </div>
    </li>

</ul>
<!-- End of Sidebar -->
