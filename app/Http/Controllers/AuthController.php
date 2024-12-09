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
        $this->apiUrl = env('API_BASE_URL', "ini harus url");
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

    public function ForgotPassword(Request $request)
    {
        return view('auth.resetPassword', ['token' => $request->token]); // Pastikan Anda memiliki view form untuk reset password.
    }

    public function ForgotPasswordProcess(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $response = Http::post("{$this->apiUrl}/api/reset-password", $request->all());

            if ($response->successful()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Reset berhasil',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => $response->json('message')
                ]);
            }
        }

        return redirect('login');
    }

    public function requestReset()
    {
        return view('auth.requestReset');
    }

    public function requestResetProcess(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $response = Http::post("{$this->apiUrl}/api/request-reset", [
                'nip' => $request->nip,
            ]);

            if ($response->successful()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Reset berhasil',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => $response->json('message')
                ]);
            }
        }

        return redirect('login');
    }

    public function logout()
    {
        Cache::clear('api_jwt_token');
        Cache::clear('user_cache');
        Session::flush();

        return redirect('login');
    }
}
