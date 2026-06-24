<?php

namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProfileController extends Controller
{
    public function index()
    {
    $response = ApiHelper::call('get', 'profile');

    $user = $response->successful()
        ? $response->json()
        : session('user');

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

        if (!$token) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if (!$baseUrl) {
            return response()->json(['message' => 'Backend URL not configured'], 500);
        }

        $response = Http::withToken($token)
            ->attach(
                'avatar',
                fopen($file->getRealPath(), 'r'),
                $file->getClientOriginalName()
            )
            ->post(rtrim($baseUrl, '/') . '/profile/avatar');

        if (!$response->successful()) {
            return response()->json([
                'message' => 'Failed to upload to backend',
                'status' => $response->status(),
                'body' => $response->body()
            ], 500);
        }

        $data = $response->json();

        if (!isset($data['avatar'])) {
            return response()->json([
                'message' => 'Invalid backend response - missing avatar key'
            ], 500);
        }

        $avatarUrl = $data['avatar'];

        // Ensure complete user object in session is updated with new avatar
        $currentUser = session('user') ?? [];
        $currentUser['avatar'] = $avatarUrl;
        session()->put('user', $currentUser);
        session()->put('user.avatar', $avatarUrl);

        // Return dengan key 'avatar_url' seperti yang diharapkan frontend JS
        return response()->json([
            'message' => 'Success',
            'avatar_url' => $avatarUrl,
            'user' => session('user')  // Return updated user object
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
