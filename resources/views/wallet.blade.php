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

    /* ─── WALLET CARDS ─── */
    .wcard {
        height: 180px;
        border-radius: 20px;
        padding: 1.5rem;
        color: white;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.2s ease-in-out;
    }
    .wcard:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
    
    .wcard-cash { background: linear-gradient(135deg, #6366F1 0%, #4F46E5 100%); }
    .wcard-bank { background: linear-gradient(135deg, #F43F5E 0%, #E11D48 100%); }
    .wcard-ewallet { background: linear-gradient(135deg, #10B981 0%, #059669 100%); }

    .wcard-chip { width: 35px; height: 26px; background: rgba(255,255,255,0.2); border-radius: 4px; border: 1px solid rgba(255,255,255,0.3); }
    .wcard-name { font-size: 1.1rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; }
    .wcard-bal { font-size: 1.4rem; font-weight: 700; }
    .wcard-bal-label { font-size: 0.7rem; opacity: 0.8; font-weight: 600; text-transform: uppercase; }

    .btn-del {
        background: rgba(255,255,255,0.15); color: white; border: none;
        padding: 5px 10px; border-radius: 8px; font-size: 0.75rem; font-weight: 600;
        transition: 0.2s;
    }
    .btn-del:hover { background: #E53E3E; }

    .add-more-box {
        height: 180px;
        border: 2px dashed #CBD5E0;
        border-radius: 20px;
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
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="wcard-chip"></div>
                                    <button type="button" class="btn-del" onclick="confirmDelete('{{ $wallet['id'] }}', '{{ addslashes($wallet['name_wallet']) }}')">Delete</button>
                                </div>
                                <div>
                                    <div class="wcard-name">{{ $wallet['name_wallet'] }}</div>
                                    <div class="wcard-bal-label">Balance</div>
                                    <div class="wcard-bal">Rp {{ number_format((float)($wallet['balance'] ?? 0), 0, ',', '.') }}</div>
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
    const modal = new bootstrap.Modal(document.getElementById('deleteWalletModal'));
    const form = document.getElementById('confirmDeleteForm');
    const nameSpan = document.getElementById('delWalletName');
    
    // Set dynamic content
    nameSpan.textContent = `'${name}'`;
    form.action = `/wallet/${id}`; // Sesuaikan dengan route destroy Anda
    
    modal.show();
}
</script>
@endpush
@endsection
