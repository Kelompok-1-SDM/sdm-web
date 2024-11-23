@extends('layouts.template')

@section('content')
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
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
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">
            <!-- Left col -->
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

                <!-- Calendar -->
                <div class="card bg-gradient-success">
                    <div class="card-header border-0">

                        <h3 class="card-title">
                            <i class="far fa-calendar-alt"></i>
                            Calendar
                        </h3>
                        <!-- tools card -->
                        <div class="card-tools">
                            <!-- button with a dropdown -->
                            <div class="btn-group">
                                <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown"
                                    data-offset="-52">
                                    <i class="fas fa-bars"></i>
                                </button>
                                <div class="dropdown-menu" role="menu">
                                    <a href="#" class="dropdown-item">Add new event</a>
                                    <a href="#" class="dropdown-item">Clear events</a>
                                    <div class="dropdown-divider"></div>
                                    <a href="#" class="dropdown-item">View calendar</a>
                                </div>
                            </div>
                            <button type="button" class="btn btn-success btn-sm" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-success btn-sm" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <!-- /. tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body pt-0">
                        <!--The calendar -->
                        <div id="calendar" style="width: 100%"></div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </section>
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
        <!-- /.row (main row) -->
    </div><!-- /.container-fluid -->
@endsection

@push('js')
    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('adminlte/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ asset('adminlte/plugins/chart.js/Chart.min.js') }}"></script>
    <!-- Sparkline -->
    {{-- <script src="{{ asset('adminlte/plugins/sparklines/sparkline.js') }}"></script> --}}
    <!-- JQVMap -->
    <script src="{{ asset('adminlte/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{ asset('adminlte/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
    <!-- daterangepicker -->
    <script src="{{ asset('adminlte/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('adminlte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <!-- Summernote -->
    <script src="{{ asset('adminlte/plugins/summernote/summernote-bs4.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('adminlte/dist/js/adminlte.js') }}"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="{{ asset('adminlte/dist/js/pages/dashboard.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Extract the data from PHP
            const data = @json($data);

            // Line Chart for Peforma Kegiatan (left chart)
            const ctxPeforma = document.getElementById('revenue-chart-canvas');
            if (ctxPeforma) {
                const months = data.peformaKegiatan.results.map(item => {
                    const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep',
                        'Oct', 'Nov', 'Dec'
                    ];
                    return monthNames[item.month - 1];
                });
                const avgKegiatan = data.peformaKegiatan.results.map(item => parseFloat(item.avgJumlahKegiatan));

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
        });
    </script>
@endpush
