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
    @method('PUT')
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
                    <label for="judulKegiatan">Nama Kegiatan</label>
                    <input type="text" name="judulKegiatan" id="judulKegiatan" class="form-control"
                        value="{{ $kegiatan['judulKegiatan'] }}" required>
                    <small id="error-judulKegiatan" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control"
                        value="{{ $kegiatan['tanggal'] ? \Carbon\Carbon::parse($kegiatan['tanggal'])->format('Y-m-d') : '' }}" required>
                    <small id="error-tanggal" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3" required>{{ $kegiatan['deskripsi'] }}</textarea>
                    <small id="error-deskripsi" class="error-text form-text text-danger"></small>
                </div>

                {{-- <div class="form-group">
                    <label for="kompetensi">Kompetensi</label>
                    <select name="kompetensi" id="kompetensi" class="form-control" required>
                        <option value="">- Pilih Kompetensi -</option>
                        @foreach ($kompetensi as $kompetensi)
                            <option value="{{ $kompetensi['kegiatanId'] }}" {{ $kompetensi['kegiatanId'] == $kegiatan['kompetensiId'] ? 'selected' : '' }}>
                                {{ $kompetensi ['nama'] }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-kompetensi" class="error-text form-text text-danger"></small>
                </div> --}}
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>
@endempty
