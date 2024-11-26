@empty($current)
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
                <a href="{{ url('/kegiatan') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/kegiatan/' . $id . '/agenda_update_ajax') }}" method="POST" id="form-edit">
        @csrf
        @method('POST')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Agenda Kegiatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Agenda</label>
                        <input type="text" name="nama_agenda" id="nama_agenda" class="form-control"
                            value="{{ $current['namaAgenda'] }}" required>
                        <small id="error-nama_agenda" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Jadwal Agenda</label>
                        <input type="date" name="jadwal_agenda" id="jadwal_agenda" class="form-control"
                            value="{{ $current['jadwalAgenda'] ? \Carbon\Carbon::parse($current['jadwalAgenda'])->format('Y-m-d') : '' }}"
                            required>
                        <small id="error-jadwal_agenda" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi Agenda</label>
                        <textarea name="deskripsi_agenda" id="deskripsi_agenda" class="form-control" rows="3" required>{{ $current['deskripsiAgenda'] }}</textarea>
                        <small id="error-deskripsi_agenda" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="is_done" id="is_done" class="form-control">
                            <option value="false">- Pilih Status -</option>
                            <option value="true" {{ $current['isDone'] ? 'selected' : '' }}>Selesai</option>
                            <option value="false" {{ $current['isDone'] == false ? 'selected' : '' }}>Belum Selesai
                            </option>
                        </select>
                        <small id="error-is_done" class="error-text form-text text-danger"></small>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </div>
        </div>
        </div>
    </form>
    <script>
        $(document).ready(function() {
            $("#form-edit").validate({
                rules: {
                    jabatan_id: {
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
