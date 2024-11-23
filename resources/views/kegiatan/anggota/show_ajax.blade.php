@empty($penugasan)
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
                <a href="{{ url('/penugasan') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail data Penugasan</h5>
                <button type="button" class="close" data-dismiss="modal" aria label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th class="text-right col-3">ID:</th>
                        <td class="col-9">{{ $penugasan['userId'] }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">NIP:</th>
                        <td class="col-9">{{ $penugasan['nip'] }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Nama:</th>
                        <td class="col-9">{{ $penugasan['nama'] }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Email:</th>
                        <td class="col-9">{{ $penugasan['email'] }}</td>
                    </tr>
                    @if ($penugasan['profileImage'] != '')
                        <tr>
                            <th class="text-right col-3">Foto Profil:</th>
                            <td class="col-9">
                                <img class='direct-chat-img' style='float: none;' src='{{ $penugasan['profileImage'] }}'
                                    alt='Ini gambar'>
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <th class="text-right col-3">Role:</th>
                        <td class="col-9">{{ $penugasan['roleKegiatan'] }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Status:</th>
                        <td class="col-9">{{ $penugasan['status'] }}</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Kembali</button>
            </div>
        </div>
    </div>
@endempty
