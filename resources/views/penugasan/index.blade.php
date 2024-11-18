@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="window.location='{{ url('/penugasan/import') }}'" class="btn btn-primary">Import</button>
                <button onclick="window.location='{{ url('/penugasan/create') }}'" class="btn btn-primary">Tambah</button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <table id="table-penugasan" class="table table-bordered table-striped table-hover table-sm">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Jadwal</th>
                        <th>Lokasi</th>
                        <th>Kompetensi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($penugasan as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->jadwal }}</td>
                            <td>{{ $item->lokasi }}</td>
                            <td>{{ $item->kompetensi }}</td>
                            <td>
                                <!-- Detail button -->
                                <button onclick="window.location='{{ url('/penugasan/'.$item->id) }}'" class="btn btn-info btn-sm">Detail</button>
                                <!-- Edit button -->
                                <button onclick="modalAction('{{ url('/penugasan/'.$item->id.'/edit') }}')" class="btn btn-warning btn-sm">Edit</button>
                                <!-- Delete button -->
                                <button onclick="if(confirm('Apakah Anda yakin ingin menghapus penugasan ini?')) { window.location='{{ url('/penugasan/'.$item->id.'/delete') }}'; }" class="btn btn-danger btn-sm">Hapus</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" data-backdrop="static" data-keyboard="false" data-width="75%"></div>
@endsection

@push('js')
<script>
    function modalAction(url = '') {
        $('#myModal').load(url, function() {
            $('#myModal').modal('show');
        });
    }
</script>
@endpush