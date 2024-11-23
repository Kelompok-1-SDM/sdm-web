<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DosenController extends Controller
{
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = env('API_BASE_URL', "ini harus url");
    }

    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Dosen',
            'list' => ['Data Pengguna', 'Dosen']
        ];
        $page = (object) [
            'title' => 'Daftar dosen yang terdaftar dalam sistem',
        ];
        $activeMenu = 'dosen'; // set menu yang sedang aktif
        // Anda dapat menambahkan logika di sini
        return view('dosen.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
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
                    $btn  = '<button onclick="modalAction(\'' . url('/dosen/' . $dosen['userId'] .
                        '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/dosen/' . $dosen['userId'] .
                        '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/dosen/' . $dosen['userId'] .
                        '/delete_ajax') . '\')"  class="btn btn-danger btn-sm">Hapus</button> ';

                    return $btn;
                })
                ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html  
                ->make(true);
        }
    }

    public function create_ajax()
    {
        return view('dosen.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {

            $rules = [
                'role' => 'required',
                'nip' => 'required',
                'nama' => 'required',
                'email' => 'required',
                'password' => 'required|min:6',
                'file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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

            if ($request->hasFile('file')) {
                $image = $request->file('file');
                $response = Http::withAuthToken()->attach('file', $image->get(), 'apa.jpeg')->post("{$this->apiUrl}/api/user", $request->all());
            } else {
                $response = Http::withAuthToken()->post("{$this->apiUrl}/api/user", $request->all());
            }

            if ($response->successful()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Data dosen berhasil disimpan',
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

    public function import()
    {
        return view('dosen.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                // validasi file harus xls atau xlsx, max 1MB 
                'file' => ['required', 'mimes:xlsx', 'max:1024']
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file');  // ambil file dari request 
            $response = Http::withAuthToken()
                ->attach('file', $file->get(), 'apa.xlsx')
                ->withQueryParameters([
                    'role' => 'dosen'
                ])
                ->post("{$this->apiUrl}/api/user/import");

            if ($response->successful()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Data dosen berhasil diimport',
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

    public function export_excel()
    {
        $response = Http::withAuthToken()->get("{$this->apiUrl}/api/user/export", [
            'role' => 'dosen'
        ]);

        if ($response->successful()) {
            // Retrieve file content and metadata
            $fileContent = $response->body();
            $fileName = "dosen_" . date('Y-m-d H:i:s') . ".xlsx";

            // Return the file as a response
            return response($fileContent)
                ->header('Content-Type', $response->header('Content-Type'))
                ->header('Content-Disposition', "attachment; filename=\"{$fileName}\"");
        }

        return response()->json([
            'status' => false,
            'message' => 'Failed to fetch the file from the API.',
        ], $response->status());
    }

    public function confirm_ajax(string $id)
    {
        $response = Http::withAuthToken()->get("{$this->apiUrl}/api/user", [
            'uid' => $id
        ]);

        if ($response->successful()) {
            return view('dosen.confirm_ajax', ['dosen' => $response->json('data')]);
        } else {
            return view('dosen.confirm_ajax', ['dosen' => null]);
        }
    }

    public function delete_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $response = Http::withAuthToken()
                ->withQueryParameters([
                    'uid' => $id
                ])
                ->delete("{$this->apiUrl}/api/user");

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
        $response = Http::withAuthToken()->get("{$this->apiUrl}/api/user", [
            'uid' => $id
        ]);

        if ($response->successful()) {
            return view('dosen.show_ajax', ['dosen' => $response->json('data')]);
        } else {
            return view('dosen.show_ajax', ['dosen' => null]);
        }
    }

    public function edit_ajax(string $id)
    {
        $response = Http::withAuthToken()->get("{$this->apiUrl}/api/user", [
            'uid' => $id
        ]);

        if ($response->successful()) {
            return view('dosen.edit_ajax', ['dosen' => $response->json('data')]);
        } else {
            return view('dosen.edit_ajax', ['dosen' => null]);
        }
    }

    public function update_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {

            $rules = [
                'role' => 'required',
                'nip' => 'nullable',
                'nama' => 'nullable',
                'email' => 'nullable',
                'password' => 'nullable|min:6',
                'file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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

            // Remove empty values (null or empty strings) from the input data
            $data = array_filter($request->except(['file']), function ($value) {
                return $value !== null && $value !== '';
            });

            if ($request->hasFile('file')) {
                $image = $request->file('file');
                $response = Http::withAuthToken()
                    ->attach('file', $image->get(), 'apa.jpeg')
                    ->withQueryParameters(['uid' => $id])
                    ->put("{$this->apiUrl}/api/user", $data);
            } else {
                $response = Http::withAuthToken()
                    ->withQueryParameters(['uid' => $id])
                    ->put("{$this->apiUrl}/api/user", $data);
            }

            if ($response->successful()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Data dosen berhasil disimpan',
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
