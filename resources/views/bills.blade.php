@extends('layouts.app')

@section('title', 'Monefy - Bills Management')

@push('styles')
<style>
    :root {
        --primary-purple: #7C4CFF;
        --soft-purple: #F3F0FF;
        --text-dark: #1E293B;
        --danger-red: #FF4757;
        --success-green: #2ED573;
    }

    body { background-color: #ffffff !important; }

    /* ─── BILLS HERO CARD ─── */
    .bills-hero {
        background: linear-gradient(135deg, #7C4CFF 0%, #9D7CFF 100%);
        border-radius: 30px;
        padding: 2.5rem;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(124, 76, 255, 0.2);
        margin-bottom: 2rem;
    }
    .bills-hero::after {
        content: ''; position: absolute; top: -50px; right: -50px;
        width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;
    }

    .bills-hero .label { font-size: 0.9rem; opacity: 0.8; font-weight: 500; margin-bottom: 0.5rem; display: block; }
    .bills-hero .amount { font-size: 2.5rem; font-weight: 800; letter-spacing: -1px; }
    .bills-hero .badge-count { 
        background: rgba(255,255,255,0.2); backdrop-filter: blur(10px);
        padding: 5px 15px; border-radius: 12px; font-size: 0.85rem; font-weight: 700;
    }
    .bills-hero .due-warning {
        background: #FFA502; color: white; padding: 6px 15px; border-radius: 12px;
        font-size: 0.8rem; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; margin-top: 1.5rem;
    }

    /* ─── TABS ─── */
    .bills-tabs {
        background: #F8FAFC; border-radius: 20px; padding: 6px; display: inline-flex; margin-bottom: 2.5rem;
        border: 1px solid #E2E8F0;
    }
    .bill-tab-btn {
        padding: 10px 30px; border-radius: 16px; border: none; background: transparent;
        color: #64748B; font-weight: 700; font-size: 0.95rem; transition: 0.3s;
    }
    .bill-tab-btn.active {
        background: white; color: var(--primary-purple); box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    /* ─── BILL CARDS ─── */
    .bill-item {
        background: white; border: 1px solid #F1F5F9; border-radius: 24px; padding: 1.5rem;
        transition: all 0.3s; margin-bottom: 1.2rem; display: flex; align-items: center; justify-content: space-between;
    }
    .bill-item:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.04); border-color: var(--primary-purple); }

    .bill-icon {
        width: 60px; height: 60px; border-radius: 18px; background: #F3F0FF;
        display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: var(--primary-purple);
    }
    .bill-info { flex: 1; margin-left: 1.5rem; }
    .bill-name { font-size: 1.15rem; font-weight: 800; color: var(--text-dark); margin-bottom: 2px; }
    .bill-provider { font-size: 0.85rem; font-weight: 600; color: #94A3B8; background: #F1F5F9; padding: 2px 10px; border-radius: 6px; }
    .bill-due { font-size: 0.8rem; color: #FFA502; font-weight: 700; margin-top: 5px; display: flex; align-items: center; gap: 4px; }
    .bill-due.overdue { color: var(--danger-red); }
    .bill-due.paid { color: var(--success-green); }

    .bill-amount-area { text-align: right; margin-right: 1.5rem; }
    .bill-amount { font-size: 1.2rem; font-weight: 800; color: var(--text-dark); }
    
    .btn-pay {
        background: #1E293B; color: white; border: none; padding: 10px 25px; border-radius: 12px;
        font-weight: 700; font-size: 0.9rem; transition: 0.3s;
    }
    .btn-pay:hover { background: var(--primary-purple); transform: scale(1.05); }

    .btn-add-float {
        position: fixed; bottom: 40px; right: 40px; width: 70px; height: 70px;
        background: #1E293B; color: white; border: none; border-radius: 20px;
        font-size: 1.8rem; display: flex; align-items: center; justify-content: center;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2); transition: 0.3s; z-index: 100;
    }
    .btn-add-float:hover { background: var(--primary-purple); transform: rotate(90deg) scale(1.1); }

    /* Modal styling */
    .premium-modal-content { border-radius: 30px; border: none; overflow: hidden; }
    .modal-header-luxury { background: var(--soft-purple); border-bottom: 0; padding: 2rem 2rem 1rem; }

    .wallet-option { border: 1.5px solid #F1F5F9 !important; transition: all 0.2s; }
    .wallet-option:hover { border-color: var(--primary-purple) !important; background: var(--soft-purple); }
    .wallet-option input:checked + div { color: var(--primary-purple); }
    .wallet-option input:checked { background-color: var(--primary-purple); border-color: var(--primary-purple); }
    
    /* CSS to highlight the parent label when child radio is checked */
    .wallet-option:has(input:checked) {
        border-color: var(--primary-purple) !important;
        background: var(--soft-purple) !important;
        box-shadow: 0 4px 12px rgba(124, 76, 255, 0.1);
    }
</style>
@endpush

@section('content')
@php
    $unpaidBills = collect($bills)->where('status', 'unpaid');
    $paidBills = collect($bills)->where('status', 'paid');
    $totalUnpaid = $unpaidBills->sum('amount');
    $dueToday = $unpaidBills->filter(fn($b) => \Carbon\Carbon::parse($b['due_date'])->isToday())->count();
@endphp

<div class="container py-4">
    {{-- Header --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('home') }}" class="text-decoration-none" style="color: var(--primary-purple); font-size: 1.3rem;">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h3 class="fw-bold mb-0">Tagihan</h3>
    </div>

    {{-- Hero Card --}}
    <div class="bills-hero">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <span class="label">Total Belum Dibayar</span>
                <div class="amount">Rp {{ number_format($totalUnpaid, 0, ',', '.') }}</div>
            </div>
            <div class="badge-count">{{ count($unpaidBills) }} Bills</div>
        </div>
        @if($dueToday > 0)
            <div class="due-warning">
                <i class="bi bi-clock-fill"></i> {{ $dueToday }} Due Today
            </div>
        @endif
    </div>

    {{-- Tabs --}}
    <div class="text-center">
        <div class="bills-tabs nav nav-pills" id="pills-tab" role="tablist">
            <button class="bill-tab-btn active" id="unpaid-tab" data-bs-toggle="pill" data-bs-target="#unpaid-content">Belum Bayar</button>
            <button class="bill-tab-btn" id="paid-tab" data-bs-toggle="pill" data-bs-target="#paid-content">Lunas</button>
        </div>
    </div>

    {{-- Tab Content --}}
    <div class="tab-content" id="pills-tabContent">
        {{-- UNPAID LIST --}}
        <div class="tab-pane fade show active" id="unpaid-content">
            @if(count($unpaidBills) > 0)
                @foreach($unpaidBills as $bill)
                <div class="bill-item">
                    <div class="bill-icon">
                        <i class="bi {{ str_contains(strtolower($bill['provider']), 'pln') ? 'bi-lightning-charge-fill' : 'bi-droplet-fill' }}"></i>
                    </div>
                    <div class="bill-info">
                        <div class="bill-name">{{ $bill['provider'] }}</div>
                        <span class="bill-provider">ID: {{ $bill['account_number'] }}</span>
                        <div class="bill-due {{ \Carbon\Carbon::parse($bill['due_date'])->isPast() && !\Carbon\Carbon::parse($bill['due_date'])->isToday() ? 'overdue' : '' }}">
                            <i class="bi bi-calendar-event"></i>
                            @if(\Carbon\Carbon::parse($bill['due_date'])->isToday())
                                Jatuh tempo hari ini
                            @elseif(\Carbon\Carbon::parse($bill['due_date'])->isPast())
                                Terlewat {{ \Carbon\Carbon::parse($bill['due_date'])->diffForHumans() }}
                            @else
                                Jatuh tempo {{ \App\Helpers\ApiHelper::formatDate($bill['due_date']) }}
                            @endif
                        </div>
                    </div>
                    <div class="bill-amount-area">
                        <div class="bill-amount">Rp {{ number_format($bill['amount'], 0, ',', '.') }}</div>
                    </div>
                    <button type="button" class="btn-pay" 
                            onclick="openPayModal({{ $bill['id'] }}, '{{ $bill['provider'] }}', {{ $bill['amount'] }})">
                        Bayar
                    </button>
                </div>
                @endforeach
            @else
                <div class="text-center py-5">
                    <img src="https://cdn-icons-png.flaticon.com/512/4436/4436481.png" width="120" class="opacity-25 mb-3">
                    <h5 class="text-muted fw-bold">Semua tagihan lunas!</h5>
                    <p class="text-muted small">Anda tidak memiliki tagihan tertunda saat ini.</p>
                </div>
            @endif
        </div>

        {{-- PAID LIST --}}
        <div class="tab-pane fade" id="paid-content">
            @foreach($paidBills as $bill)
            <div class="bill-item" style="opacity: 0.7;">
                <div class="bill-icon" style="background: #E8F9F1; color: var(--success-green);">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="bill-info">
                    <div class="bill-name">{{ $bill['provider'] }}</div>
                    <span class="bill-provider">ID: {{ $bill['account_number'] }}</span>
                    <div class="bill-due paid">
                        <i class="bi bi-patch-check-fill"></i> Lunas pada {{ \App\Helpers\ApiHelper::formatDate($bill['updated_at']) }}
                    </div>
                </div>
                <div class="bill-amount-area">
                    <div class="bill-amount">Rp {{ number_format($bill['amount'], 0, ',', '.') }}</div>
                </div>
                <form action="{{ route('bills.destroy', $bill['id']) }}" method="POST" onsubmit="return confirm('Hapus riwayat tagihan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-light rounded-pill p-2 px-3"><i class="bi bi-trash text-danger"></i></button>
                </form>
            </div>
            @endforeach
            @if(count($paidBills) === 0)
                <div class="text-center py-5">
                    <p class="text-muted">Belum ada riwayat tagihan yang dibayar.</p>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Pay Bill Modal --}}
<div class="modal fade" id="payBillModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content premium-modal-content">
            <div class="modal-header modal-header-luxury">
                <h5 class="fw-bold mb-0">Bayar Tagihan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <div class="text-muted small fw-bold">Anda akan membayar</div>
                    <h3 class="fw-bold text-dark" id="payBillName">PLN</h3>
                    <div class="display-6 fw-800 text-primary-purple" id="payBillAmount">Rp 0</div>
                </div>

                <form id="payBillForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="paid">
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">Pilih Sumber Dana (Dompet)</label>
                        <div class="wallet-selector-list d-flex flex-column gap-2">
                            @foreach($wallets as $wallet)
                            <label class="wallet-option p-3 border rounded-4 d-flex align-items-center justify-content-between cursor-pointer" style="transition: 0.3s; cursor: pointer;">
                                <div class="d-flex align-items-center gap-3">
                                    <input type="radio" name="wallet_id" value="{{ $wallet['id'] }}" required class="form-check-input mt-0">
                                    <div>
                                        <div class="fw-bold text-dark">{{ $wallet['name_wallet'] }}</div>
                                        <div class="small text-muted">Saldo: Rp {{ number_format($wallet['balance'], 0, ',', '.') }}</div>
                                    </div>
                                </div>
                                <div class="text-primary-purple"><i class="bi bi-wallet2"></i></div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-4 shadow-sm" style="background: var(--primary-purple); border:none;">
                        Konfirmasi Pembayaran
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Floating Add Button --}}
<button class="btn-add-float" data-bs-toggle="modal" data-bs-target="#addBillModal">
    <i class="bi bi-plus-lg"></i>
</button>

{{-- Add Bill Modal --}}
<div class="modal fade" id="addBillModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content premium-modal-content">
            <div class="modal-header modal-header-luxury">
                <h5 class="fw-bold mb-0">Tambah Tagihan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('bills.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Provider / Nama Tagihan</label>
                        <input type="text" name="provider" class="form-control form-control-lg border-0 bg-light rounded-4" placeholder="Misal: PLN, PDAM, Netflix" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Nomor Pelanggan / Akun</label>
                        <input type="text" name="account_number" class="form-control form-control-lg border-0 bg-light rounded-4" placeholder="Nomor ID tagihan" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Jumlah Tagihan (Rp)</label>
                            <input type="text" inputmode="numeric" id="billAmountInput" class="form-control form-control-lg border-0 bg-light rounded-4" placeholder="0" required>
                            <input type="hidden" name="amount" id="billAmountHidden">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Jatuh Tempo</label>
                            <input type="date" name="due_date" class="form-control form-control-lg border-0 bg-light rounded-4" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">Siklus</label>
                        <select name="cycle" class="form-select form-select-lg border-0 bg-light rounded-4">
                            <option value="monthly">Bulanan</option>
                            <option value="yearly">Tahunan</option>
                            <option value="once">Sekali Bayar</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-4 shadow-sm" style="background: var(--primary-purple); border:none;">
                        Simpan Tagihan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const amountInput = document.getElementById('billAmountInput');
    const hiddenInput = document.getElementById('billAmountHidden');

    if (amountInput) {
        amountInput.addEventListener('input', function(e) {
            // Strip non-numeric
            let raw = this.value.replace(/[^0-9]/g, '');
            if (!raw) {
                this.value = '';
                hiddenInput.value = '';
                return;
            }
            
            // Set hidden value for backend (numeric)
            hiddenInput.value = raw;
            
            // Format for display (10.000)
            this.value = parseInt(raw, 10).toLocaleString('id-ID').replace(/,/g, '.');
        });
    }
});

function openPayModal(id, name, amount) {
    document.getElementById('payBillName').innerText = name;
    document.getElementById('payBillAmount').innerText = 'Rp ' + amount.toLocaleString('id-ID').replace(/,/g, '.');
    
    const form = document.getElementById('payBillForm');
    form.action = `/bills/${id}`;
    
    const modal = new bootstrap.Modal(document.getElementById('payBillModal'));
    modal.show();
}
</script>
@endpush

@endsection
