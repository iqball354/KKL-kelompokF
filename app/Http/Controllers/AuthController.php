<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Tampilkan halaman login
    public function loginPage()
    {
        return view('auth.login');
    }

    // Proses submit login
    public function loginSubmit(Request $request)
    {
        // Validasi input
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Ambil data login
        $credentials = $request->only('email', 'password');

        // Coba autentikasi
        if (Auth::attempt($credentials)) {
            $user = auth()->user(); // Ambil data user yang login

            // Cek email user dan arahkan ke dashboard masing-masing
            switch ($user->email) {
                case 'dosen@gmail.com':
                    return redirect()->route('dosen.dashboard');
                case 'kaprodi@gmail.com':
                    return redirect()->route('kaprodi.dashboard');
                case 'dekan@gmail.com':
                    return redirect()->route('dekan.dashboard');
                case 'warek1@gmail.com':
                    return redirect()->route('warek1.dashboard');
                case 'hrd@gmail.com':
                    return redirect()->route('hrd.dashboard');
                case 'sdm@gmail.com':
                    return redirect()->route('sdm.dashboard');
                case 'akademik@gmail.com':
                    return redirect()->route('akademik.dashboard');
                default:
                    return redirect('/dashboard')->with('error', 'Dashboard tidak ditemukan untuk user ini.');
            }
        }

        // Jika gagal login
        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    // Logout
    public function logout()
    {
        Auth::logout();
        return redirect('/login')->with('success', 'Berhasil logout');
    }
}
