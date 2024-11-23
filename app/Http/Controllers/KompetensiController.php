<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class KompetensiController extends Controller
{
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = env('API_BASE_URL', "ini harus url");
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
                    $btn  = '<button onclick="modalAction(\'' . url('/kompetensi/' . $kompetensi['kompetensiId'] .
                        '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/kompetensi/' . $kompetensi['kompetensiId'] .
                        '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/kompetensi/' . $kompetensi['kompetensiId'] .
                        '/delete_ajax') . '\')"  class="btn btn-danger btn-sm">Hapus</button> ';

                    return $btn;
                })
                ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html  
                ->make(true);
        }
    }

    public function create_ajax()
    {
        return view('kompetensi.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {

            $rules = [
                'namaKompetensi' => 'required',
            ];

            // Validate the request
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $response = Http::withAuthToken()->post("{$this->apiUrl}/api/kompetensi", $request->all());
            if ($response->successful()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Data kompetensi berhasil disimpan',
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => $response->json('message'),
                ]);
            }
        }

        return redirect('/');
    }

    public function confirm_ajax(string $id)
    {
        $response = Http::withAuthToken()->get("{$this->apiUrl}/api/kompetensi", [
            'uid' => $id
        ]);

        if ($response->successful()) {
            return view('kompetensi.confirm_ajax', ['kompetensi' => $response->json('data')]);
        } else {
            return view('kompetensi.confirm_ajax', ['kompetensi' => null]);
        }
    }

    public function delete_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $response = Http::withAuthToken()
                ->withQueryParameters([
                    'uid' => $id
                ])
                ->delete("{$this->apiUrl}/api/kompetensi");

            if ($response->successful()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => $response->json('message')
                ]);
            }
            return redirect('/');
        }
    }

    public function show_ajax(string $id)
    {
        $response = Http::withAuthToken()->get("{$this->apiUrl}/api/kompetensi", [
            'uid' => $id
        ]);

        if ($response->successful()) {
            return view('kompetensi.show_ajax', ['kompetensi' => $response->json('data')]);
        } else {
            return view('kompetensi.show_ajax', ['kompetensi' => null]);
        }
    }

    public function edit_ajax(string $id)
    {
        $response = Http::withAuthToken()->get("{$this->apiUrl}/api/kompetensi", [
            'uid' => $id
        ]);

        if ($response->successful()) {
            return view('kompetensi.edit_ajax', ['kompetensi' => $response->json('data')]);
        } else {
            return view('kompetensi.edit_ajax', ['kompetensi' => null]);
        }
    }

    public function update_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {

            $rules = [
                'namaKompetensi' => 'required',
            ];

            // Validate the request
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $response = Http::withAuthToken()
                ->withQueryParameters(['uid' => $id])
                ->put("{$this->apiUrl}/api/kompetensi", $request->all());

            if ($response->successful()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Data kompetensi berhasil disimpan',
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => $response->json('data'),
                ]);
            }
        }


        return redirect('/');
    }
}
