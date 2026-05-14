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
                    <div class="position-relative" style="width: 250px;">
                        <input type="text" id="searchInput" class="form-control rounded-pill pe-5" placeholder="Search history...">
                        <i class="bi bi-search position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></i>
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
                                @php
                                    // Normalize the API response to ensure it's a list of transactions
                                    $txList = $histories['data'] ?? $histories ?? [];
                                    
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
                                <tr>
                                    <td class="py-3 border-bottom">
                                        <div class="icon-circle text-white d-inline-flex"
                                         style="background-color: var(--{{ $transaction['type'] === 'income' ? 'income-green' : ($transaction['type'] === 'transfer' ? 'primary-purple' : 'expense-red') }}); width: 40px; height: 40px;">
                                        <i class="bi bi-arrow-{{ $transaction['type'] === 'income' ? 'down' : ($transaction['type'] === 'transfer' ? 'left-right' : 'up') }} fw-bold"></i>
                                    </div>
                                    </td>
                                    <td class="py-3 border-bottom fw-bold text-secondary">{{ $transaction['title'] }}</td>
                                    <td class="py-3 border-bottom text-muted">{{{ \Carbon\Carbon::parse($transaction['transaction_date'])->locale('id')->settings(['formatFunction' => 'translatedFormat'])->format('j F Y') }}}</td>
                                    <td class="py-3 border-bottom text-end fw-bold"
                                        style="color: var(--{{ $transaction['type'] === 'income' ? 'income-green' : ($transaction['type'] === 'transfer' ? 'primary-purple' : 'expense-red') }});">
                                        {{ $transaction['type'] === 'income' ? '+' : ($transaction['type'] === 'transfer' ? '↔' : '-') }}Rp {{ number_format((float)($transaction['amount'] ?? 0), 0, ',', '.') }}
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterLinks = document.querySelectorAll('.filter-sidebar-btn');
    const tableContainer = document.querySelector('.table-responsive');

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
                const newTable = doc.querySelector('.table-responsive');

                if (newTable && tableContainer) {
                    tableContainer.innerHTML = newTable.innerHTML;
                }

                // Update Browser URL
                window.history.pushState({}, '', url);

                // Re-apply search filter if active
                const searchInput = document.getElementById('searchInput');
                if (searchInput && searchInput.value) {
                    searchInput.dispatchEvent(new Event('keyup'));
                }
            } catch (error) {
                console.error('AJAX Error:', error);
                alert('Failed to load data. Please try again.');
            }
        });
    });
});

// Search logic
document.addEventListener('keyup', function(e) {
    if (e.target && e.target.id === 'searchInput') {
        const filter = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            if (row.cells.length === 1) return; // Skip empty message
            
            const description = row.cells[1]?.textContent.toLowerCase() || '';
            const amount = row.cells[3]?.textContent.toLowerCase() || '';
            const date = row.cells[2]?.textContent.toLowerCase() || '';
            
            if (description.includes(filter) || amount.includes(filter) || date.includes(filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
});
</script>
@endpush
