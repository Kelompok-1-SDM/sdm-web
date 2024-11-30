@empty($admin)
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
                <a href="{{ url('/admin') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail data Admin</h5>
                <button type="button" class="close" data-dismiss="modal" aria label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th>ID</th>
                        <td>{{ $admin['userId'] }}</td>
                    </tr>
                    <tr>
                        <th>NIP</th>
                        <td>{{ $admin['nip'] }}</td>
                    </tr>
                    <tr>
                        <th>Nama</th>
                        <td>{{ $admin['nama'] }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $admin['email'] }}</td>
                    </tr>
                    @if ($admin['profileImage'] != '')
                        <tr>
                            <th>Foto Profil</th>
                            <td>
                                <img class='direct-chat-img' style='float: none;' src='{{ $admin['profileImage'] }}'
                                    alt='Ini gambar'>
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Kembali</button>
            </div>
        </div>
    </div>
@endempty
