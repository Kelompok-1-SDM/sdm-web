@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                Detail data {{ $userType }}
            </h3>
            <div class="card-tools">
                @if (session('role') == 'admin')
                    <button onclick="modalAction('{{ url('' . $userType . '/' . $user['userId'] . '/edit_ajax') }}')"
                        class="btn btn-sm btn-warning mt-1">Edit</button>
                    @if (session('user_id') != $user['userId'])
                        <button onclick="modalAction('{{ url('' . $userType . '/' . $user['userId'] . '/delete_ajax') }}')"
                            class="btn btn-sm btn-danger mt-1">Hapus</button>
                    @endif
                @endif


            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover table-sm">
                <tr>
                    <th>ID</th>
                    <td>{{ $user['userId'] }}</td>
                </tr>
                <tr>
                    <th>NIP</th>
                    <td>{{ $user['nip'] }}</td>
                </tr>
                <tr>
                    <th>Nama</th>
                    <td>{{ $user['nama'] }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $user['email'] }}</td>
                </tr>
                @if ($user['profileImage'] != '')
                    <tr>
                        <th>Foto Profil</th>
                        <td>
                            <img class='direct-chat-img' style='float: none;' src='{{ $user['profileImage'] }}'
                                alt='Ini gambar'>
                        </td>
                    </tr>
                @endif
            </table>

        </div>
    </div>
    @empty($kegiatan)
    @else
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Kegiatan yang dilakukan</h3>
            </div>
            <div class="card-body">
                {{-- Tampilkan Notifikasi Sukses atau Error --}}
                @if (session('success'))
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: '{{ session('success') }}',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    </script>
                @endif

                @if (session('error'))
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: '{{ session('error') }}',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    </script>
                @endif

                <div class="row mb-2">
                    <div class="col-md-3">
                        <label for="filterTipeKegiatan">Filter tipe kegiatan:</label>
                        <select id="filterTipeKegiatan" class="form-control">
                            <option value="">Semua</option>
                            <option value="jti">JTI</option>
                            <option value="non-jti">Non JTI</option>
                        </select>
                    </div>
                </div>

                {{-- Tabel Data --}}
                <table class="table table-bordered table-striped table-hover table-sm" id="table_kegiatan">
                    <thead>
                        <tr>
                            <th class="text-center">Nomor</th>
                            <th class="text-center">Judul kegiatan</th>
                            <th class="text-center">Tipe kegiatan</th>
                            <th class="text-center">Tanggal Mulai</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Jabatan</th>
                            <th class="text-center">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    @endempty
    
    {{-- {{dd($statistik)}} --}}

    @empty($statistik)
    @else
        <section class="col-lg">
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
        </section>
    @endempty

    {{-- Modal untuk Ajax --}}
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false"
        data-width="75%" aria-hidden="true"></div>
@endsection

@push('js')
    {{-- CDN SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('adminlte/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ asset('adminlte/plugins/chart.js/Chart.min.js') }}"></script>


    {{-- DataTables Script --}}
    <script>
        var kegiatanData = @json($kegiatan);
        const data = @json($statistik);
        var baseUrl = "{{ url('/') }}"; // This sets the base URL globally
        // Modal untuk aksi AJAX
        // function modalAction(url = '') {
        //     $('#myModal').load(url, function() {
        //         $('#myModal').modal('show');
        //     });
        // }

        $(document).ready(function() {
            // Initialize DataTables with dynamic data
            @if ($kegiatan)
                if (kegiatanData) {
                    var dataKegiatan = $('#table_kegiatan').DataTable({
                        data: kegiatanData, // Use the JSON data passed from Blade
                        columns: [{
                                data: null,
                                className: 'text-center',
                                render: (data, type, row, meta) => meta.row + 1
                            }, // Nomor
                            {
                                data: 'judul',
                                className: 'text-center',
                                render: function(data, type, row) {
                                    return `<a href='${baseUrl}/kegiatan/${row['kegiatanId']}/detail' class="text-primary">${data}</a>`;
                                }
                            }, // Nama
                            {
                                data: "tipeKegiatan",
                                className: "text-center",
                                render: function(data, type, row) {
                                    return `<small class='badge ${data === 'jti' ? 'badge-success' : 'badge-warning'}'>${data}</small>`;
                                },
                            }, // Email
                            {
                                data: "tanggalMulai",
                                className: "text-center",
                                render: function(data, type, row) {
                                    // Parse the ISO 8601 string into a Date object
                                    var date = new Date(data);

                                    // Format the date part: "d MMM yyyy"
                                    var day = date.getDate().toString().padStart(2,
                                        '0'); // Ensure two digits for day
                                    var month = date.toLocaleString('default', {
                                        month: 'short'
                                    }); // Abbreviated month
                                    var year = date.getFullYear();

                                    // Format the time part: "H:m"
                                    var hours = date.getHours().toString().padStart(2,
                                        '0'); // Ensure two digits for hours
                                    var minutes = date.getMinutes().toString().padStart(2,
                                        '0'); // Ensure two digits for minutes

                                    // Return the formatted date and time as "d MMM yyyy, H:m"
                                    return day + ' ' + month + ' ' + year + ', ' + hours + ':' +
                                        minutes;
                                },
                            }, // Jabatan
                            {
                                data: "isDone",
                                className: "text-center",
                                render: function(data, type, row) {
                                    return `<small class='badge ${data ? 'badge-success' : 'badge-warning'}'>${data ? 'Selesai' : 'Belum Selesai'}</small>`;
                                },
                            },
                            {
                                data: 'jabatan',
                                className: 'text-center',
                                render: function(data, type, row) {
                                    var badgeClass = row.isPic ? 'badge-success' : 'badge-primary';
                                    return `<small class="badge ${badgeClass}">${data} - ${row.isPic ? 'PIC' : 'Anggota'}</small>`;
                                },
                            }, // Jabatan
                            {
                                data: 'kegiatanId',
                                className: 'text-center',
                                render: function(data, type, row) {
                                    return `
                        <a class="btn btn-sm btn-info" href="${baseUrl}/kegiatan/${data}/detail">Detail</a>`;
                                },
                            }, // Aksi
                        ],
                        paging: true, // Enable pagination
                        pageLength: 10, // Items per page
                        lengthChange: true, // Allow user to change page length
                        searching: true, // Enable search
                        ordering: true, // Enable column sorting
                        info: true, // Show table info (e.g., "Showing 1 to 10 of 50 entries")
                    });
                }
            @endif


            $('#filterTipeKegiatan').on('change', function() {
                var filterValue = $(this).val(); // Get selected filter value

                // Clear all custom filters
                $.fn.dataTable.ext.search = [];

                if (filterValue !== "") {
                    // Add a custom filter for the selected value
                    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                        var tipeKegiatan = data[2]
                            .trim(); // Get raw column data (trim to avoid extra spaces)
                        return tipeKegiatan === filterValue; // Show rows matching the filter
                    });
                }

                // Redraw the table to apply filters
                dataKegiatan.draw();
            });

            @empty($statistik)
            @else
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
            @endempty
        });
    </script>
@endpush
