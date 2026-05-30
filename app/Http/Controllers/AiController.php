<?php

namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use Illuminate\Http\Request;

class AiController extends Controller
{
    public function index()
    {
        return view('ai-chat');
    }

    public function chat(Request $request)
    {
        $response = ApiHelper::call('post', 'chat', [
            'message' => $request->message
        ]);

        if ($response->successful()) {
            return response()->json([
                'reply' => $response->json()['reply'] ?? 'Tidak ada jawaban.'
            ]);
        }

        $errorMessage = $response->json()['reply'] ?? 'Gagal terhubung ke server AI. ' . $response->body();
        return response()->json(['reply' => $errorMessage], 500);
    }
}
