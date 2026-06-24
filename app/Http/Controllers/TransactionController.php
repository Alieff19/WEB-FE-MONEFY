<?php

namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Simpan transaksi baru — kirim data ke backend via ApiHelper.
     * Mendukung tipe: income, expense, transfer.
     */
    public function store(Request $request)
    {
        $data = $request->all();

        // Pastikan 'type' ada dan valid
        $validTypes = ['income', 'expense', 'transfer'];
        if (!isset($data['type']) || !in_array($data['type'], $validTypes)) {
            return response()->json(['message' => 'Tipe transaksi tidak valid.'], 422);
        }

        // Jika bukan transfer, hapus to_wallet_id agar tidak mengganggu validasi backend
        if ($data['type'] !== 'transfer') {
            unset($data['to_wallet_id']);
        }

        // Tambahkan jam sekarang jika hanya tanggal yang dikirim (agar tidak 00:00)
        if (isset($data['transaction_date']) && strlen($data['transaction_date']) === 10) {
            $data['transaction_date'] .= ' ' . date('H:i:s');
        }

        $response = ApiHelper::call('post', 'transactions', $data);

        if ($response->successful()) {
            return response()->json([
                'message' => 'Transaksi berhasil disimpan!',
                'data'    => $response->json()['data'] ?? []
            ], 201);
        }

        $error = $response->json();
        return response()->json([
            'message' => $error['message'] ?? 'Gagal menyimpan transaksi.'
        ], $response->status());
    }

    /**
     * Update transaction via backend API.
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();

        $response = ApiHelper::call('put', "transactions/{$id}", $data);

        if ($response->successful()) {
            return response()->json([
                'message' => 'Transaksi berhasil diperbarui!',
                'data'    => $response->json()['data'] ?? []
            ], 200);
        }

        $error = $response->json();
        return response()->json([
            'message' => $error['message'] ?? 'Gagal memperbarui transaksi.'
        ], $response->status());
    }

    /**
     * Delete transaction via backend API.
     */
    public function destroy($id)
    {
        $response = ApiHelper::call('delete', "transactions/{$id}");

        if ($response->successful()) {
            return response()->json(['message' => 'Transaksi berhasil dihapus!'], 200);
        }

        $error = $response->json();
        return response()->json([
            'message' => $error['message'] ?? 'Gagal menghapus transaksi.'
        ], $response->status());
    }

    /**
     * Simpan wallet baru — kirim data ke backend via ApiHelper.
     */
    public function storeWallet(Request $request)
    {
        $data = $request->only(['name_wallet', 'balance', 'category']);
        
        // Map 'type' to 'category' if needed
        if ($request->has('type') && !isset($data['category'])) {
            $data['category'] = $request->type;
        }
        
        // Default to 'Cash' and ensure category is in backend enum format
        if (!isset($data['category']) || empty($data['category'])) {
            $data['category'] = 'Cash';
        } else {
            // Normalize category to backend enum format
            $category = $data['category'];
            if ($category === 'cash') $data['category'] = 'Cash';
            elseif ($category === 'bank') $data['category'] = 'Bank Account';
            elseif ($category === 'e-wallet' || $category === 'e_wallet') $data['category'] = 'E-Wallet';
        }

        // Bersihkan titik ribuan dari balance jika ada
        if (isset($data['balance']) && is_string($data['balance'])) {
            $data['balance'] = str_replace('.', '', $data['balance']);
        }

        // Validasi sederhana sebelum kirim ke backend
        if (empty($data['name_wallet'])) {
            return response()->json(['message' => 'Nama wallet tidak boleh kosong.'], 422);
        }
        if (!isset($data['balance']) || $data['balance'] < 0) {
            return response()->json(['message' => 'Saldo awal tidak valid.'], 422);
        }

        $response = ApiHelper::call('post', 'wallets', $data);

        if ($response->successful()) {
            return response()->json([
                'message' => 'Wallet berhasil ditambahkan!',
                'data'    => $response->json()['data'] ?? [],
                'reload'  => true,   // flag untuk api.js agar reload halaman
            ], 201);
        }

        $error = $response->json();
        return response()->json([
            'message' => $error['message'] ?? 'Gagal menambahkan wallet.'
        ], $response->status());
    }

    /**
     * Ambil daftar wallet milik user yang sedang login.
     * Digunakan untuk populate dropdown wallet di modal Add Transaction.
     */
    public function wallets()
    {
        $response = ApiHelper::call('get', 'wallets');

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json(['data' => []], 200);
    }
}
