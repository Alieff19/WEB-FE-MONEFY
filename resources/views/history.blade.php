@extends('layouts.app')

@section('title', 'Monefy - History')

@push('styles')
<style>
        .filter-pills-history .nav-link {
            color: #A0AEC0 !important;
            font-weight: 600;
            padding: 0.6rem 2rem !important;
            border-radius: 20px;
            margin: 0 0.5rem;
        }
        .filter-pills-history .nav-link.active {
            background-color: var(--primary-purple);
            color: white !important;
            box-shadow: 0 4px 10px rgba(106, 76, 255, 0.3);
        }
        .history-item {
            padding: 1.5rem;
            margin-bottom: 1.25rem;
        }
        .history-item .trans-amount {
            font-size: 1.1rem;
        }
    </style>
@endpush

@section('content')
<!-- Main History Container -->
    <div class="container my-5">
        <div class="row g-4">
            <!-- Left Filter Sidebar -->
            <div class="col-lg-3">
                <h4 class="fw-bold mb-4" style="color: var(--text-dark);">Transactions</h4>
                
                <div class="bg-white p-4 rounded-4 shadow-sm">
                    <h6 class="fw-bold mb-3 text-muted">Time Period</h6>
                    <div class="d-flex flex-column gap-2 filter-pills">
                        <a href="{{ route('history', ['period' => 'day']) }}" class="btn filter-sidebar-btn text-start {{ request('period') == 'day' || !request('period') ? 'active' : '' }}">Day</a>
                        <a href="{{ route('history', ['period' => 'week']) }}" class="btn filter-sidebar-btn text-start {{ request('period') == 'week' ? 'active' : '' }}">Week</a>
                        <a href="{{ route('history', ['period' => 'month']) }}" class="btn filter-sidebar-btn text-start {{ request('period') == 'month' ? 'active' : '' }}">Month</a>
                        <a href="{{ route('history', ['period' => 'year']) }}" class="btn filter-sidebar-btn text-start {{ request('period') == 'year' ? 'active' : '' }}">Year</a>
                        <a href="{{ route('history', ['period' => 'all']) }}" class="btn filter-sidebar-btn text-start {{ request('period') == 'all' ? 'active' : '' }}">All</a>
                    </div>
                </div>
            </div>

            <!-- Main History Data Table -->
            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold mb-0" style="color: var(--text-dark);">History Logs</h4>
                    <div>
                        <button type="button" class="btn btn-outline-secondary me-2" onclick="alert('Filter feature will be handled by Backend.')"><i class="bi bi-filter"></i> Filter</button>
                        <button type="button" class="btn btn-outline-primary" onclick="alert('Export to PDF/Excel will be ready soon!')"><i class="bi bi-download"></i> Export</button>
                    </div>
                </div>

                <!-- Table Card Panel -->
                <div class="bg-white p-4 rounded-4 shadow-sm">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="color: var(--text-dark);">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="py-3 border-bottom-0 rounded-start">Type</th>
                                    <th scope="col" class="py-3 border-bottom-0">Description</th>
                                    <th scope="col" class="py-3 border-bottom-0">Time</th>
                                    <th scope="col" class="py-3 border-bottom-0 text-end rounded-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($histories ?? [] as $transaction)
                                <tr>
                                    <td class="py-3 border-bottom">
                                        <div class="icon-circle text-white d-inline-flex" style="background-color: var(--{{ $transaction['type'] === 'income' ? 'income-green' : 'expense-red' }}); width: 40px; height: 40px;">
                                            <i class="bi bi-arrow-{{ $transaction['type'] === 'income' ? 'down' : 'up' }} fw-bold"></i>
                                        </div>
                                    </td>
                                    <td class="py-3 border-bottom fw-bold text-secondary">{{ $transaction['title'] }}</td>
                                    <td class="py-3 border-bottom text-muted">{{{ \Carbon\Carbon::parse($transaction['transaction_date'])->locale('id')->settings(['formatFunction' => 'translatedFormat'])->format('j F Y') }}}</td>
                                    <td class="py-3 border-bottom text-end fw-bold" style="color: var(--{{ $transaction['type'] === 'income' ? 'income-green' : 'expense-red' }});">
                                        {{ $transaction['type'] === 'income' ? '+' : '-' }}Rp {{ number_format((float)($transaction['amount'] ?? 0), 0, ',', '.') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">No transactions found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

