<form action="{{ url('/kegiatan/' . $id . '/anggota_store_ajax') }}" method="POST" id="form-tambah">
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
                            <label>Dosen yang ditugaskan</label>
                            <select name="userId" id="userId" class="form-control">
                                <option value="">- Pilih Dosen -</option>
                                @foreach ($dosen as $l)
                                    <option value="{{ $l['userId'] }}">{{ $l['nama'] }}</option>
                                @endforeach
                            </select>
                            <small id="error-userId" class="error-text form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label>Role Kegiatan</label>
                            <select name="role" id="role" class="form-control">
                                <option value="">- Pilih Role -</option>
                                <option value="pic">PIC</option>
                                <option value="anggota">Anggota</option>
                            </select>
                            <small id="error-role" class="error-text form-text text-danger"></small>
                        </div>
                    </div>
                    <!-- Aligned buttons to the right -->
                    <div class="card-footer text-right">
                        <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                        <button type="button" id="add-user" class="btn btn-primary">Tambahkan</button>
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

        // Add user to the list
        $("#add-user").on("click", function() {
            let userId = $("#userId").val();
            let role = $("#role").val();
            let userName = $("#userId option:selected").text();
            let roleName = $("#role option:selected").text();

            if (!userId || !role) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Pastikan semua field telah diisi."
                });
                return;
            }

            if (assignedUsers[userId]) {
                // Update the user's role if they are already in the list
                assignedUsers[userId].role = role;
                $(`#user-item-${userId}`).find(".badge-role").text(roleName);
            } else {
                // Add a new user to the list
                assignedUsers[userId] = {
                    userId,
                    role
                };
                let listItem = `
                <li id="user-item-${userId}">
                    <span class="text">${userName}</span>
                    <small class="badge badge-secondary badge-role">${roleName}</small>
                    <button type="button" class="btn btn-warning btn-sm float-right edit-user" data-user-id="${userId}" data-user-role="${role}">Edit</button>
                    <button type="button" class="btn btn-danger btn-sm float-right remove-user mr-2" data-user-id="${userId}">Remove</button>
                    <input type="hidden" name="assigned_users[${userId}][userId]" value="${userId}">
                    <input type="hidden" name="assigned_users[${userId}][role]" value="${role}">
                </li>`;
                $("#assigned-users-list").append(listItem);
            }

            // Reset the dropdowns
            $("#userId").val("");
            $("#role").val("");
        });

        // Remove user from the list
        $(document).on("click", ".remove-user", function() {
            let userId = $(this).data("user-id");
            delete assignedUsers[userId];
            $(`#user-item-${userId}`).remove();
        });

        // Edit user in the list
        $(document).on("click", ".edit-user", function() {
            let userId = $(this).data("user-id");
            let userRole = assignedUsers[userId].role;

            // Update the selectors with the selected user's data
            $("#userId").val(userId).change();
            $("#role").val(userRole).change();

            // Optionally highlight the item being edited (visual feedback)
            $(`#user-item-${userId}`).addClass("bg-warning");
            setTimeout(() => {
                $(`#user-item-${userId}`).removeClass("bg-warning");
            }, 1500);
        });

        // Form submission
        $("#form-tambah").submit(function(e) {
            e.preventDefault();
            let form = $(this);

            // Check if any users have been added
            if (Object.keys(assignedUsers).length === 0) {
                Swal.fire({
                    icon: "warning",
                    title: "Tidak Ada Anggota Ditambahkan",
                    text: "Tambahkan setidaknya satu anggota sebelum menyimpan."
                });
                return;
            }

            $.ajax({
                url: form.attr("action"),
                type: form.attr("method"),
                data: form.serialize(),
                success: function(response) {
                    if (response.status) {
                        Swal.fire({
                            icon: "success",
                            title: "Berhasil",
                            text: response.message
                        });
                        location.reload();
                    } else {
                        // Display validation errors
                        $('.error-text').text('');
                        $.each(response.msgField, function(prefix, val) {
                            $('#error-' + prefix).text(val[0]);
                        });
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