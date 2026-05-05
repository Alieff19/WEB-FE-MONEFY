<?php

namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        // Ambil data profil user dari backend
        $response = ApiHelper::call('get', 'profile');
        $user = $response->successful() ? $response->json() : session('user');

        return view('profile', [
            'user' => $user,
        ]);
    }

    // Proses logout: hapus session dan redirect ke halaman login
    public function logout(Request $request)
    {
        // Kirim permintaan logout ke backend (invalidate token)
        ApiHelper::call('post', 'logout');

        // Hapus semua data session
        $request->session()->flush();

        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }
}
