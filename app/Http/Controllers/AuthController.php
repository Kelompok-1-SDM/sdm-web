<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) { // jika sudah login, maka redirect ke halaman home
            return redirect('/');
        }
        return view('auth.login');
    }

    public function postlogin(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $credentials = $request->only('username', 'password');

            // if (Auth::attempt($credentials)) {
            //     // session([
            //     //     'profile_img_path' => Auth::user()->file_profil,
            //     //     'user_id' => Auth::user()->user_id
            //     // ]);
            //     if (Auth::user()->image_profile != "") {
            //         session(['profile_img_path' => Auth::user()->image_profile]);
            //     }
            // session(['nip' => Auth::user()->nip]);
            return response()->json([
                'status' => true,
                'message' => 'Login Berhasil',
                'redirect' => url('/dashboard')
            ]);
            // }
            // return response()->json([
            //     'status' => false,
            //     'message' => 'Login Gagal'
            // ]);
        }
        return redirect('login');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }
}
