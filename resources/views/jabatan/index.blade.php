@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('jabatan/create_ajax') }}')"
                    class="btn btn-sm btn-success mt-1">Tambah</button>
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
            <table class="table table-bordered table-striped table-hover table-sm" id="table_jabatan">
                <thead>
                    <tr>
                        <th>Nomor</th>
                        <th>Nama jabatan</th>
                        <th>Role</th>
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
        var dataJabatan;
        $(document).ready(function() {
            dataJabatan = $('#table_jabatan').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: "{{ url('jabatan/list') }}",
                    type: "POST",
                },
                columns: [{
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "namaJabatan",
                        className: "text-center",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'isPic',
                        className: 'text-center',
                        render: function(data, type, row) {
                            var badgeClass = row.isPic ? 'badge-success' : 'badge-primary';
                            return `<small class="badge ${badgeClass}">${data ? 'PIC' : 'Anggota'}</small>`;
                        },
                    }, // Jabatan
                    {
                        data: "aksi",
                        className: "text-center",
                        orderable: false,
                        width: "15%",
                        searchable: false
                    }
                ]
            });
        });
    </script>
@endpush
