@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                {{ $data['judulKegiatan'] . ' - ' . date_format(date_create($data['tanggal']), 'd F Y, H:i') }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('kegiatan/'.$data['kegiatanId'].'/edit_ajax') }}')"
                    class="btn btn-sm btn-warning mt-1">Edit</button>
                <button onclick="modalAction('{{ url('kegiatan/'.$data['kegiatanId'].'/delete_ajax') }}')"
                    class="btn btn-sm btn-danger mt-1">Hapus</button>
            </div>
        </div>
        <div class="card-body">
            <p>{{ $data['deskripsi'] }}</p>
            <div class="row">
                @foreach ($data['kompetensi'] as $apalah)
                    <small class="badge badge-secondary m-2">{{ $apalah }}</small>
                @endforeach
            </div>

        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Dosen yang ditugaskan</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('kegiatan/' . $data['kegiatanId'] . '/anggota_create_ajax') }}')"
                    class="btn btn-sm btn-primary mt-1">Tambah Anggota</button>
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
                    <label for="filterTipeAnggota">Filter Tipe Anggota:</label>
                    <select id="filterTipeAnggota" class="form-control">
                        <option value="">Semua</option>
                        <option value="pic">PIC</option>
                        <option value="anggota">Anggota</option>
                    </select>
                </div>
            </div>

            {{-- Tabel Data --}}
            <table class="table table-bordered table-striped table-hover table-sm" id="table_kegiatan">
                <thead>
                    <tr>
                        <th class="text-center">Nomor</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Role</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>


    <div class="row">
        <!-- Left col -->
        <section class="col-lg-7 connectedSortable">
            <!-- TO DO List -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ion ion-clipboard mr-1"></i>
                        Agenda
                    </h3>

                    <div class="card-tools">
                        <ul class="pagination pagination-sm">
                            <li class="page-item"><a href="#" class="page-link">&laquo;</a></li>
                            <li class="page-item"><a href="#" class="page-link">1</a></li>
                            <li class="page-item"><a href="#" class="page-link">2</a></li>
                            <li class="page-item"><a href="#" class="page-link">3</a></li>
                            <li class="page-item"><a href="#" class="page-link">&raquo;</a></li>
                        </ul>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <ul class="todo-list" data-widget="todo-list">
                        @php $apa = 0; @endphp
                        @foreach ($data['agenda'] as $item)
                            <li>
                                <!-- checkbox -->
                                <div class="icheck-primary d-inline ml-2">
                                    <input type="checkbox" value="" name="todo{{ $apa }}"
                                        id="todoCheck{{ $apa }}">
                                    <label for="todoCheck{{ $apa }}"></label>
                                </div>
                                <!-- todo text -->
                                <span class="text">{{ $item['namaAgenda'] }}</span>
                                <!-- Emphasis label -->
                                @php
                                    // Create DateTime object from the agenda's date
$agendaDate = date_create($item['jadwalAgenda']);
// Get the current date and time
$currentDate = new DateTime();
// Determine badge class based on the comparison
$badgeClass = $currentDate > $agendaDate ? 'badge-danger' : 'badge-warning';
                                @endphp
                                <small class="badge {{ $badgeClass }}">
                                    <i class="far fa-clock"></i>
                                    {{ date_format($agendaDate, 'd F Y, H:i') }}
                                </small>
                                <!-- General tools such as edit or delete-->
                                <div class="tools">
                                    <i class="fas fa-edit"></i>
                                    <i class="fas fa-trash-o"></i>
                                </div>
                            </li>
                            @php $apa++; @endphp
                        @endforeach
                    </ul>

                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    <button type="button" class="btn btn-primary float-right"><i class="fas fa-plus"></i> Add
                        item</button>
                </div>
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
                        Lampiran
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

    {{-- Modal untuk Ajax --}}
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false"
        data-width="75%" aria-hidden="true"></div>
@endsection

@push('js')
    {{-- CDN SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    {{-- DataTables Script --}}
    <script>
        var usersData = @json($data['user']);
        var kegiatanId = @json($data['kegiatanId']);
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
                data: usersData, // Use the JSON data passed from Blade
                columns: [{
                        data: null,
                        className: 'text-center',
                        render: (data, type, row, meta) => meta.row + 1
                    }, // Nomor
                    {
                        data: 'nama',
                        className: 'text-center',
                        render: function(data, type, row) {
                            return `<a href="javascript:void(0);" 
               onclick="modalAction('${baseUrl}/kegiatan/anggota_show_ajax?data=${encodeURIComponent(JSON.stringify(row))}')" 
               class="text-primary">${data}</a>`;
                        }
                    }, // Nama
                    {
                        data: 'email',
                        className: 'text-center'
                    }, // Email
                    {
                        data: 'roleKegiatan',
                        className: 'text-center',
                        render: function(data) {
                            var badgeClass = data === 'pic' ? 'badge-success' : 'badge-primary';
                            return `<small class="badge ${badgeClass}">${data}</small>`;
                        },
                    }, // Role
                    {
                        data: 'status',
                        className: 'text-center',
                        render: function(data) {
                            var badgeClass = data === 'selesai' ? 'badge-success' : 'badge-warning';
                            return `<small class="badge ${badgeClass}">${data}</small>`;
                        },
                    }, // Status
                    {
                        data: 'userId',
                        className: 'text-center',
                        render: function(data, type, row) {
                            return `
                        <button class="btn btn-sm btn-warning" onclick="modalAction('${baseUrl}/kegiatan/${kegiatanId}/anggota_edit_ajax?data=${encodeURIComponent(JSON.stringify(row))}')">Edit</button>
                        <button class="btn btn-sm btn-danger" onclick="modalAction('${baseUrl}/kegiatan/${kegiatanId}/anggota_delete_ajax?data=${encodeURIComponent(JSON.stringify(row))}')">Hapus</button>`;
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

            // Dropdown filter for Tipe Kegiatan
            $('#filterTipeAnggota').on('change', function() {
                var filterValue = $(this).val(); // Get the filter value
                if (filterValue === "") {
                    dataKegiatan.column(3).search("").draw(); // Clear the filter
                } else {
                    dataKegiatan.column(3).search(filterValue).draw(); // Apply filter to column 3
                }
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
