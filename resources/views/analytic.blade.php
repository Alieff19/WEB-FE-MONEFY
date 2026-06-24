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
            @php
              // Support both analytics 'trend' and history 'period' to keep filters in sync
              $period = request('period', null);
              $selectedTrend = request('trend', 'weekly');
              if ($period) {
                if ($period === 'day' || $period === 'week') $selectedTrend = 'weekly';
                if ($period === 'month') $selectedTrend = 'monthly';
                if ($period === 'year' || $period === 'all') $selectedTrend = 'yearly';
              }

              $selectedMonth = request('month', date('n'));
              $selectedYear = request('year', date('Y'));
              $months = [
                1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
              ];
              // From a bit in the past up to 1 year ahead
              $startYear = 2020;
              $endYear = (int)date('Y') + 1;
              $years = range($startYear, $endYear);
            @endphp
          <form action="{{ route('analytic') }}" method="GET" class="flex gap-2 items-center" onsubmit="
              if(this.trend.value === 'yearly') { 
                  if(this.month) this.month.disabled = true; 
                  if(this.week) this.week.disabled = true; 
              } else if(this.trend.value === 'monthly') {
                  if(this.week) this.week.disabled = true; 
              }
          ">
             <select name="trend" onchange="this.form.submit()" class="px-6 py-2 rounded-full border border-outline-variant text-[12px] font-bold text-on-surface-variant hover:bg-surface-container transition-colors bg-transparent appearance-none cursor-pointer">
                 <option value="weekly" {{ $selectedTrend == 'weekly' ? 'selected' : '' }}>Weekly</option>
                 <option value="monthly" {{ $selectedTrend == 'monthly' ? 'selected' : '' }}>Monthly</option>
                 <option value="yearly" {{ $selectedTrend == 'yearly' ? 'selected' : '' }}>Yearly</option>
             </select>

             @if($selectedTrend !== 'yearly')
             <select name="month" onchange="this.form.submit()" class="px-6 py-2 rounded-full border border-outline-variant text-[12px] font-bold text-on-surface-variant hover:bg-surface-container transition-colors bg-transparent appearance-none cursor-pointer">
                 @foreach($months as $num => $name)
                     <option value="{{ $num }}" {{ $selectedMonth == $num ? 'selected' : '' }}>{{ $name }}</option>
                 @endforeach
             </select>
             @endif

             @if($selectedTrend === 'weekly')
             <select name="week" onchange="this.form.submit()" class="px-6 py-2 rounded-full border border-outline-variant text-[12px] font-bold text-on-surface-variant hover:bg-surface-container transition-colors bg-transparent appearance-none cursor-pointer">
                 @for($w = 1; $w <= 5; $w++)
                     <option value="{{ $w }}" {{ request('week', 1) == $w ? 'selected' : '' }}>Week {{ $w }}</option>
                 @endfor
             </select>
             @endif

             <select name="year" onchange="this.form.submit()" class="px-6 py-2 rounded-full border border-outline-variant text-[12px] font-bold text-on-surface-variant hover:bg-surface-container transition-colors bg-transparent appearance-none cursor-pointer">
                 @foreach($years as $yr)
                     <option value="{{ $yr }}" {{ $selectedYear == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                 @endforeach
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
              <h3 id="totalIncomeValue" class="text-[24px] font-bold text-green-600">Rp {{ number_format((float)($totalIncome ?? 0), 0, ',', '.') }}</h3>
          </div>
          <div class="bg-white rounded-xl p-6 shadow-sm border border-surface-container">
              <div class="flex items-center gap-3 mb-3">
                  <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-600">
                      <span class="material-symbols-outlined">arrow_upward</span>
                  </div>
                  <span class="text-[14px] font-bold text-on-surface-variant">Total Expense</span>
              </div>
              <h3 id="totalExpenseValue" class="text-[24px] font-bold text-red-600">Rp {{ number_format((float)($totalExpense ?? 0), 0, ',', '.') }}</h3>
          </div>
          <div class="bg-primary rounded-xl p-6 shadow-md text-white">
              <div class="flex items-center gap-3 mb-3">
                  <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                      <span class="material-symbols-outlined">account_balance_wallet</span>
                  </div>
                  <span class="text-[14px] font-bold text-primary-fixed">Net Balance</span>
              </div>
              <h3 id="totalBalanceValue" class="text-[24px] font-bold">Rp {{ number_format((float)($totalBalance ?? 0), 0, ',', '.') }}</h3>
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

      <!-- Financial Sandbox Simulator Card -->
      <section class="mt-8 mb-8">
        <div class="bg-white rounded-xxl p-8 shadow-sm border border-surface-container">
          <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
              <h2 class="text-[20px] font-semibold font-headline-sm text-on-surface">Financial Goal Sandbox Simulator</h2>
              <p class="text-[14px] text-on-surface-variant mt-1">Simulasikan target impian Anda berdasarkan kemampuan menabung bulanan</p>
            </div>
            <div class="flex items-center gap-2">
              <span class="px-3 py-1 bg-[#4a40c1]/10 text-[#4a40c1] text-[12px] font-bold rounded-full">Interactive Sandbox</span>
            </div>
          </div>
          
          <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Side: Sliders & Settings (5 cols) -->
            <div class="lg:col-span-5 space-y-6">
              <!-- Preset Templates -->
              <div>
                <label class="block text-[14px] font-bold text-on-surface-variant mb-2">Pilih Target Impian</label>
                <div class="flex flex-wrap gap-2">
                  <button type="button" class="btn-preset-goal px-3 py-1.5 rounded-lg border border-outline-variant text-[12px] font-semibold hover:bg-surface-container transition-all" data-amount="15000000" data-name="Beli Gadget Baru">📱 Gadget (15jt)</button>
                  <button type="button" class="btn-preset-goal px-3 py-1.5 rounded-lg border border-outline-variant text-[12px] font-semibold hover:bg-surface-container transition-all" data-amount="30000000" data-name="Liburan Impian">✈️ Liburan (30jt)</button>
                  <button type="button" class="btn-preset-goal px-3 py-1.5 rounded-lg border border-outline-variant text-[12px] font-semibold hover:bg-surface-container transition-all" data-amount="50000000" data-name="Modal Usaha">💼 Modal Usaha (50jt)</button>
                  <button type="button" class="btn-preset-goal px-3 py-1.5 rounded-lg border border-outline-variant text-[12px] font-semibold hover:bg-surface-container transition-all" data-amount="100000000" data-name="Dana Darurat">🛡️ Dana Darurat (100jt)</button>
                </div>
              </div>

              <!-- Input Name -->
              <div>
                <label for="simGoalName" class="block text-[14px] font-bold text-on-surface-variant mb-1">Nama Impian</label>
                <input type="text" id="simGoalName" value="Target Impian Saya" class="w-full px-4 py-2 rounded-lg border border-outline-variant text-[14px] focus:ring-primary focus:border-primary" style="outline: none;">
              </div>

              <!-- Slider Target Amount -->
              <div>
                <div class="flex justify-between items-center mb-1">
                  <label for="simTargetAmount" class="text-[14px] font-bold text-on-surface-variant">Target Dana</label>
                  <span id="labelTargetAmount" class="text-[14px] font-bold text-primary">Rp 15.000.000</span>
                </div>
                <input type="range" id="simTargetAmount" min="1000000" max="250000000" step="1000000" value="15000000" class="w-full accent-primary">
              </div>

              <!-- Slider Monthly Saving -->
              <div>
                <div class="flex justify-between items-center mb-1">
                  <label for="simMonthlySaving" class="text-[14px] font-bold text-on-surface-variant">Kemampuan Menabung / Bulan</label>
                  <span id="labelMonthlySaving" class="text-[14px] font-bold text-green-600">Rp 1.000.000</span>
                </div>
                <input type="range" id="simMonthlySaving" min="100000" max="20000000" step="100000" value="1000000" class="w-full accent-primary">
              </div>

            </div>

            <!-- Right Side: Chart & Milestones (7 cols) -->
            <div class="lg:col-span-7 flex flex-col justify-between">
              <div class="w-full bg-surface-container-low/30 rounded-xl border border-surface-container-high p-4 mb-6" style="height: 240px;">
                <canvas id="simulatorChart"></canvas>
              </div>

              <!-- Results Cards -->
              <div class="grid grid-cols-2 gap-4">
                <div class="bg-surface-container rounded-xl p-4 border border-outline-variant">
                  <span class="text-[12px] text-on-surface-variant block mb-1">Estimasi Waktu</span>
                  <h4 id="resultDuration" class="text-[18px] md:text-[20px] font-bold text-primary">15 Bulan</h4>
                </div>
                <div class="bg-green-100/30 rounded-xl p-4 border border-green-200/50">
                  <span class="text-[12px] text-on-surface-variant block mb-1">Target Tercapai</span>
                  <h4 id="resultTargetDate" class="text-[18px] md:text-[20px] font-bold text-green-600">September 2027</h4>
                </div>
              </div>

              <!-- Recommendation Text -->
              <div class="mt-4 p-4 rounded-xl bg-surface-container/50 border border-outline-variant text-[13px] text-on-surface-variant flex items-start gap-2">
                <span class="material-symbols-outlined text-[18px] text-primary mt-0.5">info</span>
                <p id="recommendationMessage">Dengan menabung Rp 1.000.000 per bulan ditambah imbal hasil investasi 5% pertahun, Anda membutuhkan waktu 15 bulan untuk mewujudkan "Beli Gadget Baru" senilai Rp 15.000.000.</p>
              </div>
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
    let trendChartInstance = null;
    if (ctxTrend) {
        trendChartInstance = new Chart(ctxTrend, {
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
    let comparisonChartInstance = null;
    if (ctxBar) {
        comparisonChartInstance = new Chart(ctxBar, {
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

        const rawArr = type === 'expense' ? topExpenses : topIncomes;
        const totalAmt = Number(type === 'expense' ? totalExpenseAmount : totalIncomeAmount) || 0;
        donutTotalAmount.textContent = formatRp(totalAmt);

        // Normalize items and compute percentage if missing
        const dataArr = (rawArr || []).map(item => {
          // support both array/object forms
          const category_name = item.category_name ?? item.category ?? item.name ?? '';
          const total_amount = Number(item.total_amount ?? item.amount ?? item.value ?? 0) || 0;
          const percentage = item.percentage ?? (totalAmt > 0 ? Math.round((total_amount / totalAmt) * 100) : 0);
          return { category_name, total_amount, percentage };
        }).filter(i => i.total_amount > 0);

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
            categoryListContainer.innerHTML = '';
            dataArr.forEach((item, index) => {
                const colorClass = bgClasses[index % bgClasses.length];
                const amtLabel = formatRp(item.total_amount);
                categoryListContainer.innerHTML += `
                <div class="flex justify-between items-center">
                  <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full ${colorClass}"></div>
                    <div class="flex flex-col">
                      <span class="text-[14px] text-on-surface">${item.category_name}</span>
                      <small class="text-[12px] text-on-surface-variant">${amtLabel}</small>
                    </div>
                  </div>
                  <span class="text-[14px] font-bold">${item.percentage}%</span>
                </div>`;
            });
        }
    }

    // Event Listeners for Toggles
    btnExpense.addEventListener('click', () => renderCategory('expense'));
    btnIncome.addEventListener('click', () => renderCategory('income'));

    // Refresh analytics via API when transactions change in another tab or on the same page
    async function refreshAnalytics() {
        const params = new URLSearchParams(window.location.search);
        const summaryUrl = '/analytics/summary?' + params.toString();
        const categoriesUrl = '/analytics/top-expenses?' + params.toString();

        try {
            const [summaryRes, categoriesRes] = await Promise.all([
                fetch(summaryUrl, { headers: { 'Accept': 'application/json' } }),
                fetch(categoriesUrl, { headers: { 'Accept': 'application/json' } })
            ]);

            if (!summaryRes.ok || !categoriesRes.ok) {
                console.warn('Analytics refresh failed', summaryRes.status, categoriesRes.status);
                return;
            }

            const summaryData = await summaryRes.json();
            const categoryData = await categoriesRes.json();

            // Update summary cards
            const incomeText = formatRp(summaryData.total_income ?? 0);
            const expenseText = formatRp(summaryData.total_expense ?? 0);
            const balanceText = formatRp(summaryData.total_balance ?? 0);
            document.getElementById('totalIncomeValue').textContent = incomeText;
            document.getElementById('totalExpenseValue').textContent = expenseText;
            document.getElementById('totalBalanceValue').textContent = balanceText;

            const newLabels = summaryData.chart_labels || [];
            const newIncome = summaryData.chart_income || [];
            const newExpense = summaryData.chart_expense || [];

            if (trendChartInstance) {
                trendChartInstance.data.labels = newLabels;
                trendChartInstance.data.datasets[0].data = newIncome;
                trendChartInstance.data.datasets[1].data = newExpense;
                trendChartInstance.update();
            }
            if (comparisonChartInstance) {
                comparisonChartInstance.data.labels = newLabels;
                comparisonChartInstance.data.datasets[0].data = newIncome;
                comparisonChartInstance.data.datasets[1].data = newExpense;
                comparisonChartInstance.update();
            }

            // Update category section data sources and re-render
            topExpenses.length = 0;
            topIncomes.length = 0;
            (categoryData.expenses || []).forEach(item => topExpenses.push(item));
            (categoryData.incomes || []).forEach(item => topIncomes.push(item));

            const activeType = btnExpense.classList.contains('bg-white') ? 'expense' : 'income';
            renderCategory(activeType);
        } catch (error) {
            console.error('Failed refresh analytics', error);
        }
    }

    window.addEventListener('transaction:updated', refreshAnalytics);
    window.addEventListener('storage', function(event) {
        if (event.key === 'transaction_updated_at') {
            refreshAnalytics();
        }
    });

    // Initial render
    renderCategory('expense');

    // 4. Financial Goal Sandbox Simulator
    const simTargetAmountInput = document.getElementById('simTargetAmount');
    const simMonthlySavingInput = document.getElementById('simMonthlySaving');
    const simGoalNameInput = document.getElementById('simGoalName');
    
    const labelTargetAmount = document.getElementById('labelTargetAmount');
    const labelMonthlySaving = document.getElementById('labelMonthlySaving');
    
    const resultDuration = document.getElementById('resultDuration');
    const resultTargetDate = document.getElementById('resultTargetDate');
    const recommendationMessage = document.getElementById('recommendationMessage');
    
    const presetButtons = document.querySelectorAll('.btn-preset-goal');
    const ctxSim = document.getElementById('simulatorChart');
    let simChartInstance = null;

    function runSimulation() {
        const targetAmount = parseFloat(simTargetAmountInput.value);
        const monthlySaving = parseFloat(simMonthlySavingInput.value);
        const goalName = simGoalNameInput.value.trim() || 'Target Impian';

        // Update Slider Labels
        labelTargetAmount.textContent = formatRp(targetAmount);
        labelMonthlySaving.textContent = formatRp(monthlySaving);

        let balance = 0;
        let months = 0;
        let chartData = [0];
        let chartLabels = ['Mulai'];

        // Max limit of 120 months (10 years)
        while (balance < targetAmount && months < 120) {
            months++;
            balance += monthlySaving;
            chartData.push(balance);
            chartLabels.push('Bln ' + months);
        }

        // Calculate Target Date
        const today = new Date();
        today.setMonth(today.getMonth() + months);
        const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        const targetMonthYear = monthNames[today.getMonth()] + ' ' + today.getFullYear();

        // Update Result Texts
        resultDuration.textContent = months >= 120 ? 'Lebih dari 10 Tahun' : months + ' Bulan';
        resultTargetDate.textContent = months >= 120 ? 'Sangat lama' : targetMonthYear;

        // Recommendation text
        let recText = `Dengan menyisihkan ${formatRp(monthlySaving)} per bulan secara konsisten, Anda membutuhkan waktu ${months} bulan untuk mengumpulkan dana target "${goalName}" senilai ${formatRp(targetAmount)}.`;
        recommendationMessage.textContent = recText;

        // Render / Update Chart
        if (ctxSim) {
            if (simChartInstance) {
                simChartInstance.destroy();
            }

            simChartInstance = new Chart(ctxSim, {
                type: 'line',
                data: {
                    labels: chartLabels,
                    datasets: [
                        {
                            label: 'Proyeksi Saldo Terkumpul',
                            data: chartData,
                            borderColor: '#4a40c1', // primary
                            backgroundColor: 'rgba(74, 64, 193, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0, // Straight linear line
                            pointRadius: months > 24 ? 0 : 3
                        },
                        {
                            label: 'Garis Target',
                            data: Array(chartLabels.length).fill(targetAmount),
                            borderColor: '#ba1a1a', // error/red line
                            borderDash: [5, 5],
                            borderWidth: 2,
                            fill: false,
                            pointRadius: 0
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
                        y: {
                            beginAtZero: true,
                            ticks: { callback: function(value) { return 'Rp ' + (value/1000) + 'k'; } }
                        },
                        x: { grid: { display: false } }
                    }
                }
            });
        }
    }

    if (simTargetAmountInput) {
        simTargetAmountInput.addEventListener('input', runSimulation);
        simMonthlySavingInput.addEventListener('input', runSimulation);
        simGoalNameInput.addEventListener('input', runSimulation);

        presetButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                presetButtons.forEach(b => b.classList.remove('bg-primary', 'text-white'));
                this.classList.add('bg-primary', 'text-white');

                const amount = this.dataset.amount;
                const name = this.dataset.name;

                simTargetAmountInput.value = amount;
                simGoalNameInput.value = name;

                runSimulation();
            });
        });

        // Initialize Simulation on Load
        runSimulation();
    }
});
</script>
@endsection
