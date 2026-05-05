<?php

namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use Illuminate\Http\Request;

class AnalyticController extends Controller
{
    public function index(Request $request)
    {
        $trend = $request->query('trend', '6months');

        // Ambil data ringkasan keuangan dari backend
        $summaryResponse = ApiHelper::call('get', 'analytics/summary', ['trend' => $trend]);
        $summary = $summaryResponse->successful() ? $summaryResponse->json() : [];

        // Ambil data top pengeluaran per kategori dari backend
        $expensesResponse = ApiHelper::call('get', 'analytics/top-expenses', ['trend' => $trend]);
        $topExpenses = $expensesResponse->successful() ? $expensesResponse->json() : [];

        return view('analytic', [
            'totalIncome'  => $summary['total_income']  ?? 'Rp. 0',
            'totalExpense' => $summary['total_expense'] ?? 'Rp. 0',
            'totalBalance' => $summary['total_balance'] ?? 'Rp. 0',
            'topExpenses'  => $topExpenses,
        ]);
    }
}
