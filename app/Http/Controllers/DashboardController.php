<?php

namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->query('period', 'day');

        // Ambil data ringkasan (balance, income, expense) dari backend
        $summaryResponse = ApiHelper::call('get', 'dashboard/summary', ['period' => $period]);
        $summary = $summaryResponse->successful() ? $summaryResponse->json() : [];

        // Ambil transaksi terbaru dari backend
        $transactionsResponse = ApiHelper::call('get', 'dashboard/transactions', ['period' => $period]);
        $transactions = $transactionsResponse->successful() ? $transactionsResponse->json() : [];

        return view('index', [
            'user'               => $summary['user'] ?? null,
            'totalBalance'       => $summary['total_balance']  ?? 'Rp 0',
            'totalIncome'        => $summary['total_income']   ?? 'Rp 0',
            'totalExpense'       => $summary['total_expense']  ?? 'Rp 0',
            'recentTransactions' => $transactions,
        ]);
    }
}