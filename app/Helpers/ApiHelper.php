<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class ApiHelper
{
    /**
         * Fungsi dasar untuk membungkus HTTP Request
         * 
         * @param string $method (get, post, put, delete)
         * @param string $endpoint (contoh: 'register' atau 'transactions')
         * @param array $data (payload yang dikirim)
     */
    public static function call($method, $endpoint, $data = [])
    {
        // PASTIIN nama 'monefy_backend' sesuai dengan yang kamu tulis di config/services.php
        $baseUrl = config('services.monefy_backend.base_url'); 
        
        // Debug sebentar: kalau $baseUrl kosong, kodenya bakal error cURL 6
        if (!$baseUrl) {
            throw new \Exception("Base URL API belum terdeteksi. Cek config/services.php dan .env");
        }

        $token = session('api_token');

        $request = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => $token ? "Bearer $token" : '',
        ]);

        $url = rtrim($baseUrl, '/') . '/' . ltrim($endpoint, '/');
        
        return $request->$method($url, $data);
    }
}