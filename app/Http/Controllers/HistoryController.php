<?php

namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->query('period', 'day');

        // Ambil riwayat transaksi dari backend berdasarkan filter periode
        $response = ApiHelper::call('get', 'transactions', ['period' => $period]);
        $histories = $response->successful() ? $response->json() : [];

        $summaryResponse = ApiHelper::call('get', 'dashboard/summary');
        $user = $summaryResponse->successful() ? ($summaryResponse->json()['user'] ?? null) : null;

        return view('history', [
            'histories' => $histories,
            'user' => $user,
        ]);
    }
}
