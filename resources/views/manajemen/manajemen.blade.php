@extends('layouts.template')

@section('content')
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-6">
            </div>
            <div class="col-6 text-right">
                <a href="#" class="btn btn-primary">Import</a>
                <a href="#" class="btn btn-primary">Tambah</a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">Daftar user yang tersimpan dalam sistem</h3>
                        <!-- Search bar -->
                        <div class="position-relative ml-auto" style="width: 200px;">
                            <input type="text" id="nameSearch" class="form-control pr-5" placeholder="Search by name">
                            <i class="fas fa-search position-absolute"
                                style="top: 50%; right: 10px; transform: translateY(-50%);"></i>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">

                        <table id="userTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Level</th>
                                    <th>NIP</th>
                                    <th>Email</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data pengguna dimasukkan secara manual -->
                                <tr>
                                    <td>183</td>
                                    <td>Indasiarta</td>
                                    <td>Admin</td>
                                    <td>21313132313</td>
                                    <td>john@polinema.ac.id</td>
                                    <td>
                                        <a href="#" class="btn btn-info btn-sm">Detail</a>
                                        <a href="#" class="btn btn-warning btn-sm">Edit</a>
                                        <button class="btn btn-danger btn-sm">Hapus</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>219</td>
                                    <td>Mukti Wibawa</td>
                                    <td>Manajemen</td>
                                    <td>321324324</td>
                                    <td>alexander@polinema.ac.id</td>
                                    <td>
                                        <a href="#" class="btn btn-info btn-sm">Detail</a>
                                        <a href="#" class="btn btn-warning btn-sm">Edit</a>
                                        <button class="btn btn-danger btn-sm">Hapus</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>657</td>
                                    <td>Boby Juanda</td>
                                    <td>Manajemen</td>
                                    <td>3441341</td>
                                    <td>bob@polinema.ac.id</td>
                                    <td>
                                        <a href="#" class="btn btn-info btn-sm">Detail</a>
                                        <a href="#" class="btn btn-warning btn-sm">Edit</a>
                                        <button class="btn btn-danger btn-sm">Hapus</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>175</td>
                                    <td>MikeDiva</td>
                                    <td>Manajemen</td>
                                    <td>12313233434</td>
                                    <td>mike@polinema.ac.id</td>
                                    <td>
                                        <a href="#" class="btn btn-info btn-sm">Detail</a>
                                        <a href="#" class="btn btn-warning btn-sm">Edit</a>
                                        <button class="btn btn-danger btn-sm">Hapus</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Inisialisasi DataTables
            var table = $('#userTable').DataTable({
                "responsive": true,
                "autoWidth": false,
                "searching": false // Menonaktifkan pencarian global default
            });

            // Pencarian nama secara manual
            $('#nameSearch').on('keyup', function() {
                table.columns(1).search(this.value).draw(); // Pencarian hanya pada kolom "User"
            });
        });
    </script>
@endpush
