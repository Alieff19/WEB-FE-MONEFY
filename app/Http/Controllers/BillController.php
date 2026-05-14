<?php

namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use Illuminate\Http\Request;

class BillController extends Controller
{
    public function index()
    {
        $summaryResponse = ApiHelper::call('get', 'dashboard/summary');
        $user = $summaryResponse->successful() ? ($summaryResponse->json()['user'] ?? null) : null;
        
        $billsResponse = ApiHelper::call('get', 'bills');
        $bills = $billsResponse->successful() ? $billsResponse->json() : [];

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

    // Proses pembayaran tagihan menggunakan dompet pilihan
    public function pay(Request $request, $id)
    {
        $response = ApiHelper::call('post', "bills/{$id}/pay", $request->all());

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
