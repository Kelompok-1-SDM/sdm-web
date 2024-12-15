@empty($tipekegiatan)
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
    <form action="{{ url('/tipekegiatan/' . $tipekegiatan['tipeKegiatanId'] . '/update_ajax') }}" method="POST"
        id="form-edit">
        @csrf
        @method('POST')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Data Tipe Kegiatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Tipe Kegiatan</label>
                        <input value="{{ $tipekegiatan['tipeKegiatan'] }}" type="text" name="tipe_kegiatan"
                            id="tipe_kegiatan" class="form-control">
                        <small id="error-tipe_kegiatan" class="error-text form-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label for="is_jti">Status selesai</label>
                        <select name="is_jti" id="is_jti" class="form-control" required>
                            <option value="">- Pilih Status -</option>
                            <option value="true" {{ $tipekegiatan['isJti'] ? 'selected' : '' }}>JTI</option>
                            <option value="false" {{ !$tipekegiatan['isJti'] ? 'selected' : '' }}>Non-JTI
                            </option>
                        </select>
                        <small id="error-is_jti" class="error-text form-text text-danger"></small>
                    </div>

                </div>


                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
        </div>
    </form>
    <script>
        $(document).ready(function() {
            $("#form-edit").validate({
                rules: {
                    tipe_kegiatan: {
                        minlength: 3,
                        maxlength: 255
                    },
                    is_jti: {
                        required: true
                    }
                },
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
                                dataTipeKegiatan.ajax.reload();
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
