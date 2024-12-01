<form action="{{ url('/kegiatan/' . $id . '/progress_store_ajax') }}" method="POST" id="form-progress"
    enctype="multipart/form-data">
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
                <div class="form-group">
                    <label for="deskripsi">Deskripsi Progress</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3" required></textarea>
                    <small id="error-deskripsi" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label for="file">Pilih File</label>
                    <input type="file" id="file" class="form-control" multiple>
                    <small id="error-file" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label for="attachments">Lampiran yang Ada</label>
                    <ul id="existing-attachments" class="list-group mt-2"></ul>
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
    // Initialize file input and list
    var fileInput = document.querySelector('#file');
    var existingAttachments = document.querySelector('#existing-attachments');
    var selectedFiles = new DataTransfer(); // Maintain list of selected files

    // Handle file selection
    fileInput.addEventListener('change', function() {
        Array.from(this.files).forEach(file => {
            // Prevent duplicate files
            if (Array.from(selectedFiles.files).some(f => f.name === file.name)) {
                Swal.fire({
                    icon: 'info',
                    title: 'Duplicate File',
                    text: `File "${file.name}" is already in the list.`,
                });
                return;
            }

            // Add file to DataTransfer object
            selectedFiles.items.add(file);

            // Create list item with consistent design
            const li = document.createElement('li');
            li.classList.add('list-group-item', 'd-flex', 'justify-content-between',
                'align-items-center');

            // File name
            const span = document.createElement('span');
            span.textContent = file.name;

            // Remove button
            const removeButton = document.createElement('button');
            removeButton.textContent = 'Hapus';
            removeButton.classList.add('btn', 'btn-sm', 'btn-danger');
            removeButton.addEventListener('click', function() {
                // Remove file from DataTransfer and update input
                selectedFiles.items.remove(Array.from(selectedFiles.files).indexOf(file));
                fileInput.files = selectedFiles.files;

                // Remove list item
                li.remove();
            });

            // Append elements to list item
            li.appendChild(span);
            li.appendChild(removeButton);

            // Add list item to file list
            existingAttachments.appendChild(li);
        });

        // Update file input to reflect selected files
        fileInput.files = selectedFiles.files;

        // Clear the file input to allow re-selection of the same files
        fileInput.value = '';
    });


    // AJAX Form Submission
    $("#form-progress").validate({
        rules: {
            deskripsi: {
                required: true
            }
        },
        messages: {
            deskripsi: {
                required: "Deskripsi progress is required."
            }
        },
        submitHandler: function(form) {
            const formData = new FormData(form);

            // Add files from DataTransfer to FormData
            Array.from(selectedFiles.files).forEach(file => {
                formData.append("file[]", file);
            });

            $.ajax({
                url: form.action,
                type: form.method,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status) {
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
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
                            title: 'Error',
                            text: response.message
                        });
                    }
                }
            });

            return false;
        }
    });
</script>
