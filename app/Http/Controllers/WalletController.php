<?php

namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    /**
     * Halaman utama wallet — tampilkan wallet dikelompokkan per kategori.
     */
    public function index()
    {
        $response = ApiHelper::call('get', 'wallets');

        $wallets      = [];
        $totalBalance = 0;

        $grouped = [
            'cash'     => [],
            'bank'     => [],
            'e-wallet' => [],
        ];

        if ($response->successful()) {
            $wallets = $response->json()['data'] ?? [];
            foreach ($wallets as $wallet) {
                $totalBalance += (float) ($wallet['balance'] ?? 0);
                
                // Get category, default to 'cash' if missing or unknown
                $category = strtolower($wallet['category'] ?? 'cash');
                
                // Normalisasi tipe (misal: e_wallet -> e-wallet)
                if ($category === 'e_wallet') $category = 'e-wallet';
                
                if (isset($grouped[$category])) {
                    $grouped[$category][] = $wallet;
                } else {
                    // Fallback for unknown types
                    $grouped['cash'][] = $wallet;
                }
            }
        }

        $summaryResponse = ApiHelper::call('get', 'dashboard/summary');
        $user = $summaryResponse->successful() ? ($summaryResponse->json()['user'] ?? null) : null;

        return view('wallet', compact('grouped', 'totalBalance', 'user'));
    }

    /**
     * Halaman form Add Wallet — opsional pre-select tipe via query string.
     */
    public function create(Request $request)
    {
        // Hanya set defaultType jika ada di query string, agar view tahu kapan harus me-lock pilihan
        $defaultType = $request->query('category') ?? $request->query('type'); 
        
        $summaryResponse = ApiHelper::call('get', 'dashboard/summary');
        $user = $summaryResponse->successful() ? ($summaryResponse->json()['user'] ?? null) : null;

        return view('wallet-create', compact('defaultType', 'user'));
    }

    /**
     * Simpan wallet baru via API backend lalu redirect ke halaman wallet.
     */
    public function store(Request $request)
    {
        $data = $request->only(['name_wallet', 'balance', 'category']);

        // Jika form mengirim 'type', map ke 'category'
        if ($request->has('type') && !isset($data['category'])) {
            $data['category'] = $request->type;
        }

        $response = ApiHelper::call('post', 'wallets', $data);

        if ($response->successful()) {
            return redirect()->route('wallet.index')
                ->with('success', 'Wallet berhasil ditambahkan!');
        }

        $error = $response->json();
        return redirect()->back()
            ->with('error', $error['message'] ?? 'Gagal menambahkan wallet.')
            ->withInput();
    }

    /**
     * Hapus wallet via API backend lalu redirect ke halaman wallet.
     */
    public function destroy($id)
    {
        $response = ApiHelper::call('delete', "wallets/{$id}");

        if ($response->successful()) {
            return redirect()->route('wallet.index')
                ->with('success', 'Wallet berhasil dihapus.');
        }

        $error = $response->json();
        return redirect()->route('wallet.index')
            ->with('error', $error['message'] ?? 'Gagal menghapus wallet.');
    }
}
