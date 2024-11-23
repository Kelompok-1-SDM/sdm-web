<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
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

    public function edit_ajax(string $id)
    {
        $response = Http::withAuthToken()->get("{$this->apiUrl}/api/kegiatan", [
            'uid' => $id
        ]);

        $responseKompetensi = Http::withAuthToken()->get(
            "{$this->apiUrl}/api/kompetensi",
        );

        if ($response->successful()) {
            $data = $response->json('data'); // Ambil data dari API jika respons berhasil
            $kompetensi = $responseKompetensi->json('data');

            $breadcrumb = (object) [
                'title' => 'Edit Kegiatan',
                'list' => ['Kegiatan', 'Edit Kegiatan']
            ];

            $page = (object) [
                'title' => 'Edit Data Kegiatan'
            ];

            $activeMenu = 'kegiatan';

            // Render view dengan data yang relevan
            return view('kegiatan.edit', [
                'breadcrumb' => $breadcrumb,
                'kompetensi' => $kompetensi,
                'page' => $page,
                'activeMenu' => $activeMenu,
                'kegiatan' => $data
            ]);
        } else {
            // Jika respons gagal, tampilkan error atau redirect
            return redirect()->back()->withErrors(['error' => 'Gagal mengambil data kegiatan.']);
        }
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Validasi data
            $rules = [
                'nama_kegiatan' => 'required|string|max:255',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                'deskripsi' => 'nullable|string|max:1000',
                'lokasi' => 'required|string|max:255',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors(),
                ]);
            }

            // Ambil data kegiatan
            $response = Http::withAuthToken()->put("{$this->apiUrl}/api/kegiatan/{$id}", [
                'nama_kegiatan' => $request->nama_kegiatan,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'deskripsi' => $request->deskripsi,
                'lokasi' => $request->lokasi,
            ]);

            if ($response->successful()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Data kegiatan berhasil diperbarui.',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal memperbarui data kegiatan.',
                    'errors' => $response->json('errors'),
                ]);
            }
        }
    }

    public function confirm_ajax(string $id)
    {
        $response = Http::withAuthToken()->get("{$this->apiUrl}/api/kegiatan", [
            'uid' => $id
        ]);

        if ($response->successful()) {
            $kegiatan = $response->json('data');
            return view('kegiatan.confirm_ajax', ['kegiatan' => $kegiatan]);
        } else {
            return redirect()->route('kegiatan.confirm_ajax')->with('error', 'Data kegiatan tidak ditemukan.');
        }
    }


    public function delete_ajax(Request $request, string $id)
    {
        if ($request->ajax()) {
            $response = Http::withAuthToken()->withQueryParameters(['uid' => $id])->delete(
                "{$this->apiUrl}/api/kegiatan",
            );

            if ($response->successful()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Data kegiatan berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal menghapus data kegiatan'
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid request'
        ], 400);
    }
}
