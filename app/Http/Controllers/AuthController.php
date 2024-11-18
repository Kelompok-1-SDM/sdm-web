<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

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

                $responseUser = Http::withAuthToken()->get("{$this->apiUrl}/api/user", [
                    'uid' => '',
                ]);
                Cache::put('user_cache', $responseUser->json('data'), $expiry);

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


    public function logout(Request $request)
    {
        Cache::clear('api_jwt_token');
        Cache::clear('user_cache');

        return redirect('login');
    }
}
