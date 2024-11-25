<form action="{{ url('/Agenda/store_ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Agenda</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Jadwal Agenda</label>
                    <input value="" type="date" name="jadwalAgenda" id="jadwalAgenda" class="form-control"
                        required>
                    <small id="error-jadwalAgenda" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama Agenda</label>
                    <input value="" type="text" name="namaAgenda" id="namaAgenda" class="form-control"
                        required>
                    <small id="error-namaAgenda" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <input value="" type="text" name="deskripsi" id="deskripsi" class="form-control"
                        required>
                    <small id="error-deskripsi" class="error-text form-text text-danger"></small>
                </div>
                {{-- <div class="form-group">
                    <label>Status</label>
                    <input value="" type="text" name="status" id="status" class="form-control"
                        required>
                    <small id="error-status" class="error-text form-text text-danger"></small>
                </div> --}}
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="">- Pilih Sttus -</option>
                        <option value="jalan" {{ isset($agenda['status']) && $agenda['status'] == 'jalan' ? 'selected' : '' }}>Jalan</option>
                        <option value="selesai" {{ isset($agenda['status']) && $agenda['status'] == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    <small id="error-status" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $("#form-tambah").validate({
            rules: {
                namaAgenda: {
                    required: true,
                    minlength: 3,
                    maxlength: 255
                },
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
                            dataAgenda.ajax.reload();
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
