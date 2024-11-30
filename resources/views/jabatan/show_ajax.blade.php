<<<<<<< HEAD:resources/views/dosen/show_ajax.blade.php
@empty($dosen)
=======
@empty($jabatan)
>>>>>>> 24444c93d92e7571389c3cf7db92cf1f91e5f3c5:resources/views/jabatan/show_ajax.blade.php
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan
                </div>
<<<<<<< HEAD:resources/views/dosen/show_ajax.blade.php
                <a href="{{ url('/dosen') }}" class="btn btn-warning">Kembali</a>
=======
                <button type="button" data-dismiss="modal" class="btn btn-warning">Kembali</button>
>>>>>>> 24444c93d92e7571389c3cf7db92cf1f91e5f3c5:resources/views/jabatan/show_ajax.blade.php
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
<<<<<<< HEAD:resources/views/dosen/show_ajax.blade.php
                <h5 class="modal-title" id="exampleModalLabel">Detail data Dosen</h5>
=======
                <h5 class="modal-title" id="exampleModalLabel">Detail data Manajemen</h5>
>>>>>>> 24444c93d92e7571389c3cf7db92cf1f91e5f3c5:resources/views/jabatan/show_ajax.blade.php
                <button type="button" class="close" data-dismiss="modal" aria label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
<<<<<<< HEAD:resources/views/dosen/show_ajax.blade.php
                        <th>ID</th>
                        <td>{{ $dosen['userId'] }}</td>
                    </tr>
                    <tr>
                        <th>NIP</th>
                        <td>{{ $dosen['nip'] }}</td>
                    </tr>
                    <tr>
                        <th>Nama</th>
                        <td>{{ $dosen['nama'] }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $dosen['email'] }}</td>
                    </tr>
                    @if ($dosen['profileImage'] != '')
                        <tr>
                            <th>Foto Profil</th>
                            <td>
                                <img class='direct-chat-img' style='float: none;' src='{{ $dosen['profileImage'] }}'
                                    alt='Ini gambar'>
                            </td>
                        </tr>
                    @endif
=======
                        <th>Jabatan ID</th>
                        <td>{{ $jabatan['jabatanId'] }}</td>
                    </tr>
                    <tr>
                        <th>Nama Jabatan</th>
                        <td>{{ $jabatan['namaJabatan'] }}</td>
                    </tr>
                    <tr>
                        <th>Role</th>
                        <td><small
                                class="badge {{ $jabatan['isPic'] ? 'badge-success' : 'badge-primary' }}">{{ $jabatan['isPic'] ? 'PIC' : 'Anggota' }}</small>
                        </td>
                    </tr>
>>>>>>> 24444c93d92e7571389c3cf7db92cf1f91e5f3c5:resources/views/jabatan/show_ajax.blade.php
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Kembali</button>
            </div>
        </div>
    </div>
@endempty
