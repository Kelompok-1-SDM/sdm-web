@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('kegiatan/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah</button>
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
            <table class="table table-bordered table-striped table-hover table-sm" id="table_kegiatan">
                <thead>
                    <tr>
                        <th>Nomor</th>
                        <th>Judul kegiatan</th>
                        <th>Tanggal</th>
                        <th>Tipe kegiatan</th>
                        <th>Lokasi</th>
                        <th>Deskripsi</th>
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
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }

        // DataTables Server-Side
        var dataKegiatan;
        $(document).ready(function() {
            dataKegiatan = $('#table_kegiatan').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('kegiatan/list') }}",
                    type: "POST",
                },
                columns: [{
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "judulKegiatan",
                        className: "text-center",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "tanggal",
                        className: "text-center",
                        orderable: true,
                        width: "10%",
                        searchable: true,
                        render: function(data, type, row) {
                            if (data) {
                                var date = new Date(data);
                                var year = date.getFullYear();
                                var month = ("0" + (date.getMonth() + 1)).slice(-
                                    2); // Add leading zero
                                var day = ("0" + date.getDate()).slice(-2); // Add leading zero
                                return year + "-" + month + "-" + day; // Format as YYYY-MM-DD
                            }
                            return data; // Return original value if no data
                        }
                    },
                    {
                        data: "tipeKegiatan",
                        className: "text-center",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "lokasi",
                        className: "text-center",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "deskripsi",
                        className: "text-center",
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: "aksi",
                        className: "text-center",
                        orderable: false,
                        width: "5%",
                        searchable: false
                    }
                ]
            });
        });
    </script>
@endpush
