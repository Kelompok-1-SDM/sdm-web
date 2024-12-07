<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class TipeKegiatanController extends Controller
{
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = env('API_BASE_URL', "https://restapi-sdm.lleans.dev");
    }

    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Tipe Kegiatan',
            'list' => ['Data Kegiatan', 'Tipe Kegiatan']
        ];
        $page = (object) [
            'title' => 'Daftar tipe kegiatan yang terdaftar dalam sistem',
        ];
        $activeMenu = 'tipekegiatan'; // set menu yang sedang aktif
        // Anda dapat menambahkan logika di sini
        return view('tipekegiatan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request)
    {
        $response = Http::withAuthToken()->get("{$this->apiUrl}/api/tipekegiatan");

        // dd($response->json('data'));
        if ($response->successful()) {
            $data = $response->json('data');
            return DataTables::of($data)
                ->addIndexColumn()  // menambahkan kolom index / no urut (default name kolom: DT_RowIndex)  
                ->addColumn('aksi', function ($tipekegiatan) {  // menambahkan kolom aksi  
                    $btn  = '<button onclick="modalAction(\'' . url('/tipekegiatan/' . $tipekegiatan['tipeKegiatanId'] .
                        '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                    if (session('role') != 'dosen') {
                        $btn .= '<button onclick="modalAction(\'' . url('/tipekegiatan/' . $tipekegiatan['tipeKegiatanId'] .
                            '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                        $btn .= '<button onclick="modalAction(\'' . url('/tipekegiatan/' . $tipekegiatan['tipeKegiatanId'] .
                            '/delete_ajax') . '\')"  class="btn btn-danger btn-sm">Hapus</button> ';
                    }


                    return $btn;
                })
                ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html  
                ->make(true);
        }
    }

    public function create_ajax()
    {
        return view('tipekegiatan.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {

            $rules = [
                'tipe_kegiatan' => 'required',
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

            $response = Http::withAuthToken()->post("{$this->apiUrl}/api/tipekegiatan", [
                'nama_tipe_kegiatan' => $request->tipe_kegiatan
            ]);
            if ($response->successful()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Data tipekegiatan berhasil disimpan',
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
        $response = Http::withAuthToken()->get("{$this->apiUrl}/api/tipekegiatan", [
            'uid' => $id
        ]);

        if ($response->successful()) {
            return view('tipekegiatan.confirm_ajax', ['tipekegiatan' => $response->json('data')]);
        } else {
            return view('tipekegiatan.confirm_ajax', ['tipekegiatan' => null]);
        }
    }

    public function delete_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $response = Http::withAuthToken()
                ->withQueryParameters([
                    'uid' => $id
                ])
                ->delete("{$this->apiUrl}/api/tipekegiatan");

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
        $response = Http::withAuthToken()->get("{$this->apiUrl}/api/tipekegiatan", [
            'uid' => $id
        ]);

        if ($response->successful()) {
            return view('tipekegiatan.show_ajax', ['tipekegiatan' => $response->json('data')]);
        } else {
            return view('tipekegiatan.show_ajax', ['tipekegiatan' => null]);
        }
    }

    public function edit_ajax(string $id)
    {
        $response = Http::withAuthToken()->get("{$this->apiUrl}/api/tipekegiatan", [
            'uid' => $id
        ]);

        if ($response->successful()) {
            return view('tipekegiatan.edit_ajax', ['tipekegiatan' => $response->json('data')]);
        } else {
            return view('tipekegiatan.edit_ajax', ['tipekegiatan' => null]);
        }
    }

    public function update_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {

            $rules = [
                'tipe_kegiatan' => 'required',
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
                ->put("{$this->apiUrl}/api/tipekegiatan", [
                    'nama_tipe_kegiatan' => $request->tipe_kegiatan
                ]);

            if ($response->successful()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Data tipekegiatan berhasil disimpan',
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
