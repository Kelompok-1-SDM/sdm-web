<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Yajra\DataTables\Facades\DataTables;

class KompetensiController extends Controller
{
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = env('API_BASE_URL', "https://restapi-sdm.lleans.dev");
    }

    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Kompetensi',
            'list' => ['Data Kegiatan', 'Kompetensi']
        ];
        $page = (object) [
            'title' => 'Daftar kompetensi yang terdaftar dalam sistem',
        ];
        $activeMenu = 'kompetensi'; // set menu yang sedang aktif
        // Anda dapat menambahkan logika di sini
        return view('kompetensi.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request)
    {
        $response = Http::withAuthToken()->get("{$this->apiUrl}/api/kompetensi");

        // dd($response->json('data'));
        if ($response->successful()) {
            $data = $response->json('data');
            return DataTables::of($data)
                ->addIndexColumn()  // menambahkan kolom index / no urut (default name kolom: DT_RowIndex)  
                ->addColumn('aksi', function ($kompetensi) {  // menambahkan kolom aksi  
                    $btn  = '<button onclick="modalAction(\'' . url('/user/' . $kompetensi['kompetensiId'] .
                        '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/user/' . $kompetensi['kompetensiId'] .
                        '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/user/' . $kompetensi['kompetensiId'] .
                        '/delete_ajax') . '\')"  class="btn btn-danger btn-sm">Hapus</button> ';

                    return $btn;
                })
                ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html  
                ->make(true);
        }
    }
}
