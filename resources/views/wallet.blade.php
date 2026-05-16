@extends('layouts.app')

@section('title', 'Monefy - Your Wallet')

@push('styles')
<style>
    :root {
        --primary-purple: #7C4CFF;
        --soft-purple: #F3F0FF;
        --text-dark: #2D3748;
        --card-border: #E2E8F0;
    }

    body { 
        background-color: #ffffff !important; 
        color: var(--text-dark);
    }

    .wallet-page {
        padding: 2rem 0;
    }

    /* ─── SIDEBAR ─── */
    .wallet-sidebar {
        position: sticky;
        top: 100px;
    }

    .balance-hero-card {
        background: linear-gradient(135deg, #7C4CFF 0%, #9066FF 100%);
        border-radius: 24px;
        padding: 2rem;
        color: white;
        margin-bottom: 1.5rem;
        box-shadow: 0 15px 35px rgba(124, 76, 255, 0.25);
        position: relative;
        overflow: hidden;
    }
    .balance-hero-card::after {
        content: ''; position: absolute; top: -20px; right: -20px;
        width: 120px; height: 120px; background: rgba(255,255,255,0.1); border-radius: 50%;
    }

    .balance-hero-card .balance-label { font-size: 0.85rem; font-weight: 500; opacity: 0.8; margin-bottom: 0.5rem; display: block; }
    .balance-hero-card .balance-amount { font-size: 1.8rem; font-weight: 800; letter-spacing: -0.5px; }
    .balance-hero-card .wallet-count { font-size: 0.8rem; margin-top: 1rem; display: flex; align-items: center; gap: 6px; }

    .category-nav {
        background: #ffffff;
        border: 1px solid var(--card-border);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }

    .category-link {
        display: flex;
        align-items: center;
        padding: 1.2rem 1.5rem;
        color: #718096;
        text-decoration: none;
        transition: all 0.2s;
        border-bottom: 1px solid #F1F5F9;
    }
    .category-link:last-child { border-bottom: none; }
    .category-link:hover { background: var(--soft-purple); color: var(--primary-purple); }
    .category-link.active {
        background: var(--soft-purple);
        color: var(--primary-purple);
        font-weight: 700;
        border-right: 4px solid var(--primary-purple);
    }
    .category-link i { font-size: 1.3rem; margin-right: 1.2rem; }
    .category-link .cat-info { flex: 1; }
    .category-link .cat-meta { font-size: 0.75rem; font-weight: 400; opacity: 0.7; }

    /* ─── CONTENT PANEL ─── */
    .content-panel {
        background: #ffffff;
        border: 1px solid var(--card-border);
        border-radius: 24px;
        padding: 2rem;
        min-height: 600px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }

    .tab-panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        border-bottom: 1px solid #F1F5F9;
        padding-bottom: 1.5rem;
    }
    .tab-panel-title { font-size: 1.4rem; font-weight: 800; display: flex; align-items: center; gap: 10px; }
    .tab-panel-badge { background: var(--soft-purple); color: var(--primary-purple); font-size: 0.8rem; padding: 2px 10px; border-radius: 8px; margin-left: 5px; }

    .btn-add-wallet-sm {
        background: var(--primary-purple); color: white; border: none;
        padding: 0.7rem 1.3rem; border-radius: 12px; font-weight: 700; font-size: 0.88rem;
        display: flex; align-items: center; gap: 6px; text-decoration: none; transition: 0.3s;
    }
    .btn-add-wallet-sm:hover { filter: brightness(1.1); transform: translateY(-2px); color: white; }

    /* ─── WALLET CARDS (Premium Card Art) ─── */
    .wcard {
        height: 210px;
        border-radius: 24px;
        padding: 1.5rem;
        color: white;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        border: none;
    }
    .wcard:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    }
    
    .wcard-cash { background: linear-gradient(135deg, #FF8C42 0%, #FF3D00 100%); }
    .wcard-bank { background: linear-gradient(135deg, #6A4CFF 0%, #3D2BB7 100%); }
    .wcard-ewallet { background: linear-gradient(135deg, #00B4DB 0%, #0083B0 100%); }

    .wcard-watermark {
        position: absolute;
        top: -10px;
        right: -20px;
        font-size: 4.5rem;
        font-weight: 900;
        color: rgba(255, 255, 255, 0.06);
        white-space: nowrap;
        pointer-events: none;
        text-transform: uppercase;
        font-style: italic;
        letter-spacing: 4px;
        z-index: 1;
    }

    .wcard-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        z-index: 2;
    }
    .wcard-name {
        font-size: 1.2rem;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .btn-del-premium {
        background: rgba(255,255,255,0.2);
        border: none;
        color: white;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.2s;
        backdrop-filter: blur(5px);
        z-index: 3;
    }
    .btn-del-premium:hover { background: #FF4757; transform: scale(1.1); }

    .wcard-chip-premium {
        width: 44px;
        height: 32px;
        background: linear-gradient(135deg, #f0d075 0%, #b38600 100%);
        border-radius: 6px;
        position: relative;
        padding: 4px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2px;
        box-shadow: inset 0 1px 2px rgba(255,255,255,0.5);
        z-index: 2;
        margin-top: 10px;
    }
    .chip-line { border: 0.5px solid rgba(0,0,0,0.15); border-radius: 1px; }

    .wcard-bottom {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        z-index: 2;
    }
    .wcard-bal-label {
        font-size: 0.8rem;
        opacity: 0.9;
        font-weight: 500;
        margin-bottom: 2px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .wcard-bal { font-size: 1.5rem; font-weight: 700; letter-spacing: -0.5px; }

    .wcard-brand-logo {
        display: flex;
        position: relative;
        width: 45px;
        height: 28px;
    }
    .brand-circle { width: 28px; height: 28px; border-radius: 50%; position: absolute; }
    .circle-1 { background: rgba(235, 0, 27, 0.7); left: 0; }
    .circle-2 { background: rgba(255, 95, 0, 0.7); left: 17px; }

    .add-more-box {
        height: 210px;
        border: 2px dashed #CBD5E0;
        border-radius: 24px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #A0AEC0;
        text-decoration: none;
        transition: 0.2s;
    }
    .add-more-box:hover { border-color: var(--primary-purple); color: var(--primary-purple); background: var(--soft-purple); }

    /* Flash Alerts */
    .alert-premium { border-radius: 12px; padding: 1rem 1.5rem; margin-bottom: 1.5rem; font-weight: 600; }

    @media (max-width: 991px) {
        .wallet-sidebar { position: static; margin-bottom: 2rem; }
    }
</style>
@endpush

@section('content')
@php
    $categories = [
        'cash'     => ['label' => 'Cash',           'icon' => 'bi-cash-stack',    'cardClass' => 'wcard-cash'],
        'bank'     => ['label' => 'Bank Accounts',   'icon' => 'bi-bank2',         'cardClass' => 'wcard-bank'],
        'e-wallet' => ['label' => 'E-Wallets',       'icon' => 'bi-phone',         'cardClass' => 'wcard-ewallet'],
    ];
    $activeTab = request()->query('tab', 'cash');
    if (!array_key_exists($activeTab, $categories)) $activeTab = 'cash';
    
    $totalWallets = 0;
    foreach($grouped as $list) $totalWallets += count($list);
@endphp

<div class="wallet-page">
    <div class="container">

        {{-- Page Header --}}
        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('home') }}" class="text-decoration-none" style="color: var(--primary-purple); font-size: 1.3rem;">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h4 class="fw-bold mb-0">Your Wallet</h4>
                <small class="text-muted">Manage all your wallets in one place</small>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-premium border-0 shadow-sm"><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</div>
        @endif

        <div class="row">
            {{-- LEFT: Sidebar --}}
            <div class="col-lg-3">
                <div class="wallet-sidebar">
                    {{-- Total Balance Hero --}}
                    <div class="balance-hero-card">
                        <span class="balance-label">Total Balance</span>
                        <div class="balance-amount">Rp {{ number_format($totalBalance, 0, ',', '.') }}</div>
                        <div class="wallet-count">
                            <i class="bi bi-wallet2"></i>
                            {{ $totalWallets }} wallets active
                        </div>
                    </div>

                    {{-- Category Nav --}}
                    <div class="category-nav">
                        @foreach($categories as $key => $cat)
                            @php 
                                $count = count($grouped[$key] ?? []);
                                $bal = array_sum(array_column($grouped[$key] ?? [], 'balance'));
                            @endphp
                            <a href="?tab={{ $key }}" class="category-link {{ $activeTab === $key ? 'active' : '' }}">
                                <i class="bi {{ $cat['icon'] }}"></i>
                                <div class="cat-info">
                                    <div class="cat-name">{{ $cat['label'] }}</div>
                                    <div class="cat-meta">{{ $count }} Wallets • Rp {{ number_format($bal, 0, ',', '.') }}</div>
                                </div>
                                <i class="bi bi-chevron-right ms-2 small"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- RIGHT: Main Panel --}}
            <div class="col-lg-9">
                <div class="content-panel">
                    @php
                        $activeCat = $categories[$activeTab];
                        $activeWallets = $grouped[$activeTab] ?? [];
                        $activeBal = array_sum(array_column($activeWallets, 'balance'));
                    @endphp

                    <div class="tab-panel-header">
                        <div>
                            <div class="tab-panel-title">
                                <i class="bi {{ $activeCat['icon'] }}"></i>
                                {{ $activeCat['label'] }}
                                <span class="tab-panel-badge">{{ count($activeWallets) }}</span>
                            </div>
                            <small class="text-muted">Total: <strong>Rp {{ number_format($activeBal, 0, ',', '.') }}</strong></small>
                        </div>
                        <a href="{{ route('wallet.create', ['category' => $activeTab]) }}" class="btn-add-wallet-sm">
                            <i class="bi bi-plus-lg"></i> Add {{ $activeCat['label'] }}
                        </a>
                    </div>

                    <div class="row g-4">
                        @foreach($activeWallets as $wallet)
                        <div class="col-md-6 col-xl-4">
                            <div class="wcard {{ $activeCat['cardClass'] }}">
                                <div class="wcard-watermark">{{ $wallet['name_wallet'] }}</div>
                                
                                <div class="wcard-top">
                                    <div class="wcard-name">{{ $wallet['name_wallet'] }}</div>
                                    <button type="button" class="btn-del-premium" onclick="confirmDelete('{{ $wallet['id'] }}', '{{ addslashes($wallet['name_wallet']) }}')">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </div>

                                <div class="wcard-middle">
                                    <div class="wcard-chip-premium">
                                        <div class="chip-line"></div>
                                        <div class="chip-line"></div>
                                        <div class="chip-line"></div>
                                        <div class="chip-line"></div>
                                    </div>
                                </div>

                                <div class="wcard-bottom">
                                    <div class="wcard-balance-info">
                                        <div class="wcard-bal-label">
                                            Total Balance 
                                            <i class="bi bi-eye-slash-fill ms-1" style="cursor:pointer;" onclick="toggleBalanceVisibility(this)"></i>
                                        </div>
                                        <div class="wcard-bal">Rp. {{ number_format((float)($wallet['balance'] ?? 0), 0, ',', '.') }}</div>
                                    </div>
                                    <div class="wcard-brand-logo">
                                        <div class="brand-circle circle-1"></div>
                                        <div class="brand-circle circle-2"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        <div class="col-md-6 col-xl-4">
                            <a href="{{ route('wallet.create', ['category' => $activeTab]) }}" class="add-more-box">
                                <i class="bi bi-plus-circle-dotted fs-2 mb-2"></i>
                                <span class="fw-bold">Add {{ $activeCat['label'] }}</span>
                            </a>
                        </div>
                    </div>

                    @if(count($activeWallets) === 0)
                    <div class="text-center py-5 mt-4">
                        <img src="https://cdn-icons-png.flaticon.com/512/4076/4076432.png" alt="Empty" width="80" class="opacity-25 mb-3">
                        <p class="text-muted">No wallets added yet in this category.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

{{-- ── Premium Delete Confirmation Modal ── --}}
<div class="modal fade" id="deleteWalletModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 24px; overflow: hidden;">
            <div class="modal-body p-4 text-center">
                <div class="mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-circle mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-exclamation-triangle-fill fs-1"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Delete Wallet?</h5>
                    <p class="text-muted px-3">Are you sure you want to delete <span id="delWalletName" class="fw-bold text-dark"></span>? This action cannot be undone.</p>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light w-100 py-2 fw-bold" data-bs-dismiss="modal" style="border-radius: 12px; color: #718096;">Cancel</button>
                    <form id="confirmDeleteForm" method="POST" class="w-100">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100 py-2 fw-bold" style="border-radius: 12px; background: #FF4757; border: none;">Yes, Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(id, name) {
    const modalElement = document.getElementById('deleteWalletModal');
    const modal = new bootstrap.Modal(modalElement);
    const form = document.getElementById('confirmDeleteForm');
    const nameSpan = document.getElementById('delWalletName');
    
    nameSpan.textContent = `'${name}'`;
    form.action = `/wallet/${id}`;
    
    modal.show();
}

function toggleBalanceVisibility(el) {
    const balElement = el.closest('.wcard-balance-info').querySelector('.wcard-bal');
    const isHidden = el.classList.contains('bi-eye-fill');
    
    if (!isHidden) {
        // Hide
        el.classList.replace('bi-eye-slash-fill', 'bi-eye-fill');
        balElement.setAttribute('data-orig', balElement.innerText);
        balElement.innerText = 'Rp. ••••••';
    } else {
        // Show
        el.classList.replace('bi-eye-fill', 'bi-eye-slash-fill');
        balElement.innerText = balElement.getAttribute('data-orig');
    }
}
</script>
@endpush
@endsection
