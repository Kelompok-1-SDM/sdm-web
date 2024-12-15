@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                {{ $data['judul'] }}
                <small
                    class='badge ml-2 {{ $data['isJti'] ? 'badge-success' : 'badge-warning' }}'>{{ $data['tipeKegiatan'] }} |
                    {{ $data['isJti'] ? 'JTI' : 'Non-JTI' }}</small>
            </h3>
            <div class="card-tools">
                @if (session('role') != 'dosen')
                    <button
                        onclick="modalAction('{{ url('kegiatan/edit_ajax?data=' . urlencode(json_encode(array_diff_key($data, array_flip(['lampiran', 'agenda', 'users']))))) }}')"
                        class="btn btn-sm btn-warning mt-1">Edit</button>
                    <button
                        onclick="modalAction('{{ url(
                            'kegiatan/delete_ajax?data=' .
                                urlencode(json_encode(array_diff_key($data, array_flip(['lampiran', 'agenda', 'users'])))),
                        ) }}')"
                        class="btn btn-sm btn-danger mt-1">Hapus</button>
                @endif
            </div>

        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover table-sm">
                <tr>
                    <th>Taggal Mulai</th>
                    <td>{{ \Carbon\Carbon::parse($data['tanggalMulai'])->format('d F Y, H:i') }}</td>
                </tr>
                <tr>
                    <th>Taggal Akhir</th>
                    <td>{{ \Carbon\Carbon::parse($data['tanggalAkhir'])->format('d F Y, H:i') }}</td>
                </tr>
                <tr>
                    <th>Lokasi</th>
                    <td>{{ $data['lokasi'] }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td><small
                            class='badge {{ $data['isDone'] ? 'badge-success' : 'badge-warning' }}'>{{ $data['isDone'] ? 'Selesai' : 'Belum Selesai' }}</small>
                    </td>
                </tr>
            </table>
            <h4 class="mt-3">Deskripsi kegiatan</h4>
            <p>{{ $data['deskripsi'] }}</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Progress Kegiatan</h3>

            @if (session('role') != 'dosen' || $isPic)
                <div class="card-tools">
                    <button
                        onclick="modalAction('{{ url('kegiatan/edit_progress_ajax?data=' . urlencode(json_encode(array_diff_key($data, array_flip(['lampiran', 'agenda', 'users']))))) }}')"
                        class="btn btn-sm btn-warning mt-1">Edit</button>
                </div>
            @endif

        </div>
        <div class="card-body">
            <p>{{ $data['progress'] }}</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Dosen yang ditugaskan</h3>
            <div class="card-tools">
                @if (session('role') != 'dosen' || ($isPic && !$data['isJti']))
                    <button onclick="modalAction('{{ url('kegiatan/' . $data['kegiatanId'] . '/anggota_create_ajax') }}')"
                        class="btn btn-sm btn-primary mt-1">Tambah Anggota</button>
                    @if (session('role') != 'dosen')
                        <a href="{{ url('kegiatan/' . $data['kegiatanId'] . '/surat_tugas') }}" class="btn btn-primary"><i
                                class="fa fa-file-excel"></i> Generate surat tugas</a>
                    @endif
                @endif
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
                        @foreach ($jabatan as $item)
                            <option value="{{ $item['namaJabatan'] }}">{{ $item['namaJabatan'] }}</option>
                        @endforeach
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
                        <th class="text-center">Jabatan</th>
                        @if (session('role') != 'dosen' || ($isPic && !$data['isJti']))
                            <th class="text-center">Aksi</th>
                        @endif

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
                        </ul>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <ul class="todo-list" id="agenda-list" data-widget="todo-list"></ul>
                </div>
                <!-- /.card-body -->
                @if (session('role') != 'dosen' || $isPic)
                    <div class="card-footer clearfix">
                        <button type="button" class="btn btn-primary float-right"
                            onclick="modalAction('{{ url('kegiatan/' . $data['kegiatanId'] . '/agenda_create_ajax') }}')"><i
                                class="fas fa-plus"></i> Tambah Agenda</button>
                    </div>
                @endif
            </div>
            <!-- /.card -->
        </section>
        <!-- /.Left col -->
        <!-- right col (We are only adding the ID to make the widgets sortable)-->
        <section class="col-lg-5 connectedSortable">
            <div class="card bg-gradient-white">
                <div class="card-header border-0">
                    <h3 class="card-title text-dark">
                        <i class="fas fa-th mr-1"></i>
                        Lampiran
                    </h3>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach ($data['lampiran'] as $lampiran)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <!-- Lampiran URL -->
                                    <a href="{{ $lampiran['url'] }}" target="_blank" class="text-primary">
                                        <strong>{{ $lampiran['nama'] }}</strong>
                                    </a>
                                    <br>
                                    <!-- Timestamps -->
                                    <small class="text-muted">
                                        Uploaded {{ \Carbon\Carbon::parse($lampiran['createdAt'])->format('d F Y, H:i') }}
                                    </small>
                                </div>
                                <!-- Actions -->
                                @if (session('role') != 'dosen' || $isPic)
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-danger delete-lampiran"
                                            data-id="{{ $lampiran['lampiranId'] }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                @endif

                            </li>
                        @endforeach
                    </ul>
                </div>
                @if (session('role') != 'dosen' || $isPic)
                    <div class="card-footer text-right">
                        <button type="button" class="btn btn-success add-lampiran"
                            onclick="modalAction('{{ url('kegiatan/' . $data['kegiatanId'] . '/lampiran_create_ajax') }}')">
                            <i class="fas fa-plus"></i> Tambah Lampiran
                        </button>
                    </div>
                @endif
            </div>
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
        var usersData = @json($data['users']);
        var kegiatanId = @json($data['kegiatanId']);
        var userId = @json(session('user_id'))

        var baseUrl = "{{ url('/') }}"; // This sets the base URL globally
        // Modal untuk aksi AJAX
        // function modalAction(url = '') {
        //     $('#myModal').load(url, function() {
        //         $('#myModal').modal('show');
        //     });
        // }

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
                        data: 'namaJabatan',
                        className: 'text-center',
                        render: function(data, type, row) {
                            var badgeClass = row.isPic ? 'badge-success' : 'badge-primary';
                            return `<small class="badge ${badgeClass}">${data} - ${row.isPic ? 'PIC' : 'Anggota'}</small>`;
                        },
                    }, // Jabatan
                    @if (session('role') != 'dosen' || ($isPic && !$data['isJti']))
                        {
                            data: 'userId',
                            className: 'text-center',
                            render: function(data, type, row) {
                                if (row.userId == userId) {
                                    return ``
                                }
                                return `
                        <button class="btn btn-sm btn-warning" onclick="modalAction('${baseUrl}/kegiatan/${kegiatanId}/anggota_edit_ajax?data=${encodeURIComponent(JSON.stringify(row))}')">Edit</button>
                        <button class="btn btn-sm btn-danger" onclick="modalAction('${baseUrl}/kegiatan/${kegiatanId}/anggota_delete_ajax?data=${encodeURIComponent(JSON.stringify(row))}')">Hapus</button>`;
                            },
                        }, // Aksi
                    @endif
                ],
                paging: true, // Enable pagination
                pageLength: 10, // Items per page
                lengthChange: true, // Allow user to change page length
                searching: true, // Enable search
                ordering: true, // Enable column sorting
                info: true, // Show table info (e.g., "Showing 1 to 10 of 50 entries")
            });

            // TODO
            @if (session('role') != 'dosen' || $isPic)
                $('.delete-lampiran').on('click', function() {
                    const lampiranId = $(this).data('id'); // Get the Lampiran ID
                    const deleteUrl =
                        `${baseUrl}/kegiatan/${lampiranId}/lampiran_delete_ajax`; // Construct the delete URL

                    Swal.fire({
                        title: 'Hapus Lampiran?',
                        text: "Apakah Anda yakin ingin menghapus lampiran ini?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: deleteUrl, // Use the constructed DELETE URL
                                type: 'DELETE', // Use DELETE method
                                success: function(response) {
                                    if (response.status) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Berhasil!',
                                            text: response.message,
                                            showConfirmButton: false,
                                            timer: 3000
                                        });
                                        location
                                            .reload(); // Reload the page to update the list
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Gagal!',
                                            text: response.message,
                                            showConfirmButton: true,
                                        });
                                    }
                                },
                                error: function(xhr, status, error) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: 'Terjadi kesalahan saat menghapus lampiran.',
                                        showConfirmButton: true,
                                    });
                                }
                            });
                        }
                    });
                });
            @endif

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

        document.addEventListener('DOMContentLoaded', function() {
            const agenda = @json($data['agenda']); // Your agenda data from the API
            const itemsPerPage = 10;
            let currentPage = 1;

            // Function to render the agenda list
            function renderAgenda(page = 1) {
                const agendaList = document.getElementById('agenda-list');
                agendaList.innerHTML = '';

                const start = (page - 1) * itemsPerPage;
                const end = start + itemsPerPage;
                const pageItems = agenda.slice(start, end);

                pageItems.forEach((item, index) => {
                    const agendaDate = new Date(item.jadwalAgenda);
                    const badgeClass = new Date() > agendaDate ? 'badge-danger' : 'badge-warning';
                    const isChecked = item.isDone ? 'checked disabled' : 'disabled';

                    agendaList.innerHTML += `
                    <li>
                        <div class="icheck-primary d-inline ml-2">
                            <input type="checkbox" name="todo${index}" id="todoCheck${index}" ${isChecked}>
                            <label for="todoCheck${index}"></label>
                        </div>
                        <span class="text">${item.namaAgenda}</span>
                        <small class="badge ${badgeClass}">
                            <i class="far fa-clock"></i> ${new Date(agendaDate).toISOString().slice(0, 16).replace('T', ' ')}
                        </small>
                        <br>
                        <small class="text-muted">
                            ${item.deskripsiAgenda.length > 100 ? item.deskripsiAgenda.substring(0, 100) + '...' : item.deskripsiAgenda}
                        </small>
                        
                        <div class="tools">
                            <a href="${baseUrl}/kegiatan/agenda/${item.agendaId}">
                                <i class="fas fa-edit edit-button"></i>
                            </a>
                        </div>

                    </li>
                `;
                });
            }

            // Function to render pagination
            function renderPagination() {
                const totalPages = Math.ceil(agenda.length / itemsPerPage);
                const pagination = document.getElementById('pagination');
                pagination.innerHTML = '';

                for (let i = 1; i <= totalPages; i++) {
                    pagination.innerHTML += `
                    <li class="page-item ${i === currentPage ? 'active' : ''}">
                        <a href="#" class="page-link" data-page="${i}">${i}</a>
                    </li>
                `;
                }

                // Add event listeners for page links
                document.querySelectorAll('.page-link').forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        currentPage = parseInt(this.dataset.page);
                        renderAgenda(currentPage);
                        renderPagination();
                    });
                });
            }

            // Initial render
            renderAgenda(currentPage);
            renderPagination();

            // Edit button functionality
            document.getElementById('agenda-list').addEventListener('click', function(e) {
                if (e.target.classList.contains('edit-button')) {
                    const agendaId = e.target.dataset.id;
                    window.location.href = `/agenda/${agendaId}/detail`;
                }
            });
        });
    </script>
@endpush
