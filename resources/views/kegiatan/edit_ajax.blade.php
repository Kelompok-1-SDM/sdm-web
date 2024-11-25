@empty($kegiatan)
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
                    Data yang Anda cari tidak ditemukan.
                </div>
                <a href="{{ url('/kegiatan') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/kegiatan/' . $kegiatan['kegiatanId'] . '/update_ajax') }}" method="POST" id="form-edit">
        @csrf
        @method('POST')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Data Kegiatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="judul">Nama Kegiatan</label>
                        <input type="text" name="judul" id="judul" class="form-control"
                            value="{{ $kegiatan['judul'] }}" required>
                        <small id="error-judul" class="error-text form-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label for="tanggalMulai">Tanggal Mulai</label>
                        <input type="date" name="tanggalMulai" id="tanggalMulai" class="form-control"
                            value="{{ $kegiatan['tanggalMulai'] ? \Carbon\Carbon::parse($kegiatan['tanggalMulai'])->format('Y-m-d') : '' }}"
                            required>
                        <small id="error-tanggalMulai" class="error-text form-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label for="tanggalAkhir">Tanggal</label>
                        <input type="date" name="tanggalAkhir" id="tanggalAkhir" class="form-control"
                            value="{{ $kegiatan['tanggalAkhir'] ? \Carbon\Carbon::parse($kegiatan['tanggalAkhir'])->format('Y-m-d') : '' }}"
                            required>
                        <small id="error-tanggalAkhir" class="error-text form-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label for="lokasi">Lokasi</label>
                        <input type="text" name="lokasi" id="lokasi" class="form-control"
                            value="{{ $kegiatan['lokasi'] }}" required>
                        <small id="error-lokasi" class="error-text form-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label for="tipeKegiatan">Tipe Kegiatan</label>
                        <select name="tipeKegiatan" id="tipeKegiatan" class="form-control" required>
                            <option value="">- Pilih Tipe Kegiatan -</option>
                            <option value="jti" {{ $kegiatan['tipeKegiatan'] == 'jti' ? 'selected' : '' }}>JTI</option>
                            <option value="non-jti" {{ $kegiatan['tipeKegiatan'] == 'non-jti' ? 'selected' : '' }}>Non-JTI
                            </option>
                        </select>
                        <small id="error-tipeKegiatan" class="error-text form-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3" required>{{ $kegiatan['deskripsi'] }}</textarea>
                        <small id="error-deskripsi" class="error-text form-text text-danger"></small>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Kompetensi</label>
                                <select name="kompetensiId" id="kompetensiId" class="form-control">
                                    <option value="">- Pilih Kompetensi -</option>
                                    @foreach ($kompetensi as $l)
                                        <option value="{{ $l['kompetensiId'] }}">{{ $l['namaKompetensi'] }}</option>
                                    @endforeach
                                </select>
                                <small id="error-kompetensiId" class="error-text form-text text-danger"></small>
                            </div>
                        </div>
                        <!-- Aligned buttons to the right -->
                        <div class="card-footer text-right">
                            <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                            <button type="button" id="add-kompetensi" class="btn btn-primary">Tambahkan</button>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header">
                            <h3 class="card-title">Daftar Kompetensi</h3>
                        </div>
                        <div class="card-body">
                            <ul class="todo-list" id="assigned-kompetensis-list" data-widget="todo-list">
                                @if (!empty($kegiatan['kompetensi']))
                                    @foreach ($kegiatan['kompetensi'] as $kompetensiName)
                                        @php
                                            // Find the kompetensiId by matching the name with the $kompetensi list
                                            $kompetensiItem = collect($kompetensi)->firstWhere(
                                                'namaKompetensi',
                                                $kompetensiName['namaKompetensi'],
                                            );
                                        @endphp
                                        @if ($kompetensiItem)
                                            <li id="kompetensi-item-{{ $kompetensiItem['kompetensiId'] }}">
                                                <span class="text">{{ $kompetensiItem['namaKompetensi'] }}</span>
                                                <button type="button"
                                                    class="btn btn-danger btn-sm float-right remove-kompetensi mr-2"
                                                    data-kompetensi-id="{{ $kompetensiItem['kompetensiId'] }}">Remove</button>
                                                <input type="hidden"
                                                    name="assigned_kompetensis[{{ $kompetensiItem['kompetensiId'] }}][kompetensiId]"
                                                    value="{{ $kompetensiItem['kompetensiId'] }}">
                                            </li>
                                        @endif
                                    @endforeach
                                @endif
                            </ul>
                        </div>
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
        $(document).ready(function() {
            // Prepopulate assignedKompetensi with existing kompetensi from kegiatan
            let assignedKompetensi = {};
            @if (!empty($kegiatan['kompetensi']))
                @foreach ($kegiatan['kompetensi'] as $kompetensiName)
                    @php
                        // Find the kompetensiId by matching the name with the $kompetensi list
                        $kompetensiItem = collect($kompetensi)->firstWhere('namaKompetensi', $kompetensiName);
                    @endphp
                    @if ($kompetensiItem)
                        assignedKompetensi["{{ $kompetensiItem['kompetensiId'] }}"] = {
                            kompetensiId: "{{ $kompetensiItem['kompetensiId'] }}"
                        };
                    @endif
                @endforeach
            @endif

            // Add kompetensi to the list
            $("#add-kompetensi").on("click", function() {
                let kompetensiId = $("#kompetensiId").val();
                let kompetensiName = $("#kompetensiId option:selected").text();

                // Validate input
                if (!kompetensiId) {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Pastikan semua field telah diisi."
                    });
                    return;
                }

                // Prevent duplicate kompetensi
                if (assignedKompetensi[kompetensiId]) {
                    // Swal.fire({
                    //     icon: "warning",
                    //     title: "Kompetensi Sudah Ada",
                    //     text: "Kompetensi ini sudah ditambahkan."
                    // });
                    // Reset the dropdowns
                    $("#kompetensiId").val("");
                    return;
                }

                // Add new kompetensi
                assignedKompetensi[kompetensiId] = {
                    kompetensiId
                };
                let listItem = `
                    <li id="kompetensi-item-${kompetensiId}">
                        <span class="text">${kompetensiName}</span>
                        <button type="button" class="btn btn-danger btn-sm float-right remove-kompetensi mr-2" 
                            data-kompetensi-id="${kompetensiId}">Remove</button>
                        <input type="hidden" name="assigned_kompetensis[${kompetensiId}][kompetensiId]" 
                            value="${kompetensiId}">
                    </li>`;
                $("#assigned-kompetensis-list").append(listItem);

                // Reset the dropdown
                $("#kompetensiId").val("");
            });

            // Remove kompetensi from the list
            $(document).on("click", ".remove-kompetensi", function() {
                let kompetensiId = $(this).data("kompetensi-id");
                delete assignedKompetensi[kompetensiId];
                $(`#kompetensi-item-${kompetensiId}`).remove();
            });

            // Form submission
            $("#form-edit").submit(function(e) {
                e.preventDefault();
                let form = $(this);

                // Check if any kompetensis have been added
                if (Object.keys(assignedKompetensi).length === 0) {
                    Swal.fire({
                        icon: "warning",
                        title: "Tidak Ada Kompetensi Ditambahkan",
                        text: "Tambahkan setidaknya satu kompetensi sebelum menyimpan."
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
@endempty
