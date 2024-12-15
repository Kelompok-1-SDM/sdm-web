@empty($user)
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
    <form action="{{ url('/' . $userType . '/' . $user['userId'] . '/update_ajax') }}" method="POST" id="form-edit">
        @csrf
        @method('POST')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit data {{ $userType }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="role" value="{{ $userType }}">
                    @if (session('role') != 'dosen')
                        <div class="form-group">
                            <label>Nip</label>
                            <input value="{{ $user['nip'] }}" type="number" name="nip" id="nip"
                                class="form-control">
                            <small id="error-nip" class="error-text form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label>Nama</label>
                            <input value="{{ $user['nama'] }}" type="text" name="nama" id="nama"
                                class="form-control">
                            <small id="error-nama" class="error-text form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input value="{{ $user['email'] }}" type="email" name="email" id="email"
                                class="form-control">
                            <small id="error-email" class="error-text form-text text-danger"></small>
                        </div>
                    @endif
                    <div class="form-group">
                        <label>Foto Profil</label>
                        <input type="file" name="file" id="file" class="form-control">
                        <small style="color: grey;">*optional, fill if need to be changed</small>
                        <small id="error-file" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input value="" type="password" name="password" id="password" class="form-control">
                        <small style="color: grey;">*optional, fill if need to be changed</small>
                        <small id="error-password" class="error-text form-text text-danger"></small>
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
        function prepareChangedData(form, oldData) {
            const formData = new FormData(form);
            const changedData = {};

            for (let [key, value] of formData.entries()) {
                if (value !== oldData[key]) {
                    changedData[key] = value;
                }
            }

            return changedData;
        }

        $(document).ready(function() {
            $("#form-edit").validate({
                rules: {
                    role: {
                        required: true
                    },
                    @if (session('role') != 'dosen')
                        nip: {
                            minlength: 3,
                            maxlength: 24
                        },
                        nama: {
                            minlength: 3,
                            maxlength: 255
                        },
                        email: {
                            maxlength: 255
                        },
                    @endif

                    password: {
                        minlength: 6,
                        maxlength: 20
                    },
                    file: {
                        extension: "jpg|jpeg|png|ico|bmp"
                    }
                },
                submitHandler: function(form) {
                    // Prepare payload with only changed fields
                    const changedData = prepareChangedData(form, @json($user));

                    // Stop submission if no changes
                    if (Object.keys(changedData).length === 0) {
                        Swal.fire({
                            icon: "info",
                            title: "No Changes",
                            text: "You haven't made any changes."
                        });
                        return false;
                    }

                    var formData = new FormData(
                        form); // Jadikan form ke FormData untuk menghandle file

                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: formData,
                        processData: false, // setting processData dan contentType ke false, untuk menghandle file 
                        contentType: false,
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
