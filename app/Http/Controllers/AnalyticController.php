<?php

namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use Illuminate\Http\Request;

class AnalyticController extends Controller
{
    public function index(Request $request)
    {
        // Support both 'trend' (used by analytics) and 'period' (used by history)
        $period = $request->query('period', null);
        $trend = $request->query('trend', 'weekly');
        $month = $request->query('month', date('n'));
        $year = $request->query('year', date('Y'));
        $week = $request->query('week', 1);

        // Map history's period values to analytics' trend values
        if ($period) {
            switch ($period) {
                case 'day':
                    // Show daily within current week — backend may treat as weekly
                    $trend = 'weekly';
                    break;
                case 'week':
                    $trend = 'weekly';
                    break;
                case 'month':
                    $trend = 'monthly';
                    break;
                case 'year':
                    $trend = 'yearly';
                    break;
                case 'all':
                    $trend = 'yearly';
                    break;
            }
        }

        $params = [
            'trend'  => $trend,
            'month'  => $month,
            'year'   => $year,
            'week'   => $week,
            'period' => $period
        ];

        // Ambil data ringkasan keuangan dari backend
        $summaryResponse = ApiHelper::call('get', 'analytics/summary', $params);
        $summary = $summaryResponse->successful() ? $summaryResponse->json() : [];

        // Ambil data top pengeluaran dan pemasukan per kategori dari backend
        $expensesResponse = ApiHelper::call('get', 'analytics/top-expenses', $params);
        $categoriesData = $expensesResponse->successful() ? $expensesResponse->json() : ['expenses' => [], 'incomes' => []];

        $summaryResponse = ApiHelper::call('get', 'dashboard/summary');
        $user = $summaryResponse->successful() ? ($summaryResponse->json()['user'] ?? null) : null;

        return view('analytic', [
            'totalIncome'  => $summary['total_income']  ?? 0,
            'totalExpense' => $summary['total_expense'] ?? 0,
            'totalBalance' => $summary['total_balance'] ?? 0,
            'chartLabels'  => $summary['chart_labels'] ?? [],
            'chartIncome'  => $summary['chart_income'] ?? [],
            'chartExpense' => $summary['chart_expense'] ?? [],
            'topExpenses'  => $categoriesData['expenses'] ?? [],
            'topIncomes'   => $categoriesData['incomes'] ?? [],
            'user'         => $user,
        ]);
    }
}
