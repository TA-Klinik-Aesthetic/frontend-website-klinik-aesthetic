@extends('dashboard.index')

@section('content')
    <style>
        .dataTables_filter {
            text-align: right !important;
        }

        .dataTables_filter label {
            display: inline-flex;
            align-items: center;
            white-space: nowrap;
        }

        .dataTables_filter label input {
            margin-left: .5rem;
        }

        .dataTables_wrapper .dataTables_paginate .btn {
            background-color: #F3A14B !important;
            border-color: #F3A14B !important;
            color: #fff !important;
        }

        .dataTables_wrapper .dataTables_paginate .btn:hover {
            background-color: #d18d3f !important;
            border-color: #d18d3f !important;
        }

        .btn-pale {
            background: #F3A14B !important;
            border-color: #F3A14B !important;
            color: #fff !important;
        }

        .btn-pale:hover {
            background: #d18d3f !important;
            border-color: #d18d3f !important;
            color: #fff !important;
        }
    </style>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif


    <h1 class="h3 mb-2 text-gray-800">Paket Treatment</h1>

    <button type="button" class="btn btn-pale mb-3" data-toggle="modal" data-target="#tambahPaketModal">
        <i class="fas fa-plus"></i> Tambah Paket
    </button>

    <div class="card shadow mb-4">
        <div class="card-body">
            <table id="listPaketTreatmentTable" class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th style="display:none">ID</th>
                        <th>Nama Paket</th>
                        <th>Harga</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pakets as $p)
                        @php
                            $id = $p['id_paket_treatment'] ?? ($p['id'] ?? null);
                            $nama = $p['nama_paket_treatment'] ?? ($p['nama_paket'] ?? '-');
                            $harga = $p['harga_paket_treatment'] ?? ($p['harga_paket'] ?? 0);
                            $desc = $p['deskripsi_paket_treatment'] ?? ($p['deskripsi'] ?? '-');
                        @endphp
                        <tr>
                            <td style="display:none">{{ $id }}</td>
                            <td>{{ $nama }}</td>
                            <td>Rp{{ number_format($harga, 0, ',', '.') }}</td>
                            <td class="text-truncate" style="max-width: 420px;">
                                {{ \Illuminate\Support\Str::limit($desc, 140) }}
                            </td>
                            <td>
                                <a href="{{ route('paketTreatment.show', $id) }}" class="btn btn-pale mb-2">Detail</a>
                                <button type="button" class="btn btn-pale mb-2" data-toggle="modal"
                                    data-target="#editPaketModal" onclick="populateEditModal({{ json_encode($p) }})">
                                    Edit
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Paket -->
    <div class="modal fade js-reset-on-show" id="tambahPaketModal" tabindex="-1" role="dialog"
        aria-labelledby="tambahPaketModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form action="{{ route('paketTreatment.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahPaketModalLabel">Tambah Paket Treatment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- ⬇⬇ name disesuaikan ke controller --}}
                    <div class="form-group">
                        <label>Nama Paket</label>
                        <input type="text" name="nama_paket_treatment" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi_paket_treatment" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Harga Paket</label>
                        <input type="number" name="harga_paket_treatment" class="form-control" min="0" required>
                    </div>

                    <hr>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Detail Paket</h6>
                        <button type="button" class="btn btn-pale btn-outline-pale" id="btnAddRowCreate">Tambah
                            Baris</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm" id="tblDetailsCreate">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width:60%">Treatment</th>
                                    <th style="width:20%">Jumlah Penggunaan</th>
                                    <th style="width:20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody><!-- via JS --></tbody>
                        </table>
                    </div>
                    <small class="text-muted">Minimal 1 baris detail.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-pale">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Paket -->
    <div class="modal fade" id="editPaketModal" tabindex="-1" role="dialog" aria-labelledby="editPaketModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form id="editPaketForm" method="POST" class="modal-content">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPaketModalLabel">Edit Paket Treatment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- ⬇⬇ name disesuaikan ke controller --}}
                    <div class="form-group">
                        <label>Nama Paket</label>
                        <input type="text" name="nama_paket_treatment" id="edit_nama_paket" class="form-control"
                            required>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi (opsional)</label>
                        <textarea name="deskripsi_paket_treatment" id="edit_deskripsi_paket" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Harga Paket</label>
                        <input type="number" name="harga_paket_treatment" id="edit_harga_paket" class="form-control"
                            min="0" required>
                    </div>

                    <hr>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Detail Paket</h6>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnAddRowEdit">Tambah
                            Baris</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm" id="tblDetailsEdit">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width:60%">Treatment</th>
                                    <th style="width:20%">Jumlah Penggunaan</th>
                                    <th style="width:20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody><!-- prefilled via JS --></tbody>
                        </table>
                    </div>
                    <small class="text-muted">Minimal 1 baris detail.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-pale">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            const TREATMENTS = @json($treatments);

            function buildDetailRow(index, preset = {}) {
                const tr = document.createElement('tr');
                tr.innerHTML = `
      <td>
        <select name="details[${index}][id_treatment]" class="form-control" required>
          <option value="">-- pilih treatment --</option>
          ${TREATMENTS.map(t => {
            const id  = t.id_treatment ?? t.id;
            const nm  = t.nama_treatment ?? t.nama;
            const sel = (preset.id_treatment && +preset.id_treatment === +id) ? 'selected' : '';
            return `<option value="${id}" ${sel}>${nm}</option>`;
          }).join('')}
        </select>
      </td>
      <td>
        <input type="number" name="details[${index}][jumlah_penggunaan]" class="form-control" min="1" required value="${preset.jumlah_penggunaan ?? ''}">
      </td>
      <td class="text-center">
        <button type="button" class="btn btn-sm btn-outline-danger btn-del">Hapus</button>
      </td>`;
                tr.querySelector('.btn-del').addEventListener('click', () => {
                    tr.remove();
                    reindex(tr.closest('tbody'));
                });
                return tr;
            }

            function reindex(tbody) {
                [...tbody.querySelectorAll('tr')].forEach((tr, i) => {
                    tr.querySelectorAll('select, input').forEach(el => {
                        el.name = el.name.replace(/details\[(.*?)\]/, `details[${i}]`);
                    });
                });
            }

            (function() {
                const tbody = document.querySelector('#tblDetailsCreate tbody');
                const addBtn = document.getElementById('btnAddRowCreate');

                function ensureOneRow() {
                    tbody.innerHTML = '';
                    tbody.appendChild(buildDetailRow(0));
                }

                addBtn.addEventListener('click', () => {
                    tbody.appendChild(buildDetailRow(tbody.children.length));
                });

                $('#tambahPaketModal').on('show.bs.modal', ensureOneRow);
                ensureOneRow();
            })();

            function populateEditModal(paket) {
                const form = document.getElementById('editPaketForm');
                const id = paket.id_paket_treatment ?? paket.id;
                form.action = `/paket-treatment/${id}`;

                document.getElementById('edit_nama_paket').value = paket.nama_paket_treatment ?? paket.nama_paket ?? '';
                document.getElementById('edit_deskripsi_paket').value = paket.deskripsi_paket_treatment ?? paket.deskripsi ??
                '';
                document.getElementById('edit_harga_paket').value = paket.harga_paket_treatment ?? paket.harga_paket ?? 0;

                const tbody = document.querySelector('#tblDetailsEdit tbody');
                tbody.innerHTML = '';
                const details = paket.details ?? [];
                if (!details.length) {
                    tbody.appendChild(buildDetailRow(0));
                    return;
                }
                details.forEach((d, i) => {
                    const preset = {
                        id_treatment: d.id_treatment ?? (d.treatment ? d.treatment.id_treatment : null),
                        jumlah_penggunaan: d.jumlah_penggunaan ?? 1
                    };
                    tbody.appendChild(buildDetailRow(i, preset));
                });
            }

            document.getElementById('btnAddRowEdit').addEventListener('click', () => {
                const tbody = document.querySelector('#tblDetailsEdit tbody');
                tbody.appendChild(buildDetailRow(tbody.children.length));
            });

            $(document).ready(function() {
                $('#listPaketTreatmentTable').DataTable({
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
                    columnDefs: [{
                        targets: 0,
                        visible: false,
                        searchable: false
                    }],
                    order: [
                        [0, 'desc']
                    ],
                    drawCallback: function() {
                        $('.dataTables_wrapper .dataTables_paginate a').each(function() {
                            $(this).removeClass('paginate_button').addClass(
                                'btn btn-sm btn-outline-primary mx-1');
                        });
                    }
                });
            });
        </script>
    @endpush
@endsection
