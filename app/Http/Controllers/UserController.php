<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = env('API_BASE_URL', "ini harus url");
    }

    public function index(string $userType)
    {
        $breadcrumb = (object) [
            'title' => 'Daftar ' . ucfirst($userType),
            'list' => ['Data Pengguna', ucfirst($userType)]
        ];
        $page = (object) [
            'title' => 'Daftar ' . ucfirst($userType) . ' yang terdaftar dalam sistem',
        ];
        $activeMenu = $userType; // set menu yang sedang aktif
        return view('user.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'userType' => $userType]);
    }

    public function list(string $userType)
    {
        $response = Http::withAuthToken()->get("{$this->apiUrl}/api/user", [
            'role' => $userType
        ]);

        if ($response->successful()) {
            $data = $response->json('data');
            return DataTables::of($data)
                ->addIndexColumn()  // menambahkan kolom index / no urut (default name kolom: DT_RowIndex)
                ->addColumn('aksi', function ($user) use ($userType) {  // pass $userType to the closure
                    $btn = "<a href=" . url('/' . $userType . '/' . $user['userId'] . '/detail') . " class='btn btn-info btn-sm'>Detail</a>";

                    return $btn;
                })
                ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
                ->make(true);
        }
    }


    public function detailUser(string $userType, string $id)
    {
        $response = Http::withAuthToken()->get("{$this->apiUrl}/api/user", [
            'uid' => $id
        ]);
        $responseKegiatan = Http::withAuthToken()->get("{$this->apiUrl}/api/kegiatan", [
            'uid_user' => $id
        ]);

        $breadcrumb = (object) [
            'title' => 'Detail ' . ucfirst($userType),
            'list' => ['Data Pengguna', ucfirst($userType), 'Detail ' . ucfirst($userType)]
        ];

        if ($response->successful() || $responseKegiatan->successful()) {
            return view('user.detail', [
                'breadcrumb' => $breadcrumb,
                'activeMenu' => 'mbuh',
                'user' => $response->json('data'),
                'userType' => $userType,
                'kegiatan' => $responseKegiatan->json('data')
            ]);
        }

        return redirect('/');
    }

    public function create_ajax(string $userType)
    {
        return view('user.create_ajax', ['userType' => $userType]);
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
                    'message' => 'Data user berhasil disimpan',
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

    public function import(string $userType)
    {
        return view('user.import', ['userType' => $userType]);
    }

    public function import_ajax(Request $request, string $userType)
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
                    'role' => $userType
                ])
                ->post("{$this->apiUrl}/api/user/import");

            if ($response->successful()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Data user berhasil diimport',
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

    public function export_excel(string $userType)
    {
        $response = Http::withAuthToken()->get("{$this->apiUrl}/api/user/export", [
            'role' => $userType
        ]);

        if ($response->successful()) {
            // Retrieve file content and metadata
            $fileContent = $response->body();
            $fileName = 'Data' . $userType . "_" . date('Y-m-d H:i:s') . ".xlsx";

            // Return the file as a response
            return response($fileContent)
                ->header('Content-Type', $response->header('Content-Type'))
                ->header('Content-Disposition', "attachment; filename=\"{$fileName}\"");
        }

        return response()->json([
            'status' => false,
            'message' => $response->json('message'),
        ], $response->status());
    }

    public function confirm_ajax(string $userType, string $id)
    {
        $response = Http::withAuthToken()->get("{$this->apiUrl}/api/user", [
            'uid' => $id
        ]);

        if ($response->successful()) {
            return view('user.confirm_ajax', ['user' => $response->json('data'), 'userType' => $userType]);
        } else {
            return view('user.confirm_ajax', ['user' => null, 'userType' => $userType]);
        }
    }

    public function delete_ajax(Request $request, string $_, string $id)
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

    public function edit_ajax(string $userType, string $id)
    {
        $response = Http::withAuthToken()->get("{$this->apiUrl}/api/user", [
            'uid' => $id
        ]);

        if ($response->successful()) {
            return view('user.edit_ajax', ['user' => $response->json('data'), 'userType' => $userType]);
        } else {
            return view('user.edit_ajax', ['user' => null, 'userType' => $userType]);
        }
    }

    public function update_ajax(Request $request, string $_, string $id)
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

                $dat = $response->json('data');
                session(['nama' => $dat['nama']]);
                if ($response->successful() && $dat['userId'] == session('user_id')) {
                    session(['profil_img' => $dat['profileImage']]);
                }
            } else {
                $response = Http::withAuthToken()
                    ->withQueryParameters(['uid' => $id])
                    ->put("{$this->apiUrl}/api/user", $data);
            }

            if ($response->successful()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Data user berhasil disimpan',
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

    public function tambah_kompetensi_ajax(string $userType, string $id)
    {
        $response = Http::withAuthToken()->get("{$this->apiUrl}/api/kompetensi");

        if ($response->successful()) {
            return view('user.kompetensi.create_ajax', ['kompetensi' => $response->json('data'), 'userType' => $userType, 'id' => $id]);
        } else {
            return view('user.kompetensi.create_ajax', ['kompetensi' => null, 'userType' => $userType, 'id' => $id]);
        }
    }

    public function store_kompetensi_ajax(Request $request, string $_, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {

            $rules = [
                'list_kompetensi' => 'required|array|min:1', // Ensure it's an array with at least one item
                'list_kompetensi.*' => 'required|string', // Validate each competency ID exists
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
            $formattedData = [
                'list_kompetensi' => $request->input('list_kompetensi'),
            ];

            // Send the formatted data to the API
            $response = Http::withAuthToken()
                ->withQueryParameters([
                    'uid' => $id
                ])
                ->post("{$this->apiUrl}/api/user/kompetensi", $formattedData);

            if ($response->successful()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Kompetensi berhasil disimpan',
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => $response->json('message', 'Terjadi kesalahan saat menyimpan kompetensi.'),
                ]);
            }
        }

        return redirect('/');
    }

    public function delete_kompetensi_ajax(Request $request, string $_, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {

            $rules = [
                'kompetensiIds' => 'required|array|min:1', // Ensure it's an array with at least one item
                'kompetensiIds.*' => 'required|string', // Validate each competency ID exists
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
            $formattedData = [
                'list_kompetensi' => $request->input('kompetensiIds'),
            ];

            // Send the formatted data to the API
            $response = Http::withAuthToken()
                ->withQueryParameters([
                    'uid' => $id
                ])
                ->delete("{$this->apiUrl}/api/user/kompetensi", $formattedData);

            if ($response->successful()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Kompetensi berhasil dihapus',
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => $response->json('message', 'Terjadi kesalahan saat mengahapus kompetensi.'),
                ]);
            }
        }

        return redirect('/');
    }
}
