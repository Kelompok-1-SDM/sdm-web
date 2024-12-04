<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = env('API_BASE_URL', "https://restapi-sdm.lleans.dev");
    }

    public function index()
    {
        return view('auth.login');
    }

    public function postLogin(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $response = Http::post("{$this->apiUrl}/api/login", [
                'nip' => $request->nip,
                'password' => $request->password,
            ]);

            if ($response->successful()) {
                $token = $response->json('data.token');
                $expiry = now()->addSeconds(config('services.api.token_lifetime', 604800));

                Cache::put('api_jwt_token', $token, $expiry);
                $apa = $response->json('data');
                session(['user_id' => $apa['userId'], 'role' => $apa['role'], 'profil_img' => $apa['profileImage'], 'nama' => $apa['nama']]);

                return response()->json([
                    'status' => true,
                    'message' => 'Login Berhasil',
                    'redirect' => url('/')
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => $response->json('message')
            ]);
        }

        return redirect('login');
    }

    public function ForgotPassword()
    {
        return view('auth.ResetPassword'); // Pastikan Anda memiliki view form untuk reset password.
    }

    public function requestReset()
    {
        return view('auth.requestReset');
    }

    // public function ResetPassword(Request $request)
    // {
    //     // Validasi input
    //     $validator = Validator::make($request->all(), [
    //         'password' => 'required|string|min:6|confirmed',
    //     ]);

    //     if ($validator->fails()) {
    //         return back()->withErrors($validator)->withInput();
    //     }

    //     // Cari user berdasarkan username (atau field unik lainnya)
    //     $user = User::where('username', $request->username)->first();

    //     if ($user) {
    //         // Update password user
    //         $user->password = Hash::make($request->password);
    //         $user->save();

    //         return redirect()->back()->with('status', 'Password berhasil diubah.');
    //     }
    // }

    public function logout(Request $request)
    {
        Cache::clear('api_jwt_token');
        Cache::clear('user_cache');
        Session::flush();

        return redirect('login');
    }
}
