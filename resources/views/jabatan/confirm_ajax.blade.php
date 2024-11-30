<<<<<<< HEAD:resources/views/dosen/confirm_ajax.blade.php
@empty($dosen)
=======
@empty($jabatan)
>>>>>>> 24444c93d92e7571389c3cf7db92cf1f91e5f3c5:resources/views/jabatan/confirm_ajax.blade.php
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
<<<<<<< HEAD:resources/views/dosen/confirm_ajax.blade.php
                <a href="{{ url('/dosen') }}" class="btn btn-warning">Kembali</a>
=======
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
>>>>>>> 24444c93d92e7571389c3cf7db92cf1f91e5f3c5:resources/views/jabatan/confirm_ajax.blade.php
            </div>
        </div>
    </div>
@else
<<<<<<< HEAD:resources/views/dosen/confirm_ajax.blade.php
    <form action="{{ url('/dosen/' . $dosen['userId'] . '/delete_ajax') }}" method="POST" id="form-delete">
=======
    <form action="{{ url('/jabatan/' . $jabatan['jabatanId'] . '/delete_ajax') }}" method="POST" id="form-delete">
>>>>>>> 24444c93d92e7571389c3cf7db92cf1f91e5f3c5:resources/views/jabatan/confirm_ajax.blade.php
        @csrf
        @method('DELETE')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
<<<<<<< HEAD:resources/views/dosen/confirm_ajax.blade.php
                    <h5 class="modal-title" id="exampleModalLabel">Hapus Data Dosen</h5>
=======
                    <h5 class="modal-title" id="exampleModalLabel">Hapus Data Jabatan</h5>
>>>>>>> 24444c93d92e7571389c3cf7db92cf1f91e5f3c5:resources/views/jabatan/confirm_ajax.blade.php
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
<<<<<<< HEAD:resources/views/dosen/confirm_ajax.blade.php
                            <th class="text-right col-3">NIP:</th>
                            <td class="col-9">{{ $dosen['nip'] }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Nama:</th>
                            <td class="col-9">{{ $dosen['nama'] }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Email:</th>
                            <td class="col-9">{{ $dosen['email'] }}</td>
=======
                            <th class="text-right col-3">Nama Jabatan:</th>
                            <td class="col-9">{{ $jabatan['namaJabatan'] }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Role:</th>
                            <td class="col-9"><small
                                    class="badge {{ $jabatan['isPic'] ? 'badge-success' : 'badge-primary' }}">{{ $jabatan['isPic'] ? 'PIC' : 'Anggota' }}</small>
                            </td>
>>>>>>> 24444c93d92e7571389c3cf7db92cf1f91e5f3c5:resources/views/jabatan/confirm_ajax.blade.php
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
<<<<<<< HEAD:resources/views/dosen/confirm_ajax.blade.php
                                dataDosen.ajax.reload();
=======
                                dataJabatan.ajax.reload();
>>>>>>> 24444c93d92e7571389c3cf7db92cf1f91e5f3c5:resources/views/jabatan/confirm_ajax.blade.php
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
