<?php

namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use Illuminate\Http\Request;

class BillController extends Controller
{
    public function index()
    {
        $billsResponse = ApiHelper::call('get', 'bills');
        $billsData = $billsResponse->successful() ? $billsResponse->json() : [];
        $bills = $billsData['data'] ?? $billsData;

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json($bills);
        }

        $summaryResponse = ApiHelper::call('get', 'dashboard/summary');
        $user = $summaryResponse->successful() ? ($summaryResponse->json()['user'] ?? null) : null;
        
        $walletsResponse = ApiHelper::call('get', 'wallets');
        $walletsData = $walletsResponse->successful() ? $walletsResponse->json() : [];
        $wallets = $walletsData['data'] ?? [];

        return view('bills', compact('user', 'bills', 'wallets'));
    }

    // Simpan bill baru — data dari form dikirim ke backend via ApiHelper
    public function store(Request $request)
    {
        $response = ApiHelper::call('post', 'bills', $request->all());

        if ($response->successful()) {
            return redirect()->route('bills')->with('success', 'Bill berhasil disimpan!');
        }

        $errors = $response->json()['errors'] ?? ['message' => 'Gagal menyimpan bill.'];

        return back()->withErrors($errors)->withInput();
    }

    // Update bill yang sudah ada
    public function update(Request $request, $id)
    {
        $response = ApiHelper::call('put', "bills/{$id}", $request->all());

        if ($response->successful()) {
            return redirect()->route('bills')->with('success', 'Bill berhasil diperbarui!');
        }

        $errors = $response->json()['errors'] ?? ['message' => 'Gagal memperbarui bill.'];

        return back()->withErrors($errors)->withInput();
    }

    // Proses pembayaran tagihan dengan mengupdate status ke 'paid'
    public function pay(Request $request, $id)
    {
        // Backend API expects status in update, so add status='paid' to request
        $data = $request->all();
        $data['status'] = 'paid';
        
        $response = ApiHelper::call('put', "bills/{$id}", $data);

        if ($response->successful()) {
            return redirect()->route('bills')->with('success', 'Tagihan berhasil dibayar dan masuk riwayat!');
        }

        $message = $response->json()['message'] ?? 'Gagal membayar tagihan.';
        return back()->with('error', $message);
    }

    // Hapus bill
    public function destroy($id)
    {
        ApiHelper::call('delete', "bills/{$id}");

        return redirect()->route('bills')->with('success', 'Bill berhasil dihapus!');
    }
}
