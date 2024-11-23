@empty($manajemen)
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
                <a href="{{ url('/manajemen') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail data Manajemen</h5>
                <button type="button" class="close" data-dismiss="modal" aria label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th>ID</th>
                        <td>{{ $manajemen['userId'] }}</td>
                    </tr>
                    <tr>
                        <th>NIP</th>
                        <td>{{ $manajemen['nip'] }}</td>
                    </tr>
                    <tr>
                        <th>Nama</th>
                        <td>{{ $manajemen['nama'] }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $manajemen['email'] }}</td>
                    </tr>
                    @if ($manajemen['profileImage'] != '')
                        <tr>
                            <th>Foto Profil</th>
                            <td>
                                <img class='direct-chat-img' style='float: none;' src='{{ $manajemen['profileImage'] }}'
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
