<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
            $credentials = $request->only('nip', 'password');

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

    public function ForgotPassword()
    {
        return view('auth.ResetPassword'); // Pastikan Anda memiliki view form untuk reset password.
    }

    public function ResetPassword(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Cari user berdasarkan username (atau field unik lainnya)
        $user = User::where('username', $request->username)->first();

        if ($user) {
            // Update password user
            $user->password = Hash::make($request->password);
            $user->save();

            return redirect()->back()->with('status', 'Password berhasil diubah.');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }
}