<?php

namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use Illuminate\Http\Request;

class BillController extends Controller
{
    public function index()
    {
        return view('bills');
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

    // Hapus bill
    public function destroy($id)
    {
        ApiHelper::call('delete', "bills/{$id}");

        return redirect()->route('bills')->with('success', 'Bill berhasil dihapus!');
    }
}
