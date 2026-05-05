@extends('layouts.app')

@section('title', 'Monefy - Analytic')

@push('styles')
<style>
        .analytic-card {
            background-color: white;
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            border: none;
        }
        .progress-bar-purple { background-color: var(--primary-purple); }
        .progress-bar-green { background-color: var(--income-green); }
        .progress-bar-red { background-color: var(--expense-red); }
        .progress-bar-yellow { background-color: #F59E0B; }
    </style>
@endpush

@section('content')
<!-- Main Analytic Container -->
    <div class="container my-5">
        
        <!-- Summary Cards Row -->
        <div class="row g-4 mb-5">
            <div class="col-lg-4 col-md-6">
                <div class="analytic-card">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-circle" style="background-color: rgba(16, 185, 129, 0.1); color: var(--income-green);">
                            <i class="bi bi-arrow-down fs-4"></i>
                        </div>
                        <h6 class="text-muted fw-bold ms-3 mb-0">Total Income</h6>
                    </div>
                    <h3 class="fw-bold mb-0" style="color: var(--income-green);">{{ $totalIncome ?? 'Rp. 0' }}</h3>
                    <small class="text-success fw-bold"><i class="bi bi-graph-up"></i> 12% from last month</small>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="analytic-card">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-circle" style="background-color: rgba(239, 68, 68, 0.1); color: var(--expense-red);">
                            <i class="bi bi-arrow-up fs-4"></i>
                        </div>
                        <h6 class="text-muted fw-bold ms-3 mb-0">Total Expense</h6>
                    </div>
                    <h3 class="fw-bold mb-0" style="color: var(--expense-red);">{{ $totalExpense ?? 'Rp. 0' }}</h3>
                    <small class="text-danger fw-bold"><i class="bi bi-graph-up"></i> 5% from last month</small>
                </div>
            </div>

            <div class="col-lg-4 col-md-12">
                <div class="analytic-card" style="background: var(--gradient-card);">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-circle bg-white text-primary">
                            <i class="bi bi-wallet2 fs-4"></i>
                        </div>
                        <h6 class="text-white-50 fw-bold ms-3 mb-0">Net Balance</h6>
                    </div>
                    <h3 class="fw-bold mb-0 text-white">{{ $totalBalance ?? 'Rp. 0' }}</h3>
                    <small class="text-white fw-bold">Safe Zone</small>
                </div>
            </div>
        </div>

        <!-- Main Chart & Categories -->
        <div class="row g-4">
            
            <!-- Left: Main Chart Area -->
            <div class="col-lg-8">
                <div class="analytic-card h-100">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold mb-0" style="color: var(--text-dark);">Cash Flow Trend</h4>
                        <select class="form-select form-select-sm w-auto fw-bold text-muted border-0 bg-light" onchange="window.location.href='{{ route('analytic') }}?trend=' + this.value">
                            <option value="6months" {{ request('trend') == '6months' || !request('trend') ? 'selected' : '' }}>Last 6 Months</option>
                            <option value="year" {{ request('trend') == 'year' ? 'selected' : '' }}>This Year</option>
                        </select>
                    </div>
                    <!-- Chart.js Canvas -->
                    <div style="position: relative; height:300px; width:100%">
                        <canvas id="cashflowChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Right: Category Breakdown -->
            <div class="col-lg-4">
                <div class="analytic-card h-100">
                    <h4 class="fw-bold mb-4" style="color: var(--text-dark);">Top Expenses</h4>
                    
                    @forelse($topExpenses ?? [] as $expense)
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-bold text-secondary"><i class="bi bi-tag me-2" style="color:var(--primary-purple)"></i> {{ $expense->category_name }}</span>
                            <span class="fw-bold text-dark">{{ $expense->percentage }}%</span>
                        </div>
                        <div class="progress" style="height: 10px; border-radius: 10px;">
                            <div class="progress-bar progress-bar-purple" role="progressbar" style="width: {{ $expense->percentage }}%" aria-valuenow="{{ $expense->percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small class="text-muted d-block text-end mt-1">{{ $expense->total_amount }}</small>
                    </div>
                    @empty
                    <div class="text-center text-muted">No expenses found</div>
                    @endforelse

                </div>

                </div>
            </div>

        </div>
    </div>
@endsection
