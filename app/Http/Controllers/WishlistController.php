<?php

namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $summaryResponse = ApiHelper::call('get', 'dashboard/summary');
        $user = $summaryResponse->successful() ? ($summaryResponse->json()['user'] ?? null) : null;

        $response = ApiHelper::call('get', 'wishlists');
        $wishlists = $response->successful() ? ($response->json()['data'] ?? []) : [];

        $walletsResponse = ApiHelper::call('get', 'wallets');
        $wallets = $walletsResponse->successful() ? ($walletsResponse->json()['data'] ?? []) : [];

        return view('wishlist', compact('user', 'wishlists', 'wallets'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        
        // Pastikan target_amount dikirim sebagai angka tanpa pemisah ribuan
        if (isset($data['target_amount'])) {
            $data['target_amount'] = (float) preg_replace('/[^0-9]/', '', $data['target_amount']);
        }

        $response = ApiHelper::call('post', 'wishlists', $data);

        if ($response->successful()) {
            return redirect()->route('wishlist')->with('success', 'Wishlist berhasil ditambahkan!');
        }

        $message = $response->json()['message'] ?? 'Gagal menambahkan wishlist.';
        return back()->with('error', $message);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        if (isset($data['target_amount'])) {
            $data['target_amount'] = (float) preg_replace('/[^0-9]/', '', $data['target_amount']);
        }

        $response = ApiHelper::call('put', "wishlists/{$id}", $data);

        if ($response->successful()) {
            return redirect()->route('wishlist')->with('success', 'Status wishlist diperbarui!');
        }

        $message = $response->json()['message'] ?? 'Gagal memperbarui wishlist.';
        return back()->with('error', $message);
    }

    public function destroy($id)
    {
        $response = ApiHelper::call('delete', "wishlists/{$id}");

        if ($response->successful()) {
            return redirect()->route('wishlist')->with('success', 'Wishlist dihapus!');
        }

        return back()->with('error', 'Gagal menghapus wishlist.');
    }

    public function pay(Request $request, $id)
    {
        $response = ApiHelper::call('put', "wishlists/{$id}", [
            'wallet_id' => $request->wallet_id,
            'status'    => 'terbeli',
        ]);

        if ($response->successful()) {
            return redirect()->route('wishlist')->with('success', 'Wishlist berhasil dibayar!');
        }

        $message = $response->json()['message'] ?? 'Gagal membayar wishlist. Pastikan saldo cukup.';
        return back()->with('error', $message);
    }
}
