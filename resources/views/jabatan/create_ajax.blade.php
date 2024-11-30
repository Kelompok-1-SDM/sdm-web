<<<<<<< HEAD:resources/views/dosen/create_ajax.blade.php
<form action="{{ url('/dosen/store_ajax') }}" method="POST" id="form-tambah">
=======
<form action="{{ url('/jabatan/store_ajax') }}" method="POST" id="form-tambah">
>>>>>>> 24444c93d92e7571389c3cf7db92cf1f91e5f3c5:resources/views/jabatan/create_ajax.blade.php
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
<<<<<<< HEAD:resources/views/dosen/create_ajax.blade.php
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data User</h5>
=======
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Jabatan</h5>
>>>>>>> 24444c93d92e7571389c3cf7db92cf1f91e5f3c5:resources/views/jabatan/create_ajax.blade.php
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
<<<<<<< HEAD:resources/views/dosen/create_ajax.blade.php
                <input type="hidden" name="role" value="dosen">
                <div class="form-group">
                    <label>Nip</label>
                    <input value="" type="number" name="nip" id="nip" class="form-control" required>
                    <small id="error-nip" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama</label>
                    <input value="" type="text" name="nama" id="nama" class="form-control" required>
                    <small id="error-nama" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input value="" type="email" name="email" id="email" class="form-control" required>
                    <small id="error-email" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Foto Profil</label>
                    <input type="file" name="file" id="file" class="form-control">
                    <small style="color: grey;">*optional</small>
                    <small id="error-file" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input value="" type="password" name="password" id="password" class="form-control" required>
                    <small id="error-password" class="error-text form-text text-danger"></small>
=======
                <div class="form-group">
                    <label>Nama Jabatan</label>
                    <input value="" type="text" name="nama_jabatan" id="nama_jabatan" class="form-control"
                        required>
                    <small id="error-nama_jabatan" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label for="is_pic">Tipe Role</label>
                    <select name="is_pic" id="is_pic" class="form-control" required>
                        <option value="">- Pilih Tipe Role -</option>
                        <option value="true">PIC</option>
                        <option value="false">Anggota</option>
                    </select>
                    <small id="error-is_pic" class="error-text form-text text-danger"></small>
>>>>>>> 24444c93d92e7571389c3cf7db92cf1f91e5f3c5:resources/views/jabatan/create_ajax.blade.php
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
<<<<<<< HEAD:resources/views/dosen/create_ajax.blade.php
                role: {
                    required: true
                },
                nip: {
                    required: true,
                    minlength: 3,
                    maxlength: 24
                },
                nama: {
=======
                nama_jabatan: {
>>>>>>> 24444c93d92e7571389c3cf7db92cf1f91e5f3c5:resources/views/jabatan/create_ajax.blade.php
                    required: true,
                    minlength: 3,
                    maxlength: 255
                },
<<<<<<< HEAD:resources/views/dosen/create_ajax.blade.php
                email: {
                    required: true,
                    maxlength: 255
                },
                password: {
                    required: true,
                    minlength: 6,
                    maxlength: 20
                },
                file: {
                    extension: "jpg|jpeg|png|ico|bmp"
                }
            },
            submitHandler: function(form) {
                var formData = new FormData(
                    form); // Jadikan form ke FormData untuk menghandle file 

                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: formData,
                    processData: false, // setting processData dan contentType ke false, untuk menghandle file 
                    contentType: false,
=======
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
>>>>>>> 24444c93d92e7571389c3cf7db92cf1f91e5f3c5:resources/views/jabatan/create_ajax.blade.php
                    success: function(response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
<<<<<<< HEAD:resources/views/dosen/create_ajax.blade.php
                            dataDosen.ajax.reload();
=======
                            dataJabatan.ajax.reload();
>>>>>>> 24444c93d92e7571389c3cf7db92cf1f91e5f3c5:resources/views/jabatan/create_ajax.blade.php
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
