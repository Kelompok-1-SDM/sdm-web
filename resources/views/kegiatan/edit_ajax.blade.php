@empty($kegiatan)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
                <button type="button" data-dismiss="modal" class="btn btn-warning">Kembali</button>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/kegiatan/' . $kegiatan['kegiatanId'] . '/update_ajax') }}" method="POST" id="form-edit">
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
                        <input type="text" name="judul_kegiatan" id="judul_kegiatan" class="form-control"
                            value="{{ $kegiatan['judul'] }}" required>
                        <small id="error-judul_kegiatan" class="error-text form-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label for="tanggal_mulai">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control"
                            value="{{ $kegiatan['tanggalMulai'] ? \Carbon\Carbon::parse($kegiatan['tanggalMulai'])->format('Y-m-d') : '' }}"
                            required>
                        <small id="error-tanggal_mulai" class="error-text form-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label for="tanggal_akhir">Tanggal</label>
                        <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control"
                            value="{{ $kegiatan['tanggalAkhir'] ? \Carbon\Carbon::parse($kegiatan['tanggalAkhir'])->format('Y-m-d') : '' }}"
                            required>
                        <small id="error-tanggal_akhir" class="error-text form-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label for="lokasi">Lokasi</label>
                        <input type="text" name="lokasi" id="lokasi" class="form-control"
                            value="{{ $kegiatan['lokasi'] }}" required>
                        <small id="error-lokasi" class="error-text form-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label for="tipeKegiatan">Tipe Kegiatan</label>
                        <select name="tipeKegiatan" id="tipeKegiatan" class="form-control" required>
                            <option value="">- Pilih Tipe Kegiatan -</option>
                            <option value="jti" {{ $kegiatan['tipeKegiatan'] == 'jti' ? 'selected' : '' }}>JTI</option>
                            <option value="non-jti" {{ $kegiatan['tipeKegiatan'] == 'non-jti' ? 'selected' : '' }}>Non-JTI
                            </option>
                        </select>
                        <small id="error-tipeKegiatan" class="error-text form-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label for="is_done">Status selesai</label>
                        <select name="is_done" id="is_done" class="form-control" required>
                            <option value="">- Pilih Status -</option>
                            <option value="true" {{ $kegiatan['isDone'] ? 'selected' : '' }}>Selesai</option>
                            <option value="false" {{ $kegiatan['isDone'] == false ? 'selected' : '' }}>Belum Selesai
                            </option>
                        </select>
                        <small id="error-is_done" class="error-text form-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3" required>{{ $kegiatan['deskripsi'] }}</textarea>
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
            $("#form-edit").submit(function(e) {
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
                            // Display validation errors
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
@endempty
