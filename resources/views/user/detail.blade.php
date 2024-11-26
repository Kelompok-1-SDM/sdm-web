@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                Detail data {{ $userType }}
            </h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('' . $userType . '/' . $user['userId'] . '/edit_ajax') }}')"
                    class="btn btn-sm btn-warning mt-1">Edit</button>
                @if (session('user_id') != $user['userId'])
                    <button onclick="modalAction('{{ url('' . $userType . '/' . $user['userId'] . '/delete_ajax') }}')"
                        class="btn btn-sm btn-danger mt-1">Hapus</button>
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

    @empty($user['kompetensi'])
    @else
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Kompetensi yang dimiliki</h3>
                <div class="card-tools">
                    <button
                        onclick="modalAction('{{ url('' . $userType . '/' . $user['userId'] . '/tambah_kompetensi_ajax') }}')"
                        class="btn btn-sm btn-primary mt-1">Tambah Kompetensi</button>
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
                <table class="table table-bordered table-striped table-hover table-sm" id="table_kompetensi">
                    <thead>
                        <tr>
                            <th class="text-center">Nomor</th>
                            <th class="text-center">Nama Kompetensi</th>
                            <th class="text-center">Pilih</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    @endempty

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
        var kompetensiData = @json($user['kompetensi']);
        var baseUrl = "{{ url('/') }}"; // This sets the base URL globally
        // Modal untuk aksi AJAX
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }

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

            @if ($user['kompetensi'])
                var dataKompetensi = $('#table_kompetensi').DataTable({
                    data: kompetensiData, // Use the JSON data passed from Blade
                    columns: [{
                            data: null,
                            className: 'text-center',
                            render: (data, type, row, meta) => meta.row + 1
                        }, // Nomor
                        {
                            data: 'namaKompetensi',
                            className: 'text-center',
                        }, // Nama
                        {
                            data: 'kompetensiId',
                            className: 'text-center',
                            render: function(data, type, row) {
                                return `
                        <input type="checkbox" class="delete-checkbox" data-kompetensi-id="${data}" />
                    `;
                            },
                        } // Aksi
                    ],
                    paging: true, // Enable pagination
                    pageLength: 10, // Items per page
                    lengthChange: true, // Allow user to change page length
                    searching: true, // Enable search
                    ordering: true, // Enable column sorting
                    info: true, // Show table info (e.g., "Showing 1 to 10 of 50 entries")
                });

                // Track selected kompetensi IDs
                let selectedKompetensi = new Set();

                // Add event listener to checkboxes
                $('#table_kompetensi').on('change', '.delete-checkbox', function() {
                    const kompetensiId = $(this).data('kompetensi-id');
                    if ($(this).is(':checked')) {
                        selectedKompetensi.add(kompetensiId);
                    } else {
                        selectedKompetensi.delete(kompetensiId);
                    }

                    // Find the nearest parent card-tools within table_kompetensi
                    const cardTools = $('#table_kompetensi').closest('.card').find('.card-tools');

                    // Show or hide the "Hapus" button based on selection
                    if (selectedKompetensi.size > 0) {
                        if (!cardTools.find('#delete-batch-btn').length) {
                            cardTools.append(`
                <button id="delete-batch-btn" class="btn btn-sm btn-danger mt-1">Hapus</button>
            `);
                        }
                    } else {
                        cardTools.find('#delete-batch-btn').remove();
                    }
                });


                // Handle batch delete button click
                $(document).on('click', '#delete-batch-btn', function() {
                    Swal.fire({
                        title: 'Hapus Kompetensi?',
                        text: "Apakah Anda yakin ingin menghapus kompetensi yang dipilih?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const url =
                                `${baseUrl}/{{ $userType }}/{{ $user['userId'] }}/delete_kompetensi_user`;
                            $.ajax({
                                url: url, // Endpoint for batch delete
                                type: 'POST',
                                contentType: 'application/json',
                                data: JSON.stringify({
                                    kompetensiIds: Array.from(selectedKompetensi)
                                }),
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
                                            .reload(); // Reload the table after success
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Gagal!',
                                            text: response.message,
                                            showConfirmButton: true,
                                        });
                                    }
                                }
                            });
                        }
                    });
                });
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
        });
    </script>
@endpush
