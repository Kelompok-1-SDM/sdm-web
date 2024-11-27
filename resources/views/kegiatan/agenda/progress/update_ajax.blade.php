@empty($current)
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
                    Data yang anda cari tidak ditemukan
                </div>
                <button type="button" data-dismiss="modal" class="btn btn-warning">Kembali</button>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/kegiatan/' . $current['progressId'] . '/agenda_progress_update_ajax') }}" method="POST"
        id="form-progress" enctype="multipart/form-data">
        @csrf
        @method('POST')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <input type="hidden" name="uid_agenda" value="{{ $id }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Progress</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="uid_agenda" value="{{ $id }}">
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi Progress</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3" required>{{ $current['deskripsiProgress'] }}</textarea>
                        <small id="error-deskripsi" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label for="file">Pilih File Baru</label>
                        <input type="file" id="file" class="form-control" multiple>
                        <small id="error-file" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label for="attachments">Lampiran yang Ada</label>
                        <ul id="existing-attachments" class="list-group mt-2">
                            @foreach ($current['attachments'] as $attachment)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $attachment['nama'] }}
                                    <button type="button" class="btn btn-sm btn-danger remove-attachment"
                                        data-progress-id="{{ $current['progressId'] }}"
                                        data-attachment-id="{{ $attachment['attachmentId'] }}">
                                        Hapus
                                    </button>
                                </li>
                            @endforeach
                        </ul>
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
        var baseUrl = "{{ url('/') }}"; // This sets the base URL globally

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

                // Create list item for new file
                const li = document.createElement('li');
                li.classList.add('list-group-item', 'd-flex', 'justify-content-between',
                    'align-items-center');

                // File name
                const span = document.createElement('span');
                span.textContent = file.name;

                const small = document.createElement('small');
                small.classList.add('badge', 'badge-success', 'ml-1');
                small.textContent = 'Baru';
                span.appendChild(small);

                // Remove button for new file
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

        // Handle removing existing attachments
        if (existingAttachments) {
            existingAttachments.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-attachment')) {
                    const progressId = e.target.getAttribute('data-progress-id');
                    const attachmentId = e.target.getAttribute('data-attachment-id');

                    Swal.fire({
                        title: 'Apakah Anda Yakin?',
                        text: 'Lampiran akan dihapus permanen!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: `${baseUrl}/kegiatan/${progressId}/agenda_progress_attachment_delete_ajax?uid_attachment=${attachmentId}`,
                                method: 'DELETE',
                                data: {
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(response) {
                                    if (response.status) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Berhasil',
                                            text: response.message
                                        });
                                        e.target.closest('li').remove();
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Gagal',
                                            text: response.message
                                        });
                                    }
                                },
                                error: function() {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal',
                                        text: 'Terjadi kesalahan saat menghapus lampiran.'
                                    });
                                }
                            });
                        }
                    });
                }
            });
        }

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

                // Append only new files
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
@endempty
