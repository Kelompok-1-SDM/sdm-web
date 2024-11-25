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
    <form action="{{ url('/kegiatan/' . $id . '/anggota_update_ajax') }}" method="POST" id="form-edit">
        @csrf
        @method('POST')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit data Anggota</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Dosen yang ditugaskan</label>
                        <select name="userId" id="userId" class="form-control"
                            {{ $current['status'] == 'selesai' ? 'disabled' : '' }}>
                            <option value="">- Pilih Dosen -</option>
                            @if ($current['status'] == 'selesai')
                                <option value="{{$current['userId']}}" selected>{{$current['nama']}}</option>
                            @else
                                @foreach ($dosen as $l)
                                    <option value="{{ $l['userId'] }}"
                                        {{ $l['nama'] == $current['nama'] ? 'selected' : '' }}>
                                        {{ $l['nama'] }}</option>
                                @endforeach
                            @endif
                        </select>
                        <small id="error-userId" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Role Kegiatan</label>
                        <select name="role" id="role" class="form-control"
                            {{ $current['status'] == 'selesai' ? 'disabled' : '' }}>
                            <option value="">- Pilih Role -</option>
                            <option value="pic" {{ $current['roleKegiatan'] == 'pic' ? 'selected' : '' }}>PIC</option>
                            <option value="anggota" {{ $current['roleKegiatan'] == 'anggota' ? 'selected' : '' }}>Anggota
                            </option>
                        </select>
                        <small id="error-role" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="status" class="form-control"
                            {{ $current['status'] == 'selesai' ? 'disabled' : '' }}>
                            <option value="">- Pilih Status -</option>
                            {{-- <option value="ditugaskan" {{ $current['status'] == 'ditugaskan' ? 'selected' : '' }}>Ditugaskan
                            </option> --}}
                            <option value="selesai" {{ $current['status'] == 'selesai' ? 'selected' : '' }}>Selesai
                            </option>
                        </select>
                        <small style="color: red;">*dengan memilih 'selesai', anda tidak bisa mengambalikannya menjadi
                            'ditugaskan' dan fitur edit ini akan di nonaktifkan</small>
                        <small id="error-status" class="error-text form-text text-danger"></small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                    <button type="submit" class="btn btn-primary"
                        {{ $current['status'] == 'selesai' ? 'hidden' : '' }}>Simpan</button>
                </div>
            </div>
        </div>
        </div>
    </form>
    <script>
        $(document).ready(function() {
            $("#form-edit").validate({
                rules: {
                    userId: {
                        required: true
                    },
                    role: {
                        required: true
                    },
                    status: {
                        required: true
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
