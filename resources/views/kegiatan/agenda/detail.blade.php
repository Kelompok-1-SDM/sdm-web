@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                {{ $data['namaAgenda'] }}
            </h3>
            <div class="card-tools">
                @if (session('role') != 'dosen' || (isset($data['wasMePic']) && $data['wasMePic']))
                    <button
                        onclick="modalAction('{{ url('kegiatan/' . $data['agendaId'] . '/agenda_edit_ajax?data=' . urlencode(json_encode(array_diff_key($data, array_flip(['progress', 'users']))))) }}')"
                        class="btn btn-sm btn-warning mt-1">Edit</button>
                    <button
                        onclick="modalAction('{{ url('kegiatan/' . $data['agendaId'] . '/agenda_delete_ajax?data=' . urlencode(json_encode(array_diff_key($data, array_flip(['progress', 'users']))))) }}')"
                        class="btn btn-sm btn-danger mt-1">Hapus</button>
                @endif
            </div>

        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover table-sm">
                <tr>
                    <th>Jadwal Agenda</th>
                    <td>{{ date_format(date_create($data['jadwalAgenda']), 'd F Y, H:i') }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td><small
                            class='badge {{ $data['isDone'] ? 'badge-success' : 'badge-warning' }}'>{{ $data['isDone'] ? 'Selesai' : 'Belum Selesai' }}</small>
                    </td>
                </tr>
            </table>
            <h4 class="mt-3">Deskripsi agenda</h4>
            <p>{{ $data['deskripsiAgenda'] }}</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Dosen yang ditugaskan</h3>
            <div class="card-tools">
                @if (session('role') != 'dosen' || (isset($data['wasMePic']) && $data['wasMePic']))
                    <button
                        onclick="modalAction('{{ url('kegiatan/' . $data['kegiatanId'] . '/agenda_anggota_create_ajax?uid_agenda=' . $data['agendaId']) }}')"
                        class="btn btn-sm btn-primary mt-1">Tambah Anggota</button>
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

            {{-- Tabel Data --}}
            <table class="table table-bordered table-striped table-hover table-sm" id="table_users">
                <thead>
                    <tr>
                        <th class="text-center">Nomor</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">Email</th>
                        @if (session('role') != 'dosen' || (isset($data['wasMePic']) && $data['wasMePic']))
                            <th class="text-center">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card bg-gradient-white">
        <div class="card-header border-0">
            <h3 class="card-title text-dark">
                <i class="fas fa-th mr-1"></i>
                Progress
            </h3>
        </div>
        <div class="card-body">
            <ul class="list-group">
                @foreach ($data['progress'] as $progress)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <!-- Progress URL -->
                            <strong class="text-primary"
                                onclick="modalAction('{{ url('kegiatan/agenda_progress_show_ajax?data=' . urlencode(json_encode($progress))) }}')">{{ Str::limit($progress['deskripsiProgress'], 100) }}</strong>
                            <br>
                            <!-- Timestamps -->
                            <small class="text-muted">
                                Updated at {{ \Carbon\Carbon::parse($progress['updatedAt'])->format('d F Y, H:i') }}
                            </small>
                        </div>
                        <!-- Actions -->
                        <div class="btn-group">
                            <button class="btn btn-sm btn-primary"
                                onclick="modalAction('{{ url('kegiatan/' . $data['agendaId'] . '/agenda_progress_edit_ajax?data=' . urlencode(json_encode($progress))) }}')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger delete-progress" data-id="{{ $progress['progressId'] }}">
                                <i class="fas fa-trash"></i>
                            </button>

                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="card-footer text-right">
            <button type="button" class="btn btn-success add-progress"
                onclick="modalAction('{{ url('kegiatan/' . $data['agendaId'] . '/agenda_progress_create_ajax') }}')">
                <i class="fas fa-plus"></i> Tambah Progress
            </button>
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
        var usersData = @json($data['users']);
        var agendaId = @json($data['agendaId']);

        var baseUrl = "{{ url('/') }}"; // This sets the base URL globally
        // Modal untuk aksi AJAX
        // function modalAction(url = '') {
        //     $('#myModal').load(url, function() {
        //         $('#myModal').modal('show');
        //     });
        // }

        $(document).ready(function() {
            // Initialize DataTables with dynamic data
            var dataUsers = $('#table_users').DataTable({
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
                    @if (session('role') != 'dosen' || (isset($data['wasMePic']) && $data['wasMePic']))
                        {
                            data: 'userKegiatanId',
                            className: 'text-center',
                            render: function(data, type, row) {
                                return `
                        <button class="btn btn-sm btn-danger" onclick="modalAction('${baseUrl}/kegiatan/${agendaId}/agenda_anggota_delete_ajax?data=${encodeURIComponent(JSON.stringify(row))}')">Hapus</button>`;
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
        });

        $('.delete-progress').on('click', function() {
            const progressId = $(this).data('id'); // Get the Lampiran ID
            const deleteUrl =
                `${baseUrl}/kegiatan/${progressId}/agenda_progress_delete_ajax`; // Construct the delete URL

            Swal.fire({
                title: 'Hapus Progress?',
                text: "Apakah Anda yakin ingin menghapus progress ini?",
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
                                text: 'Terjadi kesalahan saat menghapus progress.',
                                showConfirmButton: true,
                            });
                        }
                    });
                }
            });
        });
    </script>
@endpush
