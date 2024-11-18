@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                {{ $data['judulKegiatan'] . ' - ' . date_format(date_create($data['tanggal']), 'd F Y, H:i') }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('kegiatan/create_ajax') }}')"
                    class="btn btn-sm btn-warning mt-1">Edit</button>
                <button onclick="modalAction('{{ url('kegiatan/create_ajax') }}')"
                    class="btn btn-sm btn-danger mt-1">Hapus</button>
            </div>
        </div>
        <div class="card-body">
            <p>{{ $data['deskripsi'] }}</p>
            <div class="row">
                @foreach ($data['kompetensi'] as $apalah)
                    @php
                        // Define an array of color classes
                        $badgeColors = ['badge-primary', 'badge-danger', 'badge-warning', 'badge-secondary'];
                        // Randomly select a color from the array
                        $randomColor = $badgeColors[array_rand($badgeColors)];
                    @endphp
                    <small class="badge {{ $randomColor }} m-2">{{ $apalah }}</small>
                @endforeach
            </div>

        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Dosen yang ditugaskan</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('kegiatan/create_ajax') }}')"
                    class="btn btn-sm btn-primary mt-1">Tambah</button>
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
                        <th>No</th>
                        <th>User</th>
                        <th>NIP</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
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
                                <small class="badge badge-warning">
                                    <i class="far fa-clock"></i>
                                    {{ date_format(date_create($item['jadwalAgenda']), 'd F Y, H:i') }}
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
                    url: "{{ url('kegiatan/detailUser') }}",
                    type: "POST",
                    data: function(d) {
                        const path = window.location.pathname;
                        const apa = path.split('/');
                        d.uid = apa[apa.length - 2];
                    }
                },
                columns: [{
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "nama",
                        className: "text-center",
                        orderable: true,
                        width: "10%",
                        searchable: true
                    },
                    {
                        data: "nip",
                        className: "text-center",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "email",
                        className: "text-center",
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: "roleKegiatan",
                        className: "text-center",
                        orderable: true,
                        searchable: true,
                        render: function(data, type, raw) {
                            if (data == 'pic') {
                                return `<small class='badge badge-success'>${data}</small>`
                            } else {
                                return `<small class='badge badge-primary'>${data}</small>`
                            }

                        }
                    },
                    {
                        data: "status",
                        className: "text-center",
                        orderable: true,
                        searchable: true,
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
