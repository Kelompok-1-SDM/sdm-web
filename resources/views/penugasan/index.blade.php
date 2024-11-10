@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="window.location='{{ url('/penugasan/import') }}'" class="btn btn-sm btn-success mt-1">Import</button>
                <button onclick="window.location='{{ url('/penugasan/create') }}'" class="btn btn-sm btn-success">Tambah</button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered table-striped table-hover table-sm">
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
                    {{-- @foreach ($penugasan as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->jadwal }}</td>
                            <td>{{ $item->lokasi }}</td>
                            <td>{{ $item->kompetensi }}</td>
                            <td>
                                <a href="{{ url('/penugasan/' . $item->id) }}" class="btn btn-info btn-sm">Detail</a>
                                <a href="{{ url('/penugasan/' . $item->id . '/edit') }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ url('/penugasan/' . $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach --}}
                </tbody>
            </table>
        </div>
    </div>
@endsection
