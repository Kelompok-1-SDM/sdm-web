<<<<<<< HEAD:resources/views/admin/import.blade.php
<form action="{{ url('/admin/import_ajax') }}" method="POST" id="form-import" enctype="multipart/form-data">
=======
<form action="{{ url('/' . $userType . '/import_ajax') }}" method="POST" id="form-import" enctype="multipart/form-data">
>>>>>>> 24444c93d92e7571389c3cf7db92cf1f91e5f3c5:resources/views/user/import.blade.php
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
<<<<<<< HEAD:resources/views/admin/import.blade.php
                <h5 class="modal-title" id="exampleModalLabel">Import Data Admin</h5>
=======
                <h5 class="modal-title" id="exampleModalLabel">Import data {{ $userType }}</h5>
>>>>>>> 24444c93d92e7571389c3cf7db92cf1f91e5f3c5:resources/views/user/import.blade.php
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Download Template</label>
                    <a href="{{ asset('contoh_file.xlsx') }}" class="btn btn-info btn-sm" download><i
                            class="fa fa-file-excel"></i>Download</a>
<<<<<<< HEAD:resources/views/admin/import.blade.php
                    <small id="error-admin_id" class="error-text form-text text-danger"></small>
=======
                    <small id="error-user_id" class="error-text form-text text-danger"></small>
>>>>>>> 24444c93d92e7571389c3cf7db92cf1f91e5f3c5:resources/views/user/import.blade.php
                </div>
                <div class="form-group">
                    <label>Pilih File</label>
                    <input type="file" name="file" id="file" class="form-control" required>
                    <small id="error-file" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        $("#form-import").validate({
            rules: {
                file: {
                    required: true,
                    extension: "xlsx"
                },
            },
            submitHandler: function(form) {
                var formData = new FormData(
<<<<<<< HEAD:resources/views/admin/import.blade.php
                form); // Jadikan form ke FormData untuk menghandle file 
=======
                    form); // Jadikan form ke FormData untuk menghandle file 
>>>>>>> 24444c93d92e7571389c3cf7db92cf1f91e5f3c5:resources/views/user/import.blade.php

                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: formData, // Data yang dikirim berupa FormData 
                    processData: false, // setting processData dan contentType ke false, untuk menghandle file 
                    contentType: false,
                    success: function(response) {
                        if (response.status) { // jika sukses 
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
<<<<<<< HEAD:resources/views/admin/import.blade.php
                            dataAdmin.ajax.reload(); // reload datatable 
=======
                            dataUser.ajax.reload(); // reload datatable 
>>>>>>> 24444c93d92e7571389c3cf7db92cf1f91e5f3c5:resources/views/user/import.blade.php
                        } else { // jika error 
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
