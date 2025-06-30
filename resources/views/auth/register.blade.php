@extends('dashboard.index')

@section('content')
    <style>
        /* pastikan kontainer filter benar-benar rata-kanan */
        .dataTables_filter {
            text-align: right !important;
        }

        /* label cukup inline-flex, tidak full-width */
        .dataTables_filter label {
            display: inline-flex;
            align-items: center;
            white-space: nowrap;
        }

        /* jarak antara teks “Search:” dan input */
        .dataTables_filter label input {
            margin-left: 0.5rem;
        }

        /* Contoh: semua paginate button jadi merah solid */
        .dataTables_wrapper .dataTables_paginate .btn {
            background-color: #F3A14B !important;
            /* merah */
            border-color: #F3A14B !important;
            color: #fff !important;
        }

        /* Hover */
        .dataTables_wrapper .dataTables_paginate .btn:hover {
            background-color: #F3A14B !important;
            border-color: #F3A14B !important;
        }
    </style>

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

    <h1 class="h3 mb-4 text-gray-800">Daftar Pelanggan</h1>

    <button class="btn btn-pale mb-3" data-toggle="modal" data-target="#registerModal">
        <i class="fas fa-plus"></i> Tambah Akun Pelanggan
    </button>

    <div class="card shadow mb-4">
        <div class="card-body">
            <table id="laporanAkunPelangganTable" class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>No. Telp</th>
                        <th>Email</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                        <tr>
                            <td>{{ $u['nama_user'] }}</td>
                            <td>{{ $u['no_telp'] }}</td>
                            <td>{{ $u['email'] }}</td>
                            <td>
                                <button class="btn btn-pale btn-warning" data-toggle="modal" data-target="#passwordModal"
                                    onclick="openPasswordModal({{ $u['id_user'] }}, '{{ $u['nama_user'] }}')">
                                    Ubah Password
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada pelanggan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Register --}}
    <div class="modal fade" id="registerModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('akun.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Daftar Pelanggan Baru</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama_user" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>No. Telepon</label>
                        <input type="text" name="no_telp" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button class="btn btn-primary">Daftar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Ubah Password --}}
    <div class="modal fade" id="passwordModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="passwordForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Ubah Password: <span id="passwordUserName"></span></h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div id="passwordError" class="alert alert-danger d-none"></div>
                        <div class="form-group">
                            <label>Password Baru</label>
                            <input type="password" id="new_password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Konfirmasi Password</label>
                            <input type="password" id="new_password_confirmation" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentUserId = null;

        function openPasswordModal(id, name) {
            currentUserId = id;
            document.getElementById('passwordUserName').textContent = name;
            document.getElementById('passwordError').classList.add('d-none');
            document.getElementById('new_password').value = '';
            document.getElementById('new_password_confirmation').value = '';
        }

        document.getElementById('passwordForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const pass = document.getElementById('new_password').value;
            const pass2 = document.getElementById('new_password_confirmation').value;
            const errDiv = document.getElementById('passwordError');
            errDiv.classList.add('d-none');
            errDiv.innerHTML = '';

            fetch(`/akun-pelanggan/${currentUserId}/update-password`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        password: pass,
                        password_confirmation: pass2
                    })
                })
                .then(r => r.json().then(json => ({
                    status: r.status,
                    body: json
                })))
                .then(({
                    status,
                    body
                }) => {
                    if (status === 200) {
                        $('#passwordModal').modal('hide');
                        alert(body.message);
                    } else {
                        // jika ada errors dari API
                        if (body.errors) {
                            for (let field in body.errors) {
                                body.errors[field].forEach(msg => {
                                    errDiv.innerHTML += `<div>${msg}</div>`;
                                });
                            }
                        }
                        // jika tidak ada errors, tampilkan message
                        else if (body.message) {
                            errDiv.textContent = body.message;
                        }
                        errDiv.classList.remove('d-none');
                    }
                })
                .catch(err => {
                    errDiv.textContent = 'Terjadi kesalahan jaringan.';
                    errDiv.classList.remove('d-none');
                    console.error(err);
                });
        });
    </script>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#laporanAkunPelangganTable').DataTable({
                responsive: true,
                pageLength: 25,
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ],
                pagingType: 'simple_numbers',
                dom: "<'row mb-2'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 text-right'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row mt-2'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 text-right'p>>",
                drawCallback: function(settings) {
                    // styling ulang pagination setiap draw
                    $('.dataTables_wrapper .dataTables_paginate a').each(function() {
                        $(this)
                            .removeClass('paginate_button')
                            .addClass('btn btn-sm btn-outline-primary mx-1');
                    });
                }
            });
        });
    </script>
@endpush
