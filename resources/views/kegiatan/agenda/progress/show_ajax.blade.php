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

                    @if (!empty($progress['attachments']))
                        <tr>
                            <th class="text-right col-3">Attachments:</th>
                            <td>
                                <ul>
                                    @foreach ($progress['attachments'] as $attachment)
                                        <li>
                                            <a href="{{ $attachment['url'] }}" target="_blank">
                                                {{ $attachment['nama'] }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <th class="text-right col-3">Attachments:</th>
                            <p>No attachments available.</p>
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
