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
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/kegiatan/' . $id . '/anggota_delete_ajax/') }}" method="POST" id="form-delete">
        <input type="hidden" name="userId" value="{{ $penugasan['userId'] }}">
        @csrf
        @method('DELETE')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Hapus Data Penugasan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <h5><i class="icon fas fa-ban"></i> Konfirmasi !!!</h5>
                        Apakah Anda ingin menghapus data seperti di bawah ini?
                    </div>
                    <table class="table table-sm table-bordered table-striped">
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
                            <th class="text-right col-3">Jabatan:</th>
                            <td class="col-9">{{ $penugasan['namaJabatan'] }}</td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                    <button type="submit" class="btn btn-primary">Ya, Hapus</button>
                </div>
            </div>
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
                                location.reload();
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
