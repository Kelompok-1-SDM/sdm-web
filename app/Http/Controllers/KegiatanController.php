<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Yajra\DataTables\Facades\DataTables;

class KegiatanController extends Controller
{
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = env('API_BASE_URL', "ini harus url");
    }

    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Data Kegiatan',
            'list' => ['Kegiatan', 'Kegiatan']
        ];
        $page = (object) [
            'title' => 'Daftar kegiatan yang terdaftar dalam sistem'
        ];
        $activeMenu = 'kegiatan';

        return view('kegiatan.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            //'kegiatan' => $kegiatan, 
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request)
    {
        $response = Http::withAuthToken()->get("{$this->apiUrl}/api/kegiatan");

        if ($response->successful()) {
            $data = $response->json('data');
            return DataTables::of($data)
                ->addIndexColumn()  // menambahkan kolom index / no urut (default name kolom: DT_RowIndex)  
                ->addColumn('aksi', function ($kegiatan) {  // menambahkan kolom aksi  
                    $btn  = '<a href="' . url('/kegiatan/' . $kegiatan['kegiatanId'] .
                        '/detail') . '" class="btn btn-info btn-sm">Detail</a> ';
                    // $btn .= '<button onclick="modalAction(\'' . url('/kegiatan/' . $kegiatan['kegiatanId'] .
                    //     '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                    // $btn .= '<button onclick="modalAction(\'' . url('/kegiatan/' . $kegiatan['kegiatanId'] .
                    //     '/delete_ajax') . '\')"  class="btn btn-danger btn-sm">Hapus</button> ';

                    return $btn;
                })
                ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html  
                ->make(true);
        }
    }

    public function show_ajax(string $id)
    {
        $response = Http::withAuthToken()->get("{$this->apiUrl}/api/kegiatan", [
            'uid' => $id
        ]);
        $dat = $response->json('data');
        return view('kegiatan.show_ajax', ['kegiatan' => $dat]);
    }

    public function detailKegiatan(string $id)
    {
        $response = Http::withAuthToken()->get("{$this->apiUrl}/api/kegiatan", [
            'uid' => $id
        ]);
        $breadcrumb = (object) [
            'title' => 'Detail Kegiatan',
            'list' => ['Kegiatan', 'Detail Kegiatan']
        ];

        if ($response->successful()) {
            $data = $response->json('data');

            return view('kegiatan.detail', [
                'breadcrumb' => $breadcrumb,
                'activeMenu' => 'apalah',
                'data' => $data
            ]);
        }
    }

    public function detailUser(Request $request)
    {
        $response = Http::withAuthToken()->get("{$this->apiUrl}/api/kegiatan", [
            'uid' => $request->uid
        ]);

        if ($response->successful()) {
            $data = $response->json('data.user');
            return DataTables::of($data)
                ->addIndexColumn()  // menambahkan kolom index / no urut (default name kolom: DT_RowIndex)  
                ->addColumn('aksi', function ($user) {  // menambahkan kolom aksi  
                    $btn  = '<button onclick="modalAction(\'' . url('/kegiatan/' . $user['userId'] .
                        '/show_ajax') . '\')" class="btn btn-info btn-sm"> Edit </button> ';
                    $btn  .= '<button onclick="modalAction(\'' . url('/kegiatan/' . $user['userId'] .
                        '/show_ajax') . '\')" class="btn btn-warning btn-sm"> Hapus </button> ';
                    $btn  .= '<button onclick="modalAction(\'' . url('/kegiatan/' . $user['userId'] .
                        '/show_ajax') . '\')" class="btn btn-danger btn-sm"> Hapus </button> ';
                    return $btn;
                })
                ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html  
                ->make(true);
        }
    }
}
