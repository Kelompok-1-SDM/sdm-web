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
                <button type="button" data-dismiss="modal" class="btn btn-warning">Kembali</button>
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
                        <label for="jadwal_agenda">Tanggal Mulai</label>
                        <div class="input-group date" id="jadwal_agenda" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" name="jadwal_agenda"
                                value="{{ $current['jadwalAgenda'] ? \Carbon\Carbon::parse($current['jadwalAgenda'])->format('m/d/Y H:i') : '' }}"
                                data-target="#jadwal_agenda" />
                            <div class="input-group-append" data-target="#jadwal_agenda" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
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

            $('#jadwal_agenda').datetimepicker({
                icons: {
                    time: 'far fa-clock'
                },
                format: 'MM/DD/YYYY HH:mm'
            });

            $("#form-edit").validate({
                rules: {
                    jabatan_id: {
                        required: true
                    }
                },
                submitHandler: function(form) {
                    // Convert datetime fields to ISO8601
                    let formData = $(form).serializeArray(); // Convert form to jQuery object

                    formData.forEach(function(field) {
                        if ((field.name === "jadwal_agenda") && field.value) {
                            // Convert the value to a Date object
                            let date = new Date(field.value);

                            // Adjust the date to UTC
                            let utcDate = new Date(
                                Date.UTC(
                                    date.getFullYear(),
                                    date.getMonth(),
                                    date.getDate(),
                                    date.getHours(),
                                    date.getMinutes(),
                                    date.getSeconds(),
                                    date.getMilliseconds()
                                )
                            );

                            // Format to ISO8601
                            field.value = utcDate.toISOString(); // Update to UTC ISO8601 format
                        }
                    });

                    // Convert the modified form data array back to an object
                    let convertedData = {};
                    formData.forEach(function(field) {
                        convertedData[field.name] = field.value;
                    });

                    $.ajax({
                        url: form.action,
                        type: form.method,
                        contentType: "application/json", // Use JSON format
                        data: JSON.stringify(convertedData), // Send as JSON
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
