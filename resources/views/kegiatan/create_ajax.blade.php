<form action="{{ url('/kegiatan/store_ajax') }}" method="POST" id="form-create">
    @csrf
    @method('POST')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Data Kegiatan</h5>
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
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" value=""
                        required>
                    <small id="error-tanggal_mulai" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label for="tanggal_akhir">Tanggal</label>
                    <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control" value=""
                        required>
                    <small id="error-tanggal_akhir" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label for="lokasi">Lokasi</label>
                    <input type="text" name="lokasi" id="lokasi" class="form-control" value="" required>
                    <small id="error-lokasi" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label for="tipe_kegiatan">Tipe Kegiatan</label>
                    <select name="tipe_kegiatan" id="tipe_kegiatan" class="form-control" required>
                        <option value="">- Pilih Tipe Kegiatan -</option>
                        <option value="jti">JTI</option>
                        <option value="non-jti">Non-JTI</option>
                    </select>
                    <small id="error-tipe_kegiatan" class="error-text form-text text-danger"></small>
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

        // Form submission
        $("#form-create").submit(function(e) {
            e.preventDefault();
            let form = $(this);
            $.ajax({
                url: form.attr("action"),
                type: form.attr("method"),
                data: form.serialize(),
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
