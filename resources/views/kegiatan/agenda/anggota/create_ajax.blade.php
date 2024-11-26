<form action="{{ url('/kegiatan/' . $id . '/agenda_update_ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Anggota Penugasan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Dosen yang hendak ditugaskan</label>
                            <select name="userId" id="userId" class="form-control">
                                <option value="">- Pilih Dosen -</option>
                                @foreach ($penugasan as $l)
                                    <option value="{{ $l['userToKegiatanId'] }}">
                                        {{ $l['nama'] }} - {{ $l['namaJabatan'] }} -
                                        {{ $l['isPic'] ? 'PIC' : 'Anggota' }}
                                    </option>
                                @endforeach
                            </select>
                            <small id="error-userId" class="error-text form-text text-danger"></small>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">Anggota Ditugaskan</h3>
                    </div>
                    <div class="card-body">
                        <ul class="todo-list" id="assigned-users-list" data-widget="todo-list"></ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!-- Submit Button -->
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        let assignedUsers = {};

        // Automatically add or update user on selection
        $("#userId").on("change", function() {
            let userId = $(this).val();
            let userText = $("#userId option:selected").text();

            if (!userId) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Pilih dosen terlebih dahulu.",
                });
                return;
            }

            if (assignedUsers[userId]) {
                // Update the existing user's information
                $(`#user-item-${userId} .badge-jabatan`).text(assignedUsers[userId].jabatanName);
                $(`#user-item-${userId} .badge-jabatan`).removeClass("badge-primary badge-success");
                $(`#user-item-${userId} .badge-jabatan`).addClass(
                    assignedUsers[userId].isPic ? "badge-success" : "badge-primary"
                );
            } else {
                // Add a new user to the list
                let isPic = userText.includes("PIC"); // Check if the user is a PIC
                let jabatanName = userText.split(" - ")[1]; // Extract the jabatan from the user text

                assignedUsers[userId] = {
                    userId,
                    jabatanName,
                    isPic,
                };

                let badgeClass = isPic ? "badge-success" :
                    "badge-primary"; // Green for PIC, blue for Anggota

                let listItem = `
                <li id="user-item-${userId}">
                    <span class="text">${userText.split(" - ")[0]}</span>
                    <small class="badge ${badgeClass} badge-jabatan">${jabatanName}</small>
                    <button type="button" class="btn btn-danger btn-sm float-right remove-user" data-user-id="${userId}">
                        Remove
                    </button>
                    <input type="hidden" name="list_uid_user_kegiatan[]" value="${userId}">
                </li>`;
                $("#assigned-users-list").append(listItem);
            }

            // Reset the dropdown
            $("#userId").val("");
        });

        // Remove user from the list
        $(document).on("click", ".remove-user", function() {
            let userId = $(this).data("user-id");
            delete assignedUsers[userId];
            $(`#user-item-${userId}`).remove();
        });

        // Form submission
        $("#form-tambah").submit(function(e) {
            e.preventDefault();

            if (Object.keys(assignedUsers).length === 0) {
                Swal.fire({
                    icon: "warning",
                    title: "Tidak Ada Anggota Ditambahkan",
                    text: "Tambahkan setidaknya satu anggota sebelum menyimpan.",
                });
                return;
            }

            $.ajax({
                url: $(this).attr("action"),
                type: $(this).attr("method"),
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status) {
                        Swal.fire({
                            icon: "success",
                            title: "Berhasil",
                            text: response.message,
                        });
                        location.reload();
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Terjadi Kesalahan",
                            text: response.message,
                        });
                    }
                },
            });
        });
    });
</script>
