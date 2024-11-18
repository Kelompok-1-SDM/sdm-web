<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenugasanModel;
use Illuminate\Support\Facades\Http;
use Yajra\DataTables\Facades\DataTables;

class PenugasanController extends Controller
{
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = env('API_BASE_URL', "ini harus url");
    }

    public function index()
{
    $breadcrumb = (object) [
        'title' => 'Data Penugasan',
        'list' => ['Home', 'Penugasan']
    ];
    $page = (object) [
        'title' => 'Data Penugasan'
    ];
    $activeMenu = 'penugasan';

    // New dummy data
    $penugasan = [
        (object)[
            'id' => 1,
            'nama' => 'Penugasan A',
            'jadwal' => '2024-11-20 10:00:00',
            'lokasi' => 'Jakarta',
            'kompetensi' => 'Manajemen Proyek',
        ],
        (object)[
            'id' => 2,
            'nama' => 'Penugasan B',
            'jadwal' => '2024-11-21 14:00:00',
            'lokasi' => 'Bandung',
            'kompetensi' => 'Pengembangan Sistem',
        ],
        (object)[
            'id' => 3,
            'nama' => 'Penugasan C',
            'jadwal' => '2024-11-22 09:00:00',
            'lokasi' => 'Surabaya',
            'kompetensi' => 'Analisis Data',
        ],
    ];

    return view('penugasan.index', [
        'breadcrumb' => $breadcrumb,
        'page' => $page,
        'penugasan' => $penugasan,
        'activeMenu' => $activeMenu
    ]);
}
    public function list(Request $request)
    {
        $response = Http::withAuthToken()->get("{$this->apiUrl}/api/user", [
            'role' => 'dosen'
        ]);

        // dd($response->json('data'));
        if ($response->successful()) {
            $data = $response->json('data');
            return DataTables::of($data)
                ->addIndexColumn()  // menambahkan kolom index / no urut (default name kolom: DT_RowIndex)  
                ->addColumn('aksi', function ($dosen) {  // menambahkan kolom aksi  
                    $btn  = '<button onclick="modalAction(\'' . url('/user/' . $dosen['userId'] .
                        '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/user/' . $dosen['userId'] .
                        '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/user/' . $dosen['userId'] .
                        '/delete_ajax') . '\')"  class="btn btn-danger btn-sm">Hapus</button> ';

                    return $btn;
                })
                ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html  
                ->make(true);
        }
    }
}