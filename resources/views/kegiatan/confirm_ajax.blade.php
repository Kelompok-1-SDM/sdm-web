@empty($kegiatan)
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
    <form action="{{ url('/kegiatan/' . $kegiatan['kegiatanId'] . '/delete_ajax') }}" method="POST" id="form-delete">
        @csrf
        @method('DELETE')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Hapus Data kegiatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <h5><i class="icon fas fa-ban"></i> Konfirmasi !!!</h5>
                        Apakah Anda ingin menghapus data seperti di bawah ini?
                    </div>
                    <table class="table table-bordered table-striped table-hover table-sm">
                        <tr>
                            <th>ID</th>
                            <td>{{ $kegiatan['kegiatanId'] }}</td>
                        </tr>
                        <tr>
                            <th>Judul</th>
                            <td>{{ $kegiatan['judul'] }}</td>
                        </tr>
                        <tr>
                            <th>Taggal Mulai</th>
                            <td>{{ date_format(date_create($kegiatan['tanggalMulai']), 'd F Y, H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Taggal Akhir</th>
                            <td>{{ date_format(date_create($kegiatan['tanggalAkhir']), 'd F Y, H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Tipe kegiatan</th>
                            <td><small
                                    class="badge {{ strtolower($kegiatan['isJti']) ? 'badge-success' : 'badge-primary' }}">{{ $kegiatan['tipeKegiatan'] }}
                                    | {{ $kegiatan['isJti'] ? 'JTI' : 'Non-Jti' }}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>Deskripsi</th>
                            <td>{{ $kegiatan['deskripsi'] }}</td>
                        </tr>
                        <tr>
                            <th>Status kegiatan</th>
                            <td><small
                                    class="badge {{ $kegiatan['isDone'] ? 'badge-success' : 'badge-primary' }}">{{ $kegiatan['isDone'] ? 'Selesai' : 'Belum Selesai' }}</small>
                            </td>
                        </tr>
                    </table>

                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                        <button type="submit" class="btn btn-primary">Ya, Hapus</button>
                    </div>
    </form>
    <script>
        $(document).ready(function() {
            $("#form-delete").validate({
                rules: {},
                submitHandler: function(form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function(response) {
                            if (response.status) {
                                $('#myModal').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                });
                                // Go back to the previous page
                                window.history.back();

                                // Reload the page after a short delay (e.g., 500 milliseconds)
                                setTimeout(function() {
                                    location.reload();
                                }, 500); // 500 ms delay

                            } else {
                                $('.error-text').text('');
                                $.each(response.msgField, function(prefix, val) {
                                    $('#error-' + prefix).text(val[0]);
                                });
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan',
                                    text: response.message
                                });
                            }
                        }
                    });
                    return false;
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
@endempty
