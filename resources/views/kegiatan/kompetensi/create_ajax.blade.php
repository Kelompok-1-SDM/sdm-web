<form action="{{ url('/kegiatan/' . $id . '/store_kompetensi_ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Kompetensi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Kompetensi</label>
                            <select name="kompetensi" id="kompetensi" class="form-control">
                                <option value="">- Pilih Kompetensi -</option>
                                @foreach ($kompetensi as $l)
                                    <option value="{{ $l['kompetensiId'] }}">{{ $l['namaKompetensi'] }}</option>
                                @endforeach
                            </select>
                            <small id="error-kompetensi" class="error-text form-text text-danger"></small>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">Kompetensi yang Ditambahkan</h3>
                    </div>
                    <div class="card-body">
                        <ul class="todo-list" id="assigned-kompetensi-list" data-widget="todo-list"></ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!-- Rearranged buttons -->
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        let assignedKompetensi = new Set();

        // Automatically add kompetensi to the list when selected
        $("#kompetensi").on("change", function() {
            let kompetensiId = $(this).val();
            let kompetensiName = $("#kompetensi option:selected").text();

            if (!kompetensiId) {
                return; // Exit if no selection is made
            }

            if (!assignedKompetensi.has(kompetensiId)) {
                // Add new kompetensi to the list
                assignedKompetensi.add(kompetensiId);
                let listItem = `
                <li id="kompetensi-item-${kompetensiId}">
                    <span class="text">${kompetensiName}</span>
                    <button type="button" class="btn btn-danger btn-sm float-right remove-kompetensi" data-kompetensi-id="${kompetensiId}">Hapus</button>
                    <input type="hidden" name="kompetensi[]" value="${kompetensiId}">
                </li>`;
                $("#assigned-kompetensi-list").append(listItem);
            }

            // Reset the dropdown
            $("#kompetensi").val("");
        });

        // Remove kompetensi from the list
        $(document).on("click", ".remove-kompetensi", function() {
            let kompetensiId = $(this).data("kompetensi-id");
            assignedKompetensi.delete(kompetensiId);
            $(`#kompetensi-item-${kompetensiId}`).remove();
        });

        // Form submission
        $("#form-tambah").submit(function(e) {
            e.preventDefault();
            let form = $(this);

            // Prepare the kompetensi list
            if (assignedKompetensi.size === 0) {
                Swal.fire({
                    icon: "warning",
                    title: "Tidak Ada Kompetensi Ditambahkan",
                    text: "Tambahkan setidaknya satu kompetensi sebelum menyimpan."
                });
                return;
            }

            let kompetensiList = Array.from(assignedKompetensi);
            $.ajax({
                url: form.attr("action"),
                type: form.attr("method"),
                contentType: "application/json",
                data: JSON.stringify({
                    list_kompetensi: kompetensiList
                }),
                success: function(response) {
                    if (response.status) {
                        Swal.fire({
                            icon: "success",
                            title: "Berhasil",
                            text: response.message
                        });
                        location.reload();
                    } else {
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
