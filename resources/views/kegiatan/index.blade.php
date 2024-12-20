@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('kegiatan/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah
                    {{ session('role') == 'dosen' ? 'Kegiatan Non-JTI' : '' }}</button>
            </div>
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
                    <label for="filterTipeKegiatan">Filter Tipe Kegiatan:</label>
                    <select id="filterTipeKegiatan" class="form-control">
                        <option value="">Semua</option>
                        @foreach ($tipe_kegiatan as $item)
                            <option value="{{ $item['tipeKegiatan'] }}">{{ $item['tipeKegiatan'] }} |
                                {{ $item['isJti'] ? 'JTI' : 'Non-JTI' }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Tabel Data --}}
            <table class="table table-bordered table-striped table-hover table-sm" id="table_kegiatan">
                <thead>
                    <tr>
                        <th>Nomor</th>
                        <th>Judul kegiatan</th>
                        <th>Tipe kegiatan</th>
                        <th>Tanggal Mulai</th>
                        <th>Status</th>
                        <th>Lokasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    {{-- Modal untuk Ajax --}}
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false"
        data-width="75%" aria-hidden="true"></div>
@endsection

@push('js')
    {{-- CDN SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- DataTables Script --}}
    <script>
        // Modal untuk aksi AJAX
        // function modalAction(url = '') {
        //     $('#myModal').load(url, function() {
        //         $('#myModal').modal('show');
        //     });
        // }

        // DataTables Server-Side
        var dataKegiatan;
        $(document).ready(function() {
            var dataKegiatan = $('#table_kegiatan').DataTable({
                processing: true,
                serverSide: false, // Disable server-side processing
                ajax: {
                    url: "{{ url('kegiatan/list') }}",
                    type: "POST",
                },
                columns: [{
                        data: "DT_RowIndex",
                        className: "text-center"
                    },
                    {
                        data: "judul",
                        width: "20%",
                        className: "text-center"
                    },

                    {
                        data: "tipeKegiatan",
                        className: "text-center",
                        render: function(data, type, row) {
                            return `<small class='badge ${row?.isJti ? 'badge-success' : 'badge-primary'}'>${data} | ${row?.isJti ? 'JTI' : ' Non-JTI'}</small>`;
                        },
                    },
                    {
                        data: "tanggalMulai",
                        className: "text-center",
                        render: function(data, type, row) {
                            // Parse the ISO 8601 string into a Date object
                            var date = new Date(data);

                            // Format the date part: "d MMM yyyy"
                            var day = date.getUTCDate().toString().padStart(2,
                                '0'); // Ensure two digits for day

                            // Array of abbreviated month names
                            var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug',
                                'Sep', 'Oct', 'Nov', 'Dec'
                            ];
                            var month = months[date.getMonth()]; // Get the abbreviated month name

                            var year = date.getUTCFullYear();

                            // Format the time part: "H:m"
                            var hours = date.getUTCHours().toString().padStart(2,
                                '0'); // Ensure two digits for hours
                            var minutes = date.getUTCMinutes().toString().padStart(2,
                                '0'); // Ensure two digits for minutes

                            // Return the formatted date and time as "d MMM yyyy, H:m"
                            return day + ' ' + month + ' ' + year + ', ' + hours + ':' + minutes;
                        },
                    },
                    {
                        data: "isDone",
                        className: "text-center",
                        render: function(data, type, row) {
                            return `<small class='badge ${data ? 'badge-success' : 'badge-warning'}'>${data ? 'Selesai' : 'Belum Selesai'}</small>`;
                        },
                    },
                    {
                        data: "lokasi",
                        className: "text-center"
                    },
                    {
                        data: "aksi",
                        className: "text-center"
                    },
                ],
            });

            // Dropdown filter for Tipe Kegiatan
            $('#filterTipeKegiatan').on('change', function() {
                var filterValue = $(this).val(); // Get the filter value
                if (filterValue === "") {
                    dataKegiatan.column(2).search("").draw(); // Clear the filter
                } else {
                    dataKegiatan.column(2).search(filterValue).draw(); // Apply filter to column 3
                }
            });
        });
    </script>
@endpush
