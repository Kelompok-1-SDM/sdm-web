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

    private function check_jabatan_in_kegiatan(array $users)
    {
        // Find the user with the given userId
        foreach ($users as $user) {
            if (isset($user['userId']) && $user['userId'] === session('user_id')) {
                if ($user['isPic']) {
                    return true;
                }
            }
        }

        return false;
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

        $response = Http::withAuthToken()->get("{$this->apiUrl}/api/tipekegiatan");

        if ($response->successful()) {
            $data = $response->json('data');
            return view('kegiatan.index', [
                'breadcrumb' => $breadcrumb,
                'page' => $page,
                'tipe_kegiatan' => $data,
                'activeMenu' => $activeMenu
            ]);
        }
    }

    public function list()
    {
        if (session('role') == 'dosen') {
            $response = Http::withAuthToken()->withQueryParameters(['uid_user' => session('user_id')])->get("{$this->apiUrl}/api/kegiatan");
        } else {
            $response = Http::withAuthToken()->get("{$this->apiUrl}/api/kegiatan");
        }

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
            $isPic = $this->check_jabatan_in_kegiatan($response->json('data.users'));
            $data = $response->json('data');

            return view('kegiatan.detail', [
                'breadcrumb' => $breadcrumb,
                'activeMenu' => 'apalah',
                'jabatan' => $responseJabatan->json('data'),
                'isPic' => $isPic,
                'data' => $data
            ]);
        }
    }

    public function anggota_create_ajax(string $id)
    {
        $response = Http::withAuthToken()->get("{$this->apiUrl}/api/user", ['role' => 'dosen']);
        $dosen = collect($response->json('data'))->sortBy('totalJumlahKegiatan')->toArray();
        $responseJabatan = Http::withAuthToken()->get("{$this->apiUrl}/api/jabatan", ['role' => 'dosen']);
        return view('kegiatan.anggota.create_ajax', ['dosen' => $dosen, 'id' => $id, 'jabatan' => $responseJabatan->json('data')]);
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
        $response = Http::withAuthToken()->get("{$this->apiUrl}/api/jabatan");
        return view('kegiatan.anggota.edit_ajax', ['jabatan' => $response->json('data'), 'id' => $id, 'current' => $userData]);
    }

    public function anggota_update_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Define the validation rules
            $rules = [
                'jabatan_id' => 'required|string',
                'user_id' => 'required|string'
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
                'uid_user' => $request->user_id,
                'uid_jabatan'   => $request->jabatan_id,
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

    public function create_ajax()
    {
        $response = Http::withAuthToken()->get("{$this->apiUrl}/api/tipekegiatan");

        if ($response->successful()) {
            $data = $response->json('data');
            return view('kegiatan.create_ajax', ['tipe_kegiatan' => $data]);
        }
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Validation rules
            $rules = [
                'judul_kegiatan' => 'required',
                'tipe_kegiatan_uid' => 'required',
                'lokasi' => 'required',
                'tanggal_mulai' => 'required',
                'tanggal_akhir' => 'required',
                'deskripsi' => 'required',
                'is_done' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors(),
                ]);
            }

            // Send data to external API to update 'kegiatan'
            $response = Http::withAuthToken()
                ->post("{$this->apiUrl}/api/kegiatan/", $request->all());

            if ($response->successful()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Data kegiatan berhasil disimpan.',
                ]);
            }
        }
    }

    public function edit_ajax(Request $request)
    {
        $kegData = json_decode($request->query('data'), true);
        $response = Http::withAuthToken()->get("{$this->apiUrl}/api/tipekegiatan");

        // Render view dengan data yang relevan
        if ($response->successful()) {
            return view('kegiatan.edit_ajax', [
                'tipe_kegiatan' => $response->json('data'),
                'kegiatan' => $kegData
            ]);
        }
    }

    public function update_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Validation rules
            $rules = [
                'judul_kegiatan' => 'nullable',
                'tipe_kegiatan_uid' => 'nullable',
                'lokasi' => 'nullable',
                'tanggal_mulai' => 'nullable',
                'tanggal_akhir' => 'nullable',
                'deskripsi' => 'nullable',
                'is_done' => 'nullable'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors(),
                ]);
            }

            // Send data to external API to update 'kegiatan'
            $response = Http::withAuthToken()
                ->withQueryParameters([
                    'uid' => $id
                ])
                ->put("{$this->apiUrl}/api/kegiatan/", $request->all());

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

    public function lampiran_create_ajax(string $id)
    {
        return view('kegiatan.lampiran.create_ajax', ['id' => $id]);
    }

    public function lampiran_store_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                // validasi file harus xls atau xlsx, max 1MB 
                'file' => [
                    'required',
                    'max:10240', // 10MB
                    'mimetypes:application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/pdf,text/plain,image/jpeg,image/png,image/gif,image/svg+xml,image/bmp,image/webp'
                ]
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
                ->attach('files', $file->get(), $file->getClientOriginalName())
                ->withQueryParameters([
                    'uid_kegiatan' => $id
                ])
                ->post("{$this->apiUrl}/api/lampiran");

            if ($response->successful()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Data lampiran berhasil diimport',
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

    public function lampiran_delete_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Send the formatted data to the API
            $response = Http::withAuthToken()
                ->withQueryParameters([
                    'uid' => $id
                ])
                ->delete("{$this->apiUrl}/api/lampiran");

            if ($response->successful()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Lampiran berhasil dihapus',
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => $response->json('message', 'Terjadi kesalahan saat mengahapus lampiran.'),
                ]);
            }
        }

        return redirect('/');
    }

    public function agenda_create_ajax(string $id)
    {
        $response = Http::withAuthToken()->get("{$this->apiUrl}/api/penugasan", ['uid_kegiatan' => $id]);
        if ($response->successful()) {

            return view('kegiatan.agenda.create_ajax', ['penugasan' => $response->json('data'), 'id' => $id]);
        }

        return view('kegiatan.agenda.create_ajax', ['error' => $response->json('data.message'), 'penugasan' => null, 'id' => $id]);
    }

    public function agenda_store_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_agenda' => 'required',
                'jadwal_agenda' => 'required|date', // Validate as a date
                'deskripsi_agenda' => 'required|string',
                'is_done' => 'nullable|in:true,false', // Explicit validation for boolean-like strings
                'list_uid_user_kegiatan' => 'required|array|min:1', // Must be an array
                'list_uid_user_kegiatan.*' => 'required|string', // Each item must be a string
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            // Prepare data for API submission
            $formattedData = [
                'nama_agenda' => $request->nama_agenda,
                'jadwal_agenda' => $request->jadwal_agenda,
                'deskripsi_agenda' => $request->deskripsi_agenda,
                'is_done' => $request->is_done,
                'list_uid_user_kegiatan' => $request->list_uid_user_kegiatan,
            ];

            // dd($formattedData);

            // Send the formatted data to the API
            $response = Http::withAuthToken()
                ->withQueryParameters([
                    'uid_kegiatan' => $id
                ])
                ->post("{$this->apiUrl}/api/agenda", $formattedData);

            if ($response->successful()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Agenda berhasil disimpan',
                ]);
            } else {
                dd($response->json());
                return response()->json([
                    'status'  => false,
                    'message' => $response->json('message', 'Terjadi kesalahan saat menyimpan agenda.'),
                ]);
            }
        }

        return redirect('/');
    }

    public function agenda_detail(string $id)
    {
        $response = Http::withAuthToken()->get("{$this->apiUrl}/api/agenda", [
            'uid' => $id
        ]);

        $breadcrumb = (object) [
            'title' => 'Detail Agenda',
            'list' => ['Kegiatan', 'Detail Kegiatan', 'Detail Agenda']
        ];

        if ($response->successful()) {
            $data = $response->json('data');

            return view('kegiatan.agenda.detail', [
                'breadcrumb' => $breadcrumb,
                'activeMenu' => 'apalah',
                'data' => $data
            ]);
        } else if ($response->status() == 401) {
            return abort(401, 'Anda tidak dapat melihat agenda ini');
        } else {
            return back();
        }
    }

    public function agenda_edit_ajax(Request $request, string $id)
    {
        $agendaData = json_decode($request->query('data'), true);
        return view('kegiatan.agenda.edit_ajax', ['id' => $id, 'current' => $agendaData]);
    }

    public function agenda_update_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_agenda' => 'nullable',
                'jadwal_agenda' => 'nullable|date', // Validate as a date
                'deskripsi_agenda' => 'nullable|string',
                'is_done' => 'nullable|in:true,false', // Explicit validation for boolean-like strings
                'list_uid_user_kegiatan' => 'nullable|array|min:1', // Must be an array
                'list_uid_user_kegiatan.*' => 'nullable|string', // Each item must be a string
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            // Filter only existing keys from the request to include in the payload
            $allowedKeys = ['nama_agenda', 'jadwal_agenda', 'deskripsi_agenda', 'is_done', 'list_uid_user_kegiatan'];
            $formattedData = $request->only($allowedKeys);


            // Send the formatted data to the API
            $response = Http::withAuthToken()
                ->withQueryParameters([
                    'uid' => $id,
                ])
                ->put("{$this->apiUrl}/api/agenda", $formattedData);

            if ($response->successful()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Agenda berhasil diperbarui',
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => $response->json('message', 'Terjadi kesalahan saat memperbarui agenda.'),
                ]);
            }
        }
    }

    public function agenda_confirm_ajax(Request $request, string $id)
    {
        $agendaData = json_decode($request->query('data'), true);
        return view('kegiatan.agenda.confirm_ajax', ['id' => $id, 'current' => $agendaData]);
    }

    public function agenda_delete_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Send the formatted data to the API
            $response = Http::withAuthToken()
                ->withQueryParameters([
                    'uid' => $id
                ])
                ->delete("{$this->apiUrl}/api/agenda");

            if ($response->successful()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Agenda berhasil dihapus',
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => $response->json('message', 'Terjadi kesalahan saat mengahapus agenda.'),
                ]);
            }
        }

        return redirect('/');
    }

    public function agenda_anggota_create_ajax(Request $request, string $id)
    {
        $response = Http::withAuthToken()->get("{$this->apiUrl}/api/penugasan", ['uid_kegiatan' => $id]);
        if ($response->successful()) {

            return view('kegiatan.agenda.anggota.create_ajax', ['penugasan' => $response->json('data'), 'id' => $request->query('uid_agenda')]);
        }

        return view('kegiatan.agenda.anggota.create_ajax', ['error' => $response->json('data.message'), 'penugasan' => null, 'id' => $request->query('uid_agenda')]);
    }

    public function agenda_anggota_confirm_ajax(Request $request, string $id)
    {
        $userData = json_decode($request->query('data'), true);
        return view('kegiatan.agenda.anggota.confirm_ajax', ['penugasan' => $userData, 'id' => $id]);
    }

    public function agenda_anggota_delete_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Send the formatted data to the API
            $response = Http::withAuthToken()
                ->withQueryParameters([
                    'uid' => $id,
                    'uid_user_kegiatan' => $request->uid_user_kegiatan
                ])
                ->delete("{$this->apiUrl}/api/agenda/user");

            if ($response->successful()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Anggota berhasil dihapus dari agenda',
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => $response->json('message', 'Terjadi kesalahan saat mengahapus anggota dari agenda.'),
                ]);
            }
        }

        return redirect('/');
    }

    public function agenda_progress_show_ajax(Request $request)
    {
        $progressData = json_decode($request->query('data'), true);
        return view('kegiatan.agenda.progress.show_ajax', ['progress' => $progressData]);
    }

    public function agenda_progress_create_ajax(string $id)
    {
        return view('kegiatan.agenda.progress.create_ajax', ['id' => $id]);
    }

    public function agenda_progress_store_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Validation rules
            $rules = [
                'deskripsi' => 'required|string', // Rename to deskripsi_progress for API payload
                'file' => 'nullable|array', // Accept multiple files as an array
                'file.*' => 'nullable|file|max:2048', // Validate each file (max 2MB)
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $payload = [
                'deskripsi_progress' => $request->deskripsi, // Map deskripsi to deskripsi_progress
            ];

            $client = Http::withAuthToken(); // Initialize client with token
            $client->withQueryParameters(['uid_agenda' => $id]);

            if ($request->hasFile('file')) {
                foreach ($request->file('file') as $file) {
                    $client->attach('files', file_get_contents($file), $file->getClientOriginalName());
                }
            }

            // dd($payload);

            // Perform the POST request with payload and files
            $response = $client->post("{$this->apiUrl}/api/agenda/progress", $payload);

            // dd($response->json());

            if ($response->successful()) {
                return response()->json([
                    'status'  => true,
                    'message' => $response->json('Progress berhasil disimpan'),
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => $response->json('message', 'Terjadi kesalahan saat menyimpan progress.'),
                ]);
            }
        }

        return redirect('/');
    }

    public function agenda_progress_edit_ajax(Request $request, string $id)
    {
        $progressData = json_decode($request->query('data'), true);
        return view('kegiatan.agenda.progress.update_ajax', ['current' => $progressData, 'id' => $id]);
    }

    public function agenda_progress_update_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Validation rules
            $rules = [
                'deskripsi' => 'nullable|string', // Rename to deskripsi_progress for API payload
                'file' => 'nullable|array', // Accept multiple files as an array
                'file.*' => 'nullable|file|max:2048', // Validate each file (max 2MB)
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $payload = [
                'deskripsi_progress' => $request->deskripsi, // Map deskripsi to deskripsi_progress
            ];

            $client = Http::withAuthToken(); // Initialize client with token
            $client->withQueryParameters([
                'uid_agenda' => $request->uid_agenda,
                'uid' => $id
            ]);

            if ($request->hasFile('file')) {
                foreach ($request->file('file') as $file) {
                    $client->attach('files', file_get_contents($file), $file->getClientOriginalName());
                }
            }


            // Perform the POST request with payload and files
            $response = $client->put("{$this->apiUrl}/api/agenda/progress", $payload);

            // dd($response->json());

            if ($response->successful()) {
                return response()->json([
                    'status'  => true,
                    'message' => $response->json('Progress berhasil disimpan'),
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => $response->json('message', 'Terjadi kesalahan saat menyimpan progress.'),
                ]);
            }
        }

        return redirect('/');
    }

    public function agenda_progress_delete_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Send the formatted data to the API
            $response = Http::withAuthToken()
                ->withQueryParameters([
                    'uid' => $id,
                ])
                ->delete("{$this->apiUrl}/api/agenda/progress");

            if ($response->successful()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Progress berhasil dihapus dari agenda',
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => $response->json('message', 'Terjadi kesalahan saat mengahapus progress dari agenda.'),
                ]);
            }
        }

        return redirect('/');
    }

    public function agenda_progress_attachment_delete_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // dd($id . " " . $request->uid_attachment);
            // Send the formatted data to the API
            $response = Http::withAuthToken()
                ->withQueryParameters([
                    'uid' => $id,
                    'uid_attachment' => $request->uid_attachment
                ])
                ->delete("{$this->apiUrl}/api/agenda/progress-attachment");

            if ($response->successful()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Attachment berhasil dihapus dari progress',
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => $response->json('message', 'Terjadi kesalahan saat mengahapus attachment dari progress.'),
                ]);
            }
        }

        return redirect('/');
    }
}
