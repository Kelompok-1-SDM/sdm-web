<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class JabatanController extends Controller
{
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = env('API_BASE_URL', "ini harus url");
    }

    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Jabatan',
            'list' => ['Data Kegiatan', 'Jabatan']
        ];
        $page = (object) [
            'title' => 'Daftar jabatan yang terdaftar dalam sistem',
        ];
        $activeMenu = 'jabatan'; // set menu yang sedang aktif
        // Anda dapat menambahkan logika di sini
        return view('jabatan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function list()
    {
        $response = Http::withAuthToken()->get("{$this->apiUrl}/api/jabatan");

        // dd($response->json('data'));
        if ($response->successful()) {
            $data = $response->json('data');
            return DataTables::of($data)
                ->addIndexColumn()  // menambahkan kolom index / no urut (default name kolom: DT_RowIndex)  
                ->addColumn('aksi', function ($jabatan) {  // menambahkan kolom aksi  
                    $btn  = '<button onclick="modalAction(\'' . url('/jabatan/' . $jabatan['jabatanId'] .
                        '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                    if (session('role') != 'dosen') {
                        $btn .= '<button onclick="modalAction(\'' . url('/jabatan/' . $jabatan['jabatanId'] .
                            '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                        $btn .= '<button onclick="modalAction(\'' . url('/jabatan/' . $jabatan['jabatanId'] .
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
        return view('jabatan.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {

            $rules = [
                'nama_jabatan' => 'required',
                'is_pic' => 'required'
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

            $response = Http::withAuthToken()->post("{$this->apiUrl}/api/jabatan", $request->all());
            if ($response->successful()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Data jabatan berhasil disimpan',
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
        $response = Http::withAuthToken()->get("{$this->apiUrl}/api/jabatan", [
            'uid' => $id
        ]);

        if ($response->successful()) {
            return view('jabatan.confirm_ajax', ['jabatan' => $response->json('data')]);
        } else {
            return view('jabatan.confirm_ajax', ['jabatan' => null]);
        }
    }

    public function delete_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $response = Http::withAuthToken()
                ->withQueryParameters([
                    'uid' => $id
                ])
                ->delete("{$this->apiUrl}/api/jabatan");

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
        $response = Http::withAuthToken()->get("{$this->apiUrl}/api/jabatan", [
            'uid' => $id
        ]);

        if ($response->successful()) {
            return view('jabatan.show_ajax', ['jabatan' => $response->json('data')]);
        } else {
            return view('jabatan.show_ajax', ['jabatan' => null]);
        }
    }

    public function edit_ajax(string $id)
    {
        $response = Http::withAuthToken()->get("{$this->apiUrl}/api/jabatan", [
            'uid' => $id
        ]);

        if ($response->successful()) {
            return view('jabatan.edit_ajax', ['jabatan' => $response->json('data')]);
        } else {
            return view('jabatan.edit_ajax', ['jabatan' => null]);
        }
    }

    public function update_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {

            $rules = [
                'nama_jabatan' => 'required',
                'is_pic' => 'required'
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
                ->put("{$this->apiUrl}/api/jabatan", $request->all());

            if ($response->successful()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Data jabatan berhasil disimpan',
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
