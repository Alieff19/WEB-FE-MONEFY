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
                
                // Get category and normalize to lowercase keys for grouping
                $rawCategory = $wallet['category'] ?? 'Cash';
                
                // Map backend enum to grouping key
                if ($rawCategory === 'Bank Account' || $rawCategory === 'bank') {
                    $category = 'bank';
                } elseif ($rawCategory === 'E-Wallet' || $rawCategory === 'e-wallet' || $rawCategory === 'e_wallet') {
                    $category = 'e-wallet';
                } else {
                    $category = 'cash';
                }
                
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
        // Map query parameter ke enum format yang diharapkan backend
        $typeParam = $request->query('category') ?? $request->query('type');
        $defaultType = null;
        
        if ($typeParam === 'bank') $defaultType = 'Bank Account';
        elseif ($typeParam === 'e-wallet' || $typeParam === 'e_wallet') $defaultType = 'E-Wallet';
        elseif ($typeParam === 'cash') $defaultType = 'Cash';
        else $defaultType = $typeParam; // use as-is if already in correct format 
        
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
        
        // Pastikan category sudah dalam format enum yang benar
        if (isset($data['category'])) {
            $category = $data['category'];
            if ($category === 'cash') $data['category'] = 'Cash';
            elseif ($category === 'bank') $data['category'] = 'Bank Account';
            elseif ($category === 'e-wallet' || $category === 'e_wallet') $data['category'] = 'E-Wallet';
        }

        // Bersihkan titik ribuan dari balance jika ada (format Indonesia: 10.000 -> 10000)
        if (isset($data['balance']) && is_string($data['balance'])) {
            $data['balance'] = str_replace('.', '', $data['balance']);
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
