<form action="{{ url('/kegiatan/store_ajax') }}" method="POST" id="form-create">
    @csrf
    @method('POST')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Kegiatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="judul_kegiatan">Nama Kegiatan</label>
                    <input type="text" name="judul_kegiatan" id="judul_kegiatan" class="form-control" value=""
                        required>
                    <small id="error-judul_kegiatan" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label for="tanggal_mulai">Tanggal Mulai</label>
                    <div class="input-group date" id="tanggal_mulai" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" name="tanggal_mulai"
                            data-target="#tanggal_mulai" />
                        <div class="input-group-append" data-target="#tanggal_mulai" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                    <small id="error-tanggal_mulai" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label for="tanggal_akhir">Tanggal Akhir</label>
                    <div class="input-group date" id="tanggal_akhir" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" name="tanggal_akhir"
                            data-target="#tanggal_akhir" />
                        <div class="input-group-append" data-target="#tanggal_akhir" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                    <small id="error-tanggal_akhir" class="error-text form-text text-danger"></small>
                </div>


                <div class="form-group">
                    <label for="lokasi">Lokasi</label>
                    <input type="text" name="lokasi" id="lokasi" class="form-control" value="" required>
                    <small id="error-lokasi" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label for="tipe_kegiatan_uid">Tipe Kegiatan</label>
                    <select name="tipe_kegiatan_uid" id="tipe_kegiatan_uid" class="form-control" required>
                        <option value="">- Pilih Tipe Kegiatan -</option>
                        @foreach ($tipe_kegiatan as $tipeKegiatan)
                            <option value="{{ $tipeKegiatan['tipeKegiatanId'] }}">{{ $tipeKegiatan['tipeKegiatan'] }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-tipe_kegiatan_uid" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label for="is_done">Status selesai</label>
                    <select name="is_done" id="is_done" class="form-control" required>
                        <option value="false">- Pilih Status -</option>
                        <option value="true">Selesai</option>
                        <option value="false">Belum Selesai</option>
                    </select>
                    <small id="error-is_done" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3" required></textarea>
                    <small id="error-deskripsi" class="error-text form-text text-danger"></small>
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

        $('#tanggal_mulai').datetimepicker({
            icons: {
                time: 'far fa-clock'
            },
            format: 'MM/DD/YYYY HH:mm'
        });


        $('#tanggal_akhir').datetimepicker({
            icons: {
                time: 'far fa-clock'
            },
            format: 'MM/DD/YYYY HH:mm'
        });

        // Form submission
        $("#form-create").submit(function(e) {
            e.preventDefault();
            let form = $(this);

            // Convert datetime fields to ISO8601
            let formData = form.serializeArray(); // Get form data as an array
            formData.forEach(function(field) {
                if ((field.name === "tanggal_mulai" || field.name === "tanggal_akhir") && field
                    .value) {
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
                url: form.attr("action"),
                type: form.attr("method"),
                contentType: "application/json", // Use JSON format
                data: JSON.stringify(convertedData), // Send as JSON
                success: function(response) {
                    if (response.status) {
                        Swal.fire({
                            icon: "success",
                            title: "Berhasil",
                            text: response.message
                        });
                        location.reload();
                    } else {
                        $('.error-text').text('');
                        $.each(response.msgField, function(prefix, val) {
                            $('#error-' + prefix).text(val[0]);
                        });
                        Swal.fire({
                            icon: "error",
                            title: "Terjadi Kesalahan",
                            text: response.message
                        });
                    }
                }
            });
        });
    });
</script>
