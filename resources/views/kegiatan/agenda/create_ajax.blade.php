<form action="{{ url('/kegiatan/' . $id . '/agenda_store_ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Agenda Kegiatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Agenda</label>
                    <input type="text" name="nama_agenda" id="nama_agenda" class="form-control" required>
                    <small id="error-nama_agenda" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label for="jadwal_agenda">Jadwal Agenda</label>
                    <div class="input-group date" id="jadwal_agenda" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" name="jadwal_agenda"
                            data-target="#jadwal_agenda" />
                        <div class="input-group-append" data-target="#jadwal_agenda" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                    <small id="error-jadwal_agenda" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Deskripsi Agenda</label>
                    <textarea name="deskripsi_agenda" id="deskripsi_agenda" class="form-control" rows="3" required></textarea>
                    <small id="error-deskripsi_agenda" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="is_done" id="is_done" class="form-control">
                        <option value="false">- Pilih Status -</option>
                        <option value="true">Selesai</option>
                        <option value="false">Belum Selesai</option>
                    </select>
                    <small id="error-is_done" class="error-text form-text text-danger"></small>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Dosen yang hendak ditugaskan</label>
                            <select name="userId" id="userId" class="form-control">
                                <option value="">- Pilih Dosen -</option>
                                @foreach ($penugasan as $l)
                                    <option value="{{ $l['userToKegiatanId'] }}">
                                        {{ $l['nama'] }} - {{ $l['namaJabatan'] }} |
                                        {{ $l['isPic'] ? 'PIC' : 'Anggota' }} -
                                        Jumlah agenda {{ $l['agendaCount'] }}
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
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        let assignedUsers = {};

        // Initialize the datetimepicker
        $('#jadwal_agenda').datetimepicker({
            icons: {
                time: 'far fa-clock'
            },
            format: 'MM/DD/YYYY HH:mm'
        });

        // Add or update user on selection
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
                // Update the existing user's badge
                $(`#user-item-${userId} .badge-jabatan`)
                    .text(assignedUsers[userId].jabatanName)
                    .removeClass("badge-primary badge-success")
                    .addClass(assignedUsers[userId].isPic ? "badge-success" : "badge-primary");
            } else {
                // Extract jabatan and PIC status
                let jabatanName = userText.split(" - ")[1] ||
                    "Anggota"; // Default to 'Anggota' if jabatan missing
                let isPic = userText.toLowerCase().includes("pic");

                // Add the user to the assignedUsers object
                assignedUsers[userId] = {
                    userId,
                    jabatanName,
                    isPic,
                };

                // Create the list item
                let badgeClass = isPic ? "badge-success" :
                    "badge-primary"; // Green for PIC, blue for Anggota
                let listItem = `
                <li id="user-item-${userId}" class="list-group-item">
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

            // Remove the user from the assignedUsers object and the DOM
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

            // Serialize form data
            let formData = $(this).serializeArray();

            // Convert datetime fields to ISO8601
            formData.forEach(function(field) {
                if (field.name === "jadwal_agenda" && field.value) {
                    let date = new Date(field.value);

                    // Adjust the date to UTC
                    let utcDate = new Date(Date.UTC(
                        date.getFullYear(),
                        date.getMonth(),
                        date.getDate(),
                        date.getHours(),
                        date.getMinutes(),
                        date.getSeconds(),
                        date.getMilliseconds()
                    ));

                    // Format to ISO8601
                    field.value = utcDate.toISOString();
                }
            });

            // Append assignedUsers to formData
            formData.push({
                name: "assignedUsers",
                value: JSON.stringify(assignedUsers),
            });

            $.ajax({
                url: $(this).attr("action"),
                type: $(this).attr("method"),
                data: formData,
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
