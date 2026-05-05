<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Helpers\ApiHelper;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    // Menampilkan halaman form login
    public function showLoginForm()
    {
        if (session()->has('api_token')) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    // Memproses data dari form login dan mengirim ke Backend
    public function login(Request $request)
    {
        $response = ApiHelper::call('post', 'login', $request->only(['email', 'password']));

        if ($response->successful()) {
            $data = $response->json();

            // Simpan token dan data user ke session agar bisa dipakai ApiHelper nanti
            session([
                'api_token' => $data['token'],
                'user'      => $data['user']
            ]);

            // Redirect ke dashboard Monefy
            return redirect()->route('dashboard')->with('success', 'Login Berhasil!');
        }

        if (!$response->successful()) {
        dd([
            'status' => $response->status(),
            'body' => $response->json(),
            'sent_data' => $request->only(['email', 'password'])
        ]);
        }
        
        $errors = $response->json()['errors'] ?? ['message' => 'Email atau password salah.'];

        return back()->withErrors($errors)->withInput();
    }

    // Menampilkan halaman form pendaftaran
    public function showRegisterForm()
    {
        if (session()->has('api_token')) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    // Memproses data dari form dan mengirim ke Backend
    public function register(Request $request)
    {
        $response = ApiHelper::call('post', 'register', $request->all());

        if ($response->successful()) {
            $data = $response->json();

            // Simpan token dan data user ke session agar bisa dipakai ApiHelper nanti
            session([
                'api_token' => $data['token'],
                'user'      => $data['user']
            ]);

            // Redirect ke dashboard Monefy
            return redirect()->route('dashboard')->with('success', 'Registrasi Berhasil!');
        }

        $errors = $response->json()['errors'] ?? ['message' => 'Terjadi kesalahan saat mendaftar.'];

        return back()->withErrors($errors)->withInput();
    }
}