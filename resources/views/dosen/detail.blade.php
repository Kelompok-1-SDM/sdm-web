@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                Detail Data Dosen
            </h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('dosen/' . $dosen['userId'] . '/edit_ajax') }}')"
                    class="btn btn-sm btn-warning mt-1">Edit</button>
                <button onclick="modalAction('{{ url('dosen/' . $dosen['userId'] . '/delete_ajax') }}')"
                    class="btn btn-sm btn-danger mt-1">Hapus</button>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover table-sm">
                <tr>
                    <th>ID</th>
                    <td>{{ $dosen['userId'] }}</td>
                </tr>
                <tr>
                    <th>NIP</th>
                    <td>{{ $dosen['nip'] }}</td>
                </tr>
                <tr>
                    <th>Nama</th>
                    <td>{{ $dosen['nama'] }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $dosen['email'] }}</td>
                </tr>
            </table>
            <div class="row">
                @foreach ($dosen['kompetensi'] as $apalah)
                    <small class="badge badge-secondary m-2">{{ $apalah['namaKompetensi'] }}</small>
                @endforeach
            </div>

        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Kegiatan pada dosen</h3>
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
                        <th class="text-center">Detail</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
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
        var kegiatanData = @json($kegiatan);
        var baseUrl = "{{ url('/') }}"; // This sets the base URL globally
        // Modal untuk aksi AJAX
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }

        $(document).ready(function() {
            // Initialize DataTables with dynamic data
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
                            return day + ' ' + month + ' ' + year + ', ' + hours + ':' + minutes;
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
        });

        // Example functions for edit and delete actions
        function editUser(userId) {
            alert(`Edit user with ID: ${userId}`);
            // Implement modal or AJAX call for editing
        }

        function deleteUser(userId) {
            if (confirm('Are you sure you want to delete this user?')) {
                alert(`Delete user with ID: ${userId}`);
                // Implement AJAX call for deletion
            }
        }

        // Example functions for edit and delete actions
        function editUser(userId) {
            alert(`Edit user with ID: ${userId}`);
            // Implement modal or AJAX call for editing
        }

        function deleteUser(userId) {
            if (confirm('Are you sure you want to delete this user?')) {
                alert(`Delete user with ID: ${userId}`);
                // Implement AJAX call for deletion
            }
        }
    </script>
@endpush
