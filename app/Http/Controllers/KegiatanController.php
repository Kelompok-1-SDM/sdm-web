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
            'list' => ['Data Kegiatan', 'Kegiatan']
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

    public function detailKegiatan(string $id)
    {
        $response = Http::withAuthToken()->get("{$this->apiUrl}/api/kegiatan", [
            'uid' => $id
        ]);
        $responseJabatan = Http::withAuthToken()->get("{$this->apiUrl}/api/jabatan");

        $breadcrumb = (object) [
            'title' => 'Detail Kegiatan',
            'list' => ['Kegiatan', 'Detail Kegiatan']
        ];

        if ($response->successful()) {
            $data = $response->json('data');

            return view('kegiatan.detail', [
                'breadcrumb' => $breadcrumb,
                'activeMenu' => 'apalah',
                'jabatan' => $responseJabatan->json('data'),
                'data' => $data
            ]);
        }
    }

    public function anggota_create_ajax(string $id)
    {
        $response = Http::withAuthToken()->get("{$this->apiUrl}/api/user", ['role' => 'dosen']);
        $responseJabatan = Http::withAuthToken()->get("{$this->apiUrl}/api/jabatan", ['role' => 'dosen']);
        return view('kegiatan.anggota.create_ajax', ['dosen' => $response->json('data'), 'id' => $id, 'jabatan' => $responseJabatan->json('data')]);
    }

    public function anggota_store_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Define the validation rules
            $rules = [
                'assigned_users' => 'required|array',
                'assigned_users.*.userId' => 'required|distinct', // Each userId must be provided and unique
                'assigned_users.*.jabatan' => 'required|string',    // Each role must be provided
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

            // Prepare the formatted data
            $newReq = [
                'list_user_ditugaskan' => []
            ];

            foreach ($request->assigned_users as $user) {
                $newReq['list_user_ditugaskan'][] = [
                    'uid_user' => $user['userId'],
                    'uid_jabatan'   => $user['jabatan']
                ];
            }

            // Make the API request with the formatted data
            $response = Http::withAuthToken()
                ->withQueryParameters(['uid_kegiatan' => $id])
                ->post("{$this->apiUrl}/api/penugasan", $newReq);

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

    public function anggota_edit_ajax(Request $request, string $id)
    {
        $userData = json_decode($request->query('data'), true);
        if ($userData['status'] == 'ditugaskan') {
            $response = Http::withAuthToken()->get("{$this->apiUrl}/api/user", ['role' => 'dosen']);
            return view('kegiatan.anggota.edit_ajax', ['dosen' => $response->json('data'), 'id' => $id, 'current' => $userData]);
        }

        return view('kegiatan.anggota.edit_ajax', ['id' => $id, 'current' => $userData]);
    }

    public function anggota_update_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Define the validation rules
            $rules = [
                'userId' => 'required|string',
                'jabatan' => 'required|string',    // Each role must be provided
                'status' => 'required|string',
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

            // Prepare the formatted data
            $newReq = [
                'list_user_ditugaskan' => []
            ];

            $newReq['list_user_ditugaskan'][] = [
                'uid_user' => $request->userId,
                'uid_jabatan'   => $request->jabatan,
                'status' => $request->status
            ];

            // Make the API request with the formatted data
            $response = Http::withAuthToken()
                ->withQueryParameters(['uid_kegiatan' => $id])
                ->put("{$this->apiUrl}/api/penugasan", $newReq);

            if ($response->successful()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Data dosen berhasil di update',
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

    public function anggota_show_ajax(Request $request)
    {
        $userData = json_decode($request->query('data'), true);
        return view('kegiatan.anggota.show_ajax', ['penugasan' => $userData]);
    }

    public function anggota_confirm_ajax(Request $request, string $id)
    {
        $userData = json_decode($request->query('data'), true);
        return view('kegiatan.anggota.confirm_ajax', ['penugasan' => $userData, 'id' => $id]);
    }

    public function anggota_delete_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $response = Http::withAuthToken()
                ->withQueryParameters([
                    'uid_kegiatan' => $id,
                    'uid_user' => $request->userId
                ])
                ->delete("{$this->apiUrl}/api/penugasan");

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

    public function edit_ajax(Request $request)
    {
        $kegData = json_decode($request->query('data'), true);

        $responseKompetensi = Http::withAuthToken()->get(
            "{$this->apiUrl}/api/kompetensi",
        );

        if ($responseKompetensi->successful()) {
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
            return view('kegiatan.edit_ajax', [
                'breadcrumb' => $breadcrumb,
                'kompetensi' => $kompetensi,
                'page' => $page,
                'activeMenu' => $activeMenu,
                'kegiatan' => $kegData
            ]);
        } else {
            // Jika respons gagal, tampilkan error atau redirect
            return redirect()->back()->withErrors(['error' => 'Gagal mengambil data kegiatan.']);
        }
    }

    public function update_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Validation rules
            $rules = [
                'nama_kegiatan' => 'nullable',
                'tanggal_mulai' => 'nullable',
                'tanggal_selesai' => 'nullable',
                'deskripsi' => 'nullable',
                'lokasi' => 'nullable',
                'assigned_kompetensis' => 'required|array',
                'assigned_kompetensis.*.kompetensiId' => 'required|distinct'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors(),
                ]);
            }

            // Extract all 'kompetensiId' values from 'assigned_kompetensis'
            $kompetensiIds = [];

            foreach ($request->assigned_kompetensis as $kompetensi) {
                // Ensure 'kompetensiId' is added to the array
                $kompetensiIds[] = $kompetensi['kompetensiId'];
            }

            // Combine the kompetensiIds with other request data
            $payload = [
                'nama_kegiatan' => $request->nama_kegiatan,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'deskripsi' => $request->deskripsi,
                'lokasi' => $request->lokasi,
                'list_kompetensi' => $kompetensiIds, // This is the array of kompetensiId values
            ];

            // Send data to external API to update 'kegiatan'
            $response = Http::withAuthToken()
                ->withQueryParameters([
                    'uid' => $id
                ])
                ->put("{$this->apiUrl}/api/kegiatan/", $payload);

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


    public function confirm_ajax(Request $request)
    {
        $kegiatan = json_decode($request->query('data'), true);
        return view('kegiatan.confirm_ajax', ['kegiatan' => $kegiatan]);
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

    public function create_agenda(Request $request)
    {
        $response = Http::withAuthToken()->get("{$this->apiUrl}/api/agenda",
        );

        if ($response->successful()) {
            return view('kegiatan.create_agenda', ['agenda' => $response->json('data')]);
        } else {
            return view('kegiatan.create_agenda', ['agenda' => null]);
        }
    }
}
