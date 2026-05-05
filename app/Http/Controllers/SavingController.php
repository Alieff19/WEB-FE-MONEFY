<?php

namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use Illuminate\Http\Request;

class SavingController extends Controller
{
    public function index()
    {
        // Ambil daftar saving goals dari backend
        $response = ApiHelper::call('get', 'savings');
        $savings = $response->successful() ? $response->json() : [];

        // Hitung total saving dari data yang diterima
        $totalSaving = collect($savings)->sum('current_amount');
        $totalSaving = 'Rp. ' . number_format($totalSaving, 0, ',', '.');

        return view('saving', [
            'savings'      => $savings,
            'totalSaving'  => $totalSaving,
        ]);
    }

    // Simpan saving goal baru — data dari form modal dikirim ke backend
    public function store(Request $request)
    {
        $response = ApiHelper::call('post', 'savings', $request->all());

        if ($response->successful()) {
            return redirect()->route('saving')->with('success', 'Saving goal berhasil dibuat!');
        }

        $errors = $response->json()['errors'] ?? ['message' => 'Gagal membuat saving goal.'];

        return back()->withErrors($errors)->withInput();
    }

    // Update saving goal yang sudah ada
    public function update(Request $request, $id)
    {
        $response = ApiHelper::call('put', "savings/{$id}", $request->all());

        if ($response->successful()) {
            return redirect()->route('saving')->with('success', 'Saving goal berhasil diperbarui!');
        }

        $errors = $response->json()['errors'] ?? ['message' => 'Gagal memperbarui saving goal.'];

        return back()->withErrors($errors)->withInput();
    }

    // Hapus saving goal
    public function destroy($id)
    {
        ApiHelper::call('delete', "savings/{$id}");

        return redirect()->route('saving')->with('success', 'Saving goal berhasil dihapus!');
    }
}
