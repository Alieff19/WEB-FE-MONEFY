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
        .table-hover tbody tr:hover {
            background-color: #f8fafc;
        }
        /* Detail Modal Styling */
        .status-badge {
            background: #ecfdf5;
            color: #059669;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            display: inline-block;
        }
        .border-dashed {
            border-top: 1px dashed #e2e8f0;
            margin: 1.5rem 0;
        }
        #btnExportPDF {
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        }
        #btnExportPDF:hover {
            background-color: #7C4CFF !important;
            color: white !important;
            border-color: #7C4CFF !important;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(124, 76, 255, 0.25);
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
                        <a href="{{ route('history', ['period' => 'day']) }}" class="btn filter-sidebar-btn no-preloader text-start {{ request('period') == 'day' || !request('period') ? 'active' : '' }}">Day</a>
                        <a href="{{ route('history', ['period' => 'week']) }}" class="btn filter-sidebar-btn no-preloader text-start {{ request('period') == 'week' ? 'active' : '' }}">Week</a>
                        <a href="{{ route('history', ['period' => 'month']) }}" class="btn filter-sidebar-btn no-preloader text-start {{ request('period') == 'month' ? 'active' : '' }}">Month</a>
                        <a href="{{ route('history', ['period' => 'year']) }}" class="btn filter-sidebar-btn no-preloader text-start {{ request('period') == 'year' ? 'active' : '' }}">Year</a>
                        <a href="{{ route('history', ['period' => 'all']) }}" class="btn filter-sidebar-btn no-preloader text-start {{ request('period') == 'all' ? 'active' : '' }}">All</a>
                    </div>
                </div>
            </div>

            <!-- Main History Data Table -->
            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold mb-0" style="color: var(--text-dark);">History Logs</h4>
                    <div class="d-flex align-items-center gap-2">
                        <button id="btnExportPDF" data-balance="{{ $totalBalance ?? 0 }}" class="btn rounded-pill px-3 fw-bold d-flex align-items-center gap-2" style="border: 2px solid #7C4CFF; color: #7C4CFF; background: transparent; font-size: 0.9rem;">
                            <i class="bi bi-file-earmark-pdf-fill"></i> Export PDF
                        </button>
                        <div class="position-relative" style="width: 220px;">
                            <input type="text" id="searchInput" class="form-control rounded-pill pe-5" placeholder="Search history...">
                            <i class="bi bi-search position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></i>
                        </div>
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
                                <tr onclick="showDetail(this)" 
                                    data-type="{{ $transaction['type'] }}"
                                    data-title="{{ $transaction['title'] }}"
                                    data-category="{{ $transaction['category'] }}"
                                    data-date="{{ \Carbon\Carbon::parse($transaction['transaction_date'])->addHours(7)->format('d M Y, H:i') }}"
                                    data-amount="Rp {{ number_format((float)($transaction['amount'] ?? 0), 0, ',', '.') }}"
                                    data-wallet="{{ $transaction['wallet']['name_wallet'] ?? 'No Wallet' }}"
                                    data-note="{{ $transaction['note'] ?? '-' }}"
                                    data-id="{{ $transaction['id'] ?? '0' }}"
                                    style="cursor: pointer;">
                                    <td class="py-3 border-bottom">
                                        <div class="icon-circle text-white d-inline-flex"
                                         style="background-color: var(--{{ $transaction['type'] === 'income' ? 'income-green' : ($transaction['type'] === 'transfer' ? 'primary-purple' : 'expense-red') }}); width: 40px; height: 40px;">
                                        <i class="bi bi-arrow-{{ $transaction['type'] === 'income' ? 'down' : ($transaction['type'] === 'transfer' ? 'left-right' : 'up') }} fw-bold"></i>
                                    </div>
                                    </td>
                                    <td class="py-3 border-bottom fw-bold text-secondary">{{ $transaction['title'] }}</td>
                                    <td class="py-3 border-bottom text-muted" style="font-size: 0.9rem;">{{{ \Carbon\Carbon::parse($transaction['transaction_date'])->addHours(7)->locale('id')->settings(['formatFunction' => 'translatedFormat'])->format('j F Y, H:i') }}}</td>
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

    {{-- Transaction Detail Modal --}}
    <div class="modal fade" id="transactionDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 28px;">
                <div class="modal-header border-0 pb-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold" style="color: var(--text-dark);">Transaction Detail</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 pt-2">
                    <div class="text-center mb-4">
                        <div class="status-badge mb-3">
                            <i class="bi bi-check-circle-fill me-1"></i> Success
                        </div>
                        <h2 class="fw-bold mb-1" id="detailAmount" style="color: var(--text-dark); font-size: 2.2rem;">Rp 0</h2>
                        <p class="text-muted fw-semibold" id="detailType" style="text-transform: capitalize;">Expense</p>
                    </div>
                    
                    <div class="border-dashed"></div>

                    <div class="detail-rows">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted fw-medium">Reference ID</span>
                            <span class="fw-bold" id="detailRef" style="color: var(--text-dark);">TX-249102</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted fw-medium">Time</span>
                            <span class="fw-bold" id="detailTime" style="color: var(--text-dark);">12 May 2026</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted fw-medium">Category</span>
                            <span class="fw-bold" id="detailCategory" style="color: var(--text-dark);">Food & Drink</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted fw-medium">Wallet</span>
                            <span class="fw-bold text-primary" id="detailWallet">Main Cash</span>
                        </div>
                        
                        <div class="mt-4">
                            <label class="text-muted small fw-bold mb-2 d-block text-uppercase" style="letter-spacing: 1px;">Note</label>
                            <div class="p-3 rounded-4" style="background: #f8fafc; border: 1px solid #f1f5f9;">
                                <p class="mb-0 text-secondary" id="detailNote" style="font-size: 0.95rem; line-height: 1.5;">No note added.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light w-100 py-3 fw-bold" data-bs-dismiss="modal" style="border-radius: 18px; color: #64748b; background: #f1f5f9;">Close</button>
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

            // Feedback: Dim the table while loading
            if (tableContainer) {
                tableContainer.style.opacity = '0.5';
                tableContainer.style.transition = 'opacity 0.2s';
            }

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
                    tableContainer.style.opacity = '1';
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
                if (tableContainer) tableContainer.style.opacity = '1';
                alert('Failed to load data. Please try again.');
            }
        });
    });

    const btnExportPDF = document.getElementById('btnExportPDF');
    if (btnExportPDF) {
        btnExportPDF.addEventListener('click', function() {
            const rows = document.querySelectorAll('tbody tr');
            let transactions = [];
            let totalIncome = 0;
            let totalExpense = 0;
            
            // Definisikan URL logo aplikasi
            const logoUrl = '{{ asset("assets/images/logo.png") }}';
            
            // Ambil Saldo Aktif Saat Ini dari data attribute
            const actualBalance = parseFloat(btnExportPDF.dataset.balance) || 0;

            rows.forEach(row => {
                if (row.style.display === 'none') return;
                if (row.cells.length === 1 && row.cells[0].textContent.includes('No transactions')) return;

                const type = row.dataset.type;
                const title = row.dataset.title;
                const category = row.dataset.category;
                const date = row.dataset.date;
                const amountText = row.dataset.amount || 'Rp 0';
                
                const amountVal = parseFloat(amountText.replace(/[^\d]/g, '')) || 0;

                if (type === 'income') {
                    totalIncome += amountVal;
                } else if (type === 'expense') {
                    totalExpense += amountVal;
                }

                transactions.push({
                    type,
                    title,
                    category,
                    date,
                    amountText
                });
            });

            if (transactions.length === 0) {
                alert('Tidak ada data transaksi untuk diekspor.');
                return;
            }

            const netBalance = totalIncome - totalExpense;
            const activePeriodBtn = document.querySelector('.filter-sidebar-btn.active');
            const activePeriod = activePeriodBtn ? activePeriodBtn.textContent.trim() : 'Semua';

            const printFrame = document.createElement('iframe');
            printFrame.style.position = 'fixed';
            printFrame.style.right = '0';
            printFrame.style.bottom = '0';
            printFrame.style.width = '0';
            printFrame.style.height = '0';
            printFrame.style.border = '0';
            document.body.appendChild(printFrame);

            const doc = printFrame.contentWindow.document;
            doc.open();
            
            const htmlContent = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Laporan Keuangan Monefy</title>
                <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
                <style>
                    body {
                        font-family: 'Inter', sans-serif;
                        color: #1e293b;
                        padding: 40px;
                        margin: 0;
                    }
                    .header {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        border-bottom: 2px solid #f1f5f9;
                        padding-bottom: 20px;
                        margin-bottom: 30px;
                    }
                    .header-logo {
                        font-size: 24px;
                        font-weight: 800;
                        color: #7C4CFF;
                    }
                    .header-title {
                        font-size: 13px;
                        color: #64748b;
                        text-align: right;
                        line-height: 1.5;
                    }
                    .title-report {
                        font-size: 22px;
                        font-weight: 700;
                        margin-top: 5px;
                        color: #0f172a;
                    }
                    .summary-grid {
                        display: grid;
                        grid-template-columns: repeat(4, 1fr);
                        gap: 15px;
                        margin-bottom: 35px;
                    }
                    .summary-card {
                        background: #f8fafc;
                        border: 1px solid #f1f5f9;
                        border-radius: 12px;
                        padding: 16px;
                    }
                    .summary-label {
                        font-size: 10px;
                        font-weight: 600;
                        text-transform: uppercase;
                        color: #64748b;
                        letter-spacing: 0.5px;
                        margin-bottom: 6px;
                    }
                    .summary-value {
                        font-size: 16px;
                        font-weight: 700;
                    }
                    .value-income { color: #10B981; }
                    .value-expense { color: #EF4444; }
                    .value-net { color: #0f172a; }
                    
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-bottom: 40px;
                    }
                    th {
                        background: #f8fafc;
                        font-size: 11px;
                        font-weight: 700;
                        text-transform: uppercase;
                        color: #64748b;
                        text-align: left;
                        padding: 12px 16px;
                        border-bottom: 2px solid #e2e8f0;
                    }
                    td {
                        padding: 14px 16px;
                        font-size: 13px;
                        border-bottom: 1px solid #f1f5f9;
                    }
                    .badge {
                        display: inline-block;
                        padding: 4px 8px;
                        border-radius: 20px;
                        font-size: 10px;
                        font-weight: 700;
                        text-transform: uppercase;
                    }
                    .badge-income { background: #d1fae5; color: #065f46; }
                    .badge-expense { background: #fee2e2; color: #991b1b; }
                    .badge-transfer { background: #f3e8ff; color: #6b21a8; }
                    
                    .text-right { text-align: right; }
                    .footer {
                        text-align: center;
                        font-size: 11px;
                        color: #94a3b8;
                        border-top: 1px solid #f1f5f9;
                        padding-top: 20px;
                        margin-top: 50px;
                    }
                    @media print {
                        body { padding: 0; }
                    }
                </style>
            </head>
            <body>
                <div class="header">
                    <div>
                        <img src="${logoUrl}" alt="Monefy Logo" style="height: 75px; object-fit: contain; margin-bottom: 8px; display: block;">
                        <div class="title-report">Laporan Riwayat Keuangan</div>
                    </div>
                    <div class="header-title">
                        <div>Tanggal Cetak: ${new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}</div>
                        <div>Periode Filter: <strong>${activePeriod}</strong></div>
                    </div>
                </div>

                <div class="summary-grid">
                    <div class="summary-card">
                        <div class="summary-label">Total Pemasukan</div>
                        <div class="summary-value value-income">Rp ${totalIncome.toLocaleString('id-ID')}</div>
                    </div>
                    <div class="summary-card">
                        <div class="summary-label">Total Pengeluaran</div>
                        <div class="summary-value value-expense">Rp ${totalExpense.toLocaleString('id-ID')}</div>
                    </div>
                    <div class="summary-card" style="background: ${netBalance >= 0 ? '#f0fdf4' : '#fdf2f2'}; border-color: ${netBalance >= 0 ? '#dcfce7' : '#fee2e2'};">
                        <div class="summary-label">Selisih Periode</div>
                        <div class="summary-value value-net" style="color: ${netBalance >= 0 ? '#166534' : '#991b1b'};">Rp ${netBalance.toLocaleString('id-ID')}</div>
                    </div>
                    <div class="summary-card" style="background: #f5f3ff; border-color: #ddd6fe;">
                        <div class="summary-label" style="color: #6d28d9;">Saldo Saat Ini</div>
                        <div class="summary-value" style="color: #6d28d9;">Rp ${actualBalance.toLocaleString('id-ID')}</div>
                    </div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Jenis</th>
                            <th>Deskripsi / Kategori</th>
                            <th>Waktu & Tanggal</th>
                            <th class="text-right">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${transactions.map(t => {
                            let badgeClass = 'badge-expense';
                            let sign = '-';
                            if (t.type === 'income') {
                                badgeClass = 'badge-income';
                                sign = '+';
                            } else if (t.type === 'transfer') {
                                badgeClass = 'badge-transfer';
                                sign = '↔ ';
                            }

                            return `
                                <tr>
                                    <td><span class="badge ${badgeClass}">${t.type}</span></td>
                                    <td>
                                        <div style="font-weight: 600;">${t.title}</div>
                                        <div style="font-size: 11px; color: #64748b; margin-top: 2px;">Kategori: ${t.category}</div>
                                    </td>
                                    <td style="color: #64748b;">${t.date}</td>
                                    <td class="text-right" style="font-weight: 700; color: ${t.type === 'income' ? '#10B981' : (t.type === 'transfer' ? '#7C4CFF' : '#EF4444')};">
                                        ${sign}${t.amountText}
                                    </td>
                                </tr>
                            `;
                        }).join('')}
                    </tbody>
                </table>

                <div class="footer">
                    Laporan ini dibuat secara otomatis oleh aplikasi pengelolaan keuangan Monefy pada ${new Date().toLocaleString('id-ID')}.
                </div>
            </body>
            </html>
            `;

            doc.write(htmlContent);
            doc.close();

            // Tunggu gambar logo dimuat agar tidak blank/kosong saat dicetak
            let printTriggered = false;
            const triggerPrint = () => {
                if (printTriggered) return;
                printTriggered = true;
                printFrame.contentWindow.focus();
                printFrame.contentWindow.print();
                setTimeout(() => {
                    if (printFrame.parentNode) {
                        document.body.removeChild(printFrame);
                    }
                }, 1500);
            };

            const logoImg = doc.querySelector('img');
            if (logoImg) {
                logoImg.onload = triggerPrint;
                logoImg.onerror = triggerPrint;
                if (logoImg.complete) {
                    triggerPrint();
                }
            } else {
                triggerPrint();
            }

            // Fallback maksimal 2 detik jika koneksi lambat
            setTimeout(triggerPrint, 2000);
        });
    }
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
// Detail Modal logic
function showDetail(row) {
    const data = row.dataset;
    const modal = new bootstrap.Modal(document.getElementById('transactionDetailModal'));
    
    document.getElementById('detailAmount').innerText = data.amount;
    document.getElementById('detailType').innerText = data.type;
    document.getElementById('detailTime').innerText = data.date;
    document.getElementById('detailCategory').innerText = data.category;
    document.getElementById('detailWallet').innerText = data.wallet;
    document.getElementById('detailNote').innerText = data.note || '-';
    document.getElementById('detailRef').innerText = 'TX-' + (249000 + parseInt(data.id));

    // Dynamic color for amount
    const amountEl = document.getElementById('detailAmount');
    if (data.type === 'income') amountEl.style.color = '#10B981';
    else if (data.type === 'transfer') amountEl.style.color = '#6A4CFF';
    else amountEl.style.color = '#EF4444';

    modal.show();
}
</script>
@endpush
