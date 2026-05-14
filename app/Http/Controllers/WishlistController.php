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

        return view('wishlist', compact('user', 'wishlists'));
    }

    public function store(Request $request)
    {
        $response = ApiHelper::call('post', 'wishlists', $request->all());

        if ($response->successful()) {
            return redirect()->route('wishlist')->with('success', 'Wishlist berhasil ditambahkan!');
        }

        $message = $response->json()['message'] ?? 'Gagal menambahkan wishlist.';
        return back()->with('error', $message);
    }

    public function update(Request $request, $id)
    {
        $response = ApiHelper::call('put', "wishlists/{$id}", $request->all());

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
}
