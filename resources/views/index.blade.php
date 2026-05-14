@extends('layouts.app')

@section('title', 'Monefy - Web Dashboard')

@section('content')
<!-- Main Dashboard Container -->
    <div class="container my-5">
        <div class="row g-5">
            
            <!-- LEFT COLUMN -->
            <div class="col-lg-5">
                <!-- Greeting -->
                <div class="mb-4">
                    <h2 class="fw-bold" style="color: var(--primary-purple);">Hi, {{ $user['name'] ?? 'Guest' }}!</h2>
                    <h5 class="text-muted fw-normal">How are you today ?</h5>
                </div>

                <!-- Total Balance Card -->
                <div class="balance-card mb-5">
                    <div class="position-relative z-1">
                        <div class="d-flex align-items-center mb-2">
                            <span class="me-2 text-white-50">Total Balance</span>
                            <i class="bi bi-eye-slash-fill text-white-50" style="cursor:pointer;"></i>
                        </div>

                        <h1 class="fw-bold mb-4">Rp {{ number_format((float)($totalBalance ?? 0), 0, ',', '.') }}</h1>

                        <div class="row g-3">
                            <div class="col-6">
                                <div class="glass-box d-flex flex-column">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="bi bi-arrow-down-circle-fill text-success me-2 bg-white rounded-circle"></i>
                                        <span class="small text-white-50">Income</span>
                                    </div>
                                    <span class="fw-semibold">Rp {{ number_format((float)($totalIncome ?? 0), 0, ',', '.') }}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="glass-box d-flex flex-column">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="bi bi-arrow-up-circle-fill text-danger me-2 bg-white rounded-circle"></i>
                                        <span class="small text-white-50">Expense</span>
                                    </div>
                                    <span class="fw-semibold">Rp {{ number_format((float)($totalExpense ?? 0), 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Access -->
                <h5 class="fw-bold mb-3" style="color: var(--primary-purple);">Quick Access</h5>
                <div class="d-flex justify-content-between text-center px-lg-3 mb-5 mb-lg-0">
                    <div>
                        <a href="{{ route('bills') }}" class="text-decoration-none">
                            <div class="quick-access-icon mx-auto"><i class="bi bi-receipt"></i></div>
                            <span class="small fw-semibold text-primary">Bills</span>
                        </a>
                    </div>
                    <div>
                        <a href="{{ route('wallet.index') }}" class="text-decoration-none">
                            <div class="quick-access-icon mx-auto"><i class="bi bi-wallet2"></i></div>
                            <span class="small fw-semibold text-primary">Wallet</span>
                        </a>
                    </div>
                    <div>
                        <a href="{{ route('wishlist') }}" class="text-decoration-none">
                            <div class="quick-access-icon mx-auto"><i class="bi bi-magic"></i></div>
                            <span class="small fw-semibold text-primary">Wishlist</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN -->
            <div class="col-lg-7">
                <!-- Filters -->
                <ul class="nav nav-pills filter-pills mb-4 d-flex justify-content-between flex-nowrap" style="overflow-x: auto;">
                    <li class="nav-item"><a class="nav-link {{ request('period') == 'day' || !request('period') ? 'active' : '' }}" href="{{ route('home', ['period' => 'day']) }}">Day</a></li>
                    <li class="nav-item"><a class="nav-link {{ request('period') == 'week' ? 'active' : '' }}" href="{{ route('home', ['period' => 'week']) }}">Week</a></li>
                    <li class="nav-item"><a class="nav-link {{ request('period') == 'month' ? 'active' : '' }}" href="{{ route('home', ['period' => 'month']) }}">Month</a></li>
                    <li class="nav-item"><a class="nav-link {{ request('period') == 'year' ? 'active' : '' }}" href="{{ route('home', ['period' => 'year']) }}">Year</a></li>
                    <li class="nav-item"><a class="nav-link {{ request('period') == 'all' ? 'active' : '' }}" href="{{ route('home', ['period' => 'all']) }}">All</a></li>
                </ul>

                <!-- Transactions List -->
                <div class="transaction-list">
                    @php
                        // Normalize the API response to ensure it's a list of transactions
                        $txList = $recentTransactions['data'] ?? $recentTransactions ?? [];
                        
                        if (is_array($txList) && !isset($txList[0]) && count($txList) > 0) {
                            // If it's a paginator object containing a nested 'data' array
                            if (isset($txList['data'])) {
                                $txList = $txList['data'];
                            } 
                            // If it's a single transaction object instead of a list
                            elseif (isset($txList['type']) || isset($txList['amount'])) {
                                $txList = [$txList];
                            }
                        }
                        
                        // Fallback if somehow still not an iterable list
                        if (!is_array($txList) && !is_object($txList)) {
                            $txList = [];
                        }
                    @endphp
                    @forelse($txList as $transaction)
                    <div class="transaction-item">
                        <div class="icon-circle {{ $transaction['type'] === 'income' ? 'income' : ($transaction['type'] === 'transfer' ? 'transfer' : 'expense') }}">
                            <i class="bi bi-arrow-{{ $transaction['type'] === 'income' ? 'down' : ($transaction['type'] === 'transfer' ? 'left-right' : 'up') }}"></i>
                        </div>
                        <div class="me-auto">
                            <h6 class="mb-0 fw-bold">{{ $transaction['category'] }}</h6>
                            <small class="text-muted">{{{ \Carbon\Carbon::parse($transaction['transaction_date'])->locale('id')->settings(['formatFunction' => 'translatedFormat'])->format('j F Y') }}}</small>
                        </div>
                        <div class="text-end">
                            <div class="trans-amount {{ $transaction['type'] === 'income' ? 'income' : ($transaction['type'] === 'transfer' ? 'transfer' : 'expense') }}">
                                {{ $transaction['type'] === 'income' ? '+' : ($transaction['type'] === 'transfer' ? '↔' : '-') }}Rp {{ number_format((float)($transaction['amount'] ?? 0), 0, ',', '.') }}
                            </div>
                            <small class="text-muted" style="font-size: 0.75rem;">{{ $transaction['wallet']['name_wallet'] ?? 'No Wallet' }}</small>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">
                        <small>No recent transactions</small>
                    </div>
                    @endforelse

                </div>
            </div>



        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterLinks = document.querySelectorAll('.filter-pills .nav-link');
    const dashboardStats = document.querySelector('.balance-card');
    const transactionList = document.querySelector('.transaction-list');

    filterLinks.forEach(link => {
        link.addEventListener('click', async function(e) {
            e.preventDefault();
            const url = this.getAttribute('href');

            // Update UI Active State
            filterLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');

            try {
                const response = await fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const html = await response.text();
                
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                // Swap Content
                const newStats = doc.querySelector('.balance-card');
                const newTransactions = doc.querySelector('.transaction-list');

                if (newStats && dashboardStats) dashboardStats.innerHTML = newStats.innerHTML;
                if (newTransactions && transactionList) transactionList.innerHTML = newTransactions.innerHTML;

                // Update Browser URL
                window.history.pushState({}, '', url);
            } catch (error) {
                console.error('AJAX Error:', error);
                alert('Failed to load data. Please try again.');
            }
        });
    });
});
</script>
@endpush
