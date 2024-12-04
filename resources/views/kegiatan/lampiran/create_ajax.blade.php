<form action="{{ url('/kegiatan/' . $id . '/lampiran_store_ajax') }}" method="POST" id="form-lampiran">
    @csrf
    @method('POST')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Upload lampiran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Pilih File</label>
                    <input type="file" name="file" id="file" class="form-control" required>
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
    $("#form-lampiran").validate({
        rules: {
            file: {
                required: true,
                extension: "doc,docx,xls,xlsx,pdf,txt,jpg,jpeg,png,gif,svg,bmp,webp" // Allowed extensions
            },
        },
        messages: {
            file: {
                required: "File is required.",
                extension: "Please upload a valid file type: .doc, .docx, .xls, .xlsx, .pdf, .txt, .jpg, .jpeg, .png, .gif, .svg, .bmp, .webp."
            }
        },
        submitHandler: function(form) {
            var formData = new FormData(form); // Convert form to FormData to handle file

            $.ajax({
                url: form.action,
                type: form.method,
                data: formData, // Send FormData with the file
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
