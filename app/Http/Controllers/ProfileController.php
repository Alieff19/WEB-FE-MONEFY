<?php

namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $response = ApiHelper::call('get', 'profile');
        $user = $response->successful() ? $response->json() : session('user');

        // Pastikan avatar menggunakan absolute URL backend jika formatnya relative
        if (!empty($user['avatar']) && !str_starts_with($user['avatar'], 'http')) {
            $baseUrl = config('services.monefy_backend.base_url');
            $parsed = parse_url($baseUrl);
            $hostUrl = $parsed['scheme'] . '://' . $parsed['host'] . (isset($parsed['port']) ? ':' . $parsed['port'] : '');
            $user['avatar'] = $hostUrl . $user['avatar'];
        }

        return view('profile', [
            'user' => $user,
        ]);
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|max:2048',
        ]);

        $file = $request->file('avatar');
        $token = session('api_token');
        $baseUrl = config('services.monefy_backend.base_url');

        $response = \Illuminate\Support\Facades\Http::withToken($token)
            ->attach('avatar', file_get_contents($file), $file->getClientOriginalName())
            ->post(rtrim($baseUrl, '/') . '/profile/avatar');

        if ($response->successful()) {
            $data = $response->json();
            
            // Perbarui data user di session
            $user = session('user');
            
            // Backend URL base for assets (jika avatar_url relatif)
            $avatarUrl = $data['avatar_url'];
            if (!str_starts_with($avatarUrl, 'http')) {
                // Remove trailing slash from base url
                $parsed = parse_url($baseUrl);
                $hostUrl = $parsed['scheme'] . '://' . $parsed['host'] . (isset($parsed['port']) ? ':' . $parsed['port'] : '');
                $avatarUrl = $hostUrl . $avatarUrl;
            }

            $user['avatar'] = $avatarUrl;
            session(['user' => $user]);

            return response()->json(['message' => 'Success', 'avatar_url' => $avatarUrl]);
        }

        return response()->json(['message' => 'Failed to upload to backend'], 500);
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
