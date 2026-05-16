@extends('layouts.app')

@section('title', 'Monefy - Analytic')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;600;700;900&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;500;600&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script>
    tailwind.config = {
        corePlugins: {
            preflight: false, // Prevent Tailwind from overriding Bootstrap's global styles
            container: false, // Prevent Tailwind from breaking Bootstrap's .container
        },
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                    "surface-container-high": "#eae6f2",
                    "primary": "#4a40c1",
                    "on-background": "#1b1b23",
                    "surface": "#fcf8ff",
                    "on-error-container": "#93000a",
                    "outline-variant": "#c8c4d6",
                    "surface-container": "#f0ecf7",
                    "primary-fixed-dim": "#c4c0ff",
                    "on-secondary-fixed": "#1b192d",
                    "secondary-fixed-dim": "#c8c3de",
                    "error-container": "#ffdad6",
                    "on-tertiary-fixed-variant": "#454749",
                    "on-tertiary-container": "#f0f0f3",
                    "surface-variant": "#e5e1ec",
                    "inverse-surface": "#302f38",
                    "inverse-primary": "#c4c0ff",
                    "on-surface-variant": "#474554",
                    "primary-container": "#635bdb",
                    "surface-bright": "#fcf8ff",
                    "surface-container-low": "#f6f2fd",
                    "tertiary-fixed-dim": "#c6c6c9",
                    "tertiary": "#535557",
                    "on-primary": "#ffffff",
                    "on-error": "#ffffff",
                    "surface-container-highest": "#e5e1ec",
                    "tertiary-fixed": "#e2e2e5",
                    "on-primary-container": "#f2eeff",
                    "primary-fixed": "#e3dfff",
                    "surface-dim": "#dcd8e3",
                    "on-primary-fixed-variant": "#3b2fb3",
                    "on-tertiary-fixed": "#1a1c1e",
                    "secondary-container": "#e1dcf8",
                    "on-secondary": "#ffffff",
                    "surface-container-lowest": "#ffffff",
                    "on-tertiary": "#ffffff",
                    "surface-tint": "#544bcb",
                    "on-surface": "#1b1b23",
                    "error": "#ba1a1a",
                    "on-primary-fixed": "#120068",
                    "secondary-fixed": "#e4dffb",
                    "background": "#fcf8ff",
                    "on-secondary-container": "#636077",
                    "secondary": "#5f5c73",
                    "inverse-on-surface": "#f3effa",
                    "on-secondary-fixed-variant": "#47445a",
                    "outline": "#777585"
            },
            "borderRadius": {
                    "DEFAULT": "0.25rem",
                    "lg": "0.5rem",
                    "xl": "0.75rem",
                    "xxl": "1.5rem",
                    "full": "9999px"
            },
            "fontFamily": {
                    "label-md": ["Work Sans", "sans-serif"],
                    "data-mono": ["Work Sans", "sans-serif"],
                    "headline-sm": ["Hanken Grotesk", "sans-serif"],
                    "display-lg": ["Hanken Grotesk", "sans-serif"],
                    "body-md": ["Work Sans", "sans-serif"],
                    "body-lg": ["Work Sans", "sans-serif"],
                    "headline-md": ["Hanken Grotesk", "sans-serif"]
            }
          },
        },
      }
</script>
<style>
    /* Scoped Tailwind base styles to avoid affecting the global navbar */
    .tw-main {
        background-color: #fcf8ff;
        color: #1b1b23;
        font-family: "Work Sans", sans-serif;
    }
    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        display: inline-block;
        vertical-align: middle;
    }
    /* Simple reset for elements inside tw-main */
    .tw-main h1, .tw-main h2, .tw-main h3, .tw-main p {
        margin: 0;
    }

    /* FIX NAVBAR: Tailwind overrides Bootstrap's .collapse visibility */
    .collapse:not(.show) {
        display: none !important;
        visibility: visible !important;
    }
    .collapse.show {
        display: block !important;
        visibility: visible !important;
    }
    @media (min-width: 992px) {
        .navbar-expand-lg .navbar-collapse {
            display: flex !important;
            visibility: visible !important;
        }
    }
</style>
@endpush

@section('content')
<div class="tw-main w-full py-8">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      
      <!-- Header Section -->
      <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
          <h1 class="text-[32px] leading-[40px] font-bold font-headline-md text-on-surface">Analytics Dashboard</h1>
          <p class="text-[16px] leading-[24px] text-on-surface-variant mt-1">Your financial performance overview</p>
        </div>
        <div class="hidden md:flex gap-4">
          <form action="{{ route('analytic') }}" method="GET">
             <select name="trend" onchange="this.form.submit()" class="px-6 py-2 rounded-full border border-outline-variant text-[12px] font-bold text-on-surface-variant hover:bg-surface-container transition-colors bg-transparent appearance-none cursor-pointer">
                 <option value="weekly" {{ request('trend') == 'weekly' || !request('trend') ? 'selected' : '' }}>Weekly</option>
                 <option value="monthly" {{ request('trend') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                 <option value="yearly" {{ request('trend') == 'yearly' ? 'selected' : '' }}>Yearly</option>
             </select>
          </form>
        </div>
      </div>
      
      <!-- Summary Cards - Added to map the backend data ($totalIncome, etc) seamlessly -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <div class="bg-white rounded-xl p-6 shadow-sm border border-surface-container">
              <div class="flex items-center gap-3 mb-3">
                  <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                      <span class="material-symbols-outlined">arrow_downward</span>
                  </div>
                  <span class="text-[14px] font-bold text-on-surface-variant">Total Income</span>
              </div>
              <h3 class="text-[24px] font-bold text-green-600">Rp {{ number_format((float)($totalIncome ?? 0), 0, ',', '.') }}</h3>
          </div>
          <div class="bg-white rounded-xl p-6 shadow-sm border border-surface-container">
              <div class="flex items-center gap-3 mb-3">
                  <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-600">
                      <span class="material-symbols-outlined">arrow_upward</span>
                  </div>
                  <span class="text-[14px] font-bold text-on-surface-variant">Total Expense</span>
              </div>
              <h3 class="text-[24px] font-bold text-red-600">Rp {{ number_format((float)($totalExpense ?? 0), 0, ',', '.') }}</h3>
          </div>
          <div class="bg-primary rounded-xl p-6 shadow-md text-white">
              <div class="flex items-center gap-3 mb-3">
                  <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                      <span class="material-symbols-outlined">account_balance_wallet</span>
                  </div>
                  <span class="text-[14px] font-bold text-primary-fixed">Net Balance</span>
              </div>
              <h3 class="text-[24px] font-bold">Rp {{ number_format((float)($totalBalance ?? 0), 0, ',', '.') }}</h3>
          </div>
      </div>

      <!-- Monthly Overview Section -->
      <section class="mb-8">
        <div class="bg-white rounded-xxl p-8 shadow-sm border border-surface-container">
          <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
              @php
                  $trend = request('trend', 'weekly');
                  $displayTitle = 'Weekly';
                  if ($trend === 'monthly') $displayTitle = 'Monthly';
                  if ($trend === 'yearly') $displayTitle = 'Yearly';
              @endphp
              <h2 class="text-[20px] font-semibold font-headline-sm text-on-surface">{{ $displayTitle }} Overview</h2>
              <p class="text-[14px] text-on-surface-variant mt-1">Income and Expense comparison</p>
            </div>
            <div class="flex flex-wrap items-center gap-6">
              <div class="flex items-center gap-6 text-[12px] font-medium">
                <div class="flex items-center gap-2">
                  <div class="w-3 h-3 rounded-full bg-green-500"></div>
                  <span>Income</span>
                </div>
                <div class="flex items-center gap-2">
                  <div class="w-3 h-3 rounded-full bg-red-500"></div>
                  <span>Expense</span>
                </div>
              </div>
            </div>
          </div>
          <div class="w-full relative bg-surface-container-low/30 rounded-xl border border-surface-container-high p-4" style="height: 350px;">
            <canvas id="monthlyTrendChart"></canvas>
          </div>
        </div>
      </section>

      <!-- Bottom Insights Section -->
      <section class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Period Comparison (Bar Chart) -->
        <div class="lg:col-span-2 bg-white rounded-xxl p-8 shadow-sm border border-surface-container">
          <div class="flex justify-between items-center mb-8">
            <h2 class="text-[20px] font-semibold font-headline-sm text-on-surface">Period Comparison</h2>
          </div>
          <div class="w-full relative bg-surface-container-low/30 rounded-xl border border-surface-container-high p-4" style="height: 280px;">
             <canvas id="periodComparisonChart"></canvas>
          </div>
        </div>

        <!-- Category Breakdown (Donut Chart) -->
        <div class="bg-white rounded-xxl p-8 shadow-sm border border-surface-container flex flex-col">
          <div class="flex flex-col mb-6">
            <h2 class="text-[20px] font-semibold font-headline-sm text-on-surface mb-4">Category Breakdown</h2>
            <!-- Toggle Buttons -->
            <div class="flex bg-surface-container-low rounded-lg p-1 w-full gap-1">
               <button id="btnToggleIncome" class="flex-1 py-2 text-[14px] font-bold rounded-md transition-colors text-on-surface-variant hover:text-primary">Income</button>
               <button id="btnToggleExpense" class="flex-1 py-2 text-[14px] font-bold rounded-md transition-colors bg-white text-primary shadow-sm">Expense</button>
            </div>
          </div>

          <div class="flex-1 flex flex-col justify-between">
            <div class="relative w-40 h-40 mx-auto mb-8">
              <canvas id="categoryDonutChart"></canvas>
              <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                <span class="text-[12px] font-semibold text-on-surface-variant">Total</span>
                <span id="donutTotalAmount" class="text-[16px] font-bold text-primary mt-1">Rp 0</span>
              </div>
            </div>

            <div id="categoryListContainer" class="space-y-4">
               <!-- List akan diisi via JavaScript -->
            </div>
          </div>
        </div>
      </section>
  </div>
</div>

@push('styles')
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chartLabels = {!! json_encode($chartLabels ?? []) !!};
    const chartIncome = {!! json_encode($chartIncome ?? []) !!};
    const chartExpense = {!! json_encode($chartExpense ?? []) !!};
    
    const topExpenseLabels = {!! json_encode(array_map(function($e) { return is_array($e) ? $e['category_name'] : $e->category_name; }, $topExpenses ?? [])) !!};
    const topExpenseData = {!! json_encode(array_map(function($e) { return is_array($e) ? $e['total_amount'] : $e->total_amount; }, $topExpenses ?? [])) !!};
    
    // Formatting numbers to IDR
    const formatRp = (value) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(value);

    // 1. Monthly Trend Chart (Line)
    const ctxTrend = document.getElementById('monthlyTrendChart');
    if (ctxTrend) {
        new Chart(ctxTrend, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [
                    {
                        label: 'Income',
                        data: chartIncome,
                        borderColor: '#10B981', // green-500
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                    },
                    {
                        label: 'Expense',
                        data: chartExpense,
                        borderColor: '#EF4444', // red-500
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) { return context.dataset.label + ': ' + formatRp(context.parsed.y); }
                        }
                    }
                },
                scales: {
                    y: { beginAtZero: true, border: { display: false }, ticks: { callback: function(value) { return 'Rp ' + (value/1000) + 'k'; } } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // 2. Period Comparison Chart (Bar)
    const ctxBar = document.getElementById('periodComparisonChart');
    if (ctxBar) {
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [
                    {
                        label: 'Income',
                        data: chartIncome,
                        backgroundColor: '#10B981',
                        borderRadius: 6,
                    },
                    {
                        label: 'Expense',
                        data: chartExpense,
                        backgroundColor: '#EF4444',
                        borderRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: { label: function(context) { return context.dataset.label + ': ' + formatRp(context.parsed.y); } }
                    }
                },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [4, 4] }, ticks: { callback: function(value) { return 'Rp ' + (value/1000) + 'k'; } } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // 3. Category Breakdown (Donut Chart & List)
    const topExpenses = {!! json_encode($topExpenses ?? []) !!};
    const topIncomes = {!! json_encode($topIncomes ?? []) !!};
    const totalExpenseAmount = {!! json_encode($totalExpense ?? 0) !!};
    const totalIncomeAmount = {!! json_encode($totalIncome ?? 0) !!};

    const ctxDonut = document.getElementById('categoryDonutChart');
    const donutTotalAmount = document.getElementById('donutTotalAmount');
    const categoryListContainer = document.getElementById('categoryListContainer');
    const btnExpense = document.getElementById('btnToggleExpense');
    const btnIncome = document.getElementById('btnToggleIncome');

    let donutChartInstance = null;
    const colors = ['#4a40c1', '#5f5c73', '#777585', '#ba1a1a', '#535557'];
    const bgClasses = ['bg-primary', 'bg-secondary', 'bg-outline-variant', 'bg-error', 'bg-tertiary'];

    function renderCategory(type) {
        // Switch Active Button Style
        if (type === 'expense') {
            btnExpense.className = "flex-1 py-2 text-[14px] font-bold rounded-md transition-colors bg-white text-primary shadow-sm";
            btnIncome.className = "flex-1 py-2 text-[14px] font-bold rounded-md transition-colors text-on-surface-variant hover:text-primary";
        } else {
            btnIncome.className = "flex-1 py-2 text-[14px] font-bold rounded-md transition-colors bg-white text-primary shadow-sm";
            btnExpense.className = "flex-1 py-2 text-[14px] font-bold rounded-md transition-colors text-on-surface-variant hover:text-primary";
        }

        const dataArr = type === 'expense' ? topExpenses : topIncomes;
        const totalAmt = type === 'expense' ? totalExpenseAmount : totalIncomeAmount;
        donutTotalAmount.textContent = formatRp(totalAmt);

        const labels = dataArr.map(e => e.category_name);
        const dataVals = dataArr.map(e => e.total_amount);

        // Update Chart
        if (donutChartInstance) {
            donutChartInstance.destroy();
        }
        
        if (labels.length > 0) {
            donutChartInstance = new Chart(ctxDonut, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: dataVals,
                        backgroundColor: colors,
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: { display: false },
                        tooltip: { callbacks: { label: function(context) { return context.label + ': ' + formatRp(context.parsed); } } }
                    }
                }
            });
        }

        // Update List
        categoryListContainer.innerHTML = '';
        if (dataArr.length === 0) {
            categoryListContainer.innerHTML = `<div class="text-center text-on-surface-variant text-[14px] mt-4">No ${type}s found</div>`;
        } else {
            dataArr.forEach((item, index) => {
                const colorClass = bgClasses[index % bgClasses.length];
                categoryListContainer.innerHTML += `
                <div class="flex justify-between items-center">
                  <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full ${colorClass}"></div>
                    <span class="text-[14px] text-on-surface">${item.category_name}</span>
                  </div>
                  <span class="text-[14px] font-bold">${item.percentage}%</span>
                </div>`;
            });
        }
    }

    // Event Listeners for Toggles
    btnExpense.addEventListener('click', () => renderCategory('expense'));
    btnIncome.addEventListener('click', () => renderCategory('income'));

    // Initial render
    renderCategory('expense');
});
</script>
@endsection
