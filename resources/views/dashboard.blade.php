@extends('layouts.template')

@section('content')
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        @if (session('role') != 'dosen')
            <div class="row">
                <div class="col-lg-4 col-8">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $data['jumlahDosen'] }}</h3>

                            <p>Jumlah Dosen</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="{{ url('/dosen') }}" class="small-box-footer">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                @if (session('role') == 'admin')
                    <div class="col-lg-4 col-8">
                        <!-- small box -->
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $data['jumlahManajemen'] }}</h3>

                                <p>Jumlah Manajemen</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                            <a href="{{ url('/manajemen') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                @endif
                <!-- ./col -->
                <div class="col-lg-4 col-8">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $data['jumlahKegiatan'] }}</h3>

                            <p>Kegiatan</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="{{ url('/kegiatan') }}" class="small-box-footer">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
        @endif


        @if (session('role') == 'dosen')
            <!-- Section: Jumlah Tugas Bulan Sekarang -->
            <div class="row mt-4">
                <!-- Jumlah Tugas -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white d-flex align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-tasks mr-2"></i> Jumlah Tugas Bulan Ini
                            </h5>
                            <span class="badge bg-warning ml-auto p-2">
                                {{ $dosen['jumlahTugasBulanSekarang']['count'] }} Tugas
                            </span>
                        </div>
                    </div>
                </div>

                <!-- 2 Tugas Terbaru -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-clipboard-list mr-2"></i> 2 Tugas Terbaru
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach ($dosen['duaTugasTerbaru'] as $tugas)
                                    <div class="col-lg-6 col-md-12">
                                        <div class="card border-info mb-3 shadow-sm">
                                            <div class="card-body">
                                                <h5 class="card-title text-info">
                                                    <a href="{{ url('kegiatan/' . $tugas['kegiatanId'] . '/detail') }}"><i
                                                            class="fas fa-file-alt mr-2"></i> {{ $tugas['judul'] }}</a>
                                                </h5>
                                                <br>
                                                <p class="mb-2">
                                                    <strong><i class="fas fa-map-marker-alt mr-1"></i> Lokasi:</strong>
                                                    {{ $tugas['lokasi'] }}
                                                </p>
                                                <p class="mb-2">
                                                    <strong><i class="fas fa-calendar-alt mr-1"></i> Tanggal Mulai:</strong>
                                                    {{ date('d-m-Y', strtotime($tugas['tanggalMulai'])) }}
                                                </p>
                                                <p>
                                                    <strong><i class="fas fa-info-circle mr-1"></i> Deskripsi:</strong>
                                                    {{ $tugas['deskripsi'] }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @if (isset($dosen['tugasBerlangsung']))
                    <!-- Section: Tugas yang Sedang Berlangsung -->
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title">Tugas yang Sedang Berlangsung</h5>
                            </div>
                            <div class="card-body">

                                <h5 class="card-title">{{ $dosen['tugasBerlangsung']['judul'] }}</h5>
                                <p class="card-text"><strong>Lokasi:</strong> {{ $dosen['tugasBerlangsung']['lokasi'] }}
                                </p>
                                <p class="card-text"><strong>Tanggal Mulai:</strong>
                                    {{ date('d-m-Y', strtotime($dosen['tugasBerlangsung']['tanggalMulai'])) }}</p>
                                <p class="card-text"><strong>Deskripsi:</strong>
                                    {{ $dosen['tugasBerlangsung']['deskripsi'] }}
                                </p>
                                <p class="card-text"><strong>Jabatan:</strong>
                                    {{ $dosen['tugasBerlangsung']['namaJabatan'] }}
                                </p>


                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif



        <!-- /.row -->
        <!-- Main row -->
        <div class="row">
            <!-- Left col -->
            @if (session('role') != 'dosen')
                <section class="col-lg-7 connectedSortable">
                    <!-- Custom tabs (Charts with tabs)-->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie mr-1"></i>
                                Peforma Kegiatan
                            </h3>
                            <div class="card-tools">
                                <ul class="nav nav-pills ml-auto">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#revenue-chart" data-toggle="tab">Area</a>
                                    </li>
                                    {{-- <li class="nav-item">
                                    <a class="nav-link" href="#sales-chart" data-toggle="tab">Donut</a>
                                </li> --}}
                                </ul>
                            </div>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content p-0">
                                <!-- Morris chart - Sales -->
                                <div class="chart tab-pane active" id="revenue-chart"
                                    style="position: relative; height: 300px;">
                                    <canvas id="revenue-chart-canvas" height="300"></canvas>
                                </div>
                                <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;">
                                    <canvas id="donut-chart" height="300"></canvas>
                                </div>
                            </div>
                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </section>
            @endif

            <!-- /.Left col -->
            <!-- right col (We are only adding the ID to make the widgets sortable)-->
            <section class="col-lg-5 connectedSortable">

                <!-- /.card -->

                <!-- solid sales graph -->
                <!-- Right col (solid sales graph) -->
                <div class="card bg-gradient-white">
                    <div class="card-header border-0">
                        <h3 class="card-title text-dark">
                            <i class="fas fa-th mr-1"></i>
                            Jumlah Kegiatan Per-Tahun
                        </h3>
                    </div>
                    <div class="card-body">
                        <canvas class="chart" id="line-chart"
                            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>

                <!-- /.card -->
            </section>
            <!-- right col -->
        </div>

        @if (session('role') != 'dosen')
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-users mr-1"></i> List dosen dan jumah kegiatan
                            </h3>
                        </div>
                        <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                            <div class="row">
                                @foreach ($dosen as $dosenItem)
                                    <div class="col-md-4 col-sm-6">
                                        <div class="card mb-3">
                                            <div class="row g-0 align-items-center">
                                                <div class="col-4">
                                                    <img src="{{ $dosenItem['profileImage'] }}" alt="Profile Image"
                                                        class="img-fluid rounded-start"
                                                        style="height: 100px; width: 100px; object-fit: cover;">
                                                </div>
                                                <div class="col-8">
                                                    <div class="card-body p-2">
                                                        <a href="{{ url('dosen/' . $dosenItem['userId'] . '/detail') }}">
                                                            <h5 class="card-title mb-1">{{ $dosenItem['nama'] }}</h5>
                                                        </a>
                                                        <p class="card-text text-muted mb-1">
                                                            <small>{{ $dosenItem['nip'] }}</small>
                                                        </p>
                                                        <p class="card-text">
                                                            <span class="badge bg-info">Total Kegiatan:
                                                                {{ $dosenItem['totalJumlahKegiatan'] }}</span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- /.row (main row) -->
    </div><!-- /.container-fluid -->

    {{-- Modal untuk Ajax --}}
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false"
        data-width="75%" aria-hidden="true"></div>
@endsection

@push('js')
    <!-- ChartJS -->
    <script src="{{ asset('adminlte/plugins/chart.js/Chart.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Extract the data from PHP
            const data = @json($data);

            // Line Chart for Peforma Kegiatan (left chart)
            @if (session('role') != 'dosen')
                const ctxPeforma = document.getElementById('revenue-chart-canvas');
                if (ctxPeforma) {
                    const months = data.peformaKegiatan.results.map(item => {
                        const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep',
                            'Oct', 'Nov', 'Dec'
                        ];
                        return monthNames[item.month - 1];
                    });
                    const avgKegiatan = data.peformaKegiatan.results.map(item => parseFloat(item
                        .avgJumlahKegiatan));

                    new Chart(ctxPeforma.getContext('2d'), {
                        type: 'line',
                        data: {
                            labels: months,
                            datasets: [{
                                label: 'Rata-rata Kegiatan per Bulan',
                                data: avgKegiatan,
                                backgroundColor: 'rgba(53, 162, 235, 0.2)', // Soft blue background
                                borderColor: 'rgba(53, 162, 235, 1)', // Dark blue border for clarity
                                borderWidth: 2,
                                pointBackgroundColor: 'rgba(53, 162, 235, 1)', // Same color for data points
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        color: '#333'
                                    }
                                },
                                x: {
                                    ticks: {
                                        color: '#333'
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    labels: {
                                        color: '#333'
                                    }
                                }
                            }
                        }
                    });
                }

                // Bar Chart for Jumlah Kegiatan Per-Tahun (right chart)
                const ctxJumlahKegiatan = document.getElementById('line-chart');
                if (ctxJumlahKegiatan) {
                    const years = data.jumlahKegiatanPerTahun.results.map(item => item.year);
                    const kegiatanCount = data.jumlahKegiatanPerTahun.results.map(item => item.count);

                    // Use a single color for the bar chart
                    const barColor = 'rgba(255, 99, 132, 0.8)'; // Soft red color for bars

                    new Chart(ctxJumlahKegiatan.getContext('2d'), {
                        type: 'bar',
                        data: {
                            labels: years,
                            datasets: [{
                                label: 'Jumlah Kegiatan per Tahun',
                                data: kegiatanCount,
                                backgroundColor: barColor,
                                borderColor: 'rgba(255, 99, 132, 1)', // Dark red border for clarity
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        color: '#333'
                                    }
                                },
                                x: {
                                    ticks: {
                                        color: '#333'
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    labels: {
                                        color: '#333'
                                    }
                                }
                            }
                        }
                    });
                }
            @endif

            @if (session('role') == 'dosen')
                const ctxJumlahKegiatan = document.getElementById('line-chart');
                if (ctxJumlahKegiatan) {

                    // Ensure jumlahKegiatan is not null and contains data
                    const kegiatanData = data.jumlahKegiatan || [];

                    // Group and sum jumlahKegiatan by year
                    const kegiatanByYear = kegiatanData.reduce((acc, item) => {
                        acc[item.year] = (acc[item.year] || 0) + item.jumlahKegiatan;
                        return acc;
                    }, {});

                    // Extract years and sums
                    const years = Object.keys(kegiatanByYear);
                    const kegiatanCount = Object.values(kegiatanByYear);

                    if (years.length > 0) {
                        // Chart color
                        const barColor = 'rgba(255, 99, 132, 0.8)'; // Soft red

                        // Render chart
                        new Chart(ctxJumlahKegiatan.getContext('2d'), {
                            type: 'bar',
                            data: {
                                labels: years,
                                datasets: [{
                                    label: 'Jumlah Kegiatan per Tahun',
                                    data: kegiatanCount,
                                    backgroundColor: barColor,
                                    borderColor: 'rgba(255, 99, 132, 1)', // Dark red border
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            color: '#333'
                                        }
                                    },
                                    x: {
                                        ticks: {
                                            color: '#333'
                                        }
                                    }
                                },
                                plugins: {
                                    legend: {
                                        labels: {
                                            color: '#333'
                                        }
                                    }
                                }
                            }
                        });
                    } else {
                        // Fallback for no data
                        ctxJumlahKegiatan.innerHTML =
                            '<p style="color: #333; text-align: center;">No data available</p>';
                    }
                }
            @endif
        });
    </script>
@endpush
