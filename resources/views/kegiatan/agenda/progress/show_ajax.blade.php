@empty($progress)
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
                <button type="button" data-dismiss="modal" class="btn btn-warning">Kembali</button>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail data Progress</h5>
                <button type="button" class="close" data-dismiss="modal" aria label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th class="text-right col-3">ID:</th>
                        <td class="col-9">{{ $progress['progressId'] }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Deskripsi Progress:</th>
                        <td class="col-9">{{ $progress['deskripsiProgress'] }}</td>
                    </tr>
                    @if ($progress['profileImage'] != '')
                        <tr>
                            <th class="text-right col-3">Foto Profil:</th>
                            <td class="col-9">
                                <img class='direct-chat-img' style='float: none;' src='{{ $progress['profileImage'] }}'
                                    alt='Ini gambar'>
                            </td>
                        </tr>
                    @endif
                    @if (isset($progress['isPIc']))
                        <tr>
                            <th class="text-right col-3">Jabatan:</th>
                            <td class="col-9"><small
                                    class="badge {{ $progress['isPic'] ? 'badge-success' : 'badge-primary' }}">{{ $progress['namaJabatan'] }}-{{ $progress['isPic'] ? 'PIC' : 'Anggota' }}</small>
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
