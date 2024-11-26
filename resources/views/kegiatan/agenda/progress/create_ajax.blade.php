<form action="{{ url('/kegiatan/' . $id . '/progress_store_ajax') }}" method="POST" id="form-progress">
    @csrf
    @method('POST')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Progress</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="uid_agenda" value="{{ $id }}">
                <div class="form-group">
                    <label for="deskripsi">Deskripsi Progress</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3" required></textarea>
                    <small id="error-deskripsi" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label for="file">Pilih File</label>
                    <input type="file" name="file[]" id="file" class="form-control" multiple required>
                    <small id="error-file" class="error-text form-text text-danger"></small>
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
    $("#form-progress").validate({
        rules: {
            deskripsi: {
                required: true
            },
            file: {
                required: true,
                extension: "doc,docx,xls,xlsx,pdf,txt,jpg,jpeg,png,gif,svg,bmp,webp" // Allowed extensions
            },
        },
        messages: {
            deskripsi: {
                required: "Deskripsi progress is required."
            },
            file: {
                required: "At least one file is required.",
                extension: "Please upload a valid file type: .doc, .docx, .xls, .xlsx, .pdf, .txt, .jpg, .jpeg, .png, .gif, .svg, .bmp, .webp."
            }
        },
        submitHandler: function(form) {
            var formData = new FormData(form); // Convert form to FormData to handle file
            formData.delete("file"); // Remove previous single file
            var files = $("#file")[0].files;
            for (var i = 0; i < files.length; i++) {
                formData.append("file[]", files[i]); // Append each file
            }

            $.ajax({
                url: form.action,
                type: form.method,
                data: formData, // Send FormData with the files
                processData: false, // Prevent jQuery from processing the data
                contentType: false, // Prevent jQuery from setting content type
                success: function(response) {
                    if (response.status) { // Success handling
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message
                        });
                        location.reload(); // Reload datatable
                    } else { // Error handling
                        $('.error-text').text('');
                        $.each(response.msgField, function(prefix, val) {
                            $('#error-' + prefix).text(val[0]);
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
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
</script>
