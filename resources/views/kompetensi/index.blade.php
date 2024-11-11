@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/kompetensi/import') }}')" class="btn btn-sm btn-info mt-1">Import
                    Kompetensi</button>
                <a href="{{ url('/kompetensi/export_excel') }}" class="btn btn-sm btn-primary mt-1"><i class="fa fa-file-excel"></i>
                    Export Kompetensi (Excel)</a>
                <a href="{{ url('/kompetensi/export_pdf') }}" class="btn btn-sm btn-warning mt-1"><i class="fa fa-file-pdf"></i>
                    Export Kompetensi (PDF)</a>
                <button onclick="modalAction('{{ url('kompetensi/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah</button>
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
                        <th>Nomor</th>
                        <th>Nama kompetensi</th>
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
        var dataKompetensi;
        $(document).ready(function() {
            dataKompetensi = $('#table_kompetensi').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('kompetensi/list') }}",
                    type: "POST",
                },
                columns: [{
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "namaKompetensi",
                        className: "text-center",
                        orderable: true,
                        searchable: true
                    },
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
