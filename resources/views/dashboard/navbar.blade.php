<!-- Topbar -->

<style>
    /* custom pale-orange button */
    .btn-pale {
        background-color: #F3A14B !important;
        border-color: #F3A14B !important;
        color: #fff !important;
    }

    .btn-pale:hover,
    .btn-pale:focus {
        background-color: #d18d3f !important;
        /* varian gelap */
        border-color: #d18d3f !important;
        color: #fff !important;
    }
</style>

<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">


        <!-- Nav Item - Admin Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Admin</span>
            </a>
            <!-- Dropdown - Admin Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>

    </ul>

</nav>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Logout</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin logout?
            </div>
            <div class="modal-footer">
                <form action="{{ route('logout') }}" method="POST" class="w-100">
                    @csrf
                    <button type="button" class="btn btn-pale" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-pale">Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- End of Topbar -->
