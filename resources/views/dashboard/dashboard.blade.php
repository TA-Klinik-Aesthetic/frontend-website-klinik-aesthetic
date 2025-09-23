@extends('dashboard.index')

@section('content')
    {{-- Definisi kelas “pale” langsung di Blade --}}
    <style>
        .border-left-pale {
            border-left: .25rem solid #F3A14B !important;
        }

        .text-pale {
            color: #F3A14B !important;
        }

        .bg-pale {
            background-color: #F3A14B !important;
            color: #fff !important;
        }
    </style>

    <!-- Begin Page Content -->

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Booking Konsultasi -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-pale shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-pale text-uppercase mb-1">
                                Booking Konsultasi (Verifikasi)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $consultCount }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Treatment -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-pale shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-pale text-uppercase mb-1">
                                Booking Treatment (Verifikasi)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $treatCount }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-procedures fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Booking Treatment Paket -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-pale shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-pale text-uppercase mb-1">
                                Booking Treatment Paket (Verifikasi)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $treatPaketCount }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-pale shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-pale text-uppercase mb-1">
                                Komplain (Belum Dibalas)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $pendingCount }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row mb-4">
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-secondary">Populer Treatment (Top 3)</h6>
                </div>
                <div class="card-body" style="height: 320px;">
                    <canvas id="barTopTreatments" height="300"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-secondary">Populer Produk (Top 3)</h6>
                </div>
                <div class="card-body" style="height: 320px;">
                    <canvas id="barTopProducts" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-secondary">Populer Paket Treatment (Top 3)</h6>
                </div>
                <div class="card-body" style="height: 320px;">
                    <canvas id="barTopPaket" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>


    <!-- Filter Tahun -->
    <form method="GET" class="form-inline mb-4">
        <label class="mr-2 font-weight-bold">Pilih Tahun:</label>
        <select name="year" class="form-control" onchange="this.form.submit()">
            @for ($y = date('Y') - 5; $y <= date('Y') + 1; $y++)
                <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>
                    {{ $y }}
                </option>
            @endfor
        </select>
    </form>

    <!-- Chart Pembayaran Treatment per Bulan -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-secondary">
                        Pembayaran Treatment / Bulan ({{ $year }})
                    </h6>
                </div>
                <div class="card-body" style="height: 300px;">
                    <canvas id="monthlyTreatmentChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Pembayaran Produk per Bulan -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-secondary">
                        Pembayaran Produk / Bulan ({{ $year }})
                    </h6>
                </div>
                <div class="card-body" style="height: 300px;">
                    <canvas id="monthlyProductChart"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Data dari controller
        const treatLabels = {!! json_encode($treatmentLabels) !!};
        const treatData = {!! json_encode($treatmentData) !!};
        const prodLabels = {!! json_encode($productLabels) !!};
        const prodData = {!! json_encode($productData) !!};

        // Hitung max dinamis kelipatan 10
        const maxTreat = treatData.length ?
            Math.ceil(Math.max(...treatData) / 10) * 10 :
            10;
        const maxProd = prodData.length ?
            Math.ceil(Math.max(...prodData) / 10) * 10 :
            10;

        // Chart Pembayaran Treatment
        new Chart(
            document.getElementById('monthlyTreatmentChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: treatLabels,
                    datasets: [{
                        label: 'Jumlah Pembayaran',
                        backgroundColor: '#F3A14B',
                        data: treatData
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                stepSize: 10,
                                max: maxTreat
                            }
                        }]
                    }
                }
            }
        );

        // Chart Pembayaran Produk
        new Chart(
            document.getElementById('monthlyProductChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: prodLabels,
                    datasets: [{
                        label: 'Jumlah Pembayaran',
                        backgroundColor: '#F3A14B',
                        data: prodData
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                stepSize: 10,
                                max: maxProd
                            }
                        }]
                    }
                }
            }
        );
    </script>
@endpush

@push('scripts')
    <script>
        // data dari controller
        const tLabels = @json($topTreatLabels);
        const tValues = @json($topTreatValues);
        const pLabels = @json($topProdLabels);
        const pValues = @json($topProdValues);
        // NEW: paket
        const pkLabels = @json($topPaketLabels);
        const pkValues = @json($topPaketValues);

        function makeBar(id, labels, values) {
            const el = document.getElementById(id);
            if (!el) return;
            new Chart(el.getContext('2d'), {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Total Dibeli',
                        data: values,
                        backgroundColor: '#F3A14B'
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    legend: {
                        display: false
                    },
                    tooltips: {
                        callbacks: {
                            label: function(t, d) {
                                return 'Total: ' + d.datasets[0].data[t.index];
                            }
                        }
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                autoSkip: false,
                                maxRotation: 0,
                                minRotation: 0,
                                callback: function(v) {
                                    return v.length > 22 ? v.slice(0, 22) + '…' : v;
                                }
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                precision: 0
                            }
                        }]
                    }
                }
            });
        }

        makeBar('barTopTreatments', tLabels, tValues);
        makeBar('barTopProducts', pLabels, pValues);
        // NEW: render paket
        makeBar('barTopPaket', pkLabels, pkValues);
    </script>
@endpush
