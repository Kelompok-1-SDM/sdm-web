@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            @if (session('role') != 'dosen')
                <div class="card-tools">
                    <button onclick="modalAction('{{ url('tipekegiatan/create_ajax') }}')"
                        class="btn btn-sm btn-success mt-1">Tambah</button>
                </div>
            @endif
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
            <table class="table table-bordered table-striped table-hover table-sm" id="table_tipekegiatan">
                <thead>
                    <tr>
                        <th>Nomor</th>
                        <th>Tipe Kegiatan</th>
                        <th>JTI</th>
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
        var dataTipeKegiatan;
        $(document).ready(function() {
            dataTipeKegiatan = $('#table_tipekegiatan').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: "{{ url('tipekegiatan/list') }}",
                    type: "POST",
                },
                columns: [{
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "tipeKegiatan",
                        className: "text-center",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'isJti',
                        className: 'text-center',
                        render: function(data, type, row) {
                            var badgeClass = row.isJti ? 'badge-success' : 'badge-primary';
                            return `<small class="badge ${badgeClass}">${data ? 'JTI' : 'Non-JTI'}</small>`;
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
